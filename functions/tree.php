<?php

function getBranch($scriptID, $addBtns = false) {
	global $db;


	$text = $db->getOne('select concat("Я: ", Text) as Text from script where ID=?i', $scriptID);
	$buttons = $db->getAll('select Text, ToScriptID from script_buttons where ScriptID=?i', $scriptID);


	$html = '<ul><li><span><a>' . $text . '</a></span><ul>';


	if ($addBtns)
		$html .= '<li><span><a class="PlusBtn addScriptButton" data-script-id="' . $scriptID . '">+ Добавить вариант ответа</a></span></li>';

	foreach ($buttons as $button) {
		$html .= '<li><span><a>' . $button['Text'] . '</a></span>';
		if ($button['ToScriptID'])
			$html .= getBranch($button['ToScriptID'], $addBtns);
		else
			if ($addBtns)
				$html .= '<ul><li><span><a class="PlusBtn">+</a></span></li></ul>';
		//todo добавление продолжения скрипта (моих ответов)

		$html .= '</li>';
	}

	$html .= '</ul></li></ul>';

	return $html;

}