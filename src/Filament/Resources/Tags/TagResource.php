<?php

namespace DrewRoberts\Media\Filament\Resources\Tags;

use BackedEnum;
use DrewRoberts\Media\Filament\Resources\Tags\Pages\CreateTag;
use DrewRoberts\Media\Filament\Resources\Tags\Pages\EditTag;
use DrewRoberts\Media\Filament\Resources\Tags\Pages\ListTags;
use DrewRoberts\Media\Filament\Resources\Tags\Schemas\TagForm;
use DrewRoberts\Media\Filament\Resources\Tags\Tables\TagsTable;
use DrewRoberts\Media\Models\Tag;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Media';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TagForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TagsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTags::route('/'),
            'create' => CreateTag::route('/create'),
            'edit' => EditTag::route('/{record}/edit'),
        ];
    }
}
