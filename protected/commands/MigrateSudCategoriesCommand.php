<?php
/**
 * Перенос категорий раздела судопроизводство, у которых криво сохранилась иерархия из временной таблицы.
 */
class MigrateSudCategoriesCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $tempCategories = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{questionCategoryTemp}}')
            ->queryAll();

        $parent = QuestionCategory::model()->findByPk(282);
        $processedCout = 0;
        $totalCount = sizeof($tempCategories);

        foreach ($tempCategories as $tempCategory) {
            $category = new QuestionCategory();
            $category->name = $tempCategory['name'];
            $category->alias = $tempCategory['alias'];
            $category->description1 = $tempCategory['description1'];
            $category->seoTitle = $tempCategory['seoTitle'];
            $category->seoDescription = $tempCategory['seoDescription'];
            $category->seoKeywords = $tempCategory['seoKeywords'];
            $category->seoH1 = $tempCategory['seoH1'];
            $category->isDirection = $tempCategory['isDirection'];
            $category->path = '';
            $category->image = $tempCategory['image'];

            $category->appendTo($parent);

            if ($category->saveNode()) {
                ++$processedCout;
                echo $processedCout . ' / ' . $totalCount . PHP_EOL;
            }
        }
    }
}
