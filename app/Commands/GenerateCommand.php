<?php

declare(strict_types=1);

namespace App\Commands;

use App\Exceptions\ConfigException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;
use Typesetterio\Typesetter\Config;
use Typesetterio\Typesetter\Events;
use Typesetterio\Typesetter\Exceptions\InvalidConfigException;
use Typesetterio\Typesetter\Typesetter;

class GenerateCommand extends Command
{
    protected $signature = 'generate'
        . ' {--config=config.php : The name and path to config file in relation to the current working directory.}'
        . ' {--output= : The name and path to output PDF file in relation to the current working directory. By default slug version of title.}';

    protected $description = 'Generates the book content into a PDF.';

    public function handle(Typesetter $service): int
    {
        $this->info('Welcome to Typesetter!');
        $this->newLine();

        $this->registerListeners($service);

        try {
            $config = $this->getConfig();

            $pdfBinary = $service->generate($config);
        } catch (ConfigException|InvalidConfigException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->newLine();
        $this->line('Writing PDF');
        $this->newLine();
        Storage::put($this->option('output') ?? Str::slug($config->title) . '.pdf', $pdfBinary);

        $this->info('Done!');
        return self::SUCCESS;
    }

    protected function getConfig(): Config
    {
        $configOption = $this->option('config');
        if ($configOption === null) {
            throw new ConfigException('The --config option needs a value.');
        }
        $this->line('Loading config: ' . $configOption);
        $this->newLine();

        $configFile = Storage::path($configOption);
        if (Storage::missing($configOption)) {
            throw new ConfigException('Cannot find the config file at: ' . $configFile . '. Pass --config=<file> or run `typesetter init` to create one.');
        }

        // @phpstan-ignore argument.type (Config validates the array the config file returns)
        return new Config(require $configFile);
    }

    protected function registerListeners(Typesetter $typesetter): void
    {
        $typesetter->listen(Events\Starting::class, function () {
            $this->comment('Starting typesetting');
        });

        $typesetter->listen(Events\MarkdownInitialized::class, function () {
            $this->comment('Markdown initialized.');
        });

        $typesetter->listen(Events\PdfInitialized::class, function () {
            $this->comment('PDF initialized.');
        });

        $typesetter->listen(Events\ThemeAdded::class, function () {
            $this->comment('Theme added.');
        });

        $typesetter->listen(Events\CoverImageAdded::class, function () {
            $this->comment('Cover image added.');
        });
        $typesetter->listen(Events\CoverHtmlAdded::class, function () {
            $this->comment('Cover HTML added.');
        });
        $typesetter->listen(Events\CoverGenerated::class, function () {
            $this->comment('Cover automatically generated.');
        });

        $typesetter->listen(Events\TocGenerated::class, function () {
            $this->comment('Table of contents generated.');
        });

        $typesetter->listen(Events\FooterGenerated::class, function () {
            $this->comment('Footer added.');
        });

        $typesetter->listen(Events\ContentGenerating::class, function () {
            $this->comment('Converting chapters.');
        });

        $typesetter->listen(Events\PdfRendering::class, function () {
            $this->comment('Rendering PDF.');
        });

        $typesetter->listen(Events\Finished::class, function () {
            $this->comment('Finished rendering.');
        });
    }
}
