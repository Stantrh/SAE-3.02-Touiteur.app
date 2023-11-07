<?php

namespace touiteur\Database;

use PDO;

class ConnectionFactory
{
    public static ?PDO $db = null;

    public static array $config = [];
    /**
     * la méthode setConfig( $file ) qui reçoit un nom de fichier contenant les paramètres
     * de connexion, charge ce fichier avec parse_ini_file(), et stocke le tableau résultat
     * dans une variable statique.
     * • la méthode makeConnection() qui fabrique une connection en utilisant la configuration
     * stockée : fabrique le dsn, puis instancie un objet PDO
     */

    /**
     * Reçoit un nom de fichier contenant les paramètres de connexion
     * charge ce fichier avec parse_ini_file()
     * et stocke le tableau résultat dans une variable statique
     * @param $file
     * @return
     */
    public static function setConfig($file) : void{
        self::$config = parse_ini_file($file);
    }


    /**
     * Fabrique une connection en utilisant la configuration stockée :
     * Fabrique le dsn
     * Puis instancie un objet PDO
     * "mysql:host=localhost;dbname=td_php_bdd
     */
    public static function makeConnection() : PDO{
        // Crée une instance de PDO
        //"mysql:host=localhost;dbname=td_php_bdd"

        if(self::$db == null){
            $dsn = self::$config['driver'].
                ':host='.self::$config['host'].
                ';dbname='.self::$config['database'];
            self::$db = new PDO($dsn, self::$config['username'], self::$config['password'], [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false
            ]);

            self::$db->prepare('SET NAMES \'UTF8\'')->execute();
        }
        return self::$db;
    }


}