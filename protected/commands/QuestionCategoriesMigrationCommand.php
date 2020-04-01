<?php

use App\models\QuestionCategory;

/**
 * Класс для миграции таблицы БД, переход от простой двухуровневой структуры хранения
 * категорий вопросов к многоуровневой иерархии.
 */
class QuestionCategoriesMigrationCommand extends CConsoleCommand
{
    /**
     * Создание корневых элементов - категорий верхнего уровня с родителем равным нулю.
     */
    public function actionCreateRoots()
    {
        $roots = QuestionCategory::model()->findAllByAttributes(['parentId' => 0]);

        echo sizeof($roots);

        foreach ($roots as $root) {
            //$root->moveAsRoot();
            $root->root = $root->id;
            $root->level = 1;
            $root->saveNode();
        }
    }

    /**
     * Создание дочерних элементов для каждого корневого.
     */
    public function actionCreateChildren()
    {
        $roots = QuestionCategory::model()->findAllByAttributes(['parentId' => 0]);

        foreach ($roots as $root) {
            // для каждого родителя найдем его потомков и сохраним эту связь
            $children = QuestionCategory::model()->findAllByAttributes(['parentId' => $root->id]);

            foreach ($children as $child) {
                $child->moveAsLast($root);
                $child->saveNode();
            }
        }
    }

    /**
     * конвертация таблицы с записью в чистую таблицу.
     */
    public function actionConvertRoot()
    {
        Yii::app()->db->createCommand()
                ->truncateTable('{{questioncategory}}');

        $roots = Yii::app()->db->createCommand()
                ->select('*')
                ->from('{{questioncategoryold}}')
                ->where('parentId=0')
                ->queryAll();

        foreach ($roots as $root) {
            $category = new QuestionCategory();
            $category->attributes = $root;
            $category->id = $root['id'];
            $category->saveNode();
        }
    }

    public function actionConvertChildren()
    {
        $children = Yii::app()->db->createCommand()
                ->select('*')
                ->from('{{questioncategoryold}}')
                ->where('parentId!=0')
                ->queryAll();

        foreach ($children as $child) {
            $category = new QuestionCategory();
            $category->attributes = $child;
            $category->id = $child['id'];
            $parent = QuestionCategory::model()->findByPk($child['parentId']);
            $category->appendTo($parent);
            //$category->saveNode();
        }
    }
}
