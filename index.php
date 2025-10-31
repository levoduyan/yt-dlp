<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', false);
ini_set('display_startup_errors', false);

include 'config.php';

$title = '';
$m     = $main->get('m');
$act   = $main->get('act');

if( $m == '' ){
	$m = 'home';
}
if( $act == '' ){
	$act = 'index';
}

$stemp = 'm/'.$m.'.php';
$temp = $m . '/' . $act . '.tpl';
include $stemp;

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

$domain = $protocol . $_SERVER['HTTP_HOST'];
$version = 'v=1.0.'. time();
$meta_url 	= $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$st->assign('domain', $domain);
$st->assign('version', $version);
$st->assign('meta_url', $meta_url);

$st->assign('temp', $temp);
$st->assign('m', $m);
$st->assign('act', $act);

$st->display($tpldirect.'index.tpl');
