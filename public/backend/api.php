<?php
require_once '../../config.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

$method = varStr('method');

$error = $result = '';

switch ($method) {

	case 'test':
		$result = 'OK!';
		break;

	case 'calls.init': list($result, $error) = calls::init(varInt('cityCode')); break;
	case 'calls.button': list($result, $error) = calls::button(varInt('buttonID')); break;
	case 'calls.complete': list($result, $error) = calls::complete(); break;
	case 'script.addButton': list($result, $error) = script::addButton(varInt('scriptID'), varStr('text')); break;


	default:
		$error = 'Unknown method';
		break;
}

echo json_encode([
	'success' => $error === '',
	'error' => $error,
	'result' => $result
], 256);