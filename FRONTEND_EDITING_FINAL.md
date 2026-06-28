# Frontend Editing - Lokaler Test & Deployment

## ✅ Was ist implementiert

1. **TYPO3 Backend Extension**: pixelcoda_fe_editor v1.2.5 ist installiert und aktiv
2. **Site-Konfiguration**: `config/sites/main/config.yaml` enthält `pixelcoda/fe-editor` Dependency
3. **Frontend-Komponente**: `FrontendEditor.jsx` rendert die Toolbar und lädt Assets dynamisch
4. **Editable Marker**: React-Komponenten fügen `data-pc-field`, `data-table`, `data-uid`, `data-field` Attribute hinzu

## 🧪 Lokaler Test

### Schritt 1: TYPO3 Backend Login

```bash
# Browser öffnen
open "https://typo3-inst.localhost/typo3"
```

Login mit einem der verfügbaren Benutzer:
- `admin` (Admin)
- `pixelcoda` (Admin)
- `pixelcoda-editor` (Editor)

### Schritt 2: Frontend im gleichen Browser öffnen

```bash
# Im gleichen Browser (wichtig für Cookies!)
open "http://localhost:3000"
```

### Schritt 3: Was Sie sehen sollten

✅ **Toolbar oben** mit Buttons: Edit, Save, AI, Add
✅ **Editable Felder** mit `data-pc-field` Attributen
✅ **Edit-Button klickbar** - aktiviert den Bearbeitungsmodus
✅ **Felder werden zu contenteditable** nach Klick auf "Edit"
✅ **Änderungen speichern** via "Save" Button oder automatisch

### Debugging im Browser

```javascript
// Browser Console (F12)

// 1. Prüfen ob Backend-Cookie vorhanden
document.cookie.includes('be_typo_user')  // sollte true sein

// 2. Prüfen ob Toolbar gerendert wurde
document.getElementById('pc-fe-toolbar-root') !== null  // sollte true sein

// 3. Prüfen ob TYPO3 global object existiert
window.TYPO3?.settings?.ajaxUrls?.fe_editor_save  // sollte URL zeigen

// 4. Prüfen ob editierbare Felder vorhanden sind
document.querySelectorAll('[data-pc-field]').length  // sollte > 0 sein
```

## 🚀 Deployment

### 1. Änderungen committen und pushen

```bash
git add -A
git commit -m "Implement frontend editing toolbar for Next.js headless"
git push origin main
```

### 2. Railway Deployment abwarten

- Backend: https://web-production-581b4.up.railway.app/
- Frontend: https://nextjs-front-end-for-typo3-headless-production.up.railway.app/

### 3. Railway Backend Login

1. Öffnen Sie: `https://web-production-581b4.up.railway.app/typo3`
2. Login mit Backend-User
3. **Cookie bleibt im Browser!**

### 4. Railway Frontend öffnen (im gleichen Browser!)

1. Öffnen Sie: `https://nextjs-front-end-for-typo3-headless-production.up.railway.app/`
2. **Toolbar sollte erscheinen!**

## 🔧 Wie es funktioniert

### Architektur

```
Next.js Frontend (Port 3000)
  ↓
  FrontendEditor.jsx (Client Component)
    ↓
    1. Prüft ob Backend-Cookie vorhanden (`be_typo_user`)
    2. Lädt CSS von TYPO3: `/_assets/{hash}/editor.css`
    3. Rendert Toolbar-HTML mit React
    4. Lädt JS von TYPO3: `/_assets/{hash}/editor.js`
    5. JS initialisiert Toolbar mit vorhandenen `data-pc-field` Markern
    ↓
TYPO3 Backend (Backend-API)
  ↓
  Speichert Änderungen via AJAX: `/typo3/ajax/fe-editor/save`
```

### Asset-Hash Discovery

Die Komponente ermittelt automatisch den Asset-Hash:
1. Versucht `/_assets/` Directory zu lesen
2. Findet Hash via Regex: `/href="([a-f0-9]{32})/`
3. Fallback auf bekannten Hash: `118a46030edf2e8932199b42dcc98b96`

## ❗ Wichtige Hinweise

### Backend-Cookie ist essentiell

- Toolbar erscheint NUR wenn `be_typo_user` Cookie vorhanden ist
- Cookie wird von TYPO3 Backend gesetzt beim Login
- Cookie ist domain-spezifisch (localhost vs. Railway)
- **Sie MÜSSEN im Backend eingeloggt sein!**

### CORS ist konfiguriert

```yaml
# packages/pixelcoda_sitepackage/Configuration/TypoScript/FrontendEditing/setup.typoscript
config.tx_pixelcodafeeditor.cors.allowedOrigins = *
```

### Asset-Pfade

TYPO3 v14 verwendet `/_assets/{hash}/` für Extension-Ressourcen:
- CSS: `/_assets/{hash}/editor.css`
- JS: `/_assets/{hash}/editor.js`
- Icons: `/_assets/{hash}/Icons/*.svg`

Der Hash ändert sich bei jeder Composer-Installation!

## 🐛 Troubleshooting

### Toolbar erscheint nicht

1. ✅ Backend-Login prüfen: Öffnen Sie `/typo3` und loggen Sie sich ein
2. ✅ Cookie prüfen: `document.cookie.includes('be_typo_user')`
3. ✅ Browser-Cache leeren (Strg+Shift+R)
4. ✅ Console-Fehler prüfen (F12)

### Assets laden nicht (404)

1. ✅ Asset-Hash prüfen: `ls -la public/_assets/ | grep pixelcoda`
2. ✅ Komponente aktualisiert den Hash automatisch

### Felder nicht editierbar

1. ✅ Prüfen Sie `data-pc-field` Attribute im HTML
2. ✅ Klicken Sie auf "Edit" Button in der Toolbar
3. ✅ Felder sollten `contenteditable="true"` haben

### Speichern schlägt fehl

1. ✅ CSRF-Token fehlt - Backend neu aufrufen
2. ✅ Backend-Session abgelaufen - neu einloggen
3. ✅ CORS-Fehler - CORS-Konfiguration prüfen

## 📝 Nächste Schritte

- [ ] Lokal testen mit Backend-Login
- [ ] Verifizieren dass Toolbar erscheint
- [ ] Textfeld editieren und speichern testen
- [ ] Zu Railway deployen
- [ ] Railway Backend Login
- [ ] Railway Frontend testen

## 🎯 Erfolgs-Kriterien

✅ Toolbar ist sichtbar nach Backend-Login
✅ "Edit" Button aktiviert Bearbeitungsmodus
✅ Textfelder sind editierbar (contenteditable)
✅ Änderungen werden gespeichert (Save Button oder Auto-Save)
✅ Keine Console-Fehler
✅ CORS-Probleme sind gelöst
