# 🔍 Frontend Editing Debugging Guide

## Problem: Toolbar nicht sichtbar

### Checkliste für Railway:

#### 1. ✅ Code ist gepusht
```bash
git log --oneline -3
# eba088fc Add installation summary
# 58661294 Fix Railway build
# e37b2b23 Add TYPO3 Frontend Editing
```

#### 2. ⚠️ Extension muss aktiviert werden

**Im Railway Backend Service → Terminal ausführen:**
```bash
php vendor/bin/typo3 extension:setup
php vendor/bin/typo3 cache:flush
```

**Prüfen ob Extension aktiv:**
```bash
php vendor/bin/typo3 extension:list | grep pixelcoda_fe_editor
```

#### 3. ⚠️ Backend Login erforderlich

Die Toolbar erscheint NUR wenn:
- ✅ Backend User eingeloggt ist
- ✅ User hat `tt_content` Edit-Rechte
- ✅ Extension ist aktiviert

**Test:**
1. https://web-production-581b4.up.railway.app/typo3 → Login
2. https://nextjs-front-end-for-typo3-headless-production.up.railway.app/ → Toolbar?

#### 4. 🔧 Environment Variables (Optional)

**Railway Backend Service:**
```
TYPO3_FE_EDITING_ENABLED=1
```

#### 5. 🧪 Test-Seite verwenden

Die neue Test-Seite zeigt alle notwendigen Schritte:
- https://nextjs-front-end-for-typo3-headless-production.up.railway.app/fe-test

#### 6. 🔍 Browser Console prüfen

Nach Backend-Login öffne die Browser Console (F12):

**Erwartete Logs:**
```javascript
// Erfolg:
Loading TYPO3 Frontend Editor...
Editor initialized

// Fehler:
Failed to load editor.js
CORS error
404 on editor assets
```

#### 7. 📦 Extension Dateien prüfen

**Im Railway Backend Terminal:**
```bash
ls -la packages/typo3_fe_editing/packages/pixelcoda_fe_editor/
ls -la public/typo3conf/ext/pixelcoda_fe_editor/
```

#### 8. 🌐 CORS Headers prüfen

**In Railway Backend Service:**
```bash
curl -I https://web-production-581b4.up.railway.app/typo3conf/ext/pixelcoda_fe_editor/Resources/Public/editor.js
```

Sollte zeigen:
```
HTTP/2 200
access-control-allow-origin: https://nextjs-front-end-for-typo3-headless-production.up.railway.app
```

## Sofort-Lösung: Lokal testen

```bash
# DDEV starten
ddev start

# Extension aktivieren
ddev exec vendor/bin/typo3 extension:setup
ddev exec vendor/bin/typo3 cache:flush

# Backend Login
open https://typo3-inst.localhost/typo3

# Frontend Test
open https://typo3-inst.localhost/fe-test
```

## Häufige Probleme

### Problem 1: Extension nicht im Vendor
```bash
# Prüfen
ls -la vendor/pixelcoda/fe-editor

# Falls fehlt:
composer install
```

### Problem 2: Assets nicht erreichbar
```bash
# Symlinks erstellen
php vendor/bin/typo3 extension:setup

# Oder manuell:
ln -s ../../packages/typo3_fe_editing/packages/pixelcoda_fe_editor public/typo3conf/ext/pixelcoda_fe_editor
```

### Problem 3: CORS blockiert
```
TypoScript prüfen:
packages/pixelcoda_sitepackage/Configuration/TypoScript/FrontendEditing/setup.typoscript
```

### Problem 4: Toolbar geladen aber nicht sichtbar
```javascript
// Browser Console:
document.querySelector('[data-pc-toolbar]')
// Sollte Element zurückgeben

// Wenn null:
// - Extension nicht aktiviert
// - Backend nicht eingeloggt
// - User hat keine Rechte
```

## Railway Deployment Status prüfen

1. **Railway Dashboard** → Deployments
2. **Build Logs** prüfen
3. **Runtime Logs** prüfen für Fehler
4. **Environment Variables** verifizieren

## Quick Fix Commands

```bash
# Alle Caches leeren
php vendor/bin/typo3 cache:flush

# Extension neu installieren
composer install --no-dev
php vendor/bin/typo3 extension:setup

# Permissions setzen
chown -R www-data:www-data /var/www/html

# Apache neu starten
service apache2 restart
```

## Test URLs

- **Backend**: https://web-production-581b4.up.railway.app/typo3
- **Frontend**: https://nextjs-front-end-for-typo3-headless-production.up.railway.app/
- **Test Page**: https://nextjs-front-end-for-typo3-headless-production.up.railway.app/fe-test
- **Health**: https://web-production-581b4.up.railway.app/healthz

## Erfolgs-Kriterien

✅ Backend Login funktioniert
✅ Extension in Extension Manager sichtbar
✅ `vendor/pixelcoda/fe-editor` existiert
✅ Assets unter `/typo3conf/ext/pixelcoda_fe_editor/` erreichbar
✅ Browser Console zeigt keine Fehler
✅ Toolbar rechts unten sichtbar nach Login

## Support

Wenn nichts funktioniert, prüfe:
1. Railway Build Logs
2. TYPO3 Logs: `var/log/typo3_*.log`
3. Browser Console (F12)
4. Network Tab (F12) für 404/CORS Fehler
