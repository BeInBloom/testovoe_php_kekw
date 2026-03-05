<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

use App\Domain\Entities\News;
use App\Domain\ValueObjects\NewsId;

interface NewsRepositoryInterface {
    public function getById(NewsId $id): News;
    public function getLatest(): News;
    /**
     * @return array<int, News>
     */
    public function getPaginated(int $page, int $perPage): array;
    public function getTotalCount(): int;
}
