<?php

function getBranch($scriptID) {
	global $db;


	$text = $db->getOne('select concat("Я: ", Text) as Text from script where ID=?i', $scriptID);
	$buttons = $db->getAll('select Text, ToScriptID from script_buttons where ScriptID=?i', $scriptID);


	$html = '<ul><li><span><a>' . $text . '</a></span><ul>';

	foreach ($buttons as $button) {
		$html .= '<li><span><a>' . $button['Text'] . '</a></span>';
		if ($button['ToScriptID'])
			$html .= getBranch($button['ToScriptID']);
		$html .= '</li>';
	}
	$html .= '</ul></li></ul>';

	return $html;

}