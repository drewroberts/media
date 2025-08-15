<?php

namespace DrewRoberts\Media\Models;

use App\Models\User;
use DrewRoberts\Media\Models\Video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Image extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($image) {
            if (Auth::check()) {
                $image->creator_id = Auth::id();
            }
        });

        static::saving(function ($image) {
            if (empty($image->width)) {
                $data = getimagesize($image->getUrlAttribute());
                $image->width = $data[0];
                $image->height = $data[1];
            }
            if (Auth::check()) {
                $image->updater_id = Auth::id();
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updater_id');
    }

    public function getUrlAttribute()
    {
        return 'https://res.cloudinary.com/' . config('media.cloudinary_cloud_name') . '/' . $this->filename;
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
