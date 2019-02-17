<?php

/**
 * Класс для обработки запросов от Яндекс денег об успешной оплате
 *
 * Class YandexPaymentResponseProcessor
 */
class YandexPaymentResponseProcessor
{
    /** @var CHttpRequest */
    private $request;

    /** @var string Обозначение сущности, которую оплачивают */
    private $paymentType;

    /** @var int ID оплачиваемой сущности */
    private $entityId;

    /** @var string Кодовая строка для проверки хеша запроса */
    private $yandexSecret;

    /** @var string[] массив с ошибками */
    private $errors = [];

    public function __construct(CHttpRequest $request, string $yandexSecret)
    {
        $this->request = $request;
        $this->yandexSecret = $yandexSecret;
    }

    public function process()
    {
        // разбираем данные, которые пришли от Яндекса
        $amount = $this->request->getPost('amount');
        $label = $this->request->getPost('label');

        if (is_null($this->detectPaymentType($label))) {
            $this->addError('Некоректный тип плачиваемой сущности');
            return false;
        };

        if ($this->validateRequest() !== false) {
            $this->addError('Запрос не прошел проверку на целостность');
            return false;
        }
        // данные от яндекса не подделаны, можно зачислять бабло

        if ($this->paymentType == 'user') {

            $paymentProcessor = new YandexPaymentUser($this->entityId);
            $paymentProcessor->process();

/*            $user = User::model()->findByPk($this->entityId);
            if ($user) {
                //@todo Отрефакторить, выделив в отдельный метод. Дублирует код из CampaignController
                Yii::log('Пополняем баланс пользователя: ' . $user->getShortName(), 'info', 'system.web');
                $user->balance += $amount;
                $transaction = new TransactionCampaign;
                $transaction->buyerId = $user->id;
                $transaction->sum = $amount;
                $transaction->description = 'Пополнение баланса пользователя';

                $moneyTransaction = new Money();
                $moneyTransaction->accountId = 0; // Яндекс деньги
                $moneyTransaction->value = $amount;
                $moneyTransaction->type = Money::TYPE_INCOME;
                $moneyTransaction->direction = 501;
                $moneyTransaction->datetime = date('Y-m-d');
                $moneyTransaction->comment = 'Пополнение баланса пользователя ' . $user->id;


                $saveTransaction = $transaction->dbConnection->beginTransaction();
                try {
                    if ($transaction->save() && $moneyTransaction->save() && $user->save(false)) {
                        $saveTransaction->commit();
                        Yii::log('Транзакция сохранена, id: ' . $transaction->id, 'info', 'system.web');
                    } else {
                        $saveTransaction->rollback();
                    }
                } catch (Exception $e) {
                    $saveTransaction->rollback();
                    Yii::log('Ошибка при пополнении баланса пользователя ' . $userId . ' (' . $amount . ' руб.)', 'error', 'system.web');

                    throw new CHttpException(500, 'Не удалось пополнить баланс пользователя');
                }

                Yii::log('Пришло бабло от пользователя ' . $user->id . ' (' . $amount . ' руб.)', 'info', 'system.web');
                LoggerFactory::getLogger('db')->log('Пополнение баланса пользователя #' . $user->id . '(' . $user->getShortName() . ') на ' . $amount . ' руб.', 'User', $user->id);
            }*/
        } elseif ($this->paymentType == 'question') {
            $question = Question::model()->findByPk($this->entityId);

            if ($question) {

                $question->payed = 1;
                $question->price = floor($amount);

                $moneyTransaction = new Money();
                $moneyTransaction->accountId = 0; // Яндекс деньги
                $moneyTransaction->value = $amount;
                $moneyTransaction->type = Money::TYPE_INCOME;
                $moneyTransaction->direction = 504; // VIP вопросы
                $moneyTransaction->datetime = date('Y-m-d');
                $moneyTransaction->comment = 'Оплата вопроса ' . $question->id;

                $saveTransaction = $moneyTransaction->dbConnection->beginTransaction();
                try {
                    if ($question->save() && $moneyTransaction->save()) {
                        $saveTransaction->commit();
                        Yii::log('Вопрос сохранен, id: ' . $question->id, 'info', 'system.web');
                    } else {
                        $saveTransaction->rollback();
                        Yii::log('Ошибки: ' . print_r($question->errors, true) . ' ' . print_r($moneyTransaction->errors, true), 'error', 'system.web');
                    }
                } catch (Exception $e) {
                    $saveTransaction->rollback();
                    Yii::log('Ошибка при оплате вопроса ' . $question->id . ' (' . $amount . ' руб.)', 'error', 'system.web');

                    throw new CHttpException(500, 'Не удалось оплатить вопрос');
                }

                Yii::log('Пришло бабло за вопрос ' . $question->id . ' (' . $amount . ' руб.)', 'info', 'system.web');
                LoggerFactory::getLogger('db')->log('Оплата вопроса #' . $question->id . ') на ' . $amount . ' руб.', 'Question', $question->id);

            }
        }
    }

    /**
     * Получает тип сущности, за которую платят и ее id
     * @param string $label
     * @return string|null
     */
    private function detectPaymentType($label): ?string
    {
        preg_match('/([a-z]{1})\-([0-9]+)/', $label, $labelMatches);

        if (!$labelMatches[1]) {
            return null;
        }

        $this->entityId = (int)$labelMatches[2];

        if ($this->entityId == 0) {
            return null;
        }

        switch ($labelMatches[1]) {
            case 'u':
                $this->paymentType = 'user';
                break;
            case 'q':
                $this->paymentType = 'question';
                break;
            default:
                return null;
        }

        Yii::log('Субъект оплаты: ' . $this->paymentType, 'info', 'system.web');

        return $this->paymentType;
    }

    /**
     * @return array
     */
    private function validateRequest(): bool
    {
        $hash = $this->request->getPost('sha1_hash');

        $requestString = $this->request->getPost('notification_type') . '&' .
            $this->request->getPost('operation_id') . '&' .
            $this->request->getPost('amount') . '&' .
            $this->request->getPost('currency') . '&' .
            $this->request->getPost('datetime') . '&' .
            $this->request->getPost('sender') . '&' .
            $this->request->getPost('codepro') . '&' .
            $this->yandexSecret . '&' .
            $this->request->getPost('label');

        Yii::log('Собранная строка для проверки: ' . $requestString, 'info', 'system.web');

        $requestStringEncoded = sha1($requestString);

        Yii::log('Зашифрованная строка для проверки: ' . $requestStringEncoded, 'info', 'system.web');

        return $hash === $requestStringEncoded;
    }

    /**
     * Добавление ошибки
     * @param string $error
     */
    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
