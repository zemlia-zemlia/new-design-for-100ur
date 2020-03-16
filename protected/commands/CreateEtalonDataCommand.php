<?php

use App\models\User;

/**
 * Создание эталонной таблицы пользователей
 * первые 100 и последние 200 активных.
 */
class CreateEtalonDataCommand extends CConsoleCommand
{
    private $names = [
        'Александр',
        'Максим',
        'Артём',
        'Михаил',
        'Даниил',
        'Иван',
        'Дмитрий',
        'Кирилл',
        'Андрей',
        'Егор',
        'Илья',
    ];
    private $names2 = [
        'Александрович',
        'Максимович',
        'Артёмович',
        'Михайлович',
        'Даниилович',
        'Иванович',
        'Дмитриевич',
        'Кириллович',
        'Андреевич',
        'Егорович',
        'Ильич',
    ];
    private $lastNames = [
        'Смирнов',
        'Иванов',
        'Кузнецов',
        'Попов',
        'Соколов',
        'Лебедев',
        'Козлов',
        'Новиков',
        'Морозов',
        'Петров',
        'Волков',
        'Соловьев',
        'Васильев',
        'Зайцев',
        'Павлов',
        'Семенов',
    ];

    private function generateFioField($fieldName)
    {
        $sourceArray = [];
        switch ($fieldName) {
            case 'name':
                $sourceArray = $this->names;
                break;
            case 'name2':
                $sourceArray = $this->names2;
                break;
            case 'lastName':
                $sourceArray = $this->lastNames;
                break;
        }

        return $sourceArray[mt_rand(0, sizeof($sourceArray) - 1)];
    }

    /**
     * Удаляем у пользователей персональные данные, ставим всем пароль 12345 и рандомные имена.
     */
    public function actionUsers()
    {
        echo '==== Создаем из пользователей роботов =======' . PHP_EOL;
        $users = Yii::app()->db->createCommand()
                ->select('*')
                ->from('{{user}}')
                ->order('id ASC')
                ->queryAll();

        echo 'Нашлось пользователей: ' . sizeof($users) . PHP_EOL;

        foreach ($users as $counter => $user) {
            $newName = $this->generateFioField('name');
            $newName2 = $this->generateFioField('name2');
            $newLastName = $this->generateFioField('lastName');
            $newEmail = $user['id'] . '.test@100yuristov.com';
            $newPassword = User::hashPassword('12345'); // у всех будет один пароль
            $newPhone = '7999' . mt_rand(1000000, 9999999);

            echo $user['id'] . ' | ' . $newName . ' | ' .
                    $newName2 . ' | ' . $newLastName . ' | ' .
                    $newEmail . ' | ' . $newPhone . PHP_EOL;

            if (Yii::app()->db->createCommand()->update('{{user}}', [
                    'name' => $newName,
                    'name2' => $newName2,
                    'lastName' => $newLastName,
                    'email' => $newEmail,
                    'password' => $newPassword,
                    'phone' => $newPhone,
                ], 'id=:id', [':id' => $user['id']])) {
                echo 'saved' . PHP_EOL;
            }
        }
    }
}
