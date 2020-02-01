<?php

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

/**
 * Контроллер первой версии API 100 Юристов
 */
class ApiController extends CController
{
    /** @var Logger */
    private $logger;

    public function init()
    {
        $this->logger = new Logger('api');
        $rotateHandler = new RotatingFileHandler(
            Yii::getPathOfAlias("application.runtime.api_logs") . '/api.log',
            30,
            Logger::INFO
        );
        $this->logger->pushHandler($rotateHandler);
    }

    public $layout = '//frontend/atom';

    /*
     * адреса для оплаты вопросов через Яндекс кассу
     * /question/paymentSuccess - страница успешной оплаты
     * /question/paymentFail - страница неуспешной оплаты
     * /question/paymentCheck - страница для передачи запроса на проверку заказа
     * /question/paymentAviso - страница для передачи уведомления о переводе/отказе
     */

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow all users
                'actions' => array('sendLead', 'statusLead'),
                'users' => array('*'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * прием лида по POST запросу
     * Обязательные поля запроса:
     * phone - номер телефона (любой формат, важны только первые 10 цифр)
     * name - имя клиента
     * town - название города на русском
     * question - текст вопроса
     * appId - уникальный id партнера (источника лидов)
     * signature - подпись для проверки пришедших данных
     *
     * signature = md5(name.phone.town.question.appId.secretKey)
     *
     * Необязательные поля
     * email - Email клиента
     * type - Тип лида (вопрос, звонок, etc)
     * price - Цена лида
     */
    public function actionSendLead()
    {
        header('Content-type: application/json');

        /** @var CHttpRequest $request */
        $request = Yii::app()->request;

        if (!$request->isPostRequest) {
            echo json_encode(array('code' => 400, 'message' => 'No input data'));
            Yii::app()->end();
        }

        // проверяем обязательный целочисленный параметр appId
        $appId = (int)$request->getPost('appId');
        if (!$appId) {
            echo json_encode(array('code' => 400, 'message' => 'Unknown sender. Check appId parameter'));
            Yii::app()->end();
        }

        $this->logRequest($request, $_POST, $appId);

        // ищем источник по параметру appId
        $source = Leadsource::getByAppIdAsArray($appId, 60);

        // если источник не найден
        if (!$source) {
            echo json_encode(array('code' => 404, 'message' => 'Unknown or blocked sender. Check appId parameter'));
            Yii::app()->end();
        }

        // находим источник в виде объекта, в будущем он будет нужен для расчета коэффициента цены
        $sourceObject = Leadsource::model()->findByPk($source['id']);

        $secretKey = $source['secretKey'];
        $leadName = $request->getPost('name');
        $leadPhone = $request->getPost('phone');
        $leadTown = $request->getPost('town');
        $leadQuestion = $request->getPost('question');
        $signature = $request->getPost('signature');
        $leadEmail = $request->getPost('email');
        $leadType = $request->getPost('type');
        $partnerPrice = $request->getPost('price') * 100;
        $testMode = $request->getPost('testMode');

        // проверка подписи

        $correctSignature = md5($leadName . $leadPhone . $leadTown . $leadQuestion . $appId . $secretKey);
//        $demoSignature = md5("Миша"."89263549557"."Москва"."Где находится нофелет?"."5032565"."860d7117e9d1eb5c8dc092f857a079d5");
//        echo json_encode($signature);
//        echo json_encode($correctSignature);
        if ($correctSignature !== $signature || $signature == '') {
            echo json_encode(array('code' => 400, 'message' => 'Signature is wrong'));
            Yii::app()->end();
        }

        // После проверки входных данных проверим город на существование в базе

        $town = Yii::app()->db->cache(86400)->createCommand()
            ->select("id")
            ->from("{{town}}")
            ->where("LOWER(`name`)=:townName", [":townName" => mb_strtolower($leadTown, 'utf-8')])
            ->queryRow();
        // если город не найден
        if (!$town) {
            echo json_encode(array('code' => 404, 'message' => 'Unknown town. Provide correct town name in Russian language'));
            Yii::app()->end();
        }

        $townId = $town['id'];

        $model = new Lead;
        $purifier = new CHtmlPurifier();

        $model->name = CHtml::encode($leadName);
        $model->sourceId = $source['id'];
        $model->email = $leadEmail;
        if ($leadType) {
            $model->type = $leadType;
        }
        $model->townId = $townId;
        $model->question = $purifier->purify($leadQuestion);
        $model->phone = PhoneHelper::normalizePhone($leadPhone);

        // проверка на дубликаты за последние 12 часов
        if ($model->findDublicates(12 * 3600)) {
            die(json_encode(array('code' => 400, 'message' => 'Dublicates found')));
            Yii::app()->end();
        }

        // посчитаем цену покупки лида, исходя из города и региона
        $prices = $model->calculatePrices();
        if ($prices[0]) {
            // уточним цену покупки лида с учетом коэффициента покупателя
            $sourceUser = $sourceObject->user;
            $priceCoeff = !is_null($sourceUser) ? $sourceUser->priceCoeff : 1; // коэффициент, на который умножается цена покупки лида

            $model->buyPrice = $prices[0] * $priceCoeff;
        } else {
            $model->buyPrice = 0;
        }

        // Если в запросе от партнера есть цена
        if ($partnerPrice && $sourceObject->priceByPartner == 1) {
            $model->buyPrice = $partnerPrice;
        }

        // если тестовый режим, то не сохраняем, а только проверяем лид
        if ($testMode != 0) {
            if ($model->validate()) {
                echo json_encode(array('code' => 200, 'buyPrice' => MoneyFormat::rubles($model->buyPrice), 'message' => 'OK. You are in the test mode. Lead accepted but not saved.'));
                Yii::app()->end();
            } else {
                echo json_encode(array('code' => 500, 'message' => 'Lead not saved.', 'errors' => $model->errors));
                Yii::app()->end();
            }
        }

        if ($model->save()) {
            echo json_encode(array('code' => 200, 'buyPrice' => MoneyFormat::rubles($model->buyPrice), 'message' => 'OK'));
            $this->logLeadSaveResult($model);
            Yii::app()->end();
        } else {
            echo json_encode(array('code' => 500, 'message' => 'Lead not saved.', 'errors' => $model->errors));
            $this->logLeadSaveResult($model);
            Yii::app()->end();
        }
    }

    /**
     * Изменение статуса лида по POST запросу
     * Обязательные поля запроса:
     * code - уникальный код лида (хранится в БД в поле secretCode)
     * brakReason - код причины брака
     * brakComment - комментарий отбраковки
     * @throws CHttpException
     */
    public function actionStatusLead()
    {
        $availableStatuses = array(
            Lead::LEAD_STATUS_NABRAK,
        );

        $request = Yii::app()->request;

        if (!$request->isPostRequest) {
            echo json_encode(array('code' => 400, 'message' => 'No input data'));
            Yii::app()->end();
        }

        $code = CHtml::encode($_POST['code']);
        $newStatus = CHtml::encode($_POST['status']);
        $brakReason = CHtml::encode($_POST['brakReason']);
        $brakComment = CHtml::encode($_POST['brakComment']);

        if (!in_array($newStatus, $availableStatuses)) {
            echo json_encode(array('code' => 400, 'message' => 'You cannot set this status for this lead'));
            Yii::app()->end();
        }

        if ($code == '') {
            echo json_encode(array('code' => 400, 'message' => 'Please specify lead secret code'));
            Yii::app()->end();
        }

        $lead = Lead::model()->findByAttributes(array('secretCode' => $code));

        if (!$lead) {
            echo json_encode(array('code' => 400, 'message' => 'Lead not found'));
            Yii::app()->end();
        }


        if ($newStatus === Lead::LEAD_STATUS_NABRAK && !(!is_null($lead->deliveryTime) && (time() - strtotime($lead->deliveryTime) < 86400 * Yii::app()->params['leadHoldPeriodDays']))) {
            echo json_encode(array('code' => 400, 'message' => 'Lead could not be sent to brak because it was created more than ' . Yii::app()->params['leadHoldPeriodDays'] . ' days ago'));
            Yii::app()->end();
        }

        $lead->brakReason = $brakReason;
        $lead->brakComment = $brakComment;

        if ($newStatus === Lead::LEAD_STATUS_NABRAK && !$lead->brakReason) {
            $lead->addError('brakReason', 'Please specify a reason');
        }

        if ($newStatus === Lead::LEAD_STATUS_NABRAK && !$lead->brakComment) {
            $lead->addError('brakComment', 'Please specify a comment');
        }

        $lead->leadStatus = $newStatus;

        if (!$lead->hasErrors() && $lead->save()) {
            echo json_encode(array('code' => 200, 'message' => 'OK'));
            Yii::app()->end();
        } else {
            echo json_encode(array('code' => 400, 'message' => 'Lead not saved.', 'errors' => $lead->errors));
            Yii::app()->end();
        }
    }

    /**
     * @param CHttpRequest $request
     * @param array $rawData
     * @param integer $appId
     */
    private function logRequest($request, $rawData, $appId): void
    {
        $this->logger->info(print_r($rawData, true), [
            'ip' => $request->getUserHostAddress(),
            'appId' => $appId,
        ]);
    }

    /**
     * Логирует результат сохранения лида
     * @param Lead $model
     */
    private function logLeadSaveResult(Lead $model)
    {
        if ($model->hasErrors() == true) {
            $this->logger->error(print_r($model->getErrors(), true), [
                'phone' => $model->phone,
            ]);
        } else {
            $this->logger->info("Lead saved, id=" . $model->id, [
                'phone' => $model->phone,
            ]);
        }
    }
}
