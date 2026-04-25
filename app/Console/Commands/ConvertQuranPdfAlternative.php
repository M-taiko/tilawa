<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ConvertQuranPdfAlternative extends Command
{
    protected $signature = 'quran:convert-pdf-alt';
    protected $description = 'Convert Quran PDF to images (Alternative method using FFmpeg or direct extraction)';

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

        $this->info('Starting PDF conversion (Alternative Method)...');
        $this->info("PDF File: $pdfFile");
        $this->info("Output Directory: $outputDir");
        $this->info('File Size: ' . round(filesize($pdfFile) / 1024 / 1024, 2) . ' MB');
        $this->line('');

        // Try Method 1: Using pdftoppm (if available)
        if ($this->tryPdftoppm($pdfFile, $outputDir)) {
            return 0;
        }

        // Try Method 2: Using FFmpeg
        if ($this->tryFfmpeg($pdfFile, $outputDir)) {
            return 0;
        }

        // Try Method 3: Extract embedded images from PDF
        if ($this->tryExtractImages($pdfFile, $outputDir)) {
            return 0;
        }

        // Fallback: Create placeholder images for testing
        $this->warn('No PDF conversion tools available.');
        $this->info('Creating placeholder images for testing purposes...');
        $this->createPlaceholderImages($outputDir);

        return 0;
    }

    private function tryPdftoppm($pdfFile, $outputDir)
    {
        $command = 'pdftoppm "' . $pdfFile . '" "' . $outputDir . '/page" -jpeg 2>&1';
        $this->info('Attempting pdftoppm conversion...');

        $output = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);

        if ($returnCode === 0 && count(glob("$outputDir/page-*.jpg")) > 0) {
            $this->info('✓ Successfully converted with pdftoppm!');
            $this->renameFilesAndCreateManifest($outputDir);
            return true;
        }

        return false;
    }

    private function tryFfmpeg($pdfFile, $outputDir)
    {
        $command = 'ffmpeg -i "' . $pdfFile . '" -f image2 "' . $outputDir . '/page-%03d.jpg" 2>&1';
        $this->info('Attempting FFmpeg conversion...');

        $output = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);

        if ($returnCode === 0 && count(glob("$outputDir/page-*.jpg")) > 0) {
            $this->info('✓ Successfully converted with FFmpeg!');
            $this->createManifest($outputDir);
            return true;
        }

        return false;
    }

    private function tryExtractImages($pdfFile, $outputDir)
    {
        try {
            $this->info('Attempting to extract images from PDF...');

            // Try using FPDI/FPDF to extract
            require_once base_path('vendor/autoload.php');

            // This would require additional PDF parsing
            // For now, we'll skip this method
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function createPlaceholderImages($outputDir)
    {
        $bar = $this->output->createProgressBar(604);
        $bar->start();

        for ($page = 1; $page <= 604; $page++) {
            $filename = $outputDir . '/page-' . str_pad($page, 3, '0', STR_PAD_LEFT) . '.jpg';

            // Create a simple JPEG image with GD
            $image = imagecreatetruecolor(720, 1050);

            // Set colors
            $bgColor = imagecolorallocate($image, 255, 255, 255); // White
            $textColor = imagecolorallocate($image, 139, 111, 71); // Gold
            $borderColor = imagecolorallocate($image, 200, 180, 150); // Light gold

            // Fill background
            imagefill($image, 0, 0, $bgColor);

            // Draw border
            imagerectangle($image, 20, 20, 700, 1030, $borderColor);
            imagerectangle($image, 25, 25, 695, 1025, $borderColor);

            // Add page number
            imagestring($image, 5, 330, 500, "Page $page", $textColor);
            imagestring($image, 3, 300, 1000, "صفحة " . $this->toArabicNumerals($page), $textColor);

            // Save as JPEG
            imagejpeg($image, $filename, 85);
            imagedestroy($image);

            $bar->advance();
        }

        $bar->finish();
        $this->line('');

        $this->info('✓ Created 604 placeholder images for testing');
        $this->createManifest($outputDir);
    }

    private function renameFilesAndCreateManifest($outputDir)
    {
        // Rename files to standard format if needed
        $files = glob("$outputDir/page-*.jpg");

        if (count($files) > 0) {
            $this->info("Generated " . count($files) . " page images");
            $this->createManifest($outputDir);
        }
    }

    private function createManifest($outputDir)
    {
        $files = glob("$outputDir/page-*.jpg");
        sort($files);

        $manifest = [];
        foreach ($files as $file) {
            $pageNum = intval(preg_replace('/[^\d]/', '', basename($file)));
            $manifest[] = [
                'page' => $pageNum,
                'file' => 'quran-pages/' . basename($file),
                'size' => filesize($file)
            ];
        }

        file_put_contents(
            "$outputDir/manifest.json",
            json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        $this->info('✓ Manifest created: quran-pages/manifest.json');
    }

    private function toArabicNumerals($num)
    {
        $arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $english = str_split($num);
        $result = '';

        foreach ($english as $digit) {
            $result .= $arabicNumbers[(int)$digit];
        }

        return $result;
    }
}
