<?php

namespace DrewRoberts\Media\Filament\Resources\Images\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class ImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('upload')
                    ->label('Image')
                    ->image()
                    ->imageEditor()
                    ->required()
                    ->dehydrated(true)
                    ->disk('local')
                    ->directory('tmp/uploads')
                    ->visibility('private')
                    ->visibleOn('create')
                    ->afterStateUpdated(function ($state, $set) {
                        $path = null;
                        if (is_object($state) && method_exists($state, 'getRealPath')) {
                            $path = $state->getRealPath();
                        } elseif (is_string($state)) {
                            // When dehydrate(true)+disk('local'), state is a path relative to the disk root
                            $path = Storage::disk('local')->path($state);
                        }
                        if ($path && file_exists($path)) {
                            [$w, $h] = @getimagesize($path) ?: [null, null];
                            if ($w) {
                                $set('width', $w);
                            }
                            if ($h) {
                                $set('height', $h);
                            }
                        }
                    }),
                TextInput::make('description')
                    ->columnSpanFull(),
                TextInput::make('alt'),
                TextInput::make('credit'),
                Placeholder::make('preview')
                    ->label('Current Image')
                    ->visibleOn('edit')
                    ->columnSpanFull()
                    ->content(function ($record) {
                        if (! $record || ! $record->url) {
                            return new HtmlString('<div style="color:#6b7280;">No image available</div>');
                        }
                        $alt = e($record->alt ?: ($record->description ?: 'Image'));
                        $html = sprintf(
                            '<img src="%s" alt="%s" style="max-width:100%%;height:auto;display:block;border-radius:0.25rem;" />',
                            e($record->url),
                            $alt
                        );

                        return new HtmlString($html);
                    }),
                Placeholder::make('dimensions')
                    ->label('Original Uploaded File Dimensions')
                    ->visibleOn('edit')
                    ->columnSpanFull()
                    ->content(function ($record) {
                        if (! $record) {
                            return new HtmlString('');
                        }
                        $w = (int) ($record->width ?? 0);
                        $h = (int) ($record->height ?? 0);
                        $html = '<div style="display:flex;gap:1rem;align-items:center;">'
                              .'<div><span style="color:#6b7280;">Width:</span> '.e((string) $w).'</div>'
                              .'<div><span style="color:#6b7280;">Height:</span> '.e((string) $h).'</div>'
                              .'</div>';

                        return new HtmlString($html);
                    }),
            ]);
    }
}
