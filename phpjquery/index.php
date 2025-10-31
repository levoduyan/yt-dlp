<?php
error_reporting(E_ALL);
ini_set('display_errors', false);
ini_set('display_startup_errors', false);

require_once __DIR__.'/../config.php';

$m 			= $main->get('m');
$act 		= $main->get('act');
$api_key 	= $main->get('apikey');

// print_r($_SESSION['csrf_token']);
// print_r($_POST['csrf_token']);
// exit;

if ($_SERVER["REQUEST_METHOD"] === "POST" && (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
    echo "Lỗi: CSRF token không hợp lệ!";
}else if($m == 'youtube'){
    include $m.'.php';
    
}else{
    echo  "Lỗi:003 - index.ajax.";
}


if ( !file_exists( __DIR__.'/../logs/phpjquery' ) )
	@mkdir(__DIR__.'/../logs/phpjquery', 0777, true);

$filename = __DIR__.'/../logs/phpjquery/log.'.date('Y-m-d-H').'.txt';
$strLog = ob_get_contents();
@$main->writeToFile( $filename, ':[GET]:'.json_encode($_GET).':[POST]:'.json_encode($_POST).':\n'.$strLog );
unset( $strLog );