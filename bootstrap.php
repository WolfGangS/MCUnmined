<?php

defined("APP_ROOT") or define('APP_ROOT', __DIR__);
defined("APP_START") or define("APP_START", microtime(true));
defined("APP_NAME") or define("APP_NAME", "MCUNMINED");
defined("DEBUG_ENABLED") or define("DEBUG_ENABLED", true);

if(!file_exists(__DIR__ . "/vendor/autoload.php")){
	dir("COMPOSER NOT INSTALLED");
}

require_once(__DIR__ . "/vendor/autoload.php");

\WLF_IO\MCUnmined\MCUnmined::Instance()->run();