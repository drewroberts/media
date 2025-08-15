<?php

namespace DrewRoberts\Media\Commands;

use Illuminate\Console\Command;

class MediaCommand extends Command
{
    public $signature = 'media';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
