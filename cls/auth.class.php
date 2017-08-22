<?php

class auth
{


	public $user = 0;
	public $canCall = 0;
	public $canEditTree = 0;
	public $canViewTree = 0;
	public $canViewLog = 0;

	function __construct($hash)
	{
		global $db;

		$hash = $hash ?: $_COOKIE['access_token'];
		$hash = 'test';

		$userInfo = $db->getRow('select
 									u.ID, u.Login, ug.CanCall, ug.CanEditTree, ug.CanViewLog, ug.CanViewTree
								from users u
								inner join users_groups ug on u.GroupID=ug.ID 
								where Hash=?s', $hash);

		if ($userInfo) {
			$this->user = $userInfo['ID'];
			$this->canCall = $userInfo['CanCall'];
			$this->canEditTree = $userInfo['CanEditTree'];
			$this->canViewLog = $userInfo['CanViewLog'];
			$this->canViewTree = $userInfo['CanViewTree'];
		}


	}


	static public function login($login, $password)
	{
		global $db;

		$result = [];
		$error = '';


		$userInfo = $db->getRow('select ID, Password from users where Login=?s', $login);

		if ($userInfo['ID'] && password_verify($password, $userInfo['Password'])) {
			if ($result['hash'] != 'test') {
				$result['hash'] = md5(time() . $userInfo['ID'] . rand(0, 100));
				$db->query('UPDATE users SET Hash=?s WHERE ID=?i', $result['hash'], $userInfo['ID']);
			}
		} else
			$error = 'Неверный логин или пароль';

		return [$result, $error];

	}


	static public function checkToken($token)
	{
		global $db;

		$result = [];
		$error = '';

		$token = 'test';

		if (!$db->getOne('select count(*) from users where Hash=?s', $token))
			$error = 'Error';

		return [$result, $error];

	}




}