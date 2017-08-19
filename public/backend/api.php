<?php
require_once '../../config.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

$method = varStr('method');

$error = $result = '';

switch ($method) {

	case 'calls.init': list($result, $error) = calls::init(varInt('cityCode')); break;
	case 'calls.button': list($result, $error) = calls::button(varInt('buttonID')); break;
	case 'calls.complete': list($result, $error) = calls::complete(); break;
	case 'script.addButton': list($result, $error) = script::addButton(varInt('scriptID'), varStr('text')); break;
	case 'script.add': list($result, $error) = script::add(varInt('buttonID'), varStr('text')); break;
	case 'script.deleteScriptBtn': list($result, $error) = script::deleteScriptBtn(varInt('buttonID')); break;
	case 'script.deleteScript': list($result, $error) = script::deleteScript(varInt('scriptID')); break;
	case 'script.editScript': list($result, $error) = script::editScript(varInt('scriptID'), varStr('text')); break;
	case 'script.editScriptBtn': list($result, $error) = script::editScriptBtn(varInt('buttonID'), varStr('text')); break;

	case 'auth': list($result, $error) = auth::login(varStr('login'), varStr('password')); break;
	case 'auth.checkToken': list($result, $error) = auth::checkToken(varStr('token')); break;


	default:
		$error = 'Unknown method';
		break;
}

echo json_encode([
	'success' => $error === '',
	'error' => $error,
	'result' => $result
], 256);