<?php
namespace App\Database;

interface DatabaseAdapterInterface
{
    public function getConnection();

    public function query(string $query, array $params = []): array;

    /**
     * 
     * @return int
     */
    public function getLastInsertId(): int;
}
