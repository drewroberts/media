<?php

namespace DrewRoberts\Media\Filament\Resources\Images\Pages;

use DrewRoberts\Media\Filament\Resources\Images\ImageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Roberts\LaravelSingledbTenancy\Services\SuperAdmin;

class EditImage extends EditRecord
{
    protected static string $resource = ImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (): bool => app(SuperAdmin::class)->is(Auth::user())),
        ];
    }
}
