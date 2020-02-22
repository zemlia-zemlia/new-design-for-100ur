<?php

/**
 * Раздел для работы с Email рассылками.
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
        return [
            'accessControl', // perform access control for CRUD operations
        ];
    }

    /**
     * Настройки доступа к страницам
     *
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            ['allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => ['index', 'create', 'success'],
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',
            ],
            ['deny', // deny all users
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Создание рассылки.
     */
    public function actionCreate()
    {
        $mailFormModel = new MailForm();

        if (isset($_POST['MailForm'])) {
            $mailFormModel->attributes = $_POST['MailForm'];

            $mailFormModel->validate();
            if ('' == $mailFormModel->recipientEmail && '' == $mailFormModel->roleId) {
                $mailFormModel->addError('roleId', 'Необходимо указать получателя или роль');
            }

            if (!$mailFormModel->hasErrors()) {
                // создаем новую рассылку и задания по отправке
                $mail = new Mail();
                $mail->subject = $mailFormModel->subject;
                $mail->message = $mailFormModel->message;
                if ($mail->save()) {
                    $mailTasksCreated = $mailFormModel->createTasks($mail);
                }

                if ($mailTasksCreated > 0) {
                    $this->redirect(['mail/success', 'mailTasksCreated' => $mailTasksCreated]);
                }

                throw new CHttpException(400, 'Что-то пошло не так. Не удалось отправить рассылку');
            }
        }

        $this->render('create', ['mailModel' => $mailFormModel]);
    }

    /**
     * Страница успешной отправки рассылки.
     */
    public function actionSuccess($mailTasksCreated)
    {
        $this->render('success', ['created' => (int) $mailTasksCreated]);
    }
}
