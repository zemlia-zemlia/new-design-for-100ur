<?php
/**
 * Виджет с номером телефона и завлекаловкой для юриста, вылезающий на краю экрана
 */
class AnnoyingWidget extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $showAlways = false; // показывать всегда, независимо от текущего города и региона
    
    public function run()
    {
        
        $currenTownId = Yii::app()->user->getState('currentTownId');
        
        // при отладке ставим текущий город Москва
        if(YII_DEBUG && !$currenTownId) {
            $currenTownId = 598;
        }
        $currentTown = Town::model()->findByPk($currenTownId);
        
        $payedTownsRegions = Campaign::getPayedTownsRegions();
        
        $payedRegionsIds = $payedTownsRegions['regions'];
        $payedTownsIds = $payedTownsRegions['towns'];
        
        // находим массив объектов продажных городов
        $payedTownsCriteria = new CDbCriteria();
        $payedTownsCriteria->addInCondition('id', array_keys($payedTownsIds)); 
        
        $payedTowns = Town::model()->findAll($payedTownsCriteria);
        
        // находим массив объектов продажных регионов
        $payedRegionsCriteria = new CDbCriteria();
        $payedRegionsCriteria->addInCondition('id', array_keys($payedRegionsIds)); 
        
        $payedRegions = Region::model()->findAll($payedRegionsCriteria);
                
        $this->render($this->template, array(
            'currentTown'      =>  $currentTown,
            'payedRegionsIds'  =>  $payedRegionsIds,
            'payedTownsIds'    =>  $payedTownsIds,
            'payedTowns'       =>  $payedTowns,
            'payedRegions'     =>  $payedRegions,
            'showAlways'       =>  $this->showAlways,
        ));
        
    }
}