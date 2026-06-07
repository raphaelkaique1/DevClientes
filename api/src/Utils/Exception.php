<?php
declare(strict_types=1);
class Operation {
    public static function try(callable $callback): mixed {
        try {
            return $callback();
        } catch(Throwable $error) {
            return $error->getMessage();
        }
    }
}

?>