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
		global $db;
		$result = $error = '';

		if (!$phone = $db->getOne('select Phone from phones where UserID=?i and StatusID=0', auth::getID()))
		{ // если человек по базе уже разговаривает с кем-то, то отдаём этот номер

			if (strlen($cityCode) < 7) {

				do {
					$cityCode = $cityCode ?:
						$db->getOne('SELECT Code FROM city_codes WHERE Code>0 ORDER BY RAND() LIMIT 1');
					//todo учитывать сколько времени сейчас в этом городе


					$phone = $cityCode;

					$length = 10 - strlen($phone);

					while ($length--) $phone .= rand(0, 9);
				} while ($db->getOne('SELECT count(*) FROM phones WHERE Phone=?i', $phone));


				$db->query('INSERT INTO phones SET Phone=?i, UserID=?i', $phone, auth::getID());

				$db->query('INSERT INTO log SET PhoneID=?i, ButtonID=8, UserID=?i, DATETIME=NOW()', $db->insertId(), auth::getID());

			} else
				$error = 'Код города слишком длинный';

		}

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
		global $db;
		$result = $error = '';

		if ($phoneID = $db->getOne('select ID from phones where UserID=?i and StatusID=0', auth::getID()))
		{ // ищем разговоры этого человека в данный момент

			if ($buttonInfo = $db->getRow('select ID, ToScriptID from script_buttons where ID=?i', $buttonID)) {
				//todo при нажатии на кнопку проверять, что логика дерева сохраняется

				$db->query('INSERT INTO log SET PhoneID=?i, ButtonID=?i, UserID=?i, DateTime=NOW()', $phoneID, $buttonID, auth::getID());

				$result = script::get($buttonInfo['ToScriptID'] ?: 6);

				if (!$buttonInfo['ToScriptID'])
					calls::complete();

			} else
				$error = 'Неизвестная ошибка';


		} else
			$error = 'Нет активного звонка';


		return [$result, $error];
	}

	/**
	 * Завершение звонка в любой момент
	 * @return array
	 */
	static public function complete()
	{
		global $db;
		$result = $error = '';

		if ($phoneID = $db->getOne('select ID from phones where UserID=?i and StatusID=0', auth::getID()))
		{ // ищем разговоры этого человека в данный момент

			$db->query('update phones set StatusID=1 where ID=?i', $phoneID);
			// и завершаем его
			$db->query('INSERT INTO log SET PhoneID=?i, ButtonID=9, UserID=?i, DATETIME=NOW()', $phoneID, auth::getID());

		} //else $error = 'Нет активного звонка';


		return [$result, $error];
	}

}


class script
{

	static public function get($scriptID)
	{
		global $db;

		return [
			'text' => $db->getOne('select Text from script where ID=?i', $scriptID),
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
		global $db;

		$result = $error = '';

		if ($scriptID && $text)
			$db->query('insert into script_buttons set ScriptID=?i, Text=?s', $scriptID, $text);
		else
			$error = 'Заполнены не все поля';

		return [$result, $error];
	}

}