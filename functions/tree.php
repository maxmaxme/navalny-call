<?php

function getBranch($scriptID, $addBtns = false) {
	global $db, $mustache;


	$text = $db->getOne('select concat("Я: ", Text) as Text from script where ID=?i', $scriptID);
	$buttons = $db->getAll('select ID, Text, ToScriptID from script_buttons where ScriptID=?i', $scriptID);


	$html = '<ul><li><span><a>' . $text . '</a></span><ul>';


	if ($addBtns)
		$html .= $mustache->render(getMustacheTemplate('addButton'), [
			'func' => 'addScriptButton',
			'id' => $scriptID,
			'text' => '+ Добавить вариант ответа',
		]);

	foreach ($buttons as $button) {
		$html .= '<li><span><a>' . $button['Text'] . '</a></span>';
		if ($button['ToScriptID'])
			$html .= getBranch($button['ToScriptID'], $addBtns);
		else
			if ($addBtns)
				$html .= $mustache->render(getMustacheTemplate('addButton'), [
					'func' => 'addScript',
					'id' => $button['ID'],
					'text' => '+ Добавить продолжение',
					'ul' => 1
				]);


		$html .= '</li>';
	}

	$html .= '</ul></li></ul>';

	return $html;

}