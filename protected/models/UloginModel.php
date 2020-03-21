<?php

namespace App\models;

use CHttpException;
use CModel;
use Exception;
use App\extensions\Logger\LoggerFactory;
use UloginUserIdentity;
use UserBannedException;
use Yii;

class UloginModel extends CModel
{
    public $identity;
    public $network;
    public $email;
    public $full_name;
    public $token;
    public $error_message;

    private $uloginAuthUrl = 'http://ulogin.ru/token.php?token=';

    public function rules()
    {
        return [
            ['identity,network,token', 'required'],
            ['email', 'email'],
            ['identity,network,email', 'length', 'max' => 255],
            ['full_name', 'length', 'max' => 55],
        ];
    }

    public function attributeLabels()
    {
        return [
            'network' => 'Сервис',
            'identity' => 'Идентификатор сервиса',
            'email' => 'eMail',
            'full_name' => 'Имя',
        ];
    }

    public function getAuthData()
    {
        $authData = json_decode(file_get_contents($this->uloginAuthUrl . $this->token . '&host=' . $_SERVER['HTTP_HOST']), true);

        $this->setAttributes($authData);

        $this->full_name = $authData['first_name'] . ' ' . $authData['last_name'];
    }

    /**
     * @return bool
     *
     * @throws CHttpException
     * @throws Exception
     * @throws UserBannedException
     */
    public function login()
    {
        $identity = new UloginUserIdentity($this);
        if ($identity->authenticate()) {
            $duration = 3600 * 24 * 30;
            Yii::app()->user->login($identity, $duration);

            LoggerFactory::getLogger('db')->log(Yii::app()->user->roleName . ' #' . Yii::app()->user->id . ' (' . Yii::app()->user->shortName . ') залогинился на сайте', 'User', Yii::app()->user->id);
            (new UserActivity())->logActivity(Yii::app()->user->getModel(), UserActivity::ACTION_LOGIN);

            return true;
        }

        return false;
    }

    public function attributeNames()
    {
        return [
            'identity',
            'network',
            'email',
            'full_name',
            'token',
            'error_type',
            'error_message',
        ];
    }
}
