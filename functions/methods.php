<?php

class calls
{
	/**
	 * Инициализация нового звонка
	 * @param string $cityCode Код города, необязательный параметр, тогда берётся рандомный
	 * @return array
	 */
	static public function init($cityCode = '')
	{
		global $db, $auth;
		$result = $error = $phone = '';


		if ($auth->canCall) {
			if (!$phone = $db->getOne('SELECT Phone FROM phones WHERE UserID=?i AND StatusID=0', $auth->user)) { // если человек по базе уже разговаривает с кем-то, то отдаём этот номер

				if (strlen($cityCode) < 7) {

					do {
						$cityCode = $cityCode ?:
							$db->getOne('SELECT Code FROM city_codes WHERE Code>0 ORDER BY RAND() LIMIT 1');
						//todo учитывать сколько времени сейчас в этом городе


						$phone = $cityCode;

						$length = 10 - strlen($phone);

						while ($length--) $phone .= rand(0, 9);
					} while ($db->getOne('SELECT count(*) FROM phones WHERE Phone=?i', $phone));


					$db->query('INSERT INTO phones SET Phone=?i, UserID=?i', $phone, $auth->user);

					$db->query('INSERT INTO log SET PhoneID=?i, ButtonID=8, UserID=?i, DATETIME=NOW()', $db->insertId(), $auth->user);

				} else
					$error = 'Код города слишком длинный';

			}

		} else
			$error = 'Access denied';

		$result = !$error ? [
			'text' => '+7' . $phone,
			'buttons' => script::getButtons(1)
		] : '';

		return [$result, $error];

	}

	/**
	 * Вызывается при нажатии кнопки скрипта
	 * @param int $buttonID ID кнопки
	 * @return array
	 */
	static public function button($buttonID)
	{
		global $db, $auth;
		$result = $error = '';

		if ($auth->canCall) {
			if ($phoneID = $db->getOne('SELECT ID FROM phones WHERE UserID=?i AND StatusID=0', $auth->user)) { // ищем разговоры этого человека в данный момент

				if ($buttonInfo = $db->getRow('SELECT ID, ToScriptID FROM script_buttons WHERE ID=?i', $buttonID)) {
					//todo при нажатии на кнопку проверять, что логика дерева сохраняется

					$db->query('INSERT INTO log SET PhoneID=?i, ButtonID=?i, UserID=?i, DATETIME=NOW()', $phoneID, $buttonID, $auth->user);

					$result = script::get($buttonInfo['ToScriptID'] ?: 6);

					if (!$buttonInfo['ToScriptID'])
						calls::complete();

				} else
					$error = 'Неизвестная ошибка';


			} else
				$error = 'Нет активного звонка';
		} else
			$error = 'Access denied';


		return [$result, $error];
	}

	/**
	 * Завершение звонка в любой момент
	 * @return array
	 */
	static public function complete()
	{
		global $db, $auth;
		$result = $error = '';

		if ($auth->canCall) {
			if ($phoneID = $db->getOne('SELECT ID FROM phones WHERE UserID=?i AND StatusID=0', $auth->user)) { // ищем разговоры этого человека в данный момент

				$db->query('UPDATE phones SET StatusID=1 WHERE ID=?i', $phoneID);
				// и завершаем его
				$db->query('INSERT INTO log SET PhoneID=?i, ButtonID=9, UserID=?i, DATETIME=NOW()', $phoneID, $auth->user);

			} //else $error = 'Нет активного звонка';
		} else
			$error = 'Access denied';


		return [$result, $error];
	}

}


class script
{

	static public function get($scriptID)
	{
		global $db;

		return [
			'text' => $db->getOne('SELECT Text FROM script WHERE ID=?i', $scriptID),
			'buttons' => script::getButtons($scriptID)
		];
	}


	static public function getButtons($scriptID)
	{
		global $db;

		return $db->getAll('
			SELECT
				ID,
				Text
			FROM script_buttons
			WHERE ScriptID = ?i
		', $scriptID);
	}

	static public function addButton($scriptID, $text)
	{
		global $db, $auth;

		$error = '';
		$result = [];

		 if ($auth->canEditTree && $auth->canViewTree) {
			if ($scriptID && $text) {
				$db->query('INSERT INTO script_buttons SET ScriptID=?i, TEXT=?s', $scriptID, $text);

				$result['buttonID'] = $db->insertId();
			} else
				$error = 'Заполнены не все поля';
		} else
			$error = 'Access denied';

		return [$result, $error];
	}

	static public function add($buttonID, $text)
	{
		global $db, $auth;

		$error = '';
		$result = [];

		 if ($auth->canEditTree && $auth->canViewTree) {
			if ($buttonID && $text) {
				$db->query('INSERT INTO script SET TEXT=?s', $text);
				$scriptID = $db->insertId();
				$db->query('UPDATE script_buttons SET ToScriptID=?i WHERE ID=?i', $scriptID, $buttonID);

				$result['scriptID'] = $scriptID;
			} else
				$error = 'Заполнены не все поля';
		} else
			$error = 'Access denied';

		return [$result, $error];
	}

	static public function deleteScriptBtn($buttonID)
	{
		global $db, $auth;

		$error = '';
		$result = [];
		 if ($auth->canEditTree && $auth->canViewTree) {
			if ($buttonID) {
				$scriptID = $db->getOne('SELECT ToScriptID FROM script_buttons WHERE ID=?i', $buttonID);
				$db->query('DELETE FROM script_buttons WHERE ID=?i', $buttonID);
				self::deleteScript($scriptID);
			} else
				$error = 'Заполнены не все поля';
		} else
			$error = 'Access denied';

		return [$result, $error];
	}

	static public function deleteScript($scriptID)
	{
		global $db, $auth;

		$error = '';
		$result = [];
		 if ($auth->canEditTree && $auth->canViewTree) {
			if ($scriptID) {

				$result['buttonID'] = $db->getOne('SELECT ID FROM script_buttons WHERE ToScriptID=?i', $scriptID);

				$buttons = $db->getCol('SELECT ID FROM script_buttons WHERE ScriptID=?i', $scriptID);
				$db->query('DELETE FROM script WHERE ID=?i', $scriptID);
				$db->query('UPDATE script_buttons SET ToScriptID=0 WHERE ToScriptID=?i', $scriptID);

				foreach ($buttons as $buttonID)
					self::deleteScriptBtn($buttonID);
			} else
				$error = 'Заполнены не все поля';
		} else
			$error = 'Access denied';

		return [$result, $error];
	}

	static public function editScriptBtn($buttonID, $text)
	{
		global $db, $auth;

		$error = '';
		$result = [];
		 if ($auth->canEditTree && $auth->canViewTree) {
			if ($buttonID && $text) {

				$db->query('UPDATE script_buttons SET Text=?s WHERE ID=?i', $text, $buttonID);

			} else
				$error = 'Заполнены не все поля';
		} else
			$error = 'Access denied';

		return [$result, $error];
	}

	static public function editScript($scriptID, $text)
	{
		global $db, $auth;

		$error = '';
		$result = [];
		 if ($auth->canEditTree && $auth->canViewTree) {
			if ($scriptID && $text) {

				$db->query('UPDATE script SET Text=?s WHERE ID=?i', $text, $scriptID);

			} else
				$error = 'Заполнены не все поля';
		} else
			$error = 'Access denied';

		return [$result, $error];
	}

}