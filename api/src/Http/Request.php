<?php
declare(strict_types=1);
class Request {
    public static function data(): array|false {
        if(stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/x-www-form-urlencoded') !== 0) return false;
        parse_str(file_get_contents('php://input'), $content);
        foreach($content as $key => $value) $content[$key] = trim($value);
        return $content;
    }
}

?>