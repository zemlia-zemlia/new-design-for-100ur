<?php

/**
 * Контроллер первой версии API 100 Юристов
 */
class ApiController extends Controller {

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
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + sendLead', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users 
                'actions' => array('sendLead'),
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
     * 
     */
    public function actionSendLead() {
        $request = Yii::app()->request;

        if (!$request->isPostRequest) {
            echo json_encode(array('code' => 400, 'message' => 'No input data'));
            exit;
        }

        // проверяем обязательный целочисленный параметр appId
        $appId = (int) $request->getPost('appId');
        if (!$appId) {
            echo json_encode(array('code' => 400, 'message' => 'Unknown sender. Check appId parameter'));
            exit;
        }

        // ищем источник по параметру appId
        $source = Yii::app()->db->createCommand()
                ->select("*")
                ->from("{{leadsource100}}")
                ->where("appId=:appId AND active=1", array(":appId" => $appId))
                ->queryRow();

        // если источник не найден
        if (!$source) {
            echo json_encode(array('code' => 404, 'message' => 'Unknown or blocked sender. Check appId parameter'));
            exit;
        }

        $sourceId = $source['id'];
        $secretKey = $source['secretKey'];
        //echo json_encode($sourceId);
        //exit;
        $leadName = $request->getPost('name');
        $leadPhone = $request->getPost('phone');
        $leadTown = $request->getPost('town');
        $leadQuestion = $request->getPost('question');
        $signature = $request->getPost('signature');
        $leadEmail = $request->getPost('email');

        // проверка подписи

        $correctSignature = md5($leadName . $leadPhone . $leadTown . $leadQuestion . $appId . $secretKey);
        //$demoSignature = md5("Миша"."89263549557"."Москва"."Где находится нофелет?"."2464356"."860d7117e9d1eb5c8dc092f857a079d5");
        //echo json_encode($signature);
        //echo json_encode($correctSignature);
        if ($correctSignature !== $signature || $signature == '') {
            echo json_encode(array('code' => 400, 'message' => 'Signature is wrong'));
            exit;
        }

        // После проверки входных данных проверим город на существование в базе

        $town = Yii::app()->db->cache(86400)->createCommand()
                ->select("id")
                ->from("{{town}}")
                ->where("LOWER(`name`)=:townName", array(":townName" => mb_strtolower($leadTown, 'utf-8')))
                ->queryRow();
        // если источник не найден
        if (!$town) {
            echo json_encode(array('code' => 404, 'message' => 'Unknown town. Provide correct town name in Russian language'));
            exit;
        }

        $townId = $town['id'];

        $model = new Lead100;
        $purifier = new CHtmlPurifier();

        $model->name = CHtml::encode($leadName);
        $model->sourceId = $source['id'];
        $model->email = $leadEmail;
        $model->townId = $townId;
        $model->question = $purifier->purify($leadQuestion);
        $model->phone = Question::normalizePhone($leadPhone);

        // проверка на дубликаты за последние 12 часов
        if ($model->findDublicates(12 * 3600)) {
            die(json_encode(array('code' => 400, 'message' => 'Dublicates found')));
            exit;
        }

        if ($model->save()) {
            echo json_encode(array('code' => 200, 'message' => 'OK'));
            exit;
        } else {
            echo json_encode(array('code' => 500, 'message' => 'Lead not saved.', 'errors' => $model->errors));
            exit;
        }
    }

}
