<?php

namespace App;
use PDO;


class User
{
    public $pdo;

    public function __construct()
    {
        $db = new DB();
        $this->pdo = $db->conn;
    }

    public function register(
        string $full_name,
        string $email,
        string $password
    ): bool|int
    {
        $select = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $select->bindParam(":email", $email);
        $select->execute();
        if ($select->rowCount() > 0) {
            return false;
        }

        $query = "INSERT INTO users(full_name, email, password)
              VALUES (:full_name, :email, :password)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':full_name' => $full_name,
            ':email' => $email,
            ':password' => $password,
        ]);

        return $this->pdo->lastInsertId();
    }

    public function login(string $email, string $password): bool|array
    {
        $query = "SELECT * FROM users WHERE email = :email AND password = :password";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':email' => $email,
            ':password' => $password,
        ]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
            return false;
    }
}
?>