<?php
/**
 * Convert Quran PDF to images using XAMPP tools
 */

// Define paths
$pdfFile = __DIR__ . '/quran.pdf';
$outputDir = __DIR__ . '/public/quran-pages';

if (!file_exists($pdfFile)) {
    die("Error: quran.pdf not found in root directory\n");
}

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

echo "Starting PDF conversion...\n";
echo "PDF File: $pdfFile\n";
echo "Output Directory: $outputDir\n";
echo "File Size: " . round(filesize($pdfFile) / 1024 / 1024, 2) . " MB\n\n";

// Try Method 1: Using ghostscript command-line
$gsCommand = 'gswin64c -q -dNOPAUSE -dBATCH -dSAFER -sDEVICE=jpeg -r300x300 -sOutputFile="' . $outputDir . '/page-%03d.jpg" "' . $pdfFile . '"';

echo "Attempting to convert with Ghostscript...\n";
echo "Command: $gsCommand\n\n";

$output = [];
$returnCode = 0;
exec($gsCommand, $output, $returnCode);

if ($returnCode === 0) {
    echo "✓ Conversion successful!\n";

    // Count generated files
    $files = glob("$outputDir/page-*.jpg");
    echo "Generated " . count($files) . " image files\n\n";

    // Show first few files
    foreach (array_slice($files, 0, 5) as $file) {
        $size = round(filesize($file) / 1024, 2);
        echo "  - " . basename($file) . " (" . $size . " KB)\n";
    }

    if (count($files) > 5) {
        echo "  ... and " . (count($files) - 5) . " more files\n";
    }
} else {
    echo "✗ Ghostscript conversion failed (return code: $returnCode)\n";
    echo "Error output:\n";
    foreach ($output as $line) {
        echo "  " . $line . "\n";
    }
    echo "\nTrying alternative method...\n";
}

// If files were created, create manifest
if (isset($files) && count($files) > 0) {
    $manifest = [];
    foreach ($files as $file) {
        $manifest[] = [
            'page' => intval(preg_replace('/[^\d]/', '', basename($file))),
            'file' => 'quran-pages/' . basename($file),
            'size' => filesize($file)
        ];
    }

    file_put_contents(
        __DIR__ . '/public/quran-pages/manifest.json',
        json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    );

    echo "\n✓ Manifest created: quran-pages/manifest.json\n";
}
?>
