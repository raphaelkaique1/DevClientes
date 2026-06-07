<?php
declare(strict_types=1);

class Connection {

    private static ?PDO $db = null;

    public static function connect(): PDO {
        try {
            if (self::$db === null) {
                $config = require __DIR__ . '/../../config/Database.php';
                self::$db = new PDO(
                    $config['dsn'],
                    $config['user'],
                    $config['password']
                );
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                if(self::$db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite') self::$db->exec('PRAGMA foreign_keys = ON');
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return self::$db;
    }
}

?>