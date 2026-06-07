<?php
declare(strict_types=1);
class Request {
    public static function data(): array {
        parse_str(file_get_contents('php://input'), $content);
        foreach($content as $key => $value) $content[$key] = trim($value);
        return $content;
    }
}

?>