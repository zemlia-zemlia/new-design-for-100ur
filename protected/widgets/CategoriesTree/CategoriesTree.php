<?php

class CategoriesTree extends CWidget
{
    public $template = 'tree'; // представление виджета по умолчанию
    public $cacheTime = 300; // по умолчанию кэшируем  на 5 минут

    public function run()
    {
        // вытаскиваем из базы список категорий верхнего уровня
        $topCategories = Yii::app()->db->cache($this->cacheTime)->createCommand()
                ->select('id, alias, name, icon')
                ->from('{{questionCategory}}')
                ->where('lft=1') // категории верхнего уровня
                ->order('name')
                ->queryAll();

        /*
         *  SELECT c.root, COUNT(*) counter FROM `100_questioncategory` c
            LEFT JOIN `100_question2category` q2c ON q2c.cId=c.id
            LEFT JOIN `100_question` q ON q2c.qId=q.id
            WHERE q.status IN (2, 4)
            GROUP BY c.root
            ORDER BY c.id ASC
         */
        $questionsByCategoriesArray = [];
        /*$questionsByCategories = Yii::app()->db->cache($this->cacheTime)->createCommand()
                ->select('c.root, COUNT(*) counter')
                ->from('{{questionCategory}} c')
                ->leftJoin("{{question2category}} q2c", "q2c.cId=c.id")
                ->leftJoin("{{question}} q", "q2c.qId=q.id")
                ->where('q.status IN (:status1, :status2)', array(':status1' => Question::STATUS_CHECK, ':status2' => Question::STATUS_PUBLISHED))
                ->group('c.root')
                ->order('c.id ASC')
                ->queryAll();

        foreach($questionsByCategories as $row) {
            $questionsByCategoriesArray[$row['root']] = $row['counter'];
        }*/

        $this->render($this->template, [
            'topCategories' => $topCategories,
            'questionsByCategoriesArray' => $questionsByCategoriesArray,
        ]);
    }
}
