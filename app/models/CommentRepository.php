<?php
namespace App\Models;

use App\Database\DatabaseAdapterInterface;

class CommentRepository {
    private $dbAdapter;

    public function __construct(DatabaseAdapterInterface $dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    public function addComment(int $news_id, int $user_id, string $comment_text): void {
        $query = "INSERT INTO comments (news_id, user_id, comment_text) VALUES (?, ?, ?)";
        $this->dbAdapter->query($query, [$news_id, $user_id, $comment_text]);
    }

    public function deleteCommentById(int $comment_id): bool {
    $query = "DELETE FROM comments WHERE id = ?";
    $this->dbAdapter->query($query, [$comment_id]);
    return true;
    }
    
    /**
     * @return array
     */
    public function getCommentsByNewsId(int $newsId): array {
        $sql = "SELECT 
                    comments.*, 
                    users.username, 
                    users.display_name, 
                    users.avatar_path
                FROM comments
                JOIN users ON comments.user_id = users.id
                WHERE comments.news_id = ?
                ORDER BY comments.created_at DESC";
        return $this->dbAdapter->query($sql, [$newsId]);
    }



}
