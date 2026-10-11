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
        if (Storage::exists('config.php')) {
            $this->error('config.php already exists, not overwriting.');

            return self::FAILURE;
        }

        $this->info('Initializing a Typesetter project!');
        $this->newLine();

        $configFilePath = Storage::path('config.php');
        $this->comment('Generating ' . $configFilePath);
        // @phpstan-ignore argument.type (the stub ships with the application)
        Storage::put('config.php', Storage::disk('stubs')->get('config.php'));

        $welcomePath = Storage::path('content/01-welcome.md');
        $this->comment('Generating ' . $welcomePath);
        // @phpstan-ignore argument.type (the stub ships with the application)
        Storage::put('content/01-welcome.md', Storage::disk('stubs')->get('content-01-welcome.md'));

        $formattingPath = Storage::path('content/02-formatting.md');
        $this->comment('Generating ' . $formattingPath);
        // @phpstan-ignore argument.type (the stub ships with the application)
        Storage::put('content/02-formatting.md', Storage::disk('stubs')->get('content-02-formatting.md'));

        $themePath = Storage::path('my-theme/theme.html');
        $this->comment('Generating ' . $themePath);
        // @phpstan-ignore argument.type (the stub ships with the application)
        Storage::put('my-theme/theme.html', Storage::disk('stubs')->get('theme.html'));

        $this->info('Success!');
        $this->newLine();
        $this->line('Run the generate command to build the PDF.');
        $this->line('Documentation: https://typesetter.io');

        return self::SUCCESS;
    }
}
