<?php
namespace App\Factories;

use App\Models\UserRepository;
use App\Models\NewsRepository;
use App\Models\SuperUserRepository;
use App\Models\CommentRepository;
use App\Database\DatabaseAdapterInterface;
use App\Database\SqliteDatabaseAdapter;
use App\Decorators\NewsRepositoryLoggerDecorator;

class ModelFactory {
    public static function create(string $type, ?DatabaseAdapterInterface $dbAdapter = null): object
    {
        if ($dbAdapter === null) {
            $dbAdapter = new SqliteDatabaseAdapter();
        }

        switch (strtolower($type)) {
            case 'userrepository':
                return new UserRepository($dbAdapter);

            case 'newsrepository':
                $repo = new NewsRepository($dbAdapter);
                return new NewsRepositoryLoggerDecorator($repo);

            case 'superuserrepository':
                return new SuperUserRepository($dbAdapter);

            case 'commentrepository':
                return new CommentRepository($dbAdapter);

            default:
                throw new \InvalidArgumentException("Unknown model type: $type");
        }
    }
}
