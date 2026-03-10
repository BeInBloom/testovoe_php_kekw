<?php

declare(strict_types=1);

namespace App\Application\DTOs;

use InvalidArgumentException;

final readonly class NewsListDTO {
    /**
     * @param array<int, NewsDetailDTO> $news
     */
    public function __construct(
        public int $currentPage,
        public int $totalPages,
        public bool $hasNextPage,
        public array $news
    ) {
        $this->validatePageNumbers($currentPage, $totalPages);
        $this->validateNewsArray($news);
        $this->validateNavigationState($currentPage, $totalPages, $hasNextPage);
    }

    /**
     * @param array{
     *     currentPage: int,
     *     totalPages: int,
     *     hasNextPage: bool,
     *     news: array<int, array{
     *         id: int,
     *         date: string,
     *         title: string,
     *         announce: string,
     *         content: string,
     *         image: string
     *     }>
     * } $data
     */
    public static function fromArray(array $data): self {
        $news = array_map(
            fn ($item) => NewsDetailDTO::fromArray($item),
            $data['news']
        );

        return new self(
            $data['currentPage'],
            $data['totalPages'],
            $data['hasNextPage'],
            $news
        );
    }

    /**
     * @return array{
     *     currentPage: int,
     *     totalPages: int,
     *     hasNextPage: bool,
     *     news: array<int, array{
     *         id: int,
     *         date: string,
     *         title: string,
     *         announce: string,
     *         content: string,
     *         image: string
     *     }>
     * }
     */
    public function toArray(): array {
        return [
            'currentPage' => $this->currentPage,
            'totalPages'  => $this->totalPages,
            'hasNextPage' => $this->hasNextPage,
            'news'        => array_map(
                fn ($item) => $item->toArray(),
                $this->news
            ),
        ];
    }

    private function validatePageNumbers(int $currentPage, int $totalPages): void {
        if ($currentPage <= 0) {
            throw new InvalidArgumentException('Current page must be positive');
        }

        if ($totalPages < 0) {
            throw new InvalidArgumentException('Total pages cannot be negative');
        }

        if ($totalPages > 0 && $currentPage > $totalPages) {
            throw new InvalidArgumentException('Current page cannot exceed total pages');
        }
    }

    /**
     * @param array<int, NewsDetailDTO> $news
     */
    private function validateNewsArray(array $news): void {
        foreach ($news as $index => $item) {
            if (!$item instanceof NewsDetailDTO) {
                throw new InvalidArgumentException(
                    'News array must contain only NewsDetailDTO instances at index ' . $index
                );
            }
        }
    }

    private function validateNavigationState(int $currentPage, int $totalPages, bool $hasNextPage): void {
        if ($totalPages === 0 && $currentPage !== 1) {
            throw new InvalidArgumentException('Empty pagination must use page 1 as current page');
        }

        if ($totalPages === 0 && $hasNextPage) {
            throw new InvalidArgumentException('Empty pagination cannot have a next page');
        }

        if ($totalPages > 0 && $hasNextPage !== ($currentPage < $totalPages)) {
            throw new InvalidArgumentException('Next page flag is inconsistent with current page');
        }
    }
}
