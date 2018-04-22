<?php

/**
 * Раздел для работы с Email рассылками
 */
class MailController extends Controller
{

    // Шаблон страниц по умолчанию
    public $layout = '//admin/main';

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
     * Настройки доступа к страницам
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('index', 'create', 'success'),
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Создание и отправка рассылки
     */
    public function actionCreate()
    {
        $mailFormModel = new MailForm();

        if (isset($_POST['MailForm'])) {
            $mailFormModel->attributes = $_POST['MailForm'];
            
            $mailFormModel->validate();
            if ($mailFormModel->recipientEmail == '' && $mailFormModel->roleId == '') {
                $mailFormModel->addError('roleId', 'Необходимо указать получателя или роль');
            }

            if (!$mailFormModel->hasErrors()) {
                // отправка почты
                $mailsSent = $mailFormModel->send(!YII_DEV);
                if($mailsSent > 0) {
                    $this->redirect(['mail/success', 'sent' => $mailsSent]);
                } else {
                    throw new CHttpException(400, 'Что-то пошло не так. Не удалось отправить рассылку');
                }
            }
        }

        $this->render('create', ['mailModel' => $mailFormModel]);
    }

    /**
     * Страница успешной отправки рассылки
     */
    public function actionSuccess($sent)
    {
        $this->render('success', ['sent' => (int)$sent]);
    }
}
