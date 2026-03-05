<?php

declare(strict_types=1);

namespace App\Presentation\Controllers;

use App\Application\Contracts\NewsServiceInterface;
use App\Application\DTOs\NewsDetailDTO;
use App\Application\DTOs\NewsListDTO;
use App\Domain\Contracts\LoggerInterface;
use App\Domain\ValueObjects\NewsId;
use Throwable;

final readonly class NewsController {
    public function __construct(
        private NewsServiceInterface $newsService,
        private LoggerInterface $logger,
    ) {}

    public function index(int $page = 1): NewsListDTO {
        try {
            $this->logger->info('Controller: News index', ['page' => $page]);

            return $this->newsService->getNewsList($page);
        } catch (Throwable $e) {
            $this->logger->error('Error in news index controller', [
                'page'  => $page,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function detail(int $id): NewsDetailDTO {
        try {
            $this->logger->info('Controller: News detail', ['id' => $id]);

            $newsId = new NewsId($id);

            return $this->newsService->getNewsDetail($newsId);
        } catch (Throwable $e) {
            $this->logger->error('Error in news detail controller', [
                'id'    => $id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
