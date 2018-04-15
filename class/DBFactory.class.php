<?php
class DBFactory
{
    private static $db = null;
    public static function setMySQLConnection($db_host, $db_username, $db_password, $db_database, $db_port = 3306)
    {
        if(!self::$db) {
            try {
                $db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_database . ';port=' . $db_port, $db_username, $db_password);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $req = $db->prepare("SET NAMES 'utf8'");
                $req->execute();
                self::$db = $db;
            } catch (Exception $e) {
                throw new Exception("Connection error: " . $e->getMessage());
            }
        }

        return $db;
    }

    public static function getMySQLConnection(): PDO {
        if(!self::$db) throw new Exception("PDO is not initialized");
        return self::$db;
    }
}
