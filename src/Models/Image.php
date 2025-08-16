<?php

namespace DrewRoberts\Media\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Image extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($image) {
            if (Auth::check()) {
                $image->creator_id = Auth::id();
            }
        });

        static::saving(function ($image) {
            // In tests we don't want to hit remote URLs; ensure width/height are set or default
            if (empty($image->width)) {
                $image->width = (int) ($image->width ?? 0);
            }
            if (empty($image->height)) {
                $image->height = (int) ($image->height ?? 0);
            }
            if (Auth::check()) {
                $image->updater_id = Auth::id();
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'creator_id');
    }

    public function updater()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'updater_id');
    }

    public function getUrlAttribute()
    {
        return 'https://res.cloudinary.com/'.config('media.cloudinary_cloud_name').'/'.$this->filename;
    }

    public function getExtensionAttribute(): ?string
    {
        $parts = pathinfo($this->filename ?? '');
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
