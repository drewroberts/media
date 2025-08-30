<?php

namespace DrewRoberts\Media\Filament\Resources\Videos;

use BackedEnum;
use DrewRoberts\Media\Filament\Resources\Videos\Pages\CreateVideo;
use DrewRoberts\Media\Filament\Resources\Videos\Pages\EditVideo;
use DrewRoberts\Media\Filament\Resources\Videos\Pages\ListVideos;
use DrewRoberts\Media\Filament\Resources\Videos\Schemas\VideoForm;
use DrewRoberts\Media\Filament\Resources\Videos\Tables\VideosTable;
use DrewRoberts\Media\Models\Video;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Media';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return VideoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VideosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVideos::route('/'),
            'create' => CreateVideo::route('/create'),
            'edit' => EditVideo::route('/{record}/edit'),
        ];
    }
}
