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
use Silvanite\NovaFieldCloudinary\Fields\CloudinaryImage;

class Image extends Resource
{
    public static $model = \DrewRoberts\Media\Models\Image::class;

    public static $title = 'id';

    public static $search = [
        'id',
    ];

    public static $group = 'Media';

    public function fieldsForIndex(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            CloudinaryImage::make('Image', 'filename'),
            Text::make('Filename')->sortable(),
            Text::make('Width')->sortable(),
            Text::make('Height')->sortable(),
        ];
    }

    public function fields(Request $request)
    {
        return [
            CloudinaryImage::make('Image', 'filename')
                ->storeAs(function () {
                    return 'img-' . sha1((string)time());
                })->hideWhenUpdating(),
            Text::make('Width')->exceptOnForms(),
            Text::make('Height')->exceptOnForms(),

            new Panel('Info Fields', $this->infoFields()),
            new Panel('Data Fields', $this->dataFields()),
        ];
    }

    protected function infoFields()
    {
        return [
            Text::make('Description')->nullable(),
            Text::make('Alt')->nullable(),
            Text::make('Credit')->nullable(),
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
