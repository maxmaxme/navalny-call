<?php

function getBranch($scriptID, $addBtns = false) {
	global $db, $mustache, $mustacheTemplates;


	$text = $db->getOne('select concat("Я: ", Text) as Text from script where ID=?i', $scriptID);
	$buttons = $db->getAll('select ID, Text, ToScriptID from script_buttons where ScriptID=?i', $scriptID);


	$html = '<ul><li><span>' .
		$mustache->render(
			$mustacheTemplates->getTemplate('treeItem'), [
				'text' => $text,
				'type' => 'Script',
				'id' => $scriptID
			]) .
		'</span><ul>';


	if ($addBtns)
		$html .= $mustache->render($mustacheTemplates->getTemplate('addButton'), [
			'func' => 'addScriptButton',
			'id' => $scriptID,
			'text' => '+ Добавить вариант ответа',
		]);

	foreach ($buttons as $button) {
		$html .= '<li><span>' .
			$mustache->render(
				$mustacheTemplates->getTemplate('treeItem'), [
					'text' => $button['Text'],
					'type' => 'ScriptBtn',
					'id' => $button['ID']
				]) .
			'</span>';
		if ($button['ToScriptID'])
			$html .= getBranch($button['ToScriptID'], $addBtns);
		else
			if ($addBtns)
				$html .= $mustache->render($mustacheTemplates->getTemplate('addButton'), [
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