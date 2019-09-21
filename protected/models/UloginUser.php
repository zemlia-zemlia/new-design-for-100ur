<?php

/**
 * This is the model class for table "ulogin_user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $identity
 * @property string $network
 * @property string $email
 * @property string $full_name
 * @property integer $state
 * @property integer $user_id
 */
class UloginUser extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'ulogin_user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('identity, network', 'required'),
            array('identity, network, email', 'length', 'max' => 255),
            array('full_name', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, identity, network, email, full_name, state', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'user' => [self::BELONGS_TO, 'User', 'user_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'identity' => 'Identity',
            'network' => 'Network',
            'email' => 'Email',
            'full_name' => 'Full Name',
            'state' => 'State',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('identity', $this->identity, true);
        $criteria->compare('network', $this->network, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('full_name', $this->full_name, true);
        $criteria->compare('state', $this->state);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Создает новый объект UloginUser
     * @param UloginModel $uloginModel
     * @param User|null $user Пользователь 100 Юристов, если не указан, создадим нового
     * @return UloginUser
     * @throws UserBannedException
     * @throws Exception
     */
    public static function create(UloginModel $uloginModel, ?User $user = null): UloginUser
    {
        $uloginUser = new static();

        if (is_null($user)) {

            $user = User::model()->find('email=:email', [
                ':email' => $uloginModel->email,
            ]);

            if ($user instanceof User && $user->active100 == 0) {
                throw new UserBannedException('Пользователь заблокирован на сайте 100 Юристов, логин невозможен');
            }
            if (is_null($user)) {
                $user = new User();
                $user->name = $uloginModel->full_name;
                $user->role = User::ROLE_CLIENT;
                $user->email = $uloginModel->email;
                $user->active100 = 1;
                $user->password = $user->password2 = User::hashPassword($user->generatePassword());
                $user->confirm_code = md5($user->email . mt_rand(100000, 999999));
                $user->townId = 598;

                if (!$user->save()) {
                    throw new Exception('Не удалось создать пользователя');
                }
            }
        }

        $userId = $user->id;
        $uloginUser->user_id = $userId;
        $uloginUser->identity = $uloginModel->identity;
        $uloginUser->network = $uloginModel->network;
        $uloginUser->email = $uloginModel->email;
        $uloginUser->full_name = $uloginModel->full_name;

        if (!$uloginUser->save()) {
            throw new Exception('Не удалось сохранить данные об аккаунте соцсети');
        }

        return $uloginUser;
    }
}