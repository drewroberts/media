<?php

namespace DrewRoberts\Media\Filament\Resources\Images\Pages;

use DrewRoberts\Media\Filament\Resources\Images\ImageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditImage extends EditRecord
{
    protected static string $resource = ImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
