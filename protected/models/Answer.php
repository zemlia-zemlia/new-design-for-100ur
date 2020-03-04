<?php

/**
 * Класс для работы с ответами.
 *
 * The followings are the available columns in table '{{answer}}':
 *
 * @property int    $id
 * @property int    $questionId
 * @property string $answerText
 * @property string $videoLink
 * @property int    $authorId
 * @property int    $status
 * @property string $datetime
 * @property int    $transactionId
 *
 * @author Michael Krutikov m@mkrutikov.pro
 */
class Answer extends CActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_SPAM = 2;
    // время на редактирование юристом собственного ответа (сек.)
    const EDIT_TIMEOUT = 4800;

    const ANSWER_MIN_LENGTH = 50;

    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className active record class name
     *
     * @return Answer the static model class
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
        return '{{answer}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'answer';
    }

    /**
     * @return array правила валидации для атрибутов модели
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['questionId', 'required', 'message' => 'Поле {attribute} должно быть заполнено'],
            ['questionId, authorId, status', 'numerical', 'integerOnly' => true],
            ['videoLink', 'url'],
            ['answerText', 'required', 'except' => 'addVideo', 'message' => 'Не введен текст ответа'],
            ['answerText', 'length', 'except' => 'addVideo', 'min' => self::ANSWER_MIN_LENGTH, 'tooShort' => 'Текст ответа слишком короткий (минимум ' . self::ANSWER_MIN_LENGTH . ' символов)'],
            ['answerText', 'validateText', 'except' => 'addVideo', 'message' => 'Текст ответа содержит запрещенные слова (например, Email адреса)'],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['id, questionId, answerText, authorId', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'question' => [self::BELONGS_TO, 'Question', 'questionId'],
            'author' => [self::BELONGS_TO, 'User', 'authorId'],
            'karmaChanges' => [self::HAS_MANY, 'KarmaChange', 'answerId'],
            'comments' => [self::HAS_MANY, 'Comment', 'objectId', 'condition' => 'comments.type=' . Comment::TYPE_ANSWER, 'order' => 'comments.root, comments.lft'],
            'transaction' => [self::BELONGS_TO, 'TransactionCampaign', 'transactionId'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'questionId' => 'ID вопроса',
            'question' => 'Вопрос',
            'answerText' => 'Ответ',
            'authorId' => 'ID автора',
            'status' => 'Статус',
            'videoLink' => 'Ссылка на Youtube видео',
            'transactionId' => 'ID транзакции бонуса',
        ];
    }

    /**
     * возвращает массив, ключами которого являются коды статусов, а значениями - названия статусов.
     *
     * @return array массив статусов
     */
    public static function getStatusesArray()
    {
        return [
            self::STATUS_NEW => 'Предварительно опубликован',
            self::STATUS_PUBLISHED => 'Опубликован',
            self::STATUS_SPAM => 'Спам',
        ];
    }

    /**
     * возвращает название статуса для объекта.
     *
     * @return string название статуса
     */
    public function getAnswerStatusName()
    {
        $statusesArray = self::getStatusesArray();

        return $statusesArray[$this->status];
    }

    /**
     * статический метод, возвращает название статуса вопроса по коду.
     *
     * @param int $status код статуса
     *
     * @return string название статуса
     */
    public static function getStatusName($status)
    {
        $statusesArray = self::getStatusesArray();

        return $statusesArray[$status];
    }

    /**
     * Возвращает массив объектов на основе критерия поиска.
     *
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('questionId', $this->questionId);
        $criteria->compare('answerText', $this->answerText, true);
        $criteria->compare('authorId', $this->authorId);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Вызывается после сохранения объекта в базу.
     */
    protected function afterSave()
    {
        LoggerFactory::getLogger('db')->log('Юрист ' . $this->author->getShortName() . ' ответил на вопрос #' . $this->questionId, 'User', $this->authorId);
        (new UserActivity())->logActivity($this->author, UserActivity::ACTION_ANSWER_QUESTION);

        $questionAuthor = $this->question->author;
        if ($questionAuthor && 1 == $questionAuthor->active100 && true === $this->isNewRecord) {
            $questionAuthor->sendAnswerNotification($this->question, $this);
        }

        parent::afterSave();
    }

    /**
     * Получает код видео из ссылки на видео на Youtube.
     */
    public function getVideoCode()
    {
        $videoCode = '';
        if ($this->videoLink) {
            preg_match("/v=([0-9a-zA-Z\-_]+)/", $this->videoLink, $videoIdMatches);
            if ($videoIdMatches[1]) {
                $videoCode = $videoIdMatches[1];
            }
        }

        return $videoCode;
    }

    /**
     * Валидатор, проверяющий текст ответа на наличие запрещенных слов.
     *
     * @param type $attribute
     * @param type $params
     */
    public function validateText($attribute, $params)
    {
        if (preg_match("/([a-zA-Z0-9\-\.]+)@([a-zA-Z0-9\-\.]+)/u", $this->$attribute)) {
            $this->addError($attribute, 'Текст ответа содержит недопустимые символы');
        }
    }

    /**
     * @param Question $question
     *
     * @return CActiveDataProvider
     */
    public function getAnswersDataProviderByQuestion(Question $question)
    {
        $criteria = new CDbCriteria();
        $criteria->order = 't.id ASC';
        $criteria->with = 'comments';
        $criteria->addColumnCondition(['t.questionId' => $question->id]);

        $answersDataProvider = new CActiveDataProvider(Answer::class, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $answersDataProvider;
    }

    /**
     * Зачисляет юристу бонус за хороший ответ
     */
    public function payBonusForGoodAnswer()
    {
        $user = $this->author;

        $isFast = $this->isFast();

        $bonusAmount = Yii::app()->params['yuristBonus']['bonusForGoodAnswer'];
        $fastAnswerCoefficient = Yii::app()->params['yuristBonus']['fastAnswerCoefficient'];
        if ($isFast) {
            $bonusAmount *= $fastAnswerCoefficient;
        }

        $bonusTransaction = new TransactionCampaign();
        $bonusTransaction->sum = $bonusAmount;
        $bonusTransaction->type = TransactionCampaign::TYPE_ANSWER;
        $bonusTransaction->description = 'Вознаграждение за подробный' . ($isFast ? ' и быстрый' : '') . ' ответ';
        $bonusTransaction->buyerId = $user->id;
        $bonusTransaction->status = TransactionCampaign::STATUS_COMPLETE;

        if ($bonusTransaction->save()) {
            $this->transactionId = $bonusTransaction->id;
            $this->status = self::STATUS_PUBLISHED;
            $this->save();
        } else {
            Yii::log(print_r($bonusTransaction->getErrors(), true), CLogger::LEVEL_ERROR, 'application');
            throw new CHttpException(500, 'Не удалось сохранить транзакцию за ответ');
        }

        return true;
    }

    /**
     * @return bool
     *
     * @throws Exception
     */
    public function isFast(): bool
    {
        $isFast = false;
        $fastAnswerInterval = Yii::app()->params['yuristBonus']['fastAnswerInterval'];

        $intervalSinceQuestionInHours = (new DateTime($this->datetime))->diff((new DateTime($this->question->publishDate)))->h;

        if ($intervalSinceQuestionInHours <= $fastAnswerInterval) {
            $isFast = true;
        }

        return $isFast;
    }
}
