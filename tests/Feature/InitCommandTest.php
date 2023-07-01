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
        Storage::disk('stubs')->put('content-01.md', 'some-content');
        Storage::disk('stubs')->put('theme.html', 'theme-html');

        $this->artisan(InitCommand::class)
            ->expectsOutput('Initializing a Typesetter project!')
            ->expectsOutput('Success!')
            ->assertSuccessful();

        Storage::assertExists('config.php');
        self::assertEquals('some-config-content', Storage::get('config.php'));
        Storage::assertExists('config.php');
        self::assertEquals('some-content', Storage::get('content/01.md'));
        Storage::assertExists('config.php');
        self::assertEquals('theme-html', Storage::get('my-theme/theme.html'));
    }
}
