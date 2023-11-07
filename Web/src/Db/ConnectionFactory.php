<?php

namespace Iutncy\Sae\Db;

use PDO;

class ConnectionFactory
{
    private static $config;
    public static $db;

    public static function setConfig(string $nomFich){
        self::$config = parse_ini_file($nomFich);
    }

    public static function makeConnection(){
        if (is_null(self::$db)){
            $conf = self::$config;
            $dsn = "mysql:host=".$conf['host'].";dbname=".$conf['database'].";charset="."utf8";
            $user = self::$config['username'];
            $password = $conf['password'];
            self::$db = new \PDO($dsn, $user, $password);
        }
        return self::$db;
    }
}





