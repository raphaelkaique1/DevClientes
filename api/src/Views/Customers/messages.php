<?php
declare(strict_types=1);

class Warning {
    public static function success(string $message = 'Usuário criado com sucesso!'): string {
        return $message;
    }
    public static function alert(string $message = 'Não foi possível criar o usuário.'): string {
        return $message;
    }
    public static function error(string $message = 'Erro ao cadastrar usuário!'): string {
        return $message;
    }
}

?>