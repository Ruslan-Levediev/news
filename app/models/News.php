<?php
namespace App\Models;

use PDO;
use App\Core\Database;

class News {
    public int $id;
    public string $title;
    public string $content;
    public User $author;
    public string $publishDate;  
    public bool $isMain;

    public function __construct(int $id, string $title, string $content, User $author, string $publishDate, bool $isMain = false) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
        $this->publishDate = $publishDate;
        $this->isMain = $isMain;
    }
    
 
    public function getId(): int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function getAuthor(): User {
        return $this->author;
    }


    public function getPublishDate(): string {
        return $this->publishDate;
    }


    public function getFormattedPublishDate(string $timezone = 'Europe/Kiev', string $format = 'Y-m-d H:i:s'): string {
        try {
            $utcDate = new \DateTime($this->publishDate, new \DateTimeZone('UTC'));
            $utcDate->setTimezone(new \DateTimeZone($timezone));
            return $utcDate->format($format);
        } catch (\Exception $e) {
            return $this->publishDate;
        }
    }

    public function isMain(): bool {
        return $this->isMain;
    }

    /**
     * 
     * @return News[]
     */
    public static function getAll(): array {
        $conn = Database::getConnection();
        $sql = "SELECT news.*, users.id as user_id, users.username, users.email, users.role 
                FROM news 
                LEFT JOIN users ON news.author_id = users.id
                ORDER BY news.publish_date DESC";
        $stmt = $conn->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $newsList = [];
        foreach ($rows as $row) {
            $author = new User(
                (int)$row['user_id'],
                $row['username'],
                $row['email'],
                $row['role']
            );
            $newsList[] = new self(
                (int)$row['id'],
                $row['title'],
                $row['content'],
                $author,
                $row['publish_date'],
                (bool)$row['is_main']
            );
        }
        return $newsList;
    }

    /**
     * 
     * @param int $id
     * @return News|null
     */
    public static function getById(int $id): ?self {
        $conn = Database::getConnection();
        $sql = "SELECT news.*, users.id as user_id, users.username, users.email, users.role 
                FROM news 
                LEFT JOIN users ON news.author_id = users.id
                WHERE news.id = :id LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $author = new User(
            (int)$row['user_id'],
            $row['username'],
            $row['email'],
            $row['role']
        );

        return new self(
            (int)$row['id'],
            $row['title'],
            $row['content'],
            $author,
            $row['publish_date'],
            (bool)$row['is_main']
        );
    }

    /**
     * 
     * @return News|null
     */
    public static function getMainNews(): ?self {
        $conn = Database::getConnection();
        $sql = "SELECT news.*, users.id as user_id, users.username, users.email, users.role 
                FROM news 
                LEFT JOIN users ON news.author_id = users.id
                WHERE news.is_main = 1
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $author = new User(
            (int)$row['user_id'],
            $row['username'],
            $row['email'],
            $row['role']
        );

        return new self(
            (int)$row['id'],
            $row['title'],
            $row['content'],
            $author,
            $row['publish_date'],
            (bool)$row['is_main']
        );
    }
}
