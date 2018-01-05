<?php

class UserIdentity extends CUserIdentity
{
	public function authenticate()
	{
		$this->errorCode=self::ERROR_NONE;

		$user = User::model()->findByAttributes(array('email'=>$this->username));
		if(!$user || ($user->password !== User::HashPassword($this->password)))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else
		{
			$this->setState('innerId', $user->id);
			$this->setState('name', $user->name);
		}
		
		return !$this->errorCode;
	}
}