<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Database;
use App\Core\DatabaseSetup;

try {
    
    $conn = Database::getConnection();

    $dbSetup = new DatabaseSetup($conn);

    
    $dbSetup->initializeDatabase();

    echo "База данных успешно инициализирована.\n";

} catch (Exception $e) {
    echo "Ошибка инициализации базы данных: " . $e->getMessage();
}
