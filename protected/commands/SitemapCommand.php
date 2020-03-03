<?php

class SitemapCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $siteUrl = Yii::app()->urlManager->baseUrl;

        $siteMap = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
           <url>
              <loc>' . $siteUrl . '</loc>
              <lastmod>2014-10-30</lastmod>
              <changefreq>daily</changefreq>
              <priority>0.9</priority>
           </url>';
        /*
        $categories = Yii::app()->db->createCommand()
                ->select('alias')
                ->from('{{questionCategory}}')
                ->queryAll();
        foreach($categories as $cat) {
            $siteMap .= '<url>
              <loc>' . $siteUrl . '/cat/' . CHtml::encode($cat['alias']) .  '/</loc>
              <lastmod>' . date('Y-m-d') . '</lastmod>
              <changefreq>weekly</changefreq>
              <priority>0.6</priority>
           </url>';
        }
        */

        $categories = QuestionCategory::model()->findAll();
        foreach ($categories as $cat) {
            $siteMap .= '<url>
              <loc>' . Yii::app()->createUrl('questionCategory/alias', $cat->getUrl()) . '</loc>
              <lastmod>' . date('Y-m-d') . '</lastmod>
              <changefreq>weekly</changefreq>
              <priority>0.6</priority>
           </url>';
        }

        $towns = Yii::app()->db->createCommand()
                ->select('t.alias town, r.alias region, c.alias country')
                ->from('{{town}} t')
                ->leftJoin('{{region}} r', 'r.id=t.regionId')
                ->leftJoin('{{country}} c', 'c.id=t.countryId')
                ->where('c.id=2')
                ->queryAll();
        foreach ($towns as $town) {
            $siteMap .= '<url>
              <loc>' . Yii::app()->createUrl('town/alias', ['countryAlias' => CHtml::encode($town['country']), 'regionAlias' => CHtml::encode($town['region']), 'name' => CHtml::encode($town['town'])]) . '</loc>
              <lastmod>' . date('Y-m-d') . '</lastmod>
              <changefreq>weekly</changefreq>
              <priority>0.4</priority>
           </url>';
        }

        $questions = Yii::app()->db->createCommand()
                ->select('id')
                ->from('{{question}}')
                ->where('status IN(:status1, :status2)', [':status1' => Question::STATUS_PUBLISHED, ':status2' => Question::STATUS_CHECK])
                ->queryAll();
        foreach ($questions as $question) {
            $siteMap .= '<url>
              <loc>' . Yii::app()->createUrl('question/view', ['id' => $question['id']]) . '</loc>
              <lastmod>' . date('Y-m-d') . '</lastmod>
              <changefreq>weekly</changefreq>
              <priority>0.3</priority>
           </url>';
        }

        $posts = Yii::app()->db->createCommand()
                ->select('id, alias')
                ->from('{{post}}')
                ->where('datePublication<:now', [':now' => date('Y-m-d')])
                ->queryAll();
        foreach ($posts as $post) {
            $siteMap .= '<url>
              <loc>' . Yii::app()->createUrl('post/view', ['id' => $post['id'], 'alias' => $post['alias']]) . '</loc>
              <lastmod>' . date('Y-m-d') . '</lastmod>
              <changefreq>weekly</changefreq>
              <priority>0.6</priority>
           </url>';
        }

        $siteMap .= '</urlset>';

        $siteMapFilePath = YiiBase::getPathOfAlias('application') . '/../sitemap.xml';

        $file = fopen($siteMapFilePath, 'w');
        fputs($file, $siteMap);
        fclose($file);
    }
}
