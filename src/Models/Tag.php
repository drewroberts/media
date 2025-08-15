<?php

namespace DrewRoberts\Media\Models;

use App\Models\User;
use DrewRoberts\Media\Models\Video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as DbCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Tag extends Model implements Sortable
{
    use SortableTrait, HasFactory;

    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (Auth::check()) {
                $tag->creator_id = Auth::id();
            }
        });

        static::saving(static function ($tag) {
            $sanitizedName = Str::keepAlphanumericCharacters($tag->name);

            $tag->slug = strtolower($sanitizedName);

            if (Auth::check()) {
                $tag->updater_id = Auth::id();
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

    public function setNameAttribute($value)
    {
        $sanitizedName = Str::keepAlphanumericCharactersAndSpaces($value);

        $this->attributes['name'] = '#' . Str::studly($sanitizedName);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getPathAttribute()
    {
        return "/tags/{$this->slug}";
    }

    public function scopeWithType(Builder $query, $type = null): Builder
    {
        if (is_null($type)) {
            return $query;
        }

        return $query->where('type', $type)->ordered();
    }

    public static function getWithType(string $type): DbCollection
    {
        return static::withType($type)->ordered()->get();
    }

    public static function findFromString(string $name, $type = null)
    {
        return static::query()
            ->where('name', $name)
            ->where('type', $type)
            ->first();
    }

    protected static function findOrCreateFromString(string $name, $type = null)
    {
        $tag = static::findFromString($name, $type);

        if (! $tag) {
            $tag = static::create([
                'name' => $name,
                'type' => $type,
            ]);
        }

        return $tag;
    }

    public static function getTypes(): Collection
    {
        return static::select('type')
            ->distinct()
            ->get()
            ->pluck('type');
    }
}
