<?php

namespace DrewRoberts\Media\Filament\Resources\Videos\Pages;

use DrewRoberts\Media\Filament\Resources\Videos\VideoResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListVideos extends ListRecords
{
    protected static string $resource = VideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
