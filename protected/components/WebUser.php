<?php

use App\models\User;
use App\models\YuristSettings;

/**
 * Класс для работы с данными текущего залогиненного пользователя.
 */
class WebUser extends CWebUser
{
    private $_model = null;

    public $allowAutoLogin = true;
    public $authTimeout = 1000000;
    public $cacheTime = 10; // время кеширования информации о текущем пользователе

    /**
     * Возвращает объект класса User, соответствующий залогиненному пользователю.
     *
     * @param bool $noCache Получить данные, не используя кеш
     *
     * @return User Объект класса User
     */
    public function getModel($noCache = false): ?User
    {
        if (true === $noCache) {
            $this->cacheTime = 0;
        }
        if (!$this->isGuest && null === $this->_model) {
            $this->_model = User::model()->cache($this->cacheTime)->findByPk($this->id);
        }

        return $this->_model;
    }

    public function getRole(): ?int
    {
        return ($user = $this->getModel()) ? $user->role : null;
    }

    public function getRoleName(): ?string
    {
        return ($user = $this->getModel()) ? $user->getRoleName() : null;
    }

    public function getName(): ?string
    {
        return ($user = $this->getModel()) ? $user->name : null;
    }

    public function getName2(): ?string
    {
        return ($user = $this->getModel()) ? $user->name2 : null;
    }

    public function getLastName(): ?string
    {
        return ($user = $this->getModel()) ? $user->lastName : null;
    }

    public function getShortName(): ?string
    {
        return ($user = $this->getModel()) ? $user->getShortName() : null;
    }

    public function getEmail(): ?string
    {
        return ($user = $this->getModel()) ? $user->email : null;
    }

    public function getPhone(): ?string
    {
        return ($user = $this->getModel()) ? $user->phone : null;
    }

    public function getSourceId(): ?int
    {
        return ($user = $this->getModel()) ? $user->leadSourceId : null;
    }

    public function getOfficeId()
    {
        if ($user = $this->getModel()) {
            return $user->officeId;
        }
    }

    public function getLogin(): ?string
    {
        return ($user = $this->getModel()) ? $user->login : null;
    }

    public function getTownId(): ?int
    {
        return ($user = $this->getModel()) ? $user->townId : null;
    }

    public function getActive100(): int
    {
        return ($user = $this->getModel()) ? $user->active100 : 0;
    }

    public function getIsVerified(): ?int
    {
        return ($user = $this->getModel()) ? $user->settings->isVerified : null;
    }

    public function getAvatarUrl(): ?string
    {
        return ($user = $this->getModel()) ? $user->getAvatarUrl() : null;
    }

    public function getAvatar(): ?string
    {
        return ($user = $this->getModel()) ? $user->avatar : null;
    }

    public function getKarma(): int
    {
        return ($user = $this->getModel()) ? $user->karma : 0;
    }

    public function getBalance($noCache = false): int
    {
        return ($user = $this->getModel($noCache)) ? $user->balance : 0;
    }

    /**
     * Возвращает массив категорий вопросов, на которых специализируется пользователь (юрист).
     *
     * @return array массив категорий
     */
    public function getCategories(): array
    {
        return ($user = $this->getModel()) ? $user->categories : [];
    }

    /**
     * Возвращает объект настроек юриста, относящийся к текущему пользователю.
     *
     * @return YuristSettings
     */
    public function getSettings(): ?YuristSettings
    {
        return ($user = $this->getModel()) ? $user->settings : null;
    }

    /**
     * Возвращает название ранга пользователя.
     *
     * @return string
     *
     * @throws Exception
     */
    public function getRangName(): string
    {
        return ($user = $this->getModel()) ? $user->getRangName() : '';
    }

    /**
     * Возвращает количество модерированных кампаний покупателя.
     *
     * @return int Количество кампаний
     */
    public function getCampaignsModeratedCount(): int
    {
        return ($user = $this->getModel()) ? $user->campaignsModeratedCount : 0;
    }

    /**
     * Возвращает коэффициент цены лида.
     *
     * @return float
     */
    public function getPriceCoeff(): float
    {
        return ($user = $this->getModel()) ? $user->priceCoeff : 1;
    }

    /**
     * Возвращает количество вопросов с непросмотренными комментами на мои ответы.
     *
     * @return int
     */
    public function getNewEventsCount(): int
    {
        return ($user = $this->getModel()) ?
            $user->getFeed(30, true) :
            0;
    }

    /**
     * Возвращает текст сообщения для юриста с советом заполнить поле в профиле.
     *
     * @return string|null Сообщение для пользователя
     */
    public function getProfileNotification(): ?string
    {
        if ($user = $this->getModel()) {
            return $user->getProfileNotification();
        }
        return null;
    }

    /**
     * Возвращает адрес кабинета пользователя.
     *
     * @return string|null
     */
    public function getHomeUrl(): ?string
    {
        $user = $this->getModel();
        if ($user) {
            switch ($user->role) {
                case User::ROLE_BUYER:
                    return Yii::app()->createUrl('/buyer');
                    break;
                case User::ROLE_PARTNER:
                    return Yii::app()->createUrl('/webmaster');
                    break;
                case User::ROLE_ROOT:
                    return Yii::app()->createUrl('/admin');
                    break;
                default:
                    return Yii::app()->createUrl('/');
            }
        }
        return null;
    }
}
