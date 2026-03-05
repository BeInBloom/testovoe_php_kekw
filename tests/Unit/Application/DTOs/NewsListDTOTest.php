<?php

declare(strict_types=1);

namespace Tests\Unit\Application\DTOs;

use App\Application\DTOs\NewsDetailDTO;
use App\Application\DTOs\NewsListDTO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class NewsListDTOTest extends TestCase {
    public function test_from_array_and_to_array_round_trip(): void {
        $data = [
            'currentPage' => 1,
            'totalPages'  => 2,
            'hasNextPage' => true,
            'news'        => [
                [
                    'id'       => 1,
                    'date'     => '2026-03-05 10:00:00',
                    'title'    => 'Title',
                    'announce' => 'Announce',
                    'content'  => 'Content',
                    'image'    => 'image.jpg',
                ],
            ],
        ];

        $dto = NewsListDTO::fromArray($data);

        self::assertSame($data, $dto->toArray());
    }

    public function test_construct_with_invalid_current_page_throws_exception(): void {
        $this->expectException(InvalidArgumentException::class);

        new NewsListDTO(0, 1, false, []);
    }

    public function test_construct_with_non_dto_news_item_throws_exception(): void {
        $this->expectException(InvalidArgumentException::class);

        new NewsListDTO(1, 1, false, ['invalid']);
    }

    public function test_construct_accepts_news_detail_dto_array(): void {
        $item = new NewsDetailDTO(
            1,
            '2026-03-05 10:00:00',
            'Title',
            'Announce',
            'Content',
            'image.jpg'
        );

        $dto = new NewsListDTO(1, 1, false, [$item]);

        self::assertCount(1, $dto->news);
        self::assertSame($item, $dto->news[0]);
    }
}
