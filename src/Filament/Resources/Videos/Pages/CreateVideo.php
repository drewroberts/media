<?php

namespace DrewRoberts\Media\Filament\Resources\Videos\Pages;

use DrewRoberts\Media\Facades\YouTube;
use DrewRoberts\Media\Filament\Resources\Videos\VideoResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateVideo extends CreateRecord
{
    protected static string $resource = VideoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
    $raw = $this->form->getState();
    $url = $raw['youtube_url'] ?? request()->input('youtube_url');
        unset($data['youtube_url']);

        if (! $url) {
            throw ValidationException::withMessages([
                'youtube_url' => 'Please provide a YouTube URL or ID.',
            ]);
        }

        $id = YouTube::parseId((string) $url);
        if (! $id) {
            throw ValidationException::withMessages([
                'youtube_url' => 'Invalid YouTube URL or ID.',
            ]);
        }
        $data['identifier'] = $id;

        $data['source'] = $data['source'] ?? 'youtube';

        if (empty($data['title']) || empty($data['published_at'])) {
            try {
                $video = YouTube::fetch($data['identifier']);
                $data['title'] = ($data['title'] ?? null) ?: $video->title;
                $data['description'] = $data['description'] ?? $video->description;
                $data['duration'] = ($data['duration'] ?? null) ?? $video->durationSeconds;
                $data['published_at'] = ($data['published_at'] ?? null) ?? $video->publishedAt?->format('Y-m-d H:i:s');
                $data['view_count'] = ($data['view_count'] ?? null) ?? $video->viewCount;
                $data['like_count'] = ($data['like_count'] ?? null) ?? $video->likeCount;
                $data['comment_count'] = ($data['comment_count'] ?? null) ?? $video->commentCount;
                $data['privacy'] = ($data['privacy'] ?? null) ?? $video->privacyStatus;
                $data['embeddable'] = array_key_exists('embeddable', $data) ? $data['embeddable'] : ($video->embeddable ?? true);
                $data['broadcast'] = ($data['broadcast'] ?? null) ?? $video->broadcast;

                if (empty($data['credit']) && ! empty($video->channelTitle)) {
                    $data['credit'] = $video->channelTitle;
                }

                if (empty($data['image_id'])) {
                    $image = YouTube::ensureThumbnailImage($video);
                    if ($image) {
                        $data['image_id'] = $image->id;
                    }
                }
            } catch (\Throwable $e) {
                Notification::make()->title('Video fetch failed')->body($e->getMessage())->danger()->send();
            }
        }

        if (empty($data['name']) && ! empty(($data['title'] ?? null))) {
            $data['name'] = $data['title'];
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Video created')
            ->success();
    }
}
