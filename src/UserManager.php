<?php

namespace Fdjer\TestUnitaire;

use Exception;
use InvalidArgumentException;
use PDO;

class UserManager
{
    private PDO $db;

    public function __construct()
    {
        $dsn = "mysql:host=localhost;dbname=user_management;charset=utf8";
        $username = "root"; // Modifier si besoin
        $password = ""; // Modifier si besoin
        $this->db = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function addUser(string $name, string $email, string $role= 'user'): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email invalide.");
        }

        //insère m'utilisateur en tenant compte du nouveau champ role:
        $stmt = $this->db->prepare("INSERT INTO users (name, email, role) VALUES (:name, :email, :role)");
        $stmt->execute(['name' => $name, 'email' => $email, 'role' => $role]);
    }

    public function removeUser(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);

    }

    public function getUsers(): array
    {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }

    public function getUser(int $id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        if (!$user) throw new Exception("Utilisateur introuvable.");
        return $user;
    }

    public function updateUser(int $id, string $name, string $email, string $role): void
    {
        $stmt = $this->db->prepare(
            "UPDATE users SET name = :name, email = :email, role= :role 
                    WHERE id = :id");
        $stmt->execute(['id' => $id, 'name' => $name, 'email' => $email, 'role' => $role]);
    }

    public function resetTable(): void {
        $stmt = $this->db->prepare("DELETE FROM users;ALTER TABLE users AUTO_INCREMENT = 1; ");
        $stmt->execute();
    }
}

?>
