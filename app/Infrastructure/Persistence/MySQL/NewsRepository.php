<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\MySQL;

use App\Domain\Contracts\LoggerInterface;
use App\Domain\Contracts\NewsRepositoryInterface;
use App\Domain\Entities\News;
use App\Domain\Exceptions\NewsNotFoundException;
use App\Domain\ValueObjects\NewsDate;
use App\Domain\ValueObjects\NewsId;
use InvalidArgumentException;
use PDO;

final class NewsRepository implements NewsRepositoryInterface {
    public function __construct(
        private Connection $connection,
        private LoggerInterface $logger
    ) {}

    public function getById(NewsId $id): News {
        $this->logger->info('Fetching news by ID', ['id' => $id->getValue()]);

        $sql = 'SELECT * FROM news WHERE id = :id';

        $stmt = $this->connection->getPDO()->prepare($sql);
        $stmt->execute(['id' => $id->getValue()]);

        $data = $stmt->fetch();

        if (!is_array($data)) {
            $this->logger->warning('News not found', ['id' => $id->getValue()]);
            throw NewsNotFoundException::byId($id->getValue());
        }

        $this->logger->info('News fetched successfully', ['id' => $id->getValue()]);

        return $this->mapRowToEntity($data);
    }

    public function getLatest(): News {
        $this->logger->info('Fetching latest news');

        $sql = 'SELECT * FROM news ORDER BY date DESC LIMIT 1';

        $stmt = $this->connection->getPDO()->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetch();

        if (!is_array($data)) {
            $this->logger->error('No news found in database');
            throw NewsNotFoundException::latest();
        }

        $this->logger->info('Latest news fetched successfully', ['id' => (int) $data['id']]);

        return $this->mapRowToEntity($data);
    }

    /**
     * @return array<int, News>
     */
    public function getPaginated(int $page, int $perPage): array {
        $this->logger->info('Fetching paginated news', ['page' => $page, 'perPage' => $perPage]);

        $offset = ($page - 1) * $perPage;

        $sql = 'SELECT * FROM news ORDER BY date DESC LIMIT :limit OFFSET :offset';

        $stmt = $this->connection->getPDO()->prepare($sql);
        $stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        /** @var array<int, mixed> $rows */
        $rows = $stmt->fetchAll();
        $news = [];
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $news[] = $this->mapRowToEntity($row);
        }

        $this->logger->info('Paginated news fetched', [
            'page'  => $page,
            'count' => count($news),
        ]);

        return $news;
    }

    public function getTotalCount(): int {
        $this->logger->info('Fetching total news count');

        $sql = 'SELECT COUNT(*) as total FROM news';

        $stmt = $this->connection->getPDO()->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetch();

        if (
            !is_array($result) || !array_key_exists('total', $result) || !is_numeric($result['total'])
        ) {
            $this->logger->error('Failed to fetch total news count');
            return 0;
        }

        $total = (int) $result['total'];

        $this->logger->info('Total news count fetched', ['total' => $total]);

        return $total;
    }

    /**
     * @param array<string, mixed> $row
     */
    private function mapRowToEntity(array $row): News {
        $this->validateRow($row);

        $id       = (int) $row['id'];
        $date     = (string) $row['date'];
        $title    = (string) $row['title'];
        $announce = (string) $row['announce'];
        $content  = (string) $row['content'];
        $image    = (string) $row['image'];

        return new News(
            new NewsId($id),
            NewsDate::fromString($date),
            $title,
            $announce,
            $content,
            $image
        );
    }

    /**
     * @param array<string, mixed> $row
     * @phpstan-assert array{
     *     id: int|numeric-string,
     *     date: string,
     *     title: string,
     *     announce: string,
     *     content: string,
     *     image: string
     * } $row
     */
    private function validateRow(array $row): void {
        $requiredKeys = ['id', 'date', 'title', 'announce', 'content', 'image'];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $row)) {
                throw new InvalidArgumentException("Missing required key: {$key}");
            }
        }

        if (!is_int($row['id']) && !(is_string($row['id']) && is_numeric($row['id']))) {
            throw new InvalidArgumentException('Invalid news row: id must be numeric');
        }

        foreach (['date', 'title', 'announce', 'content', 'image'] as $key) {
            if (!is_string($row[$key])) {
                throw new InvalidArgumentException("Invalid news row: {$key} must be string");
            }
        }
    }
}
