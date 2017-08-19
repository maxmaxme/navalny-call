<?php


function curl($url, $postQuery = array())
{
	$postQuery = @http_build_query($postQuery, '', '&');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postQuery);
	$result = curl_exec($ch);
	return $result;
}

function jsonCurl($url, $postQuery = array())
{
	return json_decode(curl($url, $postQuery), true);
}


function varInt($name) {
	return isset($_REQUEST[$name]) ? intval($_REQUEST[$name]) : NULL;
}
function varFloat($name) {
	return isset($_REQUEST[$name]) ? floatval($_REQUEST[$name]) : NULL;
}
function varStr($name, $var = null) {
	$var = $var !== null ? $var : $_REQUEST;
	return trim($var[$name]); //isset($var[$name]) ? htmlspecialchars($var[$name]) : NULL;
}

function pre($arr) {
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}


function coalesce() {
	$args = func_get_args();
	foreach ($args as $arg) {
		if (!empty($arg)) {
			return $arg;
		}
	}
	return NULL;
}


function logMySqlError($error_msg, $error_type = E_USER_NOTICE) {

	echo '<div class="mySQLerror">Ошибка при обработке запроса!</div>' . $error_msg;

	$data = json_decode(file_get_contents(SITE_PATH . 'log.txt'), true);
	$data[] = array(
		'time' => time(),
		'error' => $error_msg,
		'url' => $_SERVER['REQUEST_URI']
	);

	file_put_contents(SITE_PATH . 'log.txt', json_encode($data, JSON_UNESCAPED_UNICODE));
}


function getIP() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}
