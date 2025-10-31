<?php

require_once __DIR__.'/include/session.php';
require_once __DIR__.'/include/global.php';

/**
 * 
 */
include_once __DIR__.'/library/smarty/libs/Smarty.class.php';

$st = new Smarty\Smarty;

/**
 * 
 */
require_once __DIR__.'/library/vendor/autoload.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$link 		= 'https://'.$_SERVER['SERVER_NAME'].'';
$tpldirect 	= __DIR__.'/templates/';

