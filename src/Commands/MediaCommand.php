<?php

namespace Drewroberts\Media\Commands;

use Illuminate\Console\Command;

class MediaCommand extends Command
{
    public $signature = 'media';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
