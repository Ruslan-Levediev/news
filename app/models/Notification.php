<?php

namespace App\Models;
use PDO;

class Notification {
    public static function getUserNotifications(PDO $conn, $userId) {
        $stmt = $conn->prepare("SELECT id, message, created_at, is_read FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function deleteNotification(PDO $conn, $notifId, $userId) {
        $stmt = $conn->prepare("DELETE FROM notifications WHERE id = ? AND user_id = ?");
        return $stmt->execute([$notifId, $userId]);
    }
    public static function markAllRead(PDO $conn, $userId) {
        $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }
}
