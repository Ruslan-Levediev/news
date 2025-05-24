<?php
namespace App\Strategies;

interface NewsDisplayStrategy
{
    /**
     * 
     * @param array 
     * @return array
     */
    public function process(array $newsList): array;
}
