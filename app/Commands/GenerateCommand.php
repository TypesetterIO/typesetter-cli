<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class GenerateCommand extends Command
{
    protected $signature = 'generate' .
    '{--config=config.php : The name and path to config file in relation to the root location of this runtime.}';

    protected $description = 'Generates the book content into a PDF.';

    public function handle(): int // TypesetterService $service): int
    {
        $this->info('Welcome to Typesetter!');
//        $this->newLine();
//
//        $this->registerListeners($service);
//
//        $configOption = $this->option('config');
//        $this->line('Loading config: ' . $configOption);
//        $this->newLine();
//        $config = TypesetterConfig::make($configOption);
//
//        $pdfBinary = $service->generate($config);
//
//        $this->newLine();
//        $this->line('Writing PDF');
//        $this->newLine();
//        Storage::disk('generated')->put($config->filename, $pdfBinary);
//
//        $this->info('Done!');
        return self::SUCCESS;
    }
//
//    protected function registerListeners(TypesetterService $service): void
//    {
//        $service->listen(Events\Starting::class, function () {
//            $this->comment('Starting typesetting');
//        });
//
//        $service->listen(Events\PDFInitialized::class, function () {
//            $this->comment('Markdown and PDF initialized');
//        });
//
//        $service->listen(Events\CoverImageAdded::class, function () {
//            $this->comment('Cover image added.');
//        });
//        $service->listen(Events\CoverHtmlAdded::class, function () {
//            $this->comment('Cover HTML added.');
//        });
//        $service->listen(Events\CoverGenerated::class, function () {
//            $this->comment('Cover automatically generated.');
//        });
//
//        $service->listen(Events\PDFRendering::class, function () {
//            $this->comment('Rendering PDF.');
//        });
//
//        $service->listen(Events\Finished::class, function () {
//            $this->comment('Finished rendering.');
//        });
//    }
}
