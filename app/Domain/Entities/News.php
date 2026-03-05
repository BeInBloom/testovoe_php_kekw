<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\NewsDate;
use App\Domain\ValueObjects\NewsId;
use InvalidArgumentException;

final readonly class News
{
    public NewsId $id;
    public NewsDate $date;
    public string $title;
    public string $announce;
    public string $content;
    public string $image;

    public function __construct(
        NewsId $id,
        NewsDate $date,
        string $title,
        string $announce,
        string $content,
        string $image
    ) {
        $this->validateTitle($title);
        $this->validateAnnounce($announce);
        $this->validateContent($content);
        $this->validateImage($image);

        $this->id = $id;
        $this->date = $date;
        $this->title = $title;
        $this->announce = $announce;
        $this->content = $content;
        $this->image = $image;
    }

    public function getId(): NewsId
    {
        return $this->id;
    }

    public function getDate(): NewsDate
    {
        return $this->date;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAnnounce(): string
    {
        return $this->announce;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    private function validateTitle(string $title): void
    {
        if (trim($title) === '') {
            throw new InvalidArgumentException('Title cannot be empty');
        }
    }

    private function validateAnnounce(string $announce): void
    {
        if (trim($announce) === '') {
            throw new InvalidArgumentException('Announce cannot be empty');
        }
    }

    private function validateContent(string $content): void
    {
        if (trim($content) === '') {
            throw new InvalidArgumentException('Content cannot be empty');
        }
    }

    private function validateImage(string $image): void
    {
        if (trim($image) === '') {
            throw new InvalidArgumentException('Image cannot be empty');
        }
    }
}
