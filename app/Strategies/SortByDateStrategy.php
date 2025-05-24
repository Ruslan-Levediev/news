<?php
namespace App\Strategies;

class SortByDateStrategy implements NewsDisplayStrategy
{
    public function process(array $newsList): array
{
    usort($newsList, function($a, $b) {
        $dateA = isset($a['publish_date']) ? strtotime($a['publish_date']) : 0;
        $dateB = isset($b['publish_date']) ? strtotime($b['publish_date']) : 0;
        return $dateB <=> $dateA;
    });
    return $newsList;
}

}
