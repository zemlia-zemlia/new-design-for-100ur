<?php

/**
 * This is the model class for table "{{mail}}".
 *
 * The followings are the available columns in table '{{mail}}':
 * @property integer $id
 * @property string $createDate
 * @property string $subject
 * @property string $message
 */
class Mail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mail}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('subject, message', 'required'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, createDate, subject, message', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'createDate' => 'Дата и время создания',
			'subject' => 'Тема',
			'message' => 'Сообщение',
		);
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('createDate',$this->createDate,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('message',$this->message,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Mail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * Отправка рассылки
     * @param integer $limit Сколько писем отправлять
     * @param bool $useSMTP Использовать ли SMTP сервер
     * @return integer Количество отправленных писем
     */
    public static function sendTasks($limit = 100, $useSMTP = false)
    {
        // разрешим скрипту работать долго
        ini_set('max_execution_time', 600);

        $mailsSent = 0;

        $tasks = Yii::app()->db->createCommand()
            ->select('t.id, m.subject, m.message, t.status, u.autologin, t.email')
            ->from('{{mailtask}} t')
            ->leftJoin('{{mail}} m', 't.mailId = m.id')
            ->leftJoin('{{user}} u', 'u.id = t.userId')
            ->where('status = :status AND t.startDate >= :startDate', [':status' => Mailtask::STATUS_NOT_SENT, ':startDate' => date('Y-m-d')])
            ->limit($limit)
            ->queryAll();

        foreach ($tasks as $task) {
            $mailer = new GTMail($useSMTP);

            $mailer->subject = $task['subject'];
            $mailer->message = $task['message'];

            if($task['autologin'] != '') {
                $autologinLink = Yii::app()->createUrl('/site/index', ['autologin' => $task['autologin']]);
                $mailer->message .= '<p>Ваша ссылка для входа на сайт без ввода пароля (ссылка действительна один раз):' .
                    CHtml::link($autologinLink, $autologinLink)
                    . '</p>';
            }

            $mailer->email = $task['email'];
            if ($mailer->sendMail()) {
                Yii::app()->db->createCommand()
                    ->update('{{mailtask}}', ['status' => Mailtask::STATUS_SENT], 'id=:id', [':id' => $task['id']]);
                $mailsSent++;
            }
        }

        return $mailsSent;
    }
}