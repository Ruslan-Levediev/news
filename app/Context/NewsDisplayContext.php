<?php
namespace App\Context;

use App\Strategies\NewsDisplayStrategy;

class NewsDisplayContext
{
    private NewsDisplayStrategy $strategy;

    public function __construct(NewsDisplayStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function setStrategy(NewsDisplayStrategy $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function display(array $newsList): array
    {
        return $this->strategy->process($newsList);
    }
}
