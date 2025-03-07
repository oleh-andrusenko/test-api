<?php
namespace App\Core;

use PDO;
use PDOException;

class Database{
    private static $connection = null;

    private $pdo;

    private function __construct()
    {
        $dbConfig =  require __DIR__ . '/../config/db.php';
        $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbName']};charset=utf8";
        try {
            $this->pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            die(json_encode(["error" => "Error. Database connection failed: " . $e->getMessage()]));
        }
    }

    public static function getConnection(){
        if(self::$connection == null){
            self::$connection = new self();
        }
        return self::$connection->pdo;
    }
}