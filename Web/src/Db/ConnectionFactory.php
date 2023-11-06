<?php

namespace Iutncy\Sae\Db;

use PDO;

class ConnectionFactory
{
    private static $config = [];
    public static $db;

    public static function setConfig($file)
    {
        self::$config = parse_ini_file($file);

    }

    public static function makeConnection()
    {
        if (self::$db === null) {
            $dsn = sprintf(
                "%s:host=%s;dbname=%s;charset=%s",
                self::$config['driver'],
                self::$config['host'],
                self::$config['dbname'],
                self::$config['charset']
            );
            self:: $db = new \PDO($dsn, self::$config['username'], self::$config['password'], [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false,
            ]);
        }
        return self::$db;
    }
}





