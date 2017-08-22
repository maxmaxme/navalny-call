<?php
session_start();
date_default_timezone_set('Europe/Moscow');

define('PROTO', (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://');
define('SITE_PATH', __DIR__ . '/');
define('CFG', SITE_PATH . 'config/');
define('CLS', SITE_PATH . 'cls/');
define('FUNCTIONS', SITE_PATH . 'functions/');


$server_configs = [
	'config.server.' . gethostname() . '.php',
	'config.server.php'
];

foreach ($server_configs as $server_config) {
	if (file_exists(CFG . $server_config)) {
		require_once CFG . $server_config;
		break;
	}
}

require_once FUNCTIONS . 'engine.php';
require_once FUNCTIONS . 'methods.php';
require_once FUNCTIONS . 'tree.php';
require_once CLS . 'auth.class.php';
require_once CLS . 'safemysql.class.php';
require_once CLS . 'mustacheTemplates.class.php';
require_once CLS . 'Mustache/Autoloader.php';
require_once CFG . 'connect.db.php';
require_once CFG . 'config.inc.php';

Mustache_Autoloader::register();
$mustache = new Mustache_Engine;
$mustacheTemplates = new mustacheTemplates;
//$auth = new auth(varStr('access_token', array_merge($_GET, $_POST)));
$auth = new auth('test');
