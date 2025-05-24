<?php
namespace App\Core;

use PDO;
use PDOException;

class DatabaseSetup {
    private PDO $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function createUsersTable(): void {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            email TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            role TEXT DEFAULT 'user'
        )";
        $this->conn->exec($sql);
    }

    public function createNewsTable(): void {
        $sql = "CREATE TABLE IF NOT EXISTS news (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            content TEXT NOT NULL,
            author_id INTEGER,
            is_main INTEGER DEFAULT 0,
            publish_date TEXT,
            FOREIGN KEY(author_id) REFERENCES users(id)
        )";
        $this->conn->exec($sql);
    }

    public function seedUsers(): void {
        $passwordHash = password_hash('admin', PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT OR IGNORE INTO users (username, email, password_hash, role) VALUES (:username, :email, :password_hash, :role)");
        $stmt->execute([
            ':username' => 'admin2',
            ':email' => 'admin2@smg.ua',
            ':password_hash' => $passwordHash,
            ':role' => 'admin'
        ]);
    }

    public function seedNews(): void {
        $stmt = $this->conn->prepare("INSERT OR IGNORE INTO news (title, content, author_id, is_main, publish_date) VALUES (:title, :content, :author_id, :is_main, :publish_date)");
        $stmt->execute([
            ':title' => 'Відкриття нової лікарні',
            ':content' => 'Опис новини...',
            ':author_id' => 1,
            ':is_main' => 1,
            ':publish_date' => date('Y-m-d H:i:s')
        ]);
    }

    public function initializeDatabase(): void {
        try {
            $this->conn->beginTransaction();

            $this->createUsersTable();
            $this->createNewsTable();

            $this->seedUsers();
            $this->seedNews();

            $this->conn->commit();
        } catch (PDOException $e) {
            $this->conn->rollBack();
            die("Не удалось создать базу данных: " . $e->getMessage());
        }
    }
}
