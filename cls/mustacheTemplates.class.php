<?php

class mustacheTemplates {

	private $directory = SITE_PATH . '/mustacheTemplates/';

	function __construct()
	{
		$files = array_diff(scandir($this->directory), array('..', '.'));

		foreach($files as $file) {

			preg_match('/^(.*)\.mst$/', $file, $matches);
			$varName = $matches[1];

			$content = file_get_contents($this->directory . $file);


			$this->$varName = $content;


		}

	}

	function getTemplate($name) {
		return $this->$name;
	}
}