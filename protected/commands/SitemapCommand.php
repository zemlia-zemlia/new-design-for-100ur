<?php

class SitemapCommand extends CConsoleCommand
{
    // рассылка уведомлений
    public function actionIndex()
    {
        $siteUrl = "http://www.100yuristov.com";
        
        $siteMap = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
           <url>
              <loc>' . $siteUrl . '</loc>
              <lastmod>2014-10-30</lastmod>
              <changefreq>daily</changefreq>
              <priority>0.9</priority>
           </url>';
        
        $categories = QuestionCategory::model()->findAll();
        foreach($categories as $cat) {
            $siteMap .= '<url>
              <loc>' . $siteUrl . '/cat/' . CHtml::encode($cat->alias) .  '/</loc>
              <lastmod>2014-10-30</lastmod>
              <changefreq>weekly</changefreq>
              <priority>0.5</priority>
           </url>';
        }
        
        $towns = Town::model()->findAll();
        foreach($towns as $town) {
            $siteMap .= '<url>
              <loc>' . $siteUrl . '/konsultaciya-yurista-' . CHtml::encode($town->alias) .  '/</loc>
              <lastmod>' . date('Y-m-d') . '</lastmod>
              <changefreq>weekly</changefreq>
              <priority>0.5</priority>
           </url>';
        }
        
        $questions = Question::model()->findAll(array(
                'condition'=>'status='.Question::STATUS_PUBLISHED,
            ));
        foreach($questions as $question) {
            $siteMap .= '<url>
              <loc>' . $siteUrl . '/q/' . $question->id .  '/</loc>
              <lastmod>' . date('Y-m-d') . '</lastmod>
              <changefreq>weekly</changefreq>
              <priority>0.5</priority>
           </url>';
        }
        
        $siteMap .= '</urlset>';
        
        $siteMapFilePath = YiiBase::getPathOfAlias('application') . '/../sitemap.xml';
        
        $file = fopen($siteMapFilePath, 'w');
        fputs($file,$siteMap);
        fclose($file);
    }
}
?>
