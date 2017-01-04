<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	protected $_id;
        const ERROR_USER_INACTIVE=5;
	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$user=User::model()->find('LOWER(email)=?',array(strtolower($this->username)));
		if($user===null) // если не нашли пользователя
			$this->errorCode=self::ERROR_USERNAME_INVALID;
                else if($user->active100!=1)
                        $this->errorCode=self::ERROR_USER_INACTIVE;
		else if(!$user->validatePassword($this->password)) // если неправильный пароль
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else // все ок
		{
			$this->_id=$user->id;
			$this->username=$user->email;
			$this->errorCode=self::ERROR_NONE;
		}
		return $this->errorCode==self::ERROR_NONE;
	}

        public function authenticateVk()
	{
            $user=User::model()->find('vkId=?',array($this->username));
            if($user===null) // если не нашли пользователя
			$this->errorCode=self::ERROR_USERNAME_INVALID;
                else if($user->active!=1)
                        $this->errorCode=self::ERROR_USER_INACTIVE;
		else // все ок
		{
			$this->_id=$user->id;
			$this->username=$user->email;
			$this->errorCode=self::ERROR_NONE;
		}
		return $this->errorCode==self::ERROR_NONE;
        }
        
        public function authenticateFb()
        {
            if($this->username) {
                $this->_id=$this->username;
                return $this->errorCode==self::ERROR_NONE;
            } else {
                $this->errorCode=self::ERROR_USERNAME_INVALID;
            }
            
        }
	/**
	 * @return integer the ID of the user record
	 */
	public function getId()
	{
		return $this->_id;
	}
}