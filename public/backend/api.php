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

	default:
		$error = 'Unknown method';
		break;
}

echo json_encode([
	'success' => $error === '',
	'error' => $error,
	'result' => $result
], 256);