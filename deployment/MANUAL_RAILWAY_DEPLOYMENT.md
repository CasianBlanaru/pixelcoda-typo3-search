# Manual Railway Deployment via Dashboard

Da die automatische CLI-Methode nicht verfügbar ist, folge diesen Schritten im Railway Dashboard:

## Schritt 1: Dateien hochladen

Die Dateien müssen manuell auf Railway hochgeladen werden. Es gibt mehrere Optionen:

### Option A: Über Railway Volume (empfohlen für Production)

Railway bietet keinen direkten File-Upload. Stattdessen:

1. **Temporärer Workaround**: Füge die Dateien zu Git hinzu (einmalig)

```bash
# Temporär große Dateien zu Git hinzufügen
git add -f deployment/typo3-database-export.sql.gz
git add -f deployment/fileadmin-files.tar.gz
git commit -m "Temp: Add data files for Railway deployment"
git push origin main
```

2. Nach dem Deployment auf Railway, im Terminal ausführen:

```bash
bash deployment/import-database.sh
bash deployment/extract-fileadmin.sh
php deployment/update-site-urls.php
```

3. Dann Dateien wieder aus Git entfernen:

```bash
git rm deployment/typo3-database-export.sql.gz
git rm deployment/fileadmin-files.tar.gz
git commit -m "Remove temporary data files from git"
git push origin main
```

### Option B: Über Railway CLI (wenn verfügbar)

```bash
# Login zuerst
railway login

# Dann Upload-Script ausführen
bash deployment/upload-to-railway.sh
```

### Option C: Über External Storage (Produktion)

Für regelmäßige Updates besser:

1. Lade Dateien zu S3/Cloud Storage hoch
2. Download in Railway via wget/curl:

```bash
# Im Railway Terminal
cd /app/deployment
wget https://your-storage.com/typo3-database-export.sql.gz
wget https://your-storage.com/fileadmin-files.tar.gz

# Dann importieren
bash import-database.sh
bash extract-fileadmin.sh
php update-site-urls.php
```

## Schritt 2: Import ausführen (Railway Terminal)

Gehe zu Railway Dashboard → TYPO3 Service → Terminal

```bash
# 1. Datenbank importieren
bash deployment/import-database.sh

# 2. Dateien extrahieren  
bash deployment/extract-fileadmin.sh

# 3. URLs aktualisieren
php deployment/update-site-urls.php

# 4. Cache leeren
php vendor/bin/typo3 cache:flush
```

## Schritt 3: Services neu starten

Im Railway Dashboard:
- TYPO3 Service → "Restart"
- Frontend Service → "Restart"

## Schritt 4: Testen

```bash
# API Test
curl -H "Accept: application/json" https://web-production-581b4.up.railway.app/

# Backend Login
open https://web-production-581b4.up.railway.app/typo3/

# Frontend
open https://nextjs-front-end-for-typo3-headless-production.up.railway.app/
```

## SCHNELLSTE LÖSUNG (für jetzt):

```bash
# 1. Temporär Dateien in Git (lokal ausführen)
git add -f deployment/typo3-database-export.sql.gz deployment/fileadmin-files.tar.gz
git commit -m "Deploy: Add database and files"
git push origin main

# 2. Warte auf Railway Deployment

# 3. Railway Terminal öffnen und ausführen:
bash deployment/import-database.sh && \
bash deployment/extract-fileadmin.sh && \
php deployment/update-site-urls.php && \
php vendor/bin/typo3 cache:flush

# 4. Services neu starten im Dashboard

# 5. Dateien aus Git entfernen (lokal)
git rm deployment/typo3-database-export.sql.gz deployment/fileadmin-files.tar.gz
git commit -m "Cleanup: Remove data files"
git push origin main
```

Das ist der schnellste Weg für eine einmalige Migration!
