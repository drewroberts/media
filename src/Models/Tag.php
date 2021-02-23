<?php

namespace DrewRoberts\Media\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as DbCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Tipoff\Support\Models\BaseModel;
use Tipoff\Support\Traits\HasCreator;
use Tipoff\Support\Traits\HasPackageFactory;
use Tipoff\Support\Traits\HasUpdater;

class Tag extends BaseModel implements Sortable
{
    use SortableTrait, HasCreator, HasUpdater, HasPackageFactory;

    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::saving(static function ($tag) {
            $sanitizedName = Str::keepAlphanumericCharacters($tag->name);
            $splitUpName = Str::splitAtCapitalLetters($sanitizedName);

            $tag->slug = Str::slug($splitUpName);
            $tag->name = '#' . Str::studly($splitUpName);
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getPathAttribute()
    {
        return "/tags/{$this->slug}";
    }

    public function scopeWithType(Builder $query, string $type = null): Builder
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

    public static function findFromString(string $name, string $type = null)
    {
        return static::query()
            ->where('name', $name)
            ->where('type', $type)
            ->first();
    }

    protected static function findOrCreateFromString(string $name, string $type = null)
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
