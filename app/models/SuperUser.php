<?php
namespace App\Models;

use PDO;

class SuperUser extends User {
    protected string $character; 

    public function __construct(
        int $id,
        string $username,
        string $email,
        string $character = 'admin',
        ?string $passwordHash = null,
        ?string $avatarPath = null,
        ?string $displayName = null
    ) {
        parent::__construct($id, $username, $email, 'admin', $passwordHash, $avatarPath, $displayName);
        $this->character = $character;
    }


    public function getCharacter(): string {
        return $this->character;
    }

    
    public function getInfo(): string {
        return "<div>
            <h3>Користувач: " . htmlspecialchars($this->getUsername()) . "</h3>
            <p>Email: " . htmlspecialchars($this->getEmail()) . "</p>
            <p>Роль: " . htmlspecialchars($this->getRole()) . "</p>
            <p>Характеристика: " . htmlspecialchars($this->getCharacter()) . "</p>
        </div>";
    }

  
    public static function addSuperUser(PDO $conn, string $username, string $email, string $passwordHash, string $character = 'admin'): int {
        $sql = "INSERT INTO users (username, email, password_hash, role, character) VALUES (:username, :email, :password_hash, 'admin', :character)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password_hash' => $passwordHash,
            ':character' => $character
        ]);
        return (int)$conn->lastInsertId();
    }

    
    public static function getSuperUserById(PDO $conn, int $id): ?self {
        $sql = "SELECT * FROM users WHERE id = :id AND role = 'admin'";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new self(
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

   
    public static function getSuperUserByLogin(PDO $conn, string $username): ?self {
        $sql = "SELECT * FROM users WHERE username = :username AND role = 'admin' LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new self(
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

    
    public function updateSuperUser(PDO $conn): bool {
        $sql = "UPDATE users SET username = :username, email = :email, character = :character, display_name = :display_name, avatar_path = :avatar_path WHERE id = :id AND role = 'admin'";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':username' => $this->username,
            ':email' => $this->email,
            ':character' => $this->character,
            ':display_name' => $this->displayName,
            ':avatar_path' => $this->avatarPath,
            ':id' => $this->id
        ]);
    }

    public static function deleteSuperUser(PDO $conn, int $id): bool {
        $sql = "DELETE FROM users WHERE id = :id AND role = 'admin'";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
