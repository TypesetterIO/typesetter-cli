<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Commands\InitCommand;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InitCommandTest extends TestCase
{
    public function testSuccess(): void
    {
        Storage::fake('local');

        Storage::fake('stubs');
        Storage::disk('stubs')->put('config.php', 'some-config-content');
        Storage::disk('stubs')->put('content-01-welcome.md', 'some-welcome-content');
        Storage::disk('stubs')->put('content-02-formatting.md', 'some-formatting-content');
        Storage::disk('stubs')->put('theme.html', 'theme-html');

        $this->artisan(InitCommand::class)
            ->expectsOutput('Initializing a Typesetter project!')
            ->expectsOutput('Success!')
            ->expectsOutput('Run the generate command to build the PDF.')
            ->expectsOutput('Documentation: https://typesetter.io')
            ->assertSuccessful();

        Storage::assertExists('config.php');
        self::assertEquals('some-config-content', Storage::get('config.php'));
        Storage::assertExists('content/01-welcome.md');
        self::assertEquals('some-welcome-content', Storage::get('content/01-welcome.md'));
        Storage::assertExists('content/02-formatting.md');
        self::assertEquals('some-formatting-content', Storage::get('content/02-formatting.md'));
        Storage::assertExists('my-theme/theme.html');
        self::assertEquals('theme-html', Storage::get('my-theme/theme.html'));
    }

    public function testDoesNotOverwriteExistingConfig(): void
    {
        Storage::fake('local');
        Storage::put('config.php', 'original');

        $this->artisan(InitCommand::class)
            ->expectsOutput('config.php already exists, not overwriting.')
            ->assertFailed();

        self::assertEquals('original', Storage::get('config.php'));
        Storage::assertMissing('content/01-welcome.md');
    }
}
