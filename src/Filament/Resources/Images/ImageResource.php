<?php

namespace DrewRoberts\Media\Filament\Resources\Images;

use BackedEnum;
use DrewRoberts\Media\Filament\Resources\Images\Pages\CreateImage;
use DrewRoberts\Media\Filament\Resources\Images\Pages\EditImage;
use DrewRoberts\Media\Filament\Resources\Images\Pages\ListImages;
use DrewRoberts\Media\Filament\Resources\Images\Schemas\ImageForm;
use DrewRoberts\Media\Filament\Resources\Images\Tables\ImagesTable;
use DrewRoberts\Media\Models\Image;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ImageResource extends Resource
{
    protected static ?string $model = Image::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Media';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'filename';

    public static function form(Schema $schema): Schema
    {
        return ImageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ImagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListImages::route('/'),
            'create' => CreateImage::route('/create'),
            'edit' => EditImage::route('/{record}/edit'),
        ];
    }
}
