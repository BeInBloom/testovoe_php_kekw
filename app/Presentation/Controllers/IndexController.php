<?php

declare(strict_types=1);

namespace App\Presentation\Controllers;

use App\Application\Contracts\NewsServiceInterface;
use App\Domain\Contracts\LoggerInterface;
use Throwable;

final readonly class IndexController
{
    public function __construct(
        private NewsServiceInterface $newsService,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @return array{
     *     latest: \App\Application\DTOs\NewsDetailDTO,
     *     list: \App\Application\DTOs\NewsListDTO
     * }
     */
    public function __invoke(int $page = 1): array
    {
        try {
            $this->logger->info('Controller: Index page', ['page' => $page]);

            $latestNews = $this->newsService->getLatestNews();
            $newsList = $this->newsService->getNewsList($page);

            return [
                'latest' => $latestNews,
                'list' => $newsList,
            ];
        } catch (Throwable $e) {
            $this->logger->error('Error in index controller', [
                'page' => $page,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
