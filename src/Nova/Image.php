<?php

namespace DrewRoberts\Media\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
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
            Text::make('Filename')->sortable(),
            Text::make('Width')->sortable(),
            Text::make('Height')->sortable(),
        ];
    }

    public function fields(Request $request)
    {
        return [
            CloudinaryImage::make('Image', 'filename')
                ->storeAs(function (Request $request) {
                    return 'img-'.sha1(time());
                }),
            Text::make('Width')->sortable(), // Want to auto generate from upload
            Text::make('Height')->sortable(), // Want to auto generate from upload
            Text::make('Description')->sortable(),
            Text::make('Alt')->sortable(),
            Text::make('Credit')->sortable(),

            HasMany::make('Videos'),

            new Panel('Data Fields', $this->dataFields()),

        ];
    }

    protected function dataFields()
    {
        return [
            ID::make(),
            BelongsTo::make('Created By', 'creator', 'App\Nova\User')->hideWhenCreating()->hideWhenUpdating(),
            DateTime::make('Created At')->hideWhenCreating()->hideWhenUpdating(),
            DateTime::make('Updated At')->hideWhenCreating()->hideWhenUpdating(),
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
