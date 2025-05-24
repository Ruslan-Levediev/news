<?php
namespace App\Database;

use App\Core\Database;
use PDO;

class SqliteDatabaseAdapter implements DatabaseAdapterInterface
{
    private PDO $connection;

    public function __construct(string $dbFile = __DIR__ . '/../../smgnews.db')
    {
        $this->connection = Database::getConnection($dbFile);
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function query(string $query, array $params = []): array
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastInsertId(): int
    {
        return (int)$this->connection->lastInsertId();
    }
}
