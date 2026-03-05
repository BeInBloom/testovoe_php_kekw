<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Contracts\NewsServiceInterface;
use App\Application\DTOs\NewsDetailDTO;
use App\Application\DTOs\NewsListDTO;
use App\Domain\Contracts\LoggerInterface;
use App\Domain\Contracts\NewsRepositoryInterface;
use App\Domain\Contracts\PaginationInterface;
use App\Domain\Entities\News;
use App\Domain\ValueObjects\NewsId;
use InvalidArgumentException;

final class NewsService implements NewsServiceInterface
{
    private const int NEWS_PER_PAGE = 4;

    public function __construct(
        private NewsRepositoryInterface $newsRepository,
        private PaginationInterface $pagination,
        private LoggerInterface $logger
    ) {
    }

    public function getLatestNews(): NewsDetailDTO
    {
        $this->logger->info('Fetching latest news in service');

        $news = $this->newsRepository->getLatest();

        return $this->mapEntityToDTO($news);
    }

    public function getNewsList(int $page): NewsListDTO
    {
        $this->validatePage($page);

        $this->logger->info('Fetching news list in service', ['page' => $page]);

        $total = $this->newsRepository->getTotalCount();
        $totalPages = $this->pagination->getTotalPages($total, self::NEWS_PER_PAGE);
        $news = $this->newsRepository->getPaginated($page, self::NEWS_PER_PAGE);
        $hasNextPage = $this->pagination->hasNextPage($page, $total, self::NEWS_PER_PAGE);

        $newsDTOs = array_map(fn (News $news) => $this->mapEntityToDTO($news), $news);

        $this->logger->info('News list fetched', [
            'page' => $page,
            'count' => count($newsDTOs),
            'totalPages' => $totalPages,
        ]);

        return new NewsListDTO($page, $totalPages, $hasNextPage, $newsDTOs);
    }

    public function getNewsDetail(NewsId $id): NewsDetailDTO
    {
        $this->logger->info('Fetching news detail in service', ['id' => $id->getValue()]);

        $news = $this->newsRepository->getById($id);

        return $this->mapEntityToDTO($news);
    }

    private function mapEntityToDTO(News $news): NewsDetailDTO
    {
        return new NewsDetailDTO(
            $news->getId()->getValue(),
            $news->getDate()->format('Y-m-d H:i:s'),
            $news->getTitle(),
            $news->getAnnounce(),
            $news->getContent(),
            $news->getImage()
        );
    }

    private function validatePage(int $page): void
    {
        if ($page <= 0) {
            throw new InvalidArgumentException('Page number must be positive');
        }
    }
}
