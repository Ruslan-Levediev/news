<?php
namespace App\Models;

class CommentModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addComment(int $news_id, int $user_id, string $comment_text): void {
        $stmt = $this->db->prepare("INSERT INTO comments (news_id, user_id, comment_text) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $news_id, $user_id, $comment_text);
        $stmt->execute();
        $stmt->close();
    }

    public function getCommentsByNewsId(int $news_id): array {
        $result = $this->db->query("
            SELECT comments.*, users.username, users.display_name, users.avatar_path
            FROM comments
            JOIN users ON comments.user_id = users.id
            WHERE comments.news_id = ?
            ORDER BY comments.created_at DESC
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
