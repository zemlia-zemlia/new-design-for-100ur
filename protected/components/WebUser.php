<?php

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
    public function getModel($noCache = false)
    {
        if (true === $noCache) {
            $this->cacheTime = 0;
        }
        if (!$this->isGuest && null === $this->_model) {
            $this->_model = User::model()->cache($this->cacheTime)->findByPk($this->id);
        }

        return $this->_model;
    }

    public function getRole()
    {
        if ($user = $this->getModel()) {
            return $user->role;
        }
    }

    public function getRoleName()
    {
        if ($user = $this->getModel()) {
            return $user->getRoleName();
        }
    }

    public function getName()
    {
        if ($user = $this->getModel()) {
            return $user->name;
        }
    }

    public function getName2()
    {
        if ($user = $this->getModel()) {
            return $user->name2;
        }
    }

    public function getLastName()
    {
        if ($user = $this->getModel()) {
            return $user->lastName;
        }
    }

    public function getShortName()
    {
        if ($user = $this->getModel()) {
            return $user->getShortName();
        }
    }

    public function getEmail()
    {
        if ($user = $this->getModel()) {
            return $user->email;
        }
    }

    public function getPhone()
    {
        if ($user = $this->getModel()) {
            return $user->phone;
        }
    }

    public function getSourceId()
    {
        if ($user = $this->getModel()) {
            return $user->leadSourceId;
        }
    }

    public function getOfficeId()
    {
        if ($user = $this->getModel()) {
            return $user->officeId;
        }
    }

    public function getLogin()
    {
        if ($user = $this->getModel()) {
            return $user->login;
        }
    }

    public function getTownId()
    {
        if ($user = $this->getModel()) {
            return $user->townId;
        }
    }

    public function getActive100()
    {
        if ($user = $this->getModel()) {
            return $user->active100;
        }
    }

    public function getIsVerified()
    {
        if ($user = $this->getModel()) {
            return $user->settings->isVerified;
        }
    }

    public function getAvatarUrl()
    {
        if ($user = $this->getModel()) {
            return $user->getAvatarUrl();
        }
    }

    public function getAvatar()
    {
        if ($user = $this->getModel()) {
            return $user->avatar;
        }
    }

    public function getKarma()
    {
        if ($user = $this->getModel()) {
            return $user->karma;
        }
    }

    public function getBalance($noCache = false)
    {
        if ($user = $this->getModel($noCache)) {
            return $user->balance;
        }
    }

    /**
     * Возвращает массив категорий вопросов, на которых специализируется пользователь (юрист).
     *
     * @return array массив категорий
     */
    public function getCategories()
    {
        if ($user = $this->getModel()) {
            return $user->categories;
        }
    }

    /**
     * Возвращает объект настроек юриста, относящийся к текущему пользователю.
     *
     * @return YuristSettings
     */
    public function getSettings()
    {
        if ($user = $this->getModel()) {
            return $user->settings;
        }
    }

    /**
     * Возвращает название ранга пользователя.
     *
     * @return string
     *
     * @throws Exception
     */
    public function getRangName()
    {
        if ($user = $this->getModel()) {
            return $user->getRangName();
        }
    }

    /**
     * Возвращает количество модерированных кампаний покупателя.
     *
     * @return int Количество кампаний
     */
    public function getCampaignsModeratedCount()
    {
        if ($user = $this->getModel()) {
            return $user->campaignsModeratedCount;
        }
    }

    /**
     * Возвращает коэффициент цены лида.
     *
     * @return type
     */
    public function getPriceCoeff()
    {
        if ($user = $this->getModel()) {
            return $user->priceCoeff;
        }
    }

    /**
     * Возвращает количество вопросов с непросмотренными комментами на мои ответы.
     *
     * @return type
     */
    public function getNewEventsCount()
    {
        if ($user = $this->getModel()) {
            return $user->getFeed(30, true);
        }
    }

    /**
     * Возвращает текст сообщения для юриста с советом заполнить поле в профиле.
     *
     * @return string Сообщение для пользователя
     */
    public function getProfileNotification()
    {
        if ($user = $this->getModel()) {
            return $user->getProfileNotification();
        }
    }

    /**
     * Возвращает адрес кабинета пользователя.
     *
     * @return string|null
     */
    public function getHomeUrl()
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
    }
}
