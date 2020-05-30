<?php

use App\models\Question;
use App\models\QuestionCategory;

class SitemapCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $siteMapFilePath = YiiBase::getPathOfAlias('application') . '/../sitemap.xml';
        $siteUrl = Yii::app()->urlManager->baseUrl;

        $siteMap = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
           <url>
              <loc>' . $siteUrl . '</loc>
              <lastmod>2014-10-30</lastmod>
              <changefreq>daily</changefreq>
              <priority>0.9</priority>
           </url>';

        file_put_contents($siteMapFilePath, $siteMap);

        $categories = QuestionCategory::model()->findAll();
        foreach ($categories as $cat) {
            /** @var QuestionCategory $cat */
            $siteMapItem = '<url>
              <loc>' . Yii::app()->createUrl('questionCategory/alias', $cat->getUrl()) . '</loc>
              <lastmod>' . date('Y-m-d') . '</lastmod>
              <changefreq>weekly</changefreq>
              <priority>0.6</priority>
            </url>';
            file_put_contents($siteMapFilePath, $siteMapItem, FILE_APPEND);
        }

        $townsReader = Yii::app()->db->createCommand()
            ->select('t.alias town, r.alias region, c.alias country')
            ->from('{{town}} t')
            ->leftJoin('{{region}} r', 'r.id=t.regionId')
            ->leftJoin('{{country}} c', 'c.id=t.countryId')
            ->where('c.id=2')
            ->query();
        foreach ($townsReader as $town) {
            $siteMapItem = '<url>
              <loc>' . Yii::app()->createUrl('town/alias', ['countryAlias' => CHtml::encode($town['country']), 'regionAlias' => CHtml::encode($town['region']), 'name' => CHtml::encode($town['town'])]) . '</loc>
              <lastmod>' . date('Y-m-d') . '</lastmod>
              <changefreq>weekly</changefreq>
              <priority>0.4</priority>
           </url>';
            file_put_contents($siteMapFilePath, $siteMapItem, FILE_APPEND);
        }

        $questionsReader = Yii::app()->db->createCommand()
            ->select('id')
            ->from('{{question}}')
            ->where('status IN(:status1, :status2)', [':status1' => Question::STATUS_PUBLISHED, ':status2' => Question::STATUS_CHECK])
            ->query();
        foreach ($questionsReader as $question) {
            $siteMapItem = '<url>
              <loc>' . Yii::app()->createUrl('question/view', ['id' => $question['id']]) . '</loc>
              <lastmod>' . date('Y-m-d') . '</lastmod>
              <changefreq>weekly</changefreq>
              <priority>0.3</priority>
           </url>';
            file_put_contents($siteMapFilePath, $siteMapItem, FILE_APPEND);
        }

        $postsReader = Yii::app()->db->createCommand()
            ->select('id, alias')
            ->from('{{post}}')
            ->where('datePublication<:now', [':now' => date('Y-m-d')])
            ->query();
        foreach ($postsReader as $post) {
            $siteMapItem = '<url>
              <loc>' . Yii::app()->createUrl('post/view', ['id' => $post['id'], 'alias' => $post['alias']]) . '</loc>
              <lastmod>' . date('Y-m-d') . '</lastmod>
              <changefreq>weekly</changefreq>
              <priority>0.6</priority>
            </url>';
            file_put_contents($siteMapFilePath, $siteMapItem, FILE_APPEND);
        }

        file_put_contents($siteMapFilePath, '</urlset>', FILE_APPEND);
    }
}
