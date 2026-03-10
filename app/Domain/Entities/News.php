<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\NewsDate;
use App\Domain\ValueObjects\NewsId;
use InvalidArgumentException;

final readonly class News {
    public function __construct(
        private NewsId $id,
        private NewsDate $date,
        private string $title,
        private string $announce,
        private string $content,
        private string $image
    ) {
        $this->validateTitle($title);
    }

    public function getId(): NewsId {
        return $this->id;
    }

    public function getDate(): NewsDate {
        return $this->date;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getAnnounce(): string {
        return $this->announce;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function getImage(): string {
        return $this->image;
    }

    private function validateTitle(string $title): void {
        if (trim($title) === '') {
            throw new InvalidArgumentException('Title cannot be empty');
        }
    }
}
