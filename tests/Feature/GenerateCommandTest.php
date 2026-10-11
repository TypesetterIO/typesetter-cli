<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GenerateCommandTest extends TestCase
{
    private string $cwd;

    // tests chdir() because the library resolves theme and content against the cwd
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
            ->expectsOutput('Loading config: config.php')
            ->expectsOutput('Starting typesetting')
            ->expectsOutput('Markdown initialized.')
            ->expectsOutput('PDF initialized.')
            ->expectsOutput('Theme added.')
            ->expectsOutput('Cover automatically generated.')
            ->expectsOutput('Table of contents generated.')
            ->expectsOutput('Footer added.')
            ->expectsOutput('Converting chapters.')
            ->expectsOutput('Rendering PDF.')
            ->expectsOutput('Finished rendering.')
            ->expectsOutput('Writing PDF')
            ->expectsOutput('Done!')
            ->assertSuccessful();

        self::assertStringStartsWith('%PDF', (string) Storage::get('my-typeset-book.pdf'));
    }

    public function testGeneratesFromInitFiles(): void
    {
        Storage::fake();

        $this->artisan('init')->assertSuccessful();

        chdir(Storage::path(''));

        $this->artisan('generate')->assertSuccessful();

        self::assertStringStartsWith('%PDF', (string) Storage::get('the-book-title.pdf'));
    }

    public function testCoverHtmlAndTitleSlug(): void
    {
        Storage::fake();
        Storage::put('specific/theme.html', '<style></style>');
        Storage::put('specific/cover.html', '<h1>Cover</h1>');
        Storage::put('specific/content/01.md', '# Chapter');
        Storage::put('config.php', '<?php return ["theme" => __DIR__ . "/specific", "content" => __DIR__ . "/specific/content", "title" => "It\'s a Book: Part 2!"];');

        $this->artisan('generate')
            ->expectsOutput('Cover HTML added.')
            ->assertSuccessful();

        Storage::assertExists('its-a-book-part-2.pdf');
    }

    public function testCoverImage(): void
    {
        Storage::fake();
        Storage::put('specific/theme.html', '<style></style>');
        Storage::put('specific/cover.jpg', (string) file_get_contents(__DIR__ . '/../data/cover.jpg'));
        Storage::put('specific/content/01.md', '# Chapter');
        Storage::put('config.php', '<?php return ["theme" => __DIR__ . "/specific", "content" => __DIR__ . "/specific/content"];');

        $this->artisan('generate')
            ->expectsOutput('Cover image added.')
            ->assertSuccessful();
    }

    public function testSuccessSpecifyingConfigAndOutput(): void
    {
        Storage::fake();
        Storage::put('specific/theme.html', '<style></style>');
        Storage::put('specific/content/01.md', '# Chapter');
        Storage::put('specific/my-config.php', '<?php return ["theme" => __DIR__, "content" => __DIR__ . "/content"];');

        $this->artisan('generate', [
            '--config' => './specific/my-config.php',
            '--output' => './my-output/file.pdf',
        ])
            ->expectsOutput('Welcome to Typesetter!')
            ->expectsOutput('Rendering PDF.')
            ->expectsOutput('Done!')
            ->assertSuccessful();

        self::assertStringStartsWith('%PDF', (string) Storage::get('my-output/file.pdf'));
    }

    public function testFailsIfConfigNotFound(): void
    {
        Storage::fake();

        $this->artisan('generate', [
            '--config' => './my-config.php',
        ])
            ->expectsOutputToContain('Cannot find the config file')
            ->assertFailed();
    }

    public function testFailsIfConfigOptionHasNoValue(): void
    {
        Storage::fake();

        $this->artisan('generate', [
            '--config' => null,
        ])
            ->expectsOutputToContain('The --config option needs a value.')
            ->assertFailed();
    }

    public function testFailsOnInvalidConfig(): void
    {
        Storage::fake();
        Storage::put('config.php', "<?php return ['bogus' => 1];");

        $this->artisan('generate')
            ->expectsOutputToContain('Unknown config key(s): bogus')
            ->assertFailed();
    }
}
