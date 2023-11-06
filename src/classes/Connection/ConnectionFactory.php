<?php

namespace touiteur\Connection;

use PDO;
use PDOException;

class ConnectionFactory {
    public static ?PDO $db = null;
    public static array $config = [];

    static function setConfig($file) {
        self::$config = parse_ini_file($file);
    }

    static function makeConnection() {
        if (self::$db == null) {
            $dsn = self::$config['driver'].
                ':host='.self::$config['host'].
                ';dbname='.self::$config['database'];

            try {


                self::$db = new PDO($dsn.";unix_socket=/opt/lampp/var/mysql/mysql.sock", self::$config['username'], self::$config['password'], [
                    PDO::ATTR_PERSISTENT => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_STRINGIFY_FETCHES => false
                ]);
            } catch (PDOException $e) {


                die('Erreur : '.$e->getMessage());
            }

            self::$db->prepare("SET NAMES 'utf8'")->execute();
        }
    }
}