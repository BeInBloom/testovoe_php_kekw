<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\MySQL;

use App\Domain\Contracts\LoggerInterface;
use PDO;
use PDOException;

final readonly class Connection {
    private PDO $pdo;
    private LoggerInterface $logger;

    public function __construct(
        string $host,
        string $database,
        string $user,
        string $password,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;

        try {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $host, $database);

            $this->pdo = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);

            $this->logger->info('Database connection established', [
                'host'     => $host,
                'database' => $database,
            ]);
        } catch (PDOException $e) {
            $this->logger->error('Database connection failed', [
                'error'    => $e->getMessage(),
                'host'     => $host,
                'database' => $database,
            ]);

            throw new PDOException('Database connection failed: ' . $e->getMessage());
        }
    }

    public function getPDO(): PDO {
        return $this->pdo;
    }
}
