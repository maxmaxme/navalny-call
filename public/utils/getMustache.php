<?php

require_once '../../config.php';

header('Content-Type: text/javascript');

$directory = SITE_PATH . '/mustacheTemplates/';
$files = array_diff(scandir($directory), array('..', '.'));

echo 'var mustacheTemplates = {};' . PHP_EOL . PHP_EOL;

foreach($files as $file) {

	preg_match('/^(.*)\.mst$/', $file, $matches);
	$varName = $matches[1];

	$content = str_replace([
		PHP_EOL,
		"'"
	], [
		'',
		"\'"
	], file_get_contents($directory . $file));


	echo "mustacheTemplates.{$varName} = '{$content}';\n";


}
