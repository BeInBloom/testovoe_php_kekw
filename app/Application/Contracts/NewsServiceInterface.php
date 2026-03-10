<?php

declare(strict_types=1);

namespace App\Application\Contracts;

use App\Application\DTOs\NewsDetailDTO;
use App\Application\DTOs\NewsListDTO;
use App\Domain\ValueObjects\NewsId;

interface NewsServiceInterface {
    public function getLatestNews(): ?NewsDetailDTO;
    public function getNewsList(int $page): NewsListDTO;
    public function getNewsDetail(NewsId $id): NewsDetailDTO;
}
