<?php

namespace DrewRoberts\Media\Filament\Resources\Tags\Pages;

use DrewRoberts\Media\Filament\Resources\Tags\TagResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
