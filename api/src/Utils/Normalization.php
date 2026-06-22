<?php
declare(strict_types=1);

enum Type {
    case CASE_TITLE;
    case CASE_UPPER;
    case CASE_LOWER;
    case CASE_EMAIL;
    case CASE_NUMBER;
};

class Normalize {
    public static function format(string|int $info, Type $method): string|int|null {
        is_string($info) && mb_strtolower($info, 'UTF-8');
        return match($method) {
            Type::CASE_TITLE  => (string) mb_convert_case($info, MB_CASE_TITLE, "UTF-8"),
            Type::CASE_UPPER  => (string) mb_strtoupper($info, "UTF-8"),
            Type::CASE_LOWER  => (string) $info,
            Type::CASE_EMAIL  => (string) filter_var($info, FILTER_SANITIZE_EMAIL),
            Type::CASE_NUMBER => (int)    $info,
            default           => null
        };
    }

}

?>