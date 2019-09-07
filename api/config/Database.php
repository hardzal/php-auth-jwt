<?php

class Database {
    private $db_host = "localhost";
    private $db_name = "latihan_api_auth";
    private $username = "root";
    private $password = "";
    private $db_options = [
        PDO::ATTR_ERRMODE   => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];

    public $connection;

    public function getConnection() {
        $this->connection = null;

        try {
            $this->connection = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->username, $this->password, $this->db_options);
        } catch(\PDOException $exception) {
            echo "Connection error: ". $exception->getMessage();
        }

        return $this->connection;
    }
}
?>