<?php

class calls
{

	static public function init($cityCode = '')
	{
		global $db;

		if (!$phone = $db->getOne('select Phone from phones where UserID=?i and StatusID=0', 1))
		{ // если человек по базе уже разговаривает с кем-то, то отдаём этот номер

			do {
				$cityCode = $cityCode ?:
					$db->getOne('SELECT Code FROM city_codes WHERE Code>0 ORDER BY RAND() LIMIT 1');

				$phone = $cityCode;

				$length = 10 - strlen($phone);

				while ($length--) $phone .= rand(0, 9);
			} while ($db->getOne('SELECT count(*) FROM phones WHERE Phone=?i', $phone));


			$db->query('INSERT INTO phones SET Phone=?i, UserID=?i', $phone, 1);

		}


		$buttons = $db->getAll('
			SELECT
				ID,
				Text
			FROM script_buttons
			WHERE ScriptID = 1
		');

		$result = [
			'text' => '+7' . $phone,
			'buttons' => $buttons
		];

		return $result;
	}

}