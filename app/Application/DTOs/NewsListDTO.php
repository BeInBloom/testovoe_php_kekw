<?php

declare(strict_types=1);

namespace App\Application\DTOs;

use InvalidArgumentException;

final readonly class NewsListDTO {
    public int $currentPage;
    public int $totalPages;
    public bool $hasNextPage;
    /** @var array<int, NewsDetailDTO> */
    public array $news;

    /**
     * @param array<int, NewsDetailDTO> $news
     */
    public function __construct(
        int $currentPage,
        int $totalPages,
        bool $hasNextPage,
        array $news
    ) {
        $this->validatePageNumbers($currentPage, $totalPages);
        $this->validateNewsArray($news);

        $this->currentPage = $currentPage;
        $this->totalPages  = $totalPages;
        $this->hasNextPage = $hasNextPage;
        $this->news        = $news;
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
}
