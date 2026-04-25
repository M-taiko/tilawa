# Quick Start: Quran PDF Viewer

## 3 Steps to Enable PDF Viewer

### Step 1: Install Ghostscript

**Windows**: Download from https://www.ghostscript.com/download/gsdnld.html → Run installer → Restart terminal

**Mac**: `brew install ghostscript`

**Linux**: `sudo apt-get install ghostscript`

### Step 2: Convert PDF
```bash
php artisan quran:convert-pdf
```
Takes 5-15 minutes. Wait for it to complete.

### Step 3: Access
Visit: `http://localhost/tilawa/quran/page/1`

---

## That's It! 🎉

Your Quran will now display as beautiful PDF pages with:
- Interactive verse overlays
- Tafsir (Islamic commentary)
- English translations
- Reasons for revelation (Asbab al-Nuzul)
- Full language support (Arabic/English)

## Troubleshooting

**"Ghostscript not found"** → Install it (Step 1) and restart terminal

**Slow conversion** → Normal! 160MB PDF = 5-15 mins

**Images look wrong** → Re-run: `php artisan quran:convert-pdf`

---

See `IMPLEMENTATION_COMPLETE.md` for full details.
