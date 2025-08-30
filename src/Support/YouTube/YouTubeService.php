<?php

namespace DrewRoberts\Media\Support\YouTube;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use DrewRoberts\Media\Models\Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YouTubeService
{
    public function __construct(
        protected ?string $apiKey = null,
    ) {
        $this->apiKey = $this->apiKey ?: (string) config('media.youtube.api_key');
    }

    public function ensureConfigured(): void
    {
        if (! $this->apiKey) {
            throw new \InvalidArgumentException('YOUTUBE_API_KEY is not configured.');
        }
    }

    public function parseId(string $input): ?string
    {
        return YouTubeIdParser::parse($input);
    }

    public function fetch(string $videoId): VideoData
    {
        $this->ensureConfigured();

        $base = (string) config('media.youtube.base_url');
        $timeout = (float) config('media.youtube.timeout', 8.0);

        $resp = Http::timeout($timeout)
            ->get($base.'/videos', [
                'part' => 'snippet,contentDetails,statistics,liveStreamingDetails,status',
                'id' => $videoId,
                'key' => $this->apiKey,
            ]);

        if (! $resp->ok()) {
            throw new \RuntimeException('YouTube API error: HTTP '.$resp->status().' '.$resp->body());
        }

        $data = $resp->json();
        $item = Arr::get($data, 'items.0');
        if (! $item) {
            throw new \RuntimeException('YouTube video not found or inaccessible.');
        }

        return $this->normalize($item);
    }

    public function normalize(array $item): VideoData
    {
        $id = (string) Arr::get($item, 'id');
        $snippet = (array) Arr::get($item, 'snippet', []);
        $content = (array) Arr::get($item, 'contentDetails', []);
        $stats = (array) Arr::get($item, 'statistics', []);
        $status = (array) Arr::get($item, 'status', []);
        $live = (array) Arr::get($item, 'liveStreamingDetails', []);

        $publishedAt = Arr::get($snippet, 'publishedAt');
        $publishedObj = $publishedAt ? new \DateTimeImmutable($publishedAt) : null;

        $durationSec = Duration::iso8601ToSeconds(Arr::get($content, 'duration'));

        // broadcast: upcoming/live/none
        $broadcast = 'none';
        if (! empty($live['scheduledStartTime'])) {
            $broadcast = 'upcoming';
        }
        if (! empty($live['actualStartTime'])) {
            $broadcast = 'live';
        }

        $thumbUrl = $this->bestThumbnailUrl(Arr::get($snippet, 'thumbnails', []));

        return new VideoData(
            id: $id,
            title: Arr::get($snippet, 'title'),
            description: Arr::get($snippet, 'description'),
            channelTitle: Arr::get($snippet, 'channelTitle'),
            durationSeconds: $durationSec,
            publishedAt: $publishedObj,
            viewCount: isset($stats['viewCount']) ? (int) $stats['viewCount'] : null,
            likeCount: isset($stats['likeCount']) ? (int) $stats['likeCount'] : null,
            commentCount: isset($stats['commentCount']) ? (int) $stats['commentCount'] : null,
            privacyStatus: Arr::get($status, 'privacyStatus'),
            embeddable: Arr::get($status, 'embeddable'),
            broadcast: $broadcast,
            thumbnailUrl: $thumbUrl,
        );
    }

    public function bestThumbnailUrl(array $thumbnails): ?string
    {
        $order = (array) config('media.youtube.thumbnail_preference', []);
        foreach ($order as $key) {
            if (! empty($thumbnails[$key]['url'])) {
                return (string) $thumbnails[$key]['url'];
            }
        }
        foreach ($thumbnails as $entry) {
            if (! empty($entry['url'])) {
                return (string) $entry['url'];
            }
        }
        return null;
    }

    /**
     * Download the YouTube thumbnail and upload to Cloudinary, returning Image model.
     * If upload fails or thumbnail missing, returns null.
     */
    public function ensureThumbnailImage(VideoData $data): ?Image
    {
        if (! $data->thumbnailUrl) return null;

        try {
            $tmp = tempnam(sys_get_temp_dir(), 'ytthumb_');
            $img = Http::timeout(6.0)->get($data->thumbnailUrl);
            if (! $img->ok()) return null;
            file_put_contents($tmp, $img->body());

            $publicId = 'yt-'.$data->id;
            $result = Cloudinary::uploadApi()->upload($tmp, [
                'public_id' => $publicId,
                'overwrite' => true,
                'resource_type' => 'image',
            ]);

            @unlink($tmp);

            $format = is_array($result) ? ($result['format'] ?? 'jpg') : 'jpg';
            $width = is_array($result) ? ($result['width'] ?? null) : null;
            $height = is_array($result) ? ($result['height'] ?? null) : null;

            return Image::create([
                'filename' => $publicId.'.'.$format,
                'width' => $width,
                'height' => $height,
                'description' => $data->title,
                'alt' => $data->title,
                'credit' => $data->channelTitle,
            ]);
        } catch (\Throwable $e) {
            Log::warning('YouTube thumbnail upload failed', [
                'videoId' => $data->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
