<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Exceptions\ConfigException;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GenerateCommandTest extends TestCase
{
    private string $cwd;

    // testSuccessDefaultValues chdir()s to exercise the "." defaults
    protected function setUp(): void
    {
        parent::setUp();

        $this->cwd = (string) getcwd();
    }

    protected function tearDown(): void
    {
        chdir($this->cwd);

        parent::tearDown();
    }

    public function testSuccessDefaultValues(): void
    {
        Storage::fake();
        Storage::put('config.php', '<?php return [];');

        chdir(__DIR__ . '/../data/default');

        $this->artisan('generate')
            ->expectsOutput('Welcome to Typesetter!')
            ->expectsOutput('Rendering PDF.')
            ->expectsOutput('Done!')
            ->assertSuccessful();

        Storage::assertExists('my-typeset-book.pdf');
    }

    public function testSuccessSpecifyingConfigAndOutput(): void
    {
        Storage::fake();
        Storage::put('specific/theme.html', '<style></style>');
        Storage::put('specific/content/01.md', '# Chapter');
        $theme = Storage::path('specific');
        $content = Storage::path('specific/content');
        Storage::put('specific/my-config.php', '<?php return ["theme" => "' . $theme . '", "content" => "' . $content . '"];');

        $this->artisan('generate', [
            '--config' => './specific/my-config.php',
            '--output' => './my-output/file.pdf',
        ])
            ->expectsOutput('Welcome to Typesetter!')
            ->expectsOutput('Rendering PDF.')
            ->expectsOutput('Done!')
            ->assertSuccessful();

        Storage::assertExists('my-output/file.pdf');
    }

    public function testThrowsExceptionIfConfigNotFound(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Cannot find the config file');

        Storage::fake();

        $this->artisan('generate', [
            '--config' => './my-config.php',
        ])
            ->assertFailed();
    }
}
