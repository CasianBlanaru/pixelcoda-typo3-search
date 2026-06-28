# Frontend Editing - Lokaler Test

## Status

✅ Extension pixelcoda_fe_editor v1.2.5 ist installiert und aktiv
✅ Site-Konfiguration enthält pixelcoda/fe-editor Dependency
✅ Frontend zeigt data-pc-field Marker an
✅ FrontendEditor React-Komponente wird geladen

## Lokaler Test - Schritt für Schritt

### 1. TYPO3 Backend Login

Öffnen Sie in Ihrem Browser:
```
https://typo3-inst.localhost/typo3
```

Verfügbare Benutzer:
- Username: `admin` (Admin-Rechte)
- Username: `pixelcoda` (Admin-Rechte)
- Username: `pixelcoda-editor` (Editor-Rechte)

**WICHTIG**: Sie müssen das Passwort kennen oder im Backend zurücksetzen.

### 2. Frontend öffnen (im gleichen Browser)

Nach dem Backend-Login öffnen Sie:
```
http://localhost:3000/
```

### 3. Was Sie sehen sollten

Wenn alles funktioniert:
- ✅ Toolbar oben auf der Seite
- ✅ Bearbeitbare Felder sind markiert
- ✅ Klick auf Felder öffnet den Editor

### 4. Test-Befehle

#### Caches leeren
```bash
ddev exec php vendor/bin/typo3 cache:flush
```

#### API-Response mit _pixelcoda testen
```bash
curl -s "https://api.typo3-inst.localhost/?type=834" \
  --cookie "be_typo_user=IHRE_COOKIE" \
  -H "Accept: application/json" | jq '._pixelcoda_editing_enabled'
```

#### Frontend HTML prüfen
```bash
curl -s "http://localhost:3000/" | grep -o 'data-pc-field' | wc -l
```
Sollte > 0 sein (aktuell: mehrere Marker gefunden)

### 5. Debugging

Falls die Toolbar nicht erscheint:

1. **Browser Console öffnen** (F12)
2. Nach Fehlern suchen:
   - `Failed to load` = CSS/JS nicht geladen
   - `CORS error` = Backend-URL falsch

3. **Überprüfen Sie die API-Response**:
```javascript
// In Browser Console
fetch('https://api.typo3-inst.localhost/?type=834', {
  credentials: 'include',
  headers: { 'Accept': 'application/json' }
})
.then(r => r.json())
.then(d => console.log(d._pixelcoda_editing_enabled));
```

4. **Extension Status prüfen**:
```bash
ddev exec php vendor/bin/typo3 extension:list | grep pixelcoda_fe_editor
```

### 6. Erfolgs-Kriterien

✅ Backend-User ist eingeloggt (Cookie vorhanden)
✅ Extension ist aktiv
✅ Site hat pixelcoda/fe-editor Dependency
✅ Frontend zeigt data-pc-field Marker
✅ FrontendEditor.jsx wird geladen
✅ API liefert _pixelcoda_editing_enabled: true (mit Login)

## Nächste Schritte nach lokalem Test

Wenn lokal alles funktioniert:

1. **Git Commit & Push**
```bash
git add .
git commit -m "Enable frontend editing in site configuration"
git push origin main
```

2. **Railway Backend - Extension Setup**
```bash
# Im Railway Backend Terminal
php vendor/bin/typo3 cache:flush
```

3. **Railway Backend Login**
   - Öffnen: `https://web-production-581b4.up.railway.app/typo3`
   - Mit Backend-User einloggen

4. **Railway Frontend öffnen**
   - Öffnen: `https://nextjs-front-end-for-typo3-headless-production.up.railway.app/`
   - Toolbar sollte erscheinen

## Bekannte Probleme

### Toolbar erscheint nicht
- Prüfen Sie, ob Backend-User eingeloggt ist
- Browser-Cache leeren
- In Inkognito-Fenster testen

### CORS-Fehler
- Prüfen Sie CORS-Konfiguration in `packages/pixelcoda_sitepackage/Configuration/TypoScript/FrontendEditing/setup.typoscript`
- Frontend-URL muss in `config.tx_pixelcodafeeditor.cors.allowedOrigins` sein

### CSS/JS lädt nicht
- Prüfen Sie, ob Backend-URL in `NEXT_PUBLIC_TYPO3_BASE_URL` korrekt ist
- Prüfen Sie, ob Backend öffentlich erreichbar ist
