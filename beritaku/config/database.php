<?php
// config/database.php

define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'beritaku');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

define('BASE_URL', 'http://localhost/beritaku/public');
if (!defined('BASE_PATH')) { define('BASE_PATH', dirname(__DIR__)); }
define('UPLOAD_PATH', BASE_PATH . '/public/uploads/');
define('UPLOAD_URL', BASE_URL . '/uploads/');

define('APP_NAME', 'BeritaKu');
define('APP_DEBUG', true);
