<?php

namespace DrewRoberts\Media\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

class MediaPlugin implements Plugin
{
    public static function make(): self
    {
        return new self;
    }

    public function getId(): string
    {
        return 'drewroberts-media';
    }

    public function register(Panel $panel): void
    {
        $panel->discoverResources(
            in: __DIR__.'/Resources',
            for: 'DrewRoberts\\Media\\Filament\\Resources'
        );
    }

    public function boot(Panel $panel): void
    {
        // no-op
    }
}
