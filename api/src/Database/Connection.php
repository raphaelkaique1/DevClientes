<?php

class Connect {

    private static ?PDO $db = null;

    public static function connect(): PDO {
        $dbPath = __DIR__ . '/main.db';
        $dsn = 'sqlite:' . $dbPath;
        try {
            self::$db = new PDO($dsn);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$db->exec('PRAGMA foreign_keys = ON');
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        return self::$db;
    }
}

?>