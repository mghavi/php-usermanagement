<?php
class Db {

    static private $instance;
    private $dbName = "phonebook";
    private $dbUsername = "root";
    private $dbPassword = "";
    private $pdo;

    private function __construct() {
        $this->pdo = new PDO("mysql:dbname=$this->dbName"
                , $this->dbUsername
                , $this->dbPassword);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * 
     * @return \Db
     */
    static public function getInstance() {
        if (null === self::$instance) {
            self::$instance = new Db;
        }
        return self::$instance;
    }

    /**
     * 
     * @return \PDO
     */
    static public function getPdo() {
        return self::getInstance()->pdo;
    }

}
