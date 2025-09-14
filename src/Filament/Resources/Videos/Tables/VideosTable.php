<?php

namespace DrewRoberts\Media\Filament\Resources\Videos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Roberts\LaravelSingledbTenancy\Services\SuperAdmin;

class VideosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->getStateUsing(fn ($record) => $record->image?->url)
                    ->imageHeight('64px'),
                TextColumn::make('identifier')->label('YouTube ID')->sortable(),
                TextColumn::make('duration')->label('Duration (s)')->sortable(),
                IconColumn::make('embeddable')->label('Embeddable')->boolean(),
                TextColumn::make('published_at')->label('Published At')->dateTime()->sortable(),
            ])
            ->filters([])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                DeleteAction::make()
                    ->visible(fn (): bool => app(SuperAdmin::class)->is(Auth::user())),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn (): bool => app(SuperAdmin::class)->is(Auth::user())),
                ]),
            ]);
    }
}
