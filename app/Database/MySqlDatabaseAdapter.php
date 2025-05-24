<?php
namespace App\Database;

use PDO;
use PDOException;

class MySqlDatabaseAdapter implements DatabaseAdapterInterface
{
    private ?PDO $connection = null;
    private string $host;
    private string $dbName;
    private string $user;
    private string $password;

    public function __construct(string $host, string $dbName, string $user, string $password)
    {
        $this->host = $host;
        $this->dbName = $dbName;
        $this->user = $user;
        $this->password = $password;
    }

    public function getConnection()
    {
        if ($this->connection === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
            try {
                $this->connection = new PDO($dsn, $this->user, $this->password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                throw new \RuntimeException("MySQL connection failed: " . $e->getMessage());
            }
        }
        return $this->connection;
    }

    public function query(string $query, array $params = []): array
    {
        $conn = $this->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getLastInsertId(): int
    {
        return (int)$this->getConnection()->lastInsertId();
    }
}
