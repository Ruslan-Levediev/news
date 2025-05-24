<?php
namespace App\Decorators;

use App\Models\NewsRepositoryInterface;

class NewsRepositoryLoggerDecorator implements NewsRepositoryInterface
{
    private NewsRepositoryInterface $wrapped;

    public function __construct(NewsRepositoryInterface $wrapped)
    {
        $this->wrapped = $wrapped;
    }

    public function getLatestNews(int $limit): array
    {
        return $this->wrapped->getLatestNews($limit);
    }    


    public function addNews(string $title, string $content, int $authorId, ?string $imagePath = null): int
    {
        error_log("Добавление новости: заголовок='{$title}', автор ID={$authorId}");
        return $this->wrapped->addNews($title, $content, $authorId, $imagePath);
    }

    
    public function addNotification(int $userId, string $message): void
    {
        error_log("Добавление уведомления пользователю ID={$userId}: {$message}");
        $this->wrapped->addNotification($userId, $message);
    }

    public function getAllNewsUnsorted(): array
    {
        return $this->wrapped->getAllNewsUnsorted();
    }

    public function getAllNews(): array
    {
        return $this->wrapped->getAllNews();
    }

    public function setMainNews(int $newsId): void
    {
        error_log("Установка новости ID={$newsId} как главной");
        $this->wrapped->setMainNews($newsId);
    }

    public function unsetMainNews(int $newsId): void
    {
        error_log("Снятие новости ID={$newsId} с главной");
        $this->wrapped->unsetMainNews($newsId);
    }

    public function deleteNewsById(int $newsId): bool
    {
        error_log("Удаление новости ID={$newsId}");
        return $this->wrapped->deleteNewsById($newsId);
    }

    public function getMainNews(): array
    {
        return $this->wrapped->getMainNews();
    }

    public function searchNews(string $searchTerm): array
    {
        return $this->wrapped->searchNews($searchTerm);
    }

    public function getNewsById(int $id): ?array
    {
        return $this->wrapped->getNewsById($id);
    }
}
