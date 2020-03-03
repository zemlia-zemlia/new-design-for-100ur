<?php

class UserActivity extends CActiveRecord
{
    /**
     * Модель для работы с пользователями.
     *
     * @property int      $id
     * @property int      $userId
     * @property int      $action
     * @property DateTime $ts
     */
    const ACTION_LOGIN = 1;
    const ACTION_AUTOLOGIN = 2;
    const ACTION_CREATE_ACCOUNT = 5;
    const ACTION_PROFILE_UPDATE = 6;

    const ACTION_ANSWER_QUESTION = 10;
    const ACTION_CREATE_QUESTION = 11;

    const ACTION_POST_COMMENT = 20;
    const ACTION_ANSWER_COMMENT = 21;
    const ACTION_COMMENT_WAS_LIKED = 22;
    const ACTION_ANSWER_WAS_LIKED = 23;

    const ACTION_TOPUP_BALANCE = 33;

    /**
     * Возвращает массив названий действий и их кодов.
     *
     * @return array
     */
    public function getActions()
    {
        return [
            self::ACTION_LOGIN => 'логин',
            self::ACTION_AUTOLOGIN => 'автологин',
            self::ACTION_CREATE_ACCOUNT => 'создан аккаунт',
            self::ACTION_PROFILE_UPDATE => 'обновлен профайл',
            self::ACTION_ANSWER_QUESTION => 'ответ на вопрос',
            self::ACTION_CREATE_QUESTION => 'вопрос задан',
            self::ACTION_POST_COMMENT => 'комментарий',
            self::ACTION_ANSWER_COMMENT => 'ответ на комментарий',
            self::ACTION_COMMENT_WAS_LIKED => 'лайк комментария',
            self::ACTION_ANSWER_WAS_LIKED => 'лайк ответа',
            self::ACTION_TOPUP_BALANCE => 'пополнение баланса',
        ];
    }

    protected static function getActionRates()
    {
        return [
            self::ACTION_LOGIN => 1,
            self::ACTION_PROFILE_UPDATE => 3,
            self::ACTION_ANSWER_QUESTION => 5,
            self::ACTION_ANSWER_WAS_LIKED => 10,
            self::ACTION_ANSWER_COMMENT => 4,
            self::ACTION_POST_COMMENT => 2,
        ];
    }

    public static function getActionRate($action)
    {
        return static::getActionRates()[$action];
    }

    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className active record class name
     *
     * @return UserActivity the static model class
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
        return '{{user_activity}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'user_activity';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        return [
            ['userId, action', 'required', 'message' => 'Поле {attribute} должно быть заполнено'],
        ];
    }

    /**
     * @return array
     */
    public function relations()
    {
        return [
            'user' => [self::BELONGS_TO, 'User', 'userId'],
        ];
    }

    /**
     * Возвращает название действия по коду.
     *
     * @return string
     *
     * @throws Exception
     */
    public function getActionName()
    {
        $actions = $this->getActions();
        if (isset($actions[$this->action])) {
            return $actions[$this->action];
        }

        throw new Exception('Invalid activity action');
    }

    /**
     * Логирует активность пользователя.
     *
     * @param User $user
     * @param int  $actionId Код активности
     *
     * @return bool Результат сохранения
     *
     * @throws Exception
     */
    public function logActivity(User $user, $actionId)
    {
        $this->userId = $user->id;
        $this->action = $actionId;
        $this->ts = (new DateTime())->format('Y-m-d H:i:s');
        $this->ip = IpHelper::getUserIP();

        return $this->save();
    }

    /**
     * Возвращает оттенок зеленого согласно баллу.
     *
     * @param int $rank
     *
     * @return string "#ff09ff"
     */
    public static function getColorByRank($rank)
    {
        $rank = ($rank > 255) ? 255 : $rank;
        $colorCodeDec = 255 - $rank;
        $colorCodeHex = dechex($colorCodeDec);

        return '#ff' . $colorCodeHex . 'ff';
    }
}
