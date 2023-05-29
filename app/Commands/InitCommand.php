<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class InitCommand extends Command
{
    protected $signature = 'init';

    protected $description = 'Initialize a Typesetter cli project in the current working directory.';

    public function handle(): int
    {
        $this->info('Initializing a Typesetter project!');
        $this->newLine();

        $configFilePath = Storage::path('config.php');
        $this->comment('Generating ' . $configFilePath);
        Storage::put('config.php', Storage::disk('stubs')->get('config.php'));

        $contentPath = Storage::path('content/01.md');
        $this->comment('Generating ' . $contentPath);
        Storage::put('content/01.md', Storage::disk('stubs')->get('content-01.md'));

        $themePath = Storage::path('my-theme/theme.html');
        $this->comment('Generating ' . $themePath);
        Storage::put('my-theme/theme.html', Storage::disk('stubs')->get('theme.html'));

        $this->info('Success!');

        return self::SUCCESS;
    }
}
