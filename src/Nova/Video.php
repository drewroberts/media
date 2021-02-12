<?php

namespace DrewRoberts\Media\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
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
            Text::make('Identifier')->required()->help(
                'The ID from YouTube or Vimeo'
            ),
            Text::make('Name')->required(),

            new Panel('Info Fields', $this->infoFields()),
            new Panel('Data Fields', $this->dataFields()),

        ];
    }

    protected function infoFields()
    {
        return [
            Select::make('Source')->options([
                'youtube' => 'YouTube',
                'vimeo' => 'Vimeo',
                'other' => 'Other',
            ])->withMeta(['value' => $this->source ?? 'youtube'])->required()->displayUsingLabels(),
            Text::make('Title')->nullable(),
            Text::make('Description')->nullable(),
            BelongsTo::make('Image')->nullable()->showCreateRelationButton(),
        ];
    }

    protected function dataFields()
    {
        return [
            ID::make(),
            BelongsTo::make('Created By', 'creator', app('nova.user'))->exceptOnForms(),
            DateTime::make('Created At')->exceptOnForms(),
            BelongsTo::make('Updated By', 'updater', app('nova.user'))->exceptOnForms(),
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
