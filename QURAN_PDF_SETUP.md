# Quran PDF Integration Setup Guide

## Overview
This document explains how to set up the Quran PDF viewer feature for the Tilawa application. The system allows users to view Quran pages as actual PDF images with interactive verse overlays.

## Prerequisites
- quran.pdf file (160MB) in the project root directory ✓
- Ghostscript installed on your system (Windows, Mac, or Linux)
- Laravel 12 application running

## Step 1: Install Ghostscript

### Windows
1. Go to: https://www.ghostscript.com/download/gsdnld.html
2. Download "GPL Ghostscript 10.04.0 for Windows (64-bit)" (or latest version)
3. Run the installer and follow the setup wizard
4. Restart your terminal/command prompt
5. Verify installation:
   ```bash
   gswin64c -version
   ```

### Mac
```bash
brew install ghostscript
```

### Linux (Ubuntu/Debian)
```bash
sudo apt-get install ghostscript
```

## Step 2: Convert PDF to Images

Once Ghostscript is installed, run the artisan command:

```bash
php artisan quran:convert-pdf
```

This command will:
- Read the quran.pdf file from the project root
- Convert all 604 pages to individual JPEG images
- Save images to `public/quran-pages/page-001.jpg` through `public/quran-pages/page-604.jpg`
- Generate `public/quran-pages/manifest.json` with metadata

**Expected output:**
```
Starting PDF conversion...
PDF File: C:\xampp\htdocs\tilawa\quran.pdf
Output Directory: C:\xampp\htdocs\tilawa\public\quran-pages
File Size: 159.63 MB

Total pages: 604
[████████████████████████████████] 100%

✓ Conversion completed!
✓ Manifest created: quran-pages/manifest.json
Generated 604 page images
```

The conversion will take several minutes depending on your system.

## Step 3: Verify PDF Pages

After conversion, check that images were created:
```bash
ls -lh public/quran-pages/ | head -10
```

You should see files like:
```
page-001.jpg
page-002.jpg
page-003.jpg
...
page-604.jpg
manifest.json
```

## Step 4: Access the PDF Viewer

Navigate to the Quran reader:
- `/quran/page/1` - First page of Quran
- `/quran/page/100` - Page 100
- `/quran/page/604` - Last page

The application will automatically detect PDF pages and use the PDF view (`page-with-pdf.blade.php`) instead of the text view.

## Features Enabled

Once PDF pages are converted, users can:

1. **View Quran Pages**: See actual manuscript pages from the quran.pdf file
2. **Click Verses**: Click on any verse to see more information
3. **Read Tafsir**: Arabic Islamic commentary (ar.muyassar edition)
4. **Read Translation**: English translations (en.sahih edition) or Saheeh International
5. **View Reasons**: Asbab al-Nuzul (reasons for revelation) in Arabic
6. **Switch Languages**: Toggle between Arabic (RTL) and English (LTR)
7. **Navigate Pages**: Previous/Next buttons and page selector dropdown

## Technical Details

### File Structure
```
public/
└── quran-pages/
    ├── page-001.jpg          # Page 1 image (300 DPI JPEG)
    ├── page-002.jpg
    ├── ...
    ├── page-604.jpg          # Page 604 image
    └── manifest.json         # Metadata file
```

### Database Schema
No new database migrations are required. The system uses existing verse data with:
- `verse.verse_text` - Original Arabic text
- `verse.verse_text_english` - English translation (if available)
- `verse.surah_id`, `verse.verse_number` - Verse identifiers
- `surah.name_arabic`, `surah.name_english` - Surah names

### Views
- `resources/views/quran/page-with-pdf.blade.php` - PDF view (auto-selected when pages exist)
- `resources/views/quran/page.blade.php` - Text view (fallback when PDF unavailable)

### API Integration
The PDF viewer uses the Al-Quran Cloud API:
- **Tafsir**: `/v1/ayah/{surah}:{verse}/{edition}`
  - `ar.muyassar` - Arabic commentary
  - `en.sahih` - English Saheeh International
- **Reasons**: `/v1/ayah/{surah}:{verse}/ar.asbabnuzul`

### Verse Overlays
- Verse overlays are currently positioned in a grid pattern
- **Future Enhancement**: Implement precise PDF coordinate mapping
- Overlays are responsive and scale with the PDF image
- Clicking an overlay shows the popup with tabs

### Language Support
The PDF viewer respects the user's language preference:
- **Arabic Mode** (RTL):
  - Loads `ar.muyassar` tafsir
  - Shows surah names in Arabic
  - Shows verse metadata as "سورة X - الآية Y"
- **English Mode** (LTR):
  - Loads `en.sahih` translation
  - Shows surah names in English
  - Shows verse metadata as "Surah X — Verse Y"

## Troubleshooting

### Issue: "Ghostscript not found"
**Solution**: Install Ghostscript from https://www.ghostscript.com/download/gsdnld.html and ensure it's in your PATH.

### Issue: Command not found on Mac
**Solution**: If `brew install ghostscript` doesn't work, try:
```bash
sudo port install ghostscript
```

### Issue: Slow conversion on large PDF
**Note**: The 160MB PDF with 604 pages will take 5-15 minutes to convert. This is normal.

### Issue: Out of memory during conversion
**Solution**: Increase PHP memory limit in `php.ini`:
```ini
memory_limit = 2048M
```

### Issue: PDF pages appear empty
**Cause**: Images may not have converted properly. Check file sizes:
```bash
ls -lh public/quran-pages/page-001.jpg
```
If the file is only a few KB or empty, re-run the conversion command.

## Advanced Configuration

### Changing Image Quality
To adjust image resolution, edit `app/Console/Commands/ConvertQuranPdf.php`:
```php
// Change resolution (300 = 300 DPI)
$pdf->setResolution(150); // Lower quality, smaller files
$pdf->setResolution(600); // Higher quality, larger files
```

### Changing Image Format
The current setup uses JPEG. To change to PNG:
```php
$pdf->setOutputFormat('png');
```

## Performance Notes

- **Image Size**: Each 300 DPI JPEG page is approximately 300-400 KB
- **Total Storage**: ~200 MB for all 604 pages
- **Load Time**: Depends on browser cache and network speed
- **Caching**: Browser automatically caches page images after first load

## Future Enhancements

1. **Precise Verse Coordinates**: Map actual PDF text positions to overlays
2. **OCR Integration**: Extract text coordinates from PDF for accuracy
3. **Audio Recitation**: Add Quran recitation synchronized with page
4. **Bookmarks**: Save favorite verses and pages
5. **Offline Storage**: Cache PDF pages with Dexie for offline reading
6. **Print Support**: Print pages with verse highlights
7. **Verse Comparison**: Compare multiple verse translations side-by-side

## Support

If you encounter issues:
1. Check that Ghostscript is properly installed: `gswin64c -version`
2. Verify quran.pdf exists: `ls -l quran.pdf`
3. Check PHP error logs: `storage/logs/laravel.log`
4. Ensure public directory is writable: `chmod -R 755 public/`

---

**Last Updated**: 2026-04-25  
**Status**: Ready for Setup
