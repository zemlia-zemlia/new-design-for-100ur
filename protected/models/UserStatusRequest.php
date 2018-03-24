<?php

/**
 * Модель для работы с заявками на изменение статусов пользователей
 *
 * Доступные поля в таблице '{{userStatusRequest}}':
 * @property integer $id
 * @property integer $yuristId
 * @property integer $status
 * @property integer $isVerified
 * @property string $vuz
 * @property string $facultet
 * @property string $education
 * @property integer $vuzTownId
 * @property integer $educationYear
 * @property string $advOrganisation
 * @property string $advNumber
 * @property string $position
 */
class UserStatusRequest extends CActiveRecord {

    // статусы заявок
    const STATUS_NEW = 0; // новая заявка
    const STATUS_ACCEPTED = 1; // одобрено
    const STATUS_DECLINED = 2; // отклонено

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return '{{userStatusRequest}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('yuristId', 'required', 'message' => 'Поле {attribute} должно быть заполнено'),
            array('vuz, facultet, education, vuzTownId, educationYear', 'required', 'on'=>'createYurist', 'message' => 'Поле {attribute} должно быть заполнено'),
            array('advOrganisation, advNumber, position', 'required', 'on'=>'createAdvocat', 'message' => 'Поле {attribute} должно быть заполнено'),
            //array('', 'required', 'on'=>'createJudge', 'message' => 'Поле {attribute} должно быть заполнено'),
            array('yuristId, status, isVerified, vuzTownId, educationYear', 'numerical', 'integerOnly' => true),
            array('vuz, facultet, education, advOrganisation, advNumber, position', 'length', 'max' => 255),
            array('userFile', 'file', 'types' => 'jpg,gif,png,tiff,pdf', 'maxSize' => '10000000', 'allowEmpty' => true, 'message' => 'Неправильный формат загруженного файла'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, yuristId, status, isVerified, vuz, facultet, education, vuzTownId, educationYear, advOrganisation, advNumber, position', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'yuristId'),
            'vuzTown' => array(self::BELONGS_TO, 'Town', 'vuzTownId'),
            'userFile' => array(self::BELONGS_TO, 'UserFile', 'fileId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'yuristId' => 'ID юриста',
            'status' => 'Статус',
            'isVerified' => 'Проверен',
            'vuz' => 'ВУЗ',
            'facultet' => 'Факультет',
            'education' => 'Образование',
            'vuzTownId' => 'Город ВУЗа',
            'educationYear' => 'Год окончания учебного заведения',
            'advOrganisation' => 'членство в адвокатском объединении',
            'advNumber' => 'номер в реестре адвокатов',
            'position' => 'должность',
        );
    }

    // возвращает массив, ключами которого являются коды статусов верификации, а значениями - названия
    static public function getVerificationStatusesArray() {
        return array(
            self::STATUS_NEW => 'новый',
            self::STATUS_ACCEPTED => 'подтверждено',
            self::STATUS_DECLINED => 'отказ',
        );
    }

    public function getVerificationStatusName() {
        $statusesArray = self::getVerificationStatusesArray();
        $statusName = $statusesArray[$this->isVerified];
        return $statusName;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('yuristId', $this->yuristId);
        $criteria->compare('status', $this->status);
        $criteria->compare('isVerified', $this->isVerified);
        $criteria->compare('vuz', $this->vuz, true);
        $criteria->compare('facultet', $this->facultet, true);
        $criteria->compare('education', $this->education, true);
        $criteria->compare('vuzTownId', $this->vuzTownId);
        $criteria->compare('educationYear', $this->educationYear);
        $criteria->compare('advOrganisation', $this->advOrganisation, true);
        $criteria->compare('advNumber', $this->advNumber, true);
        $criteria->compare('position', $this->position, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserStatusRequest the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * проверка заполненности необходимых полей в зависимости от статуса
     */
    public function validateRequest() {
        switch ($this->status) {
            case YuristSettings::STATUS_YURIST:

                if ($this->vuz == '') {
                    $this->addError('vuz', 'Не указан ВУЗ');
                }
                if ($this->facultet == '') {
                    $this->addError('facultet', 'Не указан факультет');
                }
                if ($this->education == '') {
                    $this->addError('education', 'Не указан уровень образования');
                }
                if (!$this->vuzTownId) {
                    $this->addError('vuzTownId', 'Не указан город ВУЗа');
                }
                if (!$this->educationYear) {
                    $this->addError('educationYear', 'Не указан год окончания ВУЗа');
                }
                
                $this->validate(array('userFile'));
                
                break;
            case YuristSettings::STATUS_ADVOCAT:
                if ($this->advOrganisation == '') {
                    $this->addError('advOrganisation', 'Не указана организация');
                }
                if ($this->advNumber == '') {
                    $this->addError('advNumber', 'Не указан номер в реестре');
                }
                if ($this->position == '') {
                    $this->addError('position', 'Не указана должность');
                }
                break;
        }
    }

    /**
     * Отправка уведомления о смене статуса
     * 
     * @return boolean результат: true - отправлено, false - ошибка
     */
    public function sendNotification() {
        $user = $this->user;
        $email = $user->email;

        $mailer = new GTMail;

        $mailer->subject = "100 Юристов - Смена Вашего статуса";
        $mailer->message = "<p>" . CHtml::encode($user->name) . ", Ваша заявка на изменение статуса была проверена модератором.</p>"
                . "<p>Ваша заявка ";

        $mailer->message .= ($this->isVerified == self::STATUS_ACCEPTED) ? "одобрена" : "отклонена" . "</p>";

        if ($this->isVerified == self::STATUS_DECLINED && $this->comment != '') {
            $mailer->message .= "<p><strong>Причина отказа:</strong> " . CHtml::encode($this->comment) . "</p>";
        }

        $mailer->email = $email;

        if ($mailer->sendMail()) {
            return true;
        } else {
            return false;
        }
    }

    public static function getNewRequestsCount() {
        $counterRow = Yii::app()->db->cache(600)->createCommand()
                ->select('COUNT(*) counter')
                ->from("{{userStatusRequest}}")
                ->where('isVerified = 0')
                ->queryRow();
        return $counterRow['counter'];
    }

}
