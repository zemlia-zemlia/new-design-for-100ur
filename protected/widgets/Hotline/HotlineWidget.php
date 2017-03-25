<?php
/**
 * Виджет показа номера телефона горячей линии
 * Определяет, из какого региона пользователь, есть ли в этом регионе рекламные кампании
 * и выводит/не выводит номер телефона
 */
class HotlineWidget extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $cacheTime = 0;
    
    public function run()
    {
        
        $currenTownId = Yii::app()->user->getState('currentTownId');
        
        if(!$currenTownId) {
            return false;
        }
        
        $currentTown = Town::model()->findByPk($currenTownId);
        
        // Определим, для каких регионов и городов у нас есть рекламные кампании
        $payedRegions = array();
        $payedTowns = array();
        
        $payedTownsRegions = Campaign::getPayedTownsRegions($cacheTime);
        
        $payedRegions = $payedTownsRegions['regions'];
        $payedTowns = $payedTownsRegions['towns'];
        
        
//        CustomFuncs::printr($payedRegions);
//        CustomFuncs::printr($payedTowns);
        
        /*
         * показываем виджет только если пользователь находится в одном из продажных городов ИЛИ регионов
         */
        if(array_key_exists($currentTownId, $payedTowns) || array_key_exists($currentTown->regionId, $payedRegions)) {
            $this->render($this->template);
        }
    }
}
?>