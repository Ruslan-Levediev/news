<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class SuperUserRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    /**
     * 
     * 
     * @param int $id
     * @return SuperUser|null
     */
    public function getSuperUserById(int $id): ?SuperUser
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id AND role = 'admin' LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new SuperUser(
                (int)$row['id'],
                $row['username'],
                $row['email'],
                $row['character'] ?? 'admin',
                $row['password_hash'] ?? null,
                $row['avatar_path'] ?? null,
                $row['display_name'] ?? null
            );
        }
        return null;
    }

    /**
     * 
     * @param string 
     * @return SuperUser|null
     */
    public function getSuperUserByLogin(string $username): ?SuperUser
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username AND role = 'admin' LIMIT 1");
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new SuperUser(
                (int)$row['id'],
                $row['username'],
                $row['email'],
                $row['character'] ?? 'admin',
                $row['password_hash'] ?? null,
                $row['avatar_path'] ?? null,
                $row['display_name'] ?? null
            );
        }
        return null;
    }

    // Добавьте другие методы при необходимости
}
