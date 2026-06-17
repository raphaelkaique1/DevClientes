<?php
declare(strict_types=1);
class Request {
    public static function data(): array|false {
        if(stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/x-www-form-urlencoded') !== 0) return false;
        parse_str(file_get_contents('php://input'), $content);
        return array_map(fn(string $value): string => trim($value), $content);
    }
}

?>