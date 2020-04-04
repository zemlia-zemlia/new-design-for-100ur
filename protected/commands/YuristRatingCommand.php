<?php

/*
 * команда вычисляет рейтинг юристов и записывает его в поле rating
 */

use App\models\User;

class YuristRatingCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        /*
         * Вычисляем отношение кармы к числу ответов юриста
         *
         * SELECT u.id, u.name, u.lastName, u.karma, COUNT(u.id) answers, (u.karma/COUNT(u.id)) ratio
            FROM `100_user` u
            LEFT JOIN `100_answer` a ON a.authorId = u.id
            WHERE u.role=10 AND u.active100=1
            GROUP BY a.authorId
            HAVING answers>30
            ORDER BY ratio DESC
         */

        $ratingRows = Yii::app()->db->createCommand()
                ->select('u.id, u.karma, COUNT(u.id) answers, (u.karma/COUNT(u.id)) ratio')
                ->from('{{user}} u')
                ->leftJoin('{{answer}} a', 'a.authorId = u.id')
                ->where('u.role=:role AND u.active100=1 AND a.status!=2', [':role' => User::ROLE_JURIST])
                ->group('a.authorId')
                ->having('answers>30')
                ->order('ratio DESC')
                ->queryAll();

        // обновляем записи юристов
        foreach ($ratingRows as $row) {
            Yii::app()->db->createCommand()
                    ->update('{{user}}', ['rating' => $row['ratio']], 'id=:id', [':id' => $row['id']]);
        }
    }

    /**
     * Пересчет званий юристов.
     */
    public function actionRang()
    {
        $startTime = microtime(true);

        $yurists = User::model()->findAllByAttributes(['role' => User::ROLE_JURIST, 'active100' => 1]);

        foreach ($yurists as $yurist) {
            $yurist->detectRang();
        }

        $finishTime = microtime(true);
        $executionTime = round($finishTime - $startTime, 3);

        echo 'Processed ' . sizeof($yurists) . ' records in ' . $executionTime . ' seconds';
    }
}
