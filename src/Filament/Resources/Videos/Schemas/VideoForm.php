<?php

namespace DrewRoberts\Media\Filament\Resources\Videos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class VideoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('youtube_url')
                ->label('YouTube URL or ID')
                ->dehydrated(false)
                ->helperText('Paste a YouTube URL or ID. Details will be fetched when you click Create.')
                ->required()
                ->visibleOn('create'),

            TextInput::make('name')->label('Internal Name'),
            TextInput::make('credit')->visibleOn('edit'),
            Select::make('image_id')
                ->label('Thumbnail Image')
                ->relationship(name: 'image', titleAttribute: 'filename')
                ->searchable()
                ->preload()
                ->visibleOn('edit'),
            TextInput::make('identifier')
                ->label('Video ID')
                ->maxLength(255)
                ->visibleOn('edit')
                ->disabled()
                ->dehydrated(false),

            TextInput::make('youtube_link')
                ->label('YouTube Link')
                ->disabled()
                ->dehydrated(false)
                ->helperText(fn ($record) => $record && method_exists($record, 'youtubeUrl') && $record->youtubeUrl()
                    ? new HtmlString(sprintf('<a href="%s" target="_blank" class="text-primary-600 underline">Open in YouTube</a>', $record->youtubeUrl()))
                    : null)
                ->formatStateUsing(function ($state, $record) {
                    $url = $record && method_exists($record, 'youtubeUrl') ? $record->youtubeUrl() : null;

                    return $url ?: '-';
                }),

            TextInput::make('duration')
                ->numeric()
                ->minValue(0)
                ->suffix('sec')
                ->visibleOn('edit')
                ->disabled()
                ->dehydrated(false),
            DateTimePicker::make('published_at')
                ->visibleOn('edit')
                ->disabled()
                ->dehydrated(false),

            TextInput::make('title')
                ->nullable()
                ->visibleOn('edit')
                ->disabled()
                ->dehydrated(false),
            Textarea::make('description')
                ->rows(6)
                ->visibleOn('edit'),
            Toggle::make('embeddable')
                ->default(true)
                ->visibleOn('edit')
                ->disabled()
                ->dehydrated(false),

            Select::make('broadcast')->options([
                'none' => 'None',
                'live' => 'Live',
                'upcoming' => 'Upcoming',
            ])->visibleOn('edit')->disabled()->dehydrated(false),
            Select::make('privacy')->options([
                'public' => 'Public',
                'unlisted' => 'Unlisted',
                'private' => 'Private',
            ])->visibleOn('edit')->disabled()->dehydrated(false),
            TextInput::make('location')->visibleOn('edit')->disabled()->dehydrated(false),

            TextInput::make('view_count')->numeric()->minValue(0)->visibleOn('edit')->disabled()->dehydrated(false),
            TextInput::make('like_count')->numeric()->minValue(0)->visibleOn('edit')->disabled()->dehydrated(false),
            TextInput::make('comment_count')->numeric()->minValue(0)->visibleOn('edit')->disabled()->dehydrated(false),

            TextInput::make('source')
                ->default('youtube')
                ->maxLength(50)
                ->visibleOn('edit')
                ->disabled()
                ->dehydrated(false),

            DateTimePicker::make('stream_scheduled_at')->visibleOn('edit')->disabled()->dehydrated(false),
            DateTimePicker::make('stream_started_at')->visibleOn('edit')->disabled()->dehydrated(false),
        ]);
    }
}
