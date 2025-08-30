<?php

namespace DrewRoberts\Media\Support\YouTube;

use DateTimeImmutable;

class VideoData
{
    public function __construct(
        public string $id,
        public ?string $title = null,
        public ?string $description = null,
        public ?string $channelTitle = null,
        public ?int $durationSeconds = null,
        public ?DateTimeImmutable $publishedAt = null,
        public ?int $viewCount = null,
        public ?int $likeCount = null,
        public ?int $commentCount = null,
        public ?string $privacyStatus = null,
        public ?bool $embeddable = null,
        public ?string $broadcast = null, // none|live|upcoming
        public ?string $thumbnailUrl = null,
    ) {}
}
