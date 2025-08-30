<?php

namespace DrewRoberts\Media\Filament\Resources\Images\Pages;

use Cloudinary\Api\Upload\UploadApi;
use DrewRoberts\Media\Filament\Resources\Images\ImageResource;
use Exception;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CreateImage extends CreateRecord
{
    protected static string $resource = ImageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Expect dehydrated local file path from FileUpload (stored on local disk)
        $path = $data['upload'] ?? null;
        if (is_string($path)) {
            // Convert relative disk path to absolute file system path
            $path = Storage::disk('local')->path($path);
        }

        if (! $path || ! file_exists($path)) {
            Notification::make()->title('No image selected')->danger()->send();
            throw ValidationException::withMessages(['upload' => 'Please select an image to upload.']);
        }

        if (! config('filesystems.disks.cloudinary.cloud')) {
            Notification::make()->title('Cloudinary not configured')->body('Cloud name is missing')->danger()->send();
            throw ValidationException::withMessages(['upload' => 'Cloudinary is not configured (cloud name missing).']);
        }
        if (! config('filesystems.disks.cloudinary.url')) {
            Notification::make()->title('Cloudinary not configured')->body('CLOUDINARY_URL is missing')->danger()->send();
            throw ValidationException::withMessages(['upload' => 'Cloudinary is not configured (CLOUDINARY_URL missing).']);
        }

        // Fallback dimension detection
        $localWidth = null;
        $localHeight = null;
        try {
            [$w,$h] = @getimagesize($path) ?: [null, null];
            $localWidth = $w;
            $localHeight = $h;
        } catch (Exception $e) {
        }

        if (! is_file($path) || ! is_readable($path)) {
            Log::warning('Upload file not readable', ['path' => $path]);
            Notification::make()->title('Upload file not readable')->danger()->send();
            throw ValidationException::withMessages(['upload' => 'The uploaded file is not readable on the server.']);
        }

        $publicId = 'img-'.sha1((string) microtime(true));
        try {
            // Use Cloudinary Upload API directly
            $result = (new UploadApi())->upload($path, [
                'public_id' => $publicId,
                'overwrite' => true,
                'resource_type' => 'image',
            ]);
            /** @var array<string,mixed> $resultArr */
            $resultArr = $result->getArrayCopy();
        } catch (\Throwable $e) {
            Log::error('Cloudinary upload failed', [
                'message' => $e->getMessage(),
                'path' => $path,
            ]);
            Notification::make()
                ->title('Image upload failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
            throw ValidationException::withMessages(['upload' => 'Upload to Cloudinary failed: '.$e->getMessage()]);
        }

    // Extract details from result array, with local fallbacks
    $format = $resultArr['format'] ?? null;
    $width = $resultArr['width'] ?? $localWidth;
    $height = $resultArr['height'] ?? $localHeight;
        $format = $format ?: (pathinfo($path, PATHINFO_EXTENSION) ?: 'jpg');

        // Store clean Root Path filename: "{publicId}.{ext}" (no asset_type/delivery_type)
        $data['filename'] = $publicId.'.'.$format;
        $data['width'] = $width;
        $data['height'] = $height;

        return $data;
    }
}
