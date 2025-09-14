<?php

namespace DrewRoberts\Media\Filament\Resources\Tags\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Roberts\LaravelSingledbTenancy\Services\SuperAdmin;

class TagsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->badge()
                    ->copyable()
                    ->copyMessage('Slug copied'),
                TextColumn::make('type')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('order_column')
                    ->label('Order')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
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
