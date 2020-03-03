<?php

/**
 * Класс, ответственный за аутентификацию пользователей по данным из Ulogin (провайдер соц логина)
 * Class UloginUserIdentity.
 */
class UloginUserIdentity implements IUserIdentity
{
    private $uloginModel;
    private $id;
    private $name;
    private $isAuthenticated = false;
    private $states = [];

    public function __construct(UloginModel $uloginModel)
    {
        $this->uloginModel = $uloginModel;
    }

    /**
     * Аутентификация: ищем пользователя в базе 100 Юристов по таблице социалок + по email
     * Если находим, то ОК, можно логинить.
     *
     * @param UloginModel|null $uloginModel
     *
     * @return bool
     *
     * @throws CHttpException
     * @throws UserBannedException
     */
    public function authenticate()
    {
        $uloginUser = UloginUser::model()->find('identity=:identity', [
            ':identity' => $this->uloginModel->identity,
        ]);

        if (!$uloginUser) {
            // пользователь новый, создать записи и в ulogin_user и в user
            $uloginUser = UloginUser::create($this->uloginModel, null);
        }

        /** @var User $user */
        $user = $uloginUser->user;
        if (0 == $user->active100) {
            throw new CHttpException(403, 'Пользователь заблокирован на сайте 100 Юристов, логин невозможен');
        }

        $this->id = $user->id;
        $this->isAuthenticated = true;

        return true;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIsAuthenticated()
    {
        return $this->isAuthenticated;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPersistentStates()
    {
        return $this->states;
    }
}
