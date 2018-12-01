<?php

/*
 * Сбрасывает пароли всех пользователей, генерируя каждому уникальный пароль
 * Необходимо при смене алгоритма шифрования пароля
 */

class UpdateAllPasswordsCommand extends CConsoleCommand
{

    public function actionIndex()
    {
        echo "======= Resetting all users passwords =========" . PHP_EOL;
        $users = Yii::app()->db->createCommand()
                ->select('id')
                ->from('{{user}}')
                ->queryAll();
        echo 'Users found: ' . sizeof($users) . PHP_EOL;
        
        foreach($users as $user) {
            $newPassword =  User::hashPassword(User::generatePassword(8));
            $updateResult = Yii::app()
                    ->db
                    ->createCommand()
                    ->update('{{user}}', ['password' => $newPassword], 'id=:id', [':id' => $user['id']]);
            
            if($updateResult) {
                echo 'User id ' . $user['id'] . ' updated' . PHP_EOL;
            } else {
                echo 'Error: User id ' . $user['id'] . ' NOT updated' . PHP_EOL;
            }
        }
    }
    
    public function actionTest()
    {
        $password = '123456';
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        echo 'Password: ' . $password . PHP_EOL;
        echo 'Password hash: ' . $passwordHash . PHP_EOL;
        $checkResult = password_verify($password, $passwordHash);
        echo 'Verification: ' . $checkResult . PHP_EOL;
    }

}
