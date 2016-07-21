<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
/*
        $connection = Yii::app()->db;
        $query = 'select * from tbl_user';
        $command = $connection->createCommand($query);
        $dataReader = $command->query();
        $rows = $dataReader->readAll();
        fb($rows);

        $users = array();
        foreach($rows as $item) {
            $users[$item['username']] = $item['password'];
        }

		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;*/

        $user = new User;
        $record = $user->findByAttributes(array('username' => $this->username, 'password' => $this->password));
        if ($record) {
            $this->_id = $record->id;
            $this->errorCode=self::ERROR_NONE;
        } else {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        }

        return !$this->errorCode;
/*
        $column = Yii::app()->db->createCommand(array(
            'select' => 'count(*)',
            'from'   => 'tbl_user',
            'where'  => 'username=:username and password=:password',
            'params' => array(':username' => $this->username, ':password' => $this->password)
        ))->queryColumn();

        fb($column);
        fb(reset($column));

        fb($this->errorCode);
        if (reset($column) != 0) {
            $this->errorCode=self::ERROR_NONE;
        } else {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        }

        fb($this->errorCode);
        return !$this->errorCode;
*/
	}

    public function getId() {
        return $this->_id;
    }
}