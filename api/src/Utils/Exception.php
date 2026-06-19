<?php
declare(strict_types=1);

enum Violation: string {
    case DuplicateEmailException = '23000';
}

class Operation {
    public static function runSafe(callable $callback): mixed {
        try {
            return $callback();
        } catch(Throwable $error) {
            return $error->getMessage();
        }
    }
}

?>