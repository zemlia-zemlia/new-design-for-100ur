<?php

// выводит юристу уведомление о незаполненности важных полей в профиле

class ProfileNotifier extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию

    public function run()
    {
        if (isset($_COOKIE['hide_profile_notifier'])) {
            return false;
        }
        $message = Yii::app()->user->getProfileNotification();
        
        if ($message) {
            $this->render($this->template, [
                'message' => $message,
            ]);
        }
    }
}
