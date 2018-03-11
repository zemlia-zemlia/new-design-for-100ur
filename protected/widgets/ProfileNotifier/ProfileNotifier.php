<?php

// выводит юристу уведомление о незаполненности важных полей в профиле

class ProfileNotifier extends CWidget {

    public $template = 'default'; // представление виджета по умолчанию

    public function run() {
        // пока работает только с юристами
        if (Yii::app()->user->role != User::ROLE_JURIST) {
            return false;
        }
        
        if (isset($_COOKIE['hide_profile_notifier'])) {
            return false;
        }

        if (!Yii::app()->user->avatarUrl) {
            $message = 'Пожалуйста, загрузите свою фотографию. Юристы с фотографией вызывают больше доверия. ' . 
                    CHtml::link('Загрузить', Yii::app()->createUrl('user/update', ['id' => Yii::app()->user->id]), ['class' => 'yellow-button']);
        }

        if ($message) {
            $this->render($this->template, [
                'message' => $message,
            ]);
        }
    }

}

?>