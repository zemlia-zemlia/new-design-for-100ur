<?php

use App\models\Campaign;
use App\models\Lead;
use App\models\Town;

/**
 * Виджет показа номера телефона горячей линии
 * Определяет, из какого региона пользователь, есть ли в этом регионе рекламные кампании
 * и выводит/не выводит номер телефона.
 */
class HotlineWidget extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $cacheTime = 0;
    public $showAlways = false; // показывать всегда, независимо от текущего города и региона
    public $showPhone = true; // показывать номер телефона вместо формы запроса города
    public $mobile = false; // мобильная версия

    public function run()
    {
        if (false === $this->showPhone) {
            $model = new Lead();

            return $this->render('callBack', [
                'model' => $model,
                'mobile' => $this->mobile,
                ]);
        }

        if (true === $this->showAlways) {
            $this->render($this->template, [ 'mobile' => $this->mobile]);
        } else {
            $currenTownId = Yii::app()->user->getState('currentTownId');

            if (!$currenTownId) {
                return false;
            }

            $currentTown = Town::model()->findByPk($currenTownId);

            // Определим, для каких регионов и городов у нас есть рекламные кампании
            $payedRegions = [];
            $payedTowns = [];

            $payedTownsRegions = Campaign::getPayedTownsRegions($cacheTime);

            $payedRegions = $payedTownsRegions['regions'];
            $payedTowns = $payedTownsRegions['towns'];

            /*
             * показываем виджет только если пользователь находится в одном из продажных городов ИЛИ регионов
             */
            if (array_key_exists($currentTownId, $payedTowns) || array_key_exists($currentTown->regionId, $payedRegions)) {
                $this->render($this->template);
            }
        }
    }
}
