<?php

/*
 * команда ищет вопросы, подходящие к категориям по ключевым словам и создает 
 * связки вопрос-категория
 */
class SetCategoriesCommand extends CConsoleCommand
{
    
    public $keys2categories = array(
        'кредит'    =>  47,
        'МФО'    =>  1040,
        'коллектор'    =>  48,
        'колектор'    =>  48,
        'ипотек'    =>  608,
        'наслед'    =>  60,
        'снт'    =>  323,
        'приватизац'    =>  79,
        'жкх'    =>  1074,
        'тсж'    =>  327,
        'дтп'    =>  307,
        'пдд'    =>  307,
        'осаго'    =>  307,
        'каско'    =>  307,
        'алимент'    =>  5,
        'адвокат'    =>  383,
        'развод'    =>  330,
        'дду'    =>  1506,
        'банкрот'    =>  468,
        
    );
    
    public function actionIndex()
    {
        foreach($this->keys2categories as $key => $categoryId) {
            $questionsIds = Yii::app()->db->createCommand()
                    ->select('id')
                    ->from('{{question}}')
                    ->where('status IN (' . Question::STATUS_CHECK . ', ' . Question::STATUS_PUBLISHED. ', ' . Question::STATUS_MODERATED.') AND questionText LIKE "%' . $key. '%"')
                    ->queryAll();
            
            echo $key . PHP_EOL;
            foreach($questionsIds as $questionRow) {
                echo $questionRow['id'] . PHP_EOL;
                try {
                Yii::app()->db->createCommand()
                        ->insert('{{question2category}}', array('cId' => $categoryId, 'qId' => $questionRow['id']));
                } catch(CDbException $e) {
                    // дублирование связей вопрос-категория, не записываем
                }
            }
        }
        
    }
}