<?php
declare(strict_types=1);
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

return match (true) {
    $path !== '/' && is_file(__DIR__ . $path) => false,
    str_starts_with($path, '/api/') => (function (): true {
        require __DIR__ . '/api/index.php';
        return true;
    })(),
    default => (function (): null {
        require __DIR__ . '/public/index.html';
        return null;
    })(),
};

?>