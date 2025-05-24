<?php
namespace App\Models;

use App\Database\DatabaseAdapterInterface;

class NewsRepository implements NewsRepositoryInterface
{
    private DatabaseAdapterInterface $dbAdapter;

    public function __construct(DatabaseAdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function addNews(string $title, string $content, int $authorId, ?string $imagePath = null): int
    {
        $publishDateUtc = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');

        $sql = "
            INSERT INTO news (title, content, author_id, image_path, publish_date, is_main)
            VALUES (:title, :content, :author_id, :image_path, :publish_date, 0)
        ";
        $this->dbAdapter->query($sql, [
            ':title' => $title,
            ':content' => $content,
            ':author_id' => $authorId,
            ':image_path' => $imagePath,
            ':publish_date' => $publishDateUtc
        ]);

        return $this->dbAdapter->getLastInsertId();
    }

    public function addNotification(int $userId, string $message): void
    {
        $sql = "
            INSERT INTO notifications (user_id, message)
            VALUES (:user_id, :message)
        ";
        $this->dbAdapter->query($sql, [
            ':user_id' => $userId,
            ':message' => $message
        ]);
    }

    public function getAllNewsUnsorted(): array
    {
        $sql = "
            SELECT news.*, users.username AS author_name, users.display_name
            FROM news
            LEFT JOIN users ON news.author_id = users.id
        ";
        return $this->dbAdapter->query($sql);
    }

    public function getAllNews(): array
    {
        $sql = "
            SELECT news.*, users.username AS author_name, users.display_name
            FROM news
            LEFT JOIN users ON news.author_id = users.id
            ORDER BY news.publish_date DESC
        ";
        return $this->dbAdapter->query($sql);
    }
    
    public function getLatestNews(int $limit): array
    {
        $sql = "SELECT news.*, users.username AS author_name, users.display_name
                FROM news
                LEFT JOIN users ON news.author_id = users.id
                ORDER BY news.publish_date DESC
                LIMIT ?";
        return $this->dbAdapter->query($sql, [$limit]);
    }
    
    public function setMainNews(int $newsId): void
    {
        $sql = "UPDATE news SET is_main = 1 WHERE id = :id";
        $this->dbAdapter->query($sql, [':id' => $newsId]);
    }

    public function unsetMainNews(int $newsId): void
    {
        $sql = "UPDATE news SET is_main = 0 WHERE id = :id";
        $this->dbAdapter->query($sql, [':id' => $newsId]);
    }

    public function deleteNewsById(int $newsId): bool
    {
        $sql = "DELETE FROM news WHERE id = :id";
        $this->dbAdapter->query($sql, [':id' => $newsId]);
        return true;
    }

    public function getMainNews(): array
    {
        $sql = "
            SELECT news.*, users.username AS author_name, users.display_name
            FROM news
            LEFT JOIN users ON news.author_id = users.id
            WHERE news.is_main = 1
            ORDER BY news.publish_date DESC
        ";
        return $this->dbAdapter->query($sql);
    }

    public function searchNews(string $searchTerm): array
    {
        $sql = "
            SELECT news.*, users.username AS author_name, users.display_name
            FROM news
            LEFT JOIN users ON news.author_id = users.id
            WHERE news.title LIKE :search OR news.content LIKE :search
            ORDER BY news.publish_date DESC
        ";
        $likeTerm = '%' . $searchTerm . '%';
        return $this->dbAdapter->query($sql, [':search' => $likeTerm]);
    }

    public function getNewsById(int $id): ?array
    {
        $sql = "
            SELECT news.*, users.username AS author_name, users.display_name
            FROM news
            LEFT JOIN users ON news.author_id = users.id
            WHERE news.id = :id
        ";
        $result = $this->dbAdapter->query($sql, [':id' => $id]);
        return $result[0] ?? null;
    }
}
