# Quran PDF Conversion Guide

## Current Status
The `quran.pdf` file (160MB) exists in the project root, but cannot be automatically converted to page images due to missing system dependencies.

## Why Conversion Failed
The Laravel project uses the `spatie/pdf-to-image` package, which requires **one of these** to be installed on your system:
- **Ghostscript** (recommended)
- **ImageMagick** with Ghostscript
- **LibreOffice** (alternative)

## Solution: Install Ghostscript

### For Windows:
1. Download Ghostscript from: https://www.ghostscript.com/download/gsdnld.html
2. Download the latest **GPL Ghostscript for Windows (64-bit)** version
3. Run the installer and complete installation
4. After installation, restart your command prompt / terminal
5. Verify installation:
   ```bash
   gswin64c -version
   ```

### For Mac:
```bash
brew install ghostscript
```

### For Linux (Ubuntu/Debian):
```bash
sudo apt-get install ghostscript
```

## After Installing Ghostscript

Run the artisan command to convert the PDF:
```bash
php artisan quran:convert-pdf
```

This will:
- Convert all 604 pages of the Quran PDF to individual JPEG images
- Save them to `public/quran-pages/page-001.jpg` through `public/quran-pages/page-604.jpg`
- Create a `public/quran-pages/manifest.json` file with page metadata
- Each image will be approximately 300 DPI (high quality)

## What Happens After Conversion

Once pages are generated, the app will serve PDF page images at:
- `/quran-pages/page-001.jpg`
- `/quran-pages/page-002.jpg`
- etc.

The `page-with-pdf.blade.php` view will display these images and allow users to:
1. Click on any verse
2. See tafsir (Islamic commentary)
3. See reasons for revelation (Asbab al-Nuzul)
4. See English translations (when selected)

## Alternative: Cloud-Based Conversion

If you prefer not to install Ghostscript locally, you can:
1. Use an online PDF to image converter (temporary solution)
2. Upload PDF pages manually to `public/quran-pages/`
3. Implement cloud-based PDF conversion API in the application

## File Structure After Conversion

```
public/
└── quran-pages/
    ├── page-001.jpg
    ├── page-002.jpg
    ├── ...
    ├── page-604.jpg
    └── manifest.json
```

## Next Steps

1. Install Ghostscript on your system
2. Run: `php artisan quran:convert-pdf`
3. Access the new PDF-based Quran reader at: `/quran/page/1`
4. The app will automatically detect and use the PDF page images if available
