<?php

declare(strict_types=1);

namespace Tests\Unit\Application\DTOs;

use App\Application\DTOs\NewsDetailDTO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class NewsDetailDTOTest extends TestCase {
    public function test_from_array_and_to_array_round_trip(): void {
        $data = [
            'id'       => 7,
            'date'     => '2026-03-05 10:00:00',
            'title'    => 'Title',
            'announce' => 'Announce',
            'content'  => 'Content',
            'image'    => 'image.jpg',
        ];

        $dto = NewsDetailDTO::fromArray($data);

        self::assertSame($data, $dto->toArray());
    }

    public function test_construct_with_invalid_id_throws_exception(): void {
        $this->expectException(InvalidArgumentException::class);

        new NewsDetailDTO(
            0,
            '2026-03-05 10:00:00',
            'Title',
            'Announce',
            'Content',
            'image.jpg'
        );
    }

    public function test_construct_with_empty_title_throws_exception(): void {
        $this->expectException(InvalidArgumentException::class);

        new NewsDetailDTO(
            1,
            '2026-03-05 10:00:00',
            ' ',
            'Announce',
            'Content',
            'image.jpg'
        );
    }

    public function test_construct_accepts_empty_optional_fields(): void {
        $dto = new NewsDetailDTO(
            1,
            '2026-03-05 10:00:00',
            'Title',
            '',
            '',
            ''
        );

        self::assertSame('', $dto->announce);
        self::assertSame('', $dto->content);
        self::assertSame('', $dto->image);
    }
}
