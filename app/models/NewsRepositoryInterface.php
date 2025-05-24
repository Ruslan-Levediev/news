<?php
namespace App\Models;

interface NewsRepositoryInterface
{
    public function addNews(string $title, string $content, int $authorId, ?string $imagePath = null): int;

    public function addNotification(int $userId, string $message): void;

    public function getAllNewsUnsorted(): array;

    public function getAllNews(): array;

    public function setMainNews(int $newsId): void;

    public function unsetMainNews(int $newsId): void;

    public function deleteNewsById(int $newsId): bool;

    public function getMainNews(): array;

    public function searchNews(string $searchTerm): array;

    public function getNewsById(int $id): ?array;

    public function getLatestNews(int $limit): array;

    
}