# Headless Integration für GSAP Animations & Frontend Editing

## Überblick

Beide Extensions (`pixelcoda-content-gsap-animation` und `pixelcoda-typo3-fe-editing`) sind vollständig für Headless-Betrieb konfiguriert.

## TYPO3 Backend Setup

### 1. Extension Installation

Beide Extensions sind bereits in `/packages` installiert:
- `packages/pixelcoda_content_gsap_animation/`
- `packages/typo3_fe_editing/packages/pixelcoda_fe_editor/`

### 2. TypoScript Include

Die Headless-Konfiguration wird automatisch geladen:

**GSAP Animation:**
```typoscript
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:content_gsap_animation/Configuration/TypoScript/Headless/setup.typoscript">
```

**Frontend Editing:**
```typoscript
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:pixelcoda_fe_editor/Configuration/TypoScript/setup.typoscript">
```

### 3. JSON API Output

Jedes Content-Element erhält in der JSON-API:

**Animation Daten (`_gsap`):**
```json
{
  "content": {
    "_gsap": {
      "animation": "fade-up",
      "duration": 1000,
      "delay": 0,
      "easing": "power2.out",
      "offset": 50,
      "anchorPlacement": "top-bottom",
      "once": true,
      "mirror": false
    }
  }
}
```

**Frontend Editing Metadata (`_pixelcoda`):**
```json
{
  "content": {
    "_pixelcoda": {
      "uid": 123,
      "ctype": "text",
      "pid": 1,
      "backendEditUrl": "/typo3/record/edit?edit[tt_content][123]=edit",
      "responsive": {
        "mobile": 12,
        "tablet": 12,
        "desktop": 12
      },
      "container": false
    }
  }
}
```

## Next.js Frontend Setup

### 1. Abhängigkeiten

GSAP ist bereits in `frontend/package.json`:
```json
{
  "dependencies": {
    "gsap": "^3.12.5"
  }
}
```

### 2. Components

**GsapAnimatedContent Component:**
`frontend/src/components/GsapAnimatedContent.jsx`
- Verarbeitet GSAP Animation Settings
- Registriert ScrollTrigger
- Unterstützt alle Standard-Animationen

**ContentElement Wrapper:**
`frontend/src/components/ContentElement.jsx`
- Kombiniert GSAP Animationen + Editing Metadata
- Fügt `data-t3-*` Attribute hinzu
- Wrapper für alle Content Elements

### 3. Verwendung

```jsx
import { ContentElement } from '@/components/ContentElement';

export default function TextElement({ content }) {
  return (
    <ContentElement content={content}>
      <article>
        <h2>{content.header}</h2>
        <div dangerouslySetInnerHTML={{ __html: content.bodytext }} />
      </article>
    </ContentElement>
  );
}
```

## Lokal Testen (DDEV)

```bash
# TYPO3 Backend
ddev start
ddev composer install

# Cache leeren
ddev exec vendor/bin/typo3 cache:flush

# Frontend
cd frontend
yarn install
yarn dev
```

Zugriff:
- Backend: https://api.typo3-inst.localhost/typo3
- Frontend: https://typo3-inst.localhost:3000
- API: https://api.typo3-inst.localhost/?type=834

## Railway Deployment

### TYPO3 Backend

1. Environment Variables setzen:
```
TYPO3_INSTALL_DB_HOST=...
TYPO3_INSTALL_DB_NAME=...
TYPO3_INSTALL_DB_USER=...
TYPO3_INSTALL_DB_PASSWORD=...
```

2. Deploy aus Repository Root:
```bash
railway up
```

### Next.js Frontend

1. Environment Variables in Railway:
```
NEXT_PUBLIC_API_BASE_URL=https://api.your-domain.com
NEXT_PUBLIC_TYPO3_BASE_URL=https://api.your-domain.com
NEXT_PUBLIC_BASE_URL=https://your-domain.com
NEXT_PUBLIC_FRONTEND_FILE_API=/headless/fileadmin
NEXT_PUBLIC_SKIN=premium
NEXT_PUBLIC_HEADLESS_DEVTOOLS=true
```

2. Deploy aus `frontend/` Verzeichnis:
```bash
cd frontend
railway up
```

## Unterstützte Animationen

- `fade` - Einfaches Einblenden
- `fade-up` / `fade-down` - Einblenden mit vertikaler Bewegung
- `fade-left` / `fade-right` - Einblenden mit horizontaler Bewegung
- `zoom-in` / `zoom-out` - Skalierungs-Animationen
- `flip-left` / `flip-right` / `flip-up` / `flip-down` - 3D Flip-Animationen
- `slide-up` / `slide-down` / `slide-left` / `slide-right` - Slide-Animationen

## Debugging

### DevTools aktivieren

```env
NEXT_PUBLIC_HEADLESS_DEVTOOLS=true
```

Öffnen mit:
- macOS: `CMD + SHIFT + H`
- Windows/Linux: `CTRL + SHIFT + H`

### ScrollTrigger Markers

Im Development-Modus werden automatisch ScrollTrigger Markers angezeigt.

## Sicherheit

### Frontend Editing Metadata

- `backendEditUrl` wird nur für eingeloggte Backend-User oder im Development-Context exponiert
- `pid` wird nur exponiert wenn explizit aktiviert oder Backend-User eingeloggt
- Kontrolle über Extension Configuration

### CORS

Für Railway-Deployment CORS-Header in TYPO3 konfigurieren:

```php
// config/system/additional.php
$GLOBALS['TYPO3_CONF_VARS']['FE']['additionalHeaders'] = [
    'Access-Control-Allow-Origin' => 'https://your-frontend-domain.com',
    'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
    'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
];
```

## Fehlerbehebung

### Animationen funktionieren nicht

1. GSAP installiert prüfen: `yarn list gsap`
2. Browser-Console auf Fehler prüfen
3. ScrollTrigger Plugin importiert: `import ScrollTrigger from 'gsap/ScrollTrigger'`

### Editing Metadata fehlt

1. Backend-User eingeloggt?
2. Extension Configuration prüfen
3. JSON API Response prüfen: `?type=834`

### Railway Build Fehler

1. Node Version korrekt: `>=22.0.0`
2. `yarn build` lokal testen
3. Railway Logs prüfen: `railway logs`
