<?php

namespace DrewRoberts\Media\Filament\Resources\Tags\Pages;

use DrewRoberts\Media\Filament\Resources\Tags\TagResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Roberts\LaravelSingledbTenancy\Services\SuperAdmin;

class EditTag extends EditRecord
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (): bool => app(SuperAdmin::class)->is(Auth::user())),
        ];
    }
}
