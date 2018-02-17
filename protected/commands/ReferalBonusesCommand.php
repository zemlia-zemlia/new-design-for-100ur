<?php

/**
 * Консольный скрипт, начисляющий пользователям бонусы за привлеченных пользователей
 * по реферальной программе
 */
class ReferalBonusesCommand extends CConsoleCommand {

    public function actionIndex() {
        /*
          SELECT u.id, u.name, t.id FROM `100_user` u
          LEFT JOIN `100_partnertransaction` t ON t.userId = u.id
          WHERE u.active100=1 AND u.refId!=0 AND t.id IS NULL
         */
        $referalsRows = Yii::app()->db->createCommand()
                ->select('u.id, u.name')
                ->from('{{user}} u')
                ->leftJoin('{{partnertransaction}} t', 't.userId = u.id')
                ->where('u.active100=1 AND u.refId!=0 AND t.id IS NULL')
                ->queryAll();

        // запишем массив id рефералов, чтобы потом вытащить их в виде объектов
        $referalsIds = [];

        foreach ($referalsRows as $row) {
            $referalsIds[] = $row['id'];
        }

        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $referalsIds);
        $criteria->with = ["answersCount", "questionsCount"];
        $referals = User::model()->findAll($criteria);

        //print_r($referals);

        foreach ($referals as $referal) {
            // для каждого реферала вычисляем, какой за него положен бонус
            $bonus = (int) $referal->referalOk();

            // если бонус пока не положен, проверяем следующего
            if ($bonus == 0) {
                continue;
            }

            $transaction = new PartnerTransaction;
            $transaction->userId = $referal->id;
            $transaction->sum = $bonus;
            $transaction->comment = "Реферальный бонус";
            $transaction->partnerId = $referal->refId; // кто пригласил
            if ($transaction->save()) {
                if (Yii::app()->db->createCommand("UPDATE {{user}} SET balance = balance+" . $bonus . " WHERE id=" . $referal->refId)->query()) {
                    echo 'баланс пополнен' . PHP_EOL;
                }
            }
        }
    }

}
