<?php
declare(strict_types=1);
require __DIR__ . '/api/config/URL.php';

return match (true) {
    $PATH !== '/' && is_file(__DIR__ . $PATH) => false,
    str_starts_with($PATH, '/api/') => (function (): true {
        require __DIR__ . '/api/index.php';
        return true;
    })(),
    default => (function (): null {
        require __DIR__ . '/public/index.html';
        return null;
    })()
};

?>