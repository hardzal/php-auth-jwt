<?php

class User {
    private $connection;
    private $table_name = 'users';

    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    public function create() {
        $query = "INSERT INTO ". $this->table_name . "
            SET
                firstname = :firstname,
                lastname = :lastname,
                email = :email,
                password = :password
        ";

        $statement = $this->connection->prepare($query);

        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $statement->bindParam(':firstname', $this->firstname);
        $statement->bindParam(':lastname', $this->lastname);
        $statement->bindParam(':email', $this->email);

        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $statement->bindParam(':password', $password_hash);

        if($statement->execute()) {
            return true;
        }

        printf("Error: ". $statement->error);
        return false;
    }

    public function emailExists() {
        $query = "SELECT id, firstname, lastname, password FROM ". $this->table_name . "
            WHERE email = ? LIMIT 0,1";

        $statement = $this->connection->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));

        $statement->bindParam(1, $this->email);

        $statement->execute();

        $num = $statement->rowCount();

        if($num > 0) {
            $row = $statement->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->password = $row['password'];

            return true;
        }

        return false;
    }

    public function update() {
        $password_set = !empty($this->password) ? "password = :password" : "";

        $query = "UPDATE ". $this->table_name ." SET 
                firstname = :firstname,
                lastname = :lastname,
                email = :email,
                {$password_set}
            WHERE id = :id";
        
        $statement = $this->connection->prepare($query);

        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $statement->bindParam(':firstname', $this->firstname);
        $statement->bindParam(':lastname', $this->lastname);
        $statement->bindParam(':email', $this->email);

        if(!empty($this->password)) {
            $this->password = htmlspecialchars(strip_tags($this->password));

            $password_hash = password_hash($this->password,  PASSWORD_BCRYPT);
            $statement->bindParam(':password', $password_hash);
        }

        $statement->bindParam(':id', $this->id);

        if($statement->execute()) {
            return true;
        }

        return false;
    }
}