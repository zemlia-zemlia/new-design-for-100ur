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
        
        $towns = Yii::app()->db->createCommand()
                ->select('alias')
                ->from('{{town}}')
                ->queryAll();
        foreach($towns as $town) {
            $siteMap .= '<url>
              <loc>' . $siteUrl . '/konsultaciya-yurista-' . CHtml::encode($town['alias']) .  '/</loc>
              <lastmod>' . date('Y-m-d') . '</lastmod>
              <changefreq>weekly</changefreq>
              <priority>0.4</priority>
           </url>';
        }
        
        /*$questions = Question::model()->findAll(array(
                'condition'=>'status='.Question::STATUS_PUBLISHED,
            ));*/
        $questions = Yii::app()->db->createCommand()
                ->select('id')
                ->from('{{question}}')
                ->where('status=:status', array(':status'=>Question::STATUS_PUBLISHED))
                ->queryAll();
        foreach($questions as $question) {
            $siteMap .= '<url>
              <loc>' . $siteUrl . '/q/' . $question['id'] .  '/</loc>
              <lastmod>' . date('Y-m-d') . '</lastmod>
              <changefreq>weekly</changefreq>
              <priority>0.3</priority>
           </url>';
        }
        
        /*$posts = Post::model()->findAll(array(
                'condition'=>'t.datePublication<NOW()',
            ));*/
        $posts = Yii::app()->db->createCommand()
                ->select('id')
                ->from('{{post}}')
                ->where('datePublication<:now', array(':now'=>date('Y-m-d')))
                ->queryAll();
        foreach($posts as $post) {
            $siteMap .= '<url>
              <loc>' . $siteUrl . '/post/' . $post['id'] .  '/</loc>
              <lastmod>' . date('Y-m-d') . '</lastmod>
              <changefreq>weekly</changefreq>
              <priority>0.6</priority>
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
