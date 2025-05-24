<?php
namespace App\Strategies;

class SortByDateDescStrategy implements NewsSortStrategy
{
    public function sort(array $newsList): array
    {
        usort($newsList, function($a, $b) {
            $dateA = strtotime($a['publish_date'] ?? '0');
            $dateB = strtotime($b['publish_date'] ?? '0');
            return $dateB <=> $dateA;
        });
        return $newsList;
    }
}
