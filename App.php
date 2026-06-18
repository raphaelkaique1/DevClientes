<?php
declare(strict_type=1);
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if($path !== '/' && is_file(__DIR__ . $path)) return false;
if(str_starts_with($path, '/api/')) {
    require __DIR__ . '/api/index.php';
    return true;
}
require __DIR__ . '/public/index.html';

?>