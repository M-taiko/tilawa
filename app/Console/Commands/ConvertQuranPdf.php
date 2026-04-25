<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ConvertQuranPdf extends Command
{
    protected $signature = 'quran:convert-pdf';
    protected $description = 'Convert Quran PDF to individual page images';

    public function handle()
    {
        $pdfFile = base_path('quran.pdf');
        $outputDir = public_path('quran-pages');

        if (!file_exists($pdfFile)) {
            $this->error('Error: quran.pdf not found in root directory');
            return 1;
        }

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $this->info('Starting PDF conversion...');
        $this->info("PDF File: $pdfFile");
        $this->info("Output Directory: $outputDir");
        $this->info('File Size: ' . round(filesize($pdfFile) / 1024 / 1024, 2) . ' MB');
        $this->line('');

        // Try using spatie/pdf-to-image
        try {
            $this->convertUsingSpatie($pdfFile, $outputDir);
        } catch (\Exception $e) {
            $this->error('Spatie conversion failed: ' . $e->getMessage());
            $this->error('Ghostscript/ImageMagick not installed on system');
            $this->line('');
            $this->info('Alternative: Install Ghostscript for Windows from https://www.ghostscript.com/download/gsdnld.html');
            return 1;
        }

        return 0;
    }

    private function convertUsingSpatie($pdfFile, $outputDir)
    {
        try {
            // Temporarily use a wrapper directory for spatie
            $tmpDir = storage_path('pdf_conversion_tmp');
            if (!is_dir($tmpDir)) {
                mkdir($tmpDir, 0755, true);
            }

            $pdf = new \Spatie\PdfToImage\Pdf($pdfFile);
            $pdf->setOutputFormat('jpg');

            // Get page count
            $pageCount = $pdf->getNumberOfPages();
            $this->info("Total pages: $pageCount");
            $this->line('');

            $bar = $this->output->createProgressBar($pageCount);
            $bar->start();

            for ($page = 1; $page <= $pageCount; $page++) {
                $outputFile = "$outputDir/page-" . str_pad($page, 3, '0', STR_PAD_LEFT) . '.jpg';

                try {
                    $pdf->setPage($page)
                        ->save($outputFile);
                    $bar->advance();
                } catch (\Exception $e) {
                    $this->warn("Failed to convert page $page: " . $e->getMessage());
                }
            }

            $bar->finish();
            $this->line('');
            $this->info('✓ Conversion completed!');

            // Create manifest
            $files = glob("$outputDir/page-*.jpg");
            $manifest = [];
            foreach ($files as $file) {
                $manifest[] = [
                    'page' => intval(preg_replace('/[^\d]/', '', basename($file))),
                    'file' => 'quran-pages/' . basename($file),
                    'size' => filesize($file)
                ];
            }

            file_put_contents(
                "$outputDir/manifest.json",
                json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );

            $this->info('✓ Manifest created: quran-pages/manifest.json');
            $this->info("Generated " . count($files) . " page images");
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
