<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property string $id
 * @property string $name
 * @property string $name2
 * @property string $lastName
 * @property integer $role
 * @property string $position
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property integer $active
 * @property string $managerId
 * @property string $townId
 * @property string $registerDate
 */
class User extends CActiveRecord
{
	
        public $password2; //password confirmation while creating or changing account
        public $verifyCode; // using in Captcha
        //
        // константы для ролей пользователей
        const ROLE_SECRETARY = 0;
        const ROLE_OPERATOR = 2;
        const ROLE_CLIENT = 3;
        const ROLE_EDITOR = 5;
        const ROLE_BUYER = 6;
        const ROLE_EXECUTOR = 8;
        const ROLE_JURIST = 10;
        const ROLE_MANAGER = 20;
        const ROLE_ROOT = 100;
        
        const USER_PHOTO_PATH = "/upload/userphoto";
        const USER_PHOTO_THUMB_FOLDER = "/thumbs";
        const DEFAULT_AVATAR_FILE = "/pics/yurist.png";
        /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, email, phone, townId', 'required', 'message'=>'Поле {attribute} должно быть заполнено'),
			array('role, active, managerId, townId', 'numerical', 'integerOnly'=>true),
			array('name, position, email, phone', 'length', 'max'=>255),
                        array('name2, lastName', 'safe'),
                        array('townId', 'match','not'=>true, 'pattern'=>'/^0$/', 'message'=>'Поле Город не заполнено'),
                        array('password','length','min'=>5,'max'=>128, 'tooShort'=>'Минимальная длина пароля 5 символов', 'allowEmpty'=>($this->scenario=='update' || $this->scenario=='register')),
                        array('password2', 'compare', 'compareAttribute'=>'password', 'except'=>'confirm, create, register, update', 'message'=>'Пароли должны совпадать','allowEmpty'=>($this->scenario=='update' || $this->scenario=='register')),
			array('email','email', 'message'=>'В Email допускаются латинские символы, цифры, точка и дефис'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, role, position, email, phone, password, active, manager', 'safe', 'on'=>'search'),
		);
	}
        
        // возвращает асоциативный массив, ключами которого являются 
        // коды ролей пользователей, а значениями - названия ролей
        static public function getRoleNamesArray()
        {
            return array(
                self::ROLE_SECRETARY    =>  'секретарь',
                self::ROLE_OPERATOR     =>  'оператор call-центра',
                self::ROLE_CLIENT       =>  'пользователь',
                self::ROLE_EDITOR       =>  'контент-менеджер',
                self::ROLE_JURIST       =>  'юрист',
                self::ROLE_MANAGER      =>  'руководитель',
                self::ROLE_ROOT         =>  'администратор',
                self::ROLE_BUYER        =>  'покупатель лидов',
            );
        }
        
        public function getRoleName()
        {
            $rolesNames = self::getRoleNamesArray();
            $roleName = $rolesNames[(int)$this->role];
            return $roleName;
        }

        // возвращает массив объектов класса User, у которых роль Менеджер
        static public function getManagers()
        {
            $managers = User::model()->findAllByAttributes(array(
                'role'      =>  self::ROLE_MANAGER,
                'active'    =>  1,
            ));
            return $managers;
        }
        
        // возвращает массив, ключами которого являются id менеджеров
        // а значениями - их имена
        static public function getManagersNames()
        {
            $managers = self::getManagers();
            $managersNames = array('0'  =>  'нет руководителя');
            foreach($managers as $manager) {
                $managersNames[$manager->id] = $manager->name;
            }
            return $managersNames;
        }
        
        // возвращает массив, ключами которого являются id активных юристов, а значениями - их имена
        public function getAllJuristsIdsNames()
        {
            $allJurists = array();    
            $jurists = User::model()->findAllByAttributes(array(
                'role'      =>  self::ROLE_JURIST,
                'active'    =>  1,
            ));
            foreach($jurists as $jurist) {
                $allJurists[$jurist->id] = $jurist->name;
            }
            return $allJurists;
        }
        
        
        // возвращает массив, ключами которого являются id активных покупателей, а значениями - их имена
        public function getAllBuyersIdsNames()
        {
            $allBuyers = array();    
            $buyers = User::model()->findAllByAttributes(array(
                'role'      =>  self::ROLE_BUYER,
                'active'    =>  1,
            ));
            foreach($buyers as $buyer) {
                $allBuyers[$buyer->id] = $buyer->lastName . ' ' . $buyer->name;
            }
            return $allBuyers;
        }
        

                // активация пользователя
        public function activate()
	{
	  $this->active = 1;
	}

        /**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                    'manager'   =>  array(self::BELONGS_TO, 'User', 'managerId'),
                    'settings'  =>  array(self::HAS_ONE, 'YuristSettings', 'yuristId'),
                    'town'      =>  array(self::BELONGS_TO, 'Town', 'townId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'        => 'ID',
			'name'      => 'Имя',
                        'name2'     => 'Отчество',
                        'lastName'  => 'Фамилия',
			'role'      => 'Роль',
			'position'  => 'Должность',
			'email'     => 'Email',
			'phone'     => 'Телефон',
			'password'  => 'Пароль',
                        'password2' => 'Пароль еще раз',
			'active'    => 'Активность',
			'managerId' => 'Руководитель',
                        'birthday'  => 'Дата рождения',
                        'townId'    => 'Город',
                        'town'      => 'Город',
                        'avatarFile'=> 'Фотография',
                        'registerDate'  =>  'Дата регистрации',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('role',$this->role);
		$criteria->compare('position',$this->position,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('managerId',$this->manager,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        
    protected function beforeSave()
    {
        if(parent::beforeSave())
            {
            if($this->isNewRecord || (strlen($this->password)<128))
            {
                $this->password = self::hashPassword($this->password);
            }
        return true;
        }
        else
        return false;
    }
    
        // отправляет пользователю письмо со ссылкой на подтверждение email. 
        // Если указан параметр $newPassword, он будет выслан в письме 
        // как новый пароль
        public function sendConfirmation($newPassword = null)
        {
            
            $mailer = new GTMail;
            
            $confirmLink = CHtml::decode("http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('user/confirm', array(
                'email'=>$this->email,
                'code'=>$this->confirm_code,
                ))) . "?utm_source=100yuristov&utm_medium=mail&utm_campaign=user_registration";
            
            $mailer->subject = "100 юристов - Регистрация пользователя";
            $mailer->message = "
                <h1>Регистрация на сайте 100 юристов</h1>
                <p>Здравствуйте!<br />
                Вы зарегистрировались на сайте <a href='http://".$_SERVER['SERVER_NAME']."'>100 юристов</a></p>".
            "<p>Для того, чтобы начать пользоваться всеми возможностями сайта, необходимо подтвердить свой email. Для этого перейдите по ссылке:</p>".
            "<p><strong>" . CHtml::link($confirmLink,$confirmLink) . "</strong></p>";
            
            if($newPassword) {
                $mailer->message .= "<h2>Ваш временный пароль</h2>
                <p>После подтверждения Email вы сможете войти на сайт, используя временный пароль <strong>" . $newPassword . "</strong></p>";
            }
            $mailer->email = $this->email;
            
            if($mailer->sendMail()) {
                return true;
            } else {
                return false;
            }
            
        }
        
        // изменяет пароль пользователя на $newPassword, высылает ему на 
        // почту новый пароль
        public function changePassword($newPassword)
        {
            if(empty($newPassword)) $newPassword=self::generatePassword(6);
            {
                $this->password = $newPassword;
                $this->password2 = $newPassword;
                if($this->save())
                {
                   if($this->sendChangedPassword($newPassword)) {
                       return true;
                   } else {
                       // не удалось отправить письмо с новым паролем пользователю
                       return false;
                   }
                } else { 
                    // не удалось сохранить объект пользователя с новым паролем
                    if(YII_DEBUG === true) {
                        CustomFuncs::printr($this->errors);
                    }
                    return false;
                }
            }
            
        }
        
        // отправляет пользователю его пароль (используем после активации аккаунта)
        public function sendNewPassword($newPassword)
        {
            $mailer = new GTMail;
            $mailer->subject = CHtml::encode($this->name) . ", Ваш пароль для личного кабинета 100 юристов";
            $mailer->message = "Здравствуйте!<br />
                Вы упешно зарегистрировались на портале 100 юристов.<br /><br />
                Ваш логин: " . CHtml::encode($this->email) . "<br />
                Ваш временный пароль: ".$newPassword."<br /><br />
                Вы всегда можете поменять его на любой другой, зайдя в ".CHtml::link("личный кабинет","http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('site/login'))." на нашем сайте.<br /><br />
                <br /><br />";
            $mailer->email = $this->email;
            
            if($mailer->sendMail()) {
                return true;
            } else {
                // не удалось отправить письмо
                return false;
            }
        }


        // высылает пароль $newPassword на email пользователю
        public function sendChangedPassword($newPassword)
        {
            $mailer = new GTMail;
            $mailer->subject = "Смена пароля пользователя";
            $mailer->message = "Здравствуйте!<br />
                Вы или кто-то, указавший ваш E-mail, запросил восстановление пароля на портале 100 юристов.<br /><br />
                Ваш временный пароль: ".$newPassword."<br /><br />
                Вы всегда можете поменять его на любой другой, зайдя в ".CHtml::link("личный кабинет","http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('site/login'))." на нашем сайте.<br /><br />
                Если Вы не запрашивали восстановление пароля, обратитесь, пожалуйста, к администратору сайта. <br /><br />";
            $mailer->email = $this->email;
            
            if($mailer->sendMail()) {
                return true;
            } else {
                // не удалось отправить письмо
                return false;
            }
            
        }
        
        // генерирует пароль длиной $len символов
        public function generatePassword($len = 6)
        {
            
            return substr(md5(mt_rand().mt_rand()), mt_rand(1,15), $len);
        }
        
        //Takes a password and returns the salted hash
        //$password - the password to hash
        //returns - the hash of the password (128 hex characters)
        public function hashPassword($password)
        {
            $salt = bin2hex(mcrypt_create_iv(32)); //get 256 random bits in hex
            $hash = hash("sha256", $salt . $password); //prepend the salt, then hash
            //store the salt and hash in the same string, so only 1 DB column is needed
            $final = $salt . $hash;
            return $final;
        }

        //Validates a password
        //returns true if hash is the correct hash for that password
        //$this->password - the hash created by HashPassword (stored in your DB)
        //$password - the password to verify
        //returns - true if the password is valid, false otherwise.
        public function validatePassword($password)
        {
            $salt = substr($this->password, 0, 64); //get the salt from the front of the hash
            $validHash = substr($this->password, 64, 64); //the SHA256

            $testHash = hash("sha256", $salt . $password); //hash the password being tested
            
            //if the hashes are exactly the same, the password is valid
            return $testHash === $validHash;
        } 
        
        // возвращает массив объектов класса User, которые являются подчиненными менеджера
        public function myEmployees()
        {
            $myEmployeesArray = User::model()->findAllByAttributes(array('active'=>1,'managerId'=>$this->id));
            return $myEmployeesArray;
        }
        
        // возвращает массив id подчиненных
        public function myEmployeesIds()
        {
            $myEmployees = array();
            $myEmployeesArray = $this->myEmployees();
            foreach($myEmployeesArray as $myEmployee) {
                $myEmployees[] = $myEmployee['id'];
            }
            return $myEmployees;
        }
        
        // возвращает URL аватара текущего пользователя. Если аватар не задан, возвращает пустую строку
        public function getAvatarUrl($size = 'thumb')
        {
            if(!$this->avatar) {
                return self::DEFAULT_AVATAR_FILE;
            }
            $avatarFolder = User::USER_PHOTO_PATH;
            if($size == 'thumb') {
                $avatarFolder .= User::USER_PHOTO_THUMB_FOLDER; 
            }
            return $avatarFolder . "/" . $this->avatar;
        }
        
        public function getShortName()
        {
            return $this->lastName . '&nbsp;' . mb_substr($this->name, 0,2) . '.' . mb_substr($this->name2,0,2) . '.';
            //return $this->lastName;
        }
        
        public function publishNewQuestions()
        {
            Yii::app()->db->createCommand()
                    ->update('{{question}}', array('status'=>Question::STATUS_CHECK, 'publishDate'=>date('Y-m-d H:i:s')),
                            'authorId!=0 AND authorId=:authorId AND status=:statusOld',
                            array(':authorId'=>$this->id, ':statusOld'=>  Question::STATUS_NEW)); 
                    
        }
        
        
        public function sendAnswerNotification($question, $answer)
        {
            if($this->active == 0) {
                return;
            }
            
            if(!$question) {
                return;
            }
            
            $questionLink = "http://www.100yuristov.com/q/" . $question->id . "/?utm_source=100yuristov&utm_medium=mail&utm_campaign=answer_notification&utm_term=" . $question->id;
            
            $mailer = new GTMail;
            $mailer->subject = CHtml::encode($this->name) . ", новый ответ на Ваш вопрос!";
            $mailer->message = "<h1>Новый ответ на Ваш вопрос</h1>
                <p>Здравствуйте, " . CHtml::encode($this->name) . "<br /><br />
                Спешим сообщить, что на " . CHtml::link("Ваш вопрос",$questionLink) . " получен новый ответ юриста " . CHtml::encode($answer->author->name . ' ' . $answer->author->lastName) . ".
                <br /><br />
                Будем держать Вас в курсе поступления других ответов. 
                <br /><br />
                " . CHtml::link("Посмотреть ответ",$questionLink, array('class'=>'btn')) . "
                </p>";
            $mailer->email = $this->email;
            
            if($mailer->sendMail(true, '100yuristov')) {
                return true;
            } else {
                // не удалось отправить письмо
                return false;
            }
        }

}