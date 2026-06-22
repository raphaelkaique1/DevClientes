<?php
declare(strict_types=1);

class Validate {

    public static function id(string|int|float $id, int $range): ?int {
        return $id > 0 && $id <= $range ? $id : null;
    }

    public static function name(string $name): ?string {
        return preg_match("/^[\p{L}\s'.-]+$/u", $name) ? $name : null;
    }

    public static function email(string $email): ?string {
        return filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE);
    }

    public static function role(string $role): ?string {
        return match ($role) {
            'fullstack' => 'Full Stack',
            'backend'   => 'Back End',
            'frontend'  => 'Front End',
            default     => null,
        };
    }
}

?>