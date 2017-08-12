<?php

class auth
{

	static public function getID()
	{
		return end(explode('.', $_SERVER['REMOTE_ADDR']));
	}
}