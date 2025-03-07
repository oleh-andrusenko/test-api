<?php

namespace App\Models;

require_once __DIR__ . '/../Core/Database.php';

use App\Core\Database;


class User
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }
    public function getAllUsers()
    {
        $stmt = $this->pdo->query("SELECT id, name, email FROM users");
        return $stmt->fetchAll();
    }
    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare("SELECT id, name, email FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function updateUser($id, $name, $email, $password)
    {
        $stmt = $this->pdo->prepare('UPDATE users  SET name=?, email=?, password=?, updated_at=NOW() WHERE id=?');
        return $stmt->execute([$name, $email, $password, $id]);
    }
    public function deleteUser($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function createUser($name, $email, $password)
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
        return $stmt->execute([$name, $email, $password]);
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
}