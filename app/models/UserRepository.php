<?php
namespace App\Models;

use PDO;
use App\Core\Database;

class UserRepository {
    private PDO $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function addUser(string $username, string $email, string $passwordHash, string $role = 'user'): bool {
        $stmt = $this->conn->prepare(
            "INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :password_hash, :role)"
        );
        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password_hash' => $passwordHash,
            ':role' => $role
        ]);
    }

    public function updateUserProfile(int $userId, ?string $displayName, ?string $avatarPath): bool {
        $sql = "UPDATE users SET display_name = :display_name, avatar_path = :avatar_path WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':display_name' => $displayName,
            ':avatar_path' => $avatarPath,
            ':id' => $userId
        ]);
    }

    public function markNotificationRead(int $notificationId, int $userId): bool {
        $sql = "UPDATE notifications SET is_read = 1 WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $notificationId,
            ':user_id' => $userId
        ]);
    }

    public function getUserById(int $id): ?User {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            if ($row['role'] === 'admin') {
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
            return new User(
                (int)$row['id'],
                $row['username'],
                $row['email'],
                $row['role'] ?? 'user',
                $row['password_hash'] ?? null,
                $row['avatar_path'] ?? null,
                $row['display_name'] ?? null
            );
        }
        return null;
    }

    public function clearNotifications(int $userId): bool {
        $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':user_id' => $userId]);
    }

    public function getUserByUsername(string $username): ?User {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            if ($row['role'] === 'admin') {
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
            return new User(
                (int)$row['id'],
                $row['username'],
                $row['email'],
                $row['role'] ?? 'user',
                $row['password_hash'] ?? null,
                $row['avatar_path'] ?? null,
                $row['display_name'] ?? null
            );
        }
        return null;
    }

    public function belongsToUser(int $notificationId, int $userId): bool {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM notifications WHERE id = :notificationId AND user_id = :userId"
        );
        $stmt->execute([
            ':notificationId' => $notificationId,
            ':userId' => $userId
        ]);
        return $stmt->fetchColumn() > 0;
    }

    public function deleteNotification(int $notificationId): bool {
        $stmt = $this->conn->prepare("DELETE FROM notifications WHERE id = :id");
        return $stmt->execute([':id' => $notificationId]);
    }

    public function getAllUsers(): array {
        $stmt = $this->conn->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserNotifications(int $userId): array {
        $stmt = $this->conn->prepare("SELECT id, message, created_at, is_read FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
