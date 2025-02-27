<?php

namespace App\Config;
abstract class DbConnect
{
    private static ?\PDO $pdo = null;

    public static function getPDO(): \PDO
    {
        if (self::$pdo === null) {
            $settings = parse_ini_file("settings.ini", true);
            $databaseSettings = $settings["database"];

            $driver = $databaseSettings["driver"];
            $hostname = $databaseSettings["host"];
            $port = $databaseSettings["port"];
            $dbName = $databaseSettings["dbname"];
            $charset = $databaseSettings["charset"];
            $username = $databaseSettings["user"];
            $password = $databaseSettings["password"];
            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ];

            self::$pdo = new \PDO("$driver:host=$hostname;port=$port;dbname=$dbName;charset=$charset", $username, $password, $options);
        }
        return self::$pdo;
    }

    public static function closeConnection(): void
    {
        self::$pdo = null;
    }
}
