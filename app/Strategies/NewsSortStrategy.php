<?php
namespace App\Strategies;

interface NewsSortStrategy
{
    /**
     * 
     * @param array 
     * @return array
     */
    public function sort(array $newsList): array;
}
