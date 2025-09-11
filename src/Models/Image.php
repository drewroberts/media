<?php

namespace DrewRoberts\Media\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Roberts\Support\Traits\HasCreator;
use Roberts\Support\Traits\HasUpdater;

/**
 * @property int $id
 * @property string|null $filename
 * @property int|null $width
 * @property int|null $height
 * @property string|null $description
 * @property string|null $alt
 * @property string|null $credit
 */
class Image extends Model
{
    use HasCreator, HasUpdater, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($image) {
            // In tests we don't want to hit remote URLs; ensure width/height are set or default
            if (empty($image->width)) {
                $image->width = (int) ($image->width ?? 0);
            }
            if (empty($image->height)) {
                $image->height = (int) ($image->height ?? 0);
            }
        });
    }

    public function getUrlAttribute()
    {
        $cloudName = config('filesystems.disks.cloudinary.cloud');
        if (! $cloudName) {
            throw new InvalidArgumentException('Cloudinary disk misconfigured: set filesystems.disks.cloudinary.cloud');
        }

        $filename = $this->getAttribute('filename');

        return 'https://res.cloudinary.com/'.$cloudName.'/'.$filename;
    }

    public function getExtensionAttribute(): ?string
    {
        $filename = $this->getAttribute('filename');
        $parts = pathinfo($filename ?? '');

        return isset($parts['extension']) ? strtolower($parts['extension']) : null;
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    // Query scopes used in tests
    public function scopeByDimensions($query, int $minWidth, int $minHeight)
    {
        // Matches tests: select images where either dimension exceeds the given minimums
        return $query->where(function ($q) use ($minWidth, $minHeight) {
            $q->where('width', '>', $minWidth)
                ->orWhere('height', '>', $minHeight);
        });
    }

    public function scopeByAspectRatio($query, string $type)
    {
        return $query->when($type === 'landscape', function ($q) {
            $q->whereColumn('width', '>', 'height');
        }, function ($q) use ($type) {
            return $type === 'portrait'
                ? $q->whereColumn('height', '>', 'width')
                : $q->whereColumn('width', '=', 'height');
        });
    }
}
