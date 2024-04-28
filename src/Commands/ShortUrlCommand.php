<?php

namespace StevenFox\Larashurl\Commands;

use Illuminate\Console\Command;

class ShortUrlCommand extends Command
{
    public $signature = 'laravel-short-urls';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
