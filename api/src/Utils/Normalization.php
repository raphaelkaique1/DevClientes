<?php
declare(strict_types=1);

enum Type {
    case CASE_TITLE;
    case CASE_UPPER;
    case CASE_LOWER;
    case CASE_EMAIL;
};

class Normalize {
    public static function format(string $text, Type $method): string {
        $text = mb_strtolower($text, 'UTF-8');
        return match($method) {
            Type::CASE_TITLE => mb_convert_case($text, MB_CASE_TITLE, "UTF-8"),
            Type::CASE_UPPER => mb_strtoupper($text, "UTF-8"),
            Type::CASE_LOWER => $text,
            Type::CASE_EMAIL => filter_var($text, FILTER_SANITIZE_EMAIL)
        };
    }

}

?>