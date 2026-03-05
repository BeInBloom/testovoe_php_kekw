<?php

declare(strict_types=1);

namespace App\Application\DTOs;

use InvalidArgumentException;

final readonly class NewsDetailDTO
{
    public int $id;
    public string $date;
    public string $title;
    public string $announce;
    public string $content;
    public string $image;

    public function __construct(
        int $id,
        string $date,
        string $title,
        string $announce,
        string $content,
        string $image
    ) {
        $this->validateId($id);
        $this->validateDate($date);
        $this->validateString($title, 'Title');
        $this->validateString($announce, 'Announce');
        $this->validateString($content, 'Content');
        $this->validateString($image, 'Image');

        $this->id = $id;
        $this->date = $date;
        $this->title = $title;
        $this->announce = $announce;
        $this->content = $content;
        $this->image = $image;
    }

    /**
     * @param array{
     *     id: int,
     *     date: string,
     *     title: string,
     *     announce: string,
     *     content: string,
     *     image: string
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['date'],
            $data['title'],
            $data['announce'],
            $data['content'],
            $data['image']
        );
    }

    /**
     * @return array{
     *     id: int,
     *     date: string,
     *     title: string,
     *     announce: string,
     *     content: string,
     *     image: string
     * }
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'title' => $this->title,
            'announce' => $this->announce,
            'content' => $this->content,
            'image' => $this->image,
        ];
    }

    private function validateId(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('News ID must be positive');
        }
    }

    private function validateDate(string $date): void
    {
        if (trim($date) === '') {
            throw new InvalidArgumentException('Date cannot be empty');
        }
    }

    private function validateString(string $value, string $fieldName): void
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException($fieldName . ' cannot be empty');
        }
    }
}
