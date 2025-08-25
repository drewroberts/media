<?php

namespace DrewRoberts\Media\Models;

use DrewRoberts\Media\Support\Sanitizer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as DbCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 *
 * @method static Builder<Tag> ordered()
 */
class Tag extends Model implements Sortable
{
    use HasFactory, SortableTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'order_column' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (Auth::check()) {
                $tag->creator_id = Auth::id();
            }
        });

        static::saving(static function ($tag) {
            $sanitizedName = Sanitizer::keepAlphanumericCharacters($tag->name);

            $tag->slug = strtolower($sanitizedName);

            if (Auth::check()) {
                $tag->updater_id = Auth::id();
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

    public function setNameAttribute($value)
    {
        $sanitizedName = Sanitizer::keepAlphanumericCharactersAndSpaces($value);

        $this->attributes['name'] = '#'.Str::studly($sanitizedName);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getPathAttribute()
    {
        $slug = (string) $this->getAttribute('slug');

        return "/tags/{$slug}";
    }

    /**
     * @param  Builder<Tag>  $query
     * @return Builder<Tag>
     */
    public function scopeWithType(Builder $query, $type = null): Builder
    {
        if (is_null($type)) {
            return $query;
        }

        return $query->where('type', $type)->ordered();
    }

    /**
     * @return DbCollection<int, Tag>
     */
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

    public static function findOrCreateFromString(string $name, $type = null)
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
