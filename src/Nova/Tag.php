<?php

namespace DrewRoberts\Media\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Laravel\Nova\Resource;

class Tag extends Resource
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
            Text::make('Name'),
            Text::make('Slug')->exceptOnForms(),
            Text::make('Type')->nullable(),
            Number::make('Order Column')->nullable(),

            new Panel('Data Fields', $this->dataFields()),
        ];
    }

    protected function dataFields()
    {
        return [
            ID::make(),
            BelongsTo::make('Created By', 'creator', \App\Nova\User::class)->exceptOnForms(),
            DateTime::make('Created At')->exceptOnForms(),
            BelongsTo::make('Updated By', 'updater', \App\Nova\User::class)->exceptOnForms(),
            DateTime::make('Updated At')->exceptOnForms(),
        ];
    }

    public function cards(Request $request)
    {
        return [];
    }

    public function filters(Request $request)
    {
        return [];
    }

    public function lenses(Request $request)
    {
        return [];
    }

    public function actions(Request $request)
    {
        return [];
    }
}
