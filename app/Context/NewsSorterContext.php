<?php
namespace App\Context;

use App\Strategies\NewsSortStrategy;

class NewsSorterContext
{
    private NewsSortStrategy $strategy;

    public function __construct(NewsSortStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function setStrategy(NewsSortStrategy $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function sort(array $newsList): array
    {
        return $this->strategy->sort($newsList);
    }
}
