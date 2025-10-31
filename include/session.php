<?php
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => true,  // Chỉ hoạt động trên HTTPS
    'cookie_samesite' => 'Strict'
]);
ob_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Tạo token ngẫu nhiên
}

?>