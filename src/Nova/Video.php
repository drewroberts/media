<?php

namespace DrewRoberts\Media\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Laravel\Nova\Resource;

class Video extends Resource
{
    public static $model = \DrewRoberts\Media\Models\Video::class;

    public static $title = 'id';

    public static $search = [
        'id',
    ];

    public static $group = 'Media';

    public function fieldsForIndex(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')->sortable(),
            Text::make('Source')->sortable(),
            Text::make('Title')->sortable(),
        ];
    }

    public function fields(Request $request)
    {
        return [
            ID::make(),
            Text::make('Name'),
            Text::make('Source'),
            Text::make('Title'),
            Text::make('Discription'),
            BelongsTo::make('Image'),

            new Panel('Data Fields', $this->dataFields()),

        ];
    }

    protected function dataFields()
    {
        return [
            ID::make(),
            BelongsTo::make('Created By', 'creator', \App\Nova\User::class)->exceptOnForms(),
            DateTime::make('Created At')->exceptOnForms(),
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
