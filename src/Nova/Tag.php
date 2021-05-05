<?php

namespace DrewRoberts\Media\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Tipoff\Support\Nova\BaseResource;

class Tag extends BaseResource
{
    public static $model = \DrewRoberts\Media\Models\Tag::class;

    public static $title = 'name';

    public static $search = [
        'id',
        'name',
        'slug',
        'type',
    ];

    public static $group = 'Website Content';

    public function fieldsForIndex(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')->sortable(),
            Text::make('Slug')->sortable(),
            Text::make('Type')->sortable(),
            Number::make('Order Column')->sortable(),
        ];
    }

    public function fields(Request $request)
    {
        return [
            Text::make('Name')->rules('required'),
            Text::make('Slug')->exceptOnForms()->rules('required'),
            Text::make('Type')->nullable(),
            Number::make('Order Column')->nullable(),

            new Panel('Data Fields', $this->dataFields()),
        ];
    }

    protected function dataFields(): array
    {
        return array_merge(
            parent::dataFields(),
            $this->creatorDataFields(),
            $this->updaterDataFields(),
        );
    }
}
