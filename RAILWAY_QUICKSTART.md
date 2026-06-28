# Frontend Editing - Railway Quick Start

## ✅ Installation Complete

Die TYPO3 Frontend Editing Extension ist vollständig installiert und konfiguriert.

## 🚀 Railway Deployment

Der Code wurde erfolgreich gepusht. Railway wird automatisch deployen:

- **Backend**: https://web-production-581b4.up.railway.app/
- **Frontend**: https://nextjs-front-end-for-typo3-headless-production.up.railway.app/

## 📋 Nächste Schritte

### 1. Railway Environment Variablen setzen

#### Backend Service
```
TYPO3_FE_EDITING_ENABLED=1
OPENAI_API_KEY=sk-...  (optional für AI)
OPENAI_MODEL=gpt-4.1-mini  (optional)
```

#### Frontend Service
```
NEXT_PUBLIC_API_BASE_URL=https://web-production-581b4.up.railway.app
NEXT_PUBLIC_TYPO3_BASE_URL=https://web-production-581b4.up.railway.app
NEXT_PUBLIC_BASE_URL=https://nextjs-front-end-for-typo3-headless-production.up.railway.app
```

### 2. Extension aktivieren (einmalig)

Nach Railway Deployment Backend-Terminal öffnen:

```bash
# In Railway Backend Service -> Terminal
php vendor/bin/typo3 extension:setup
php vendor/bin/typo3 cache:flush
```

### 3. Testen

1. Backend Login: https://web-production-581b4.up.railway.app/typo3
2. Frontend öffnen: https://nextjs-front-end-for-typo3-headless-production.up.railway.app/
3. Toolbar sollte erscheinen (rechts unten)

## 🎯 Features

✅ **Inline Editing** - Text direkt bearbeiten
✅ **Drag & Drop** - Content Element verschieben
✅ **Bild Editor** - Contextual Editor öffnen
✅ **AI Assistent** - Optional mit OpenAI/Claude/Mistral
✅ **Headless** - Funktioniert mit JSON API
✅ **Barrierefrei** - WCAG 2.1 AA konform

## 📝 Verwendung im Code

### Beispiel Content Element

```jsx
import { ContentElement, EditableHeadline, EditableBodytext } from '@/components/EditableContent';

export default function Article({ content }) {
  return (
    <ContentElement uid={content.id} type={content.type}>
      <EditableHeadline uid={content.id} level="h2">
        {content.header}
      </EditableHeadline>
      
      <EditableBodytext uid={content.id}>
        {content.bodytext}
      </EditableBodytext>
    </ContentElement>
  );
}
```

## 🔧 Troubleshooting

### Toolbar nicht sichtbar
- TYPO3 Backend Login prüfen
- User Permissions für `tt_content` prüfen
- Caches leeren: `php vendor/bin/typo3 cache:flush`

### CORS Fehler
- Frontend URL in TypoScript korrekt?
- Railway Environment Variables gesetzt?

### Änderungen werden nicht gespeichert
- Content kommt aus Datenbank (nicht hardcoded)?
- User hat `tables_modify` für `tt_content`?

## 📚 Dokumentation

- **Setup Guide**: `FRONTEND_EDITING_SETUP.md`
- **Usage Examples**: `frontend/FRONTEND_EDITING.md`
- **Extension README**: `packages/typo3_fe_editing/packages/pixelcoda_fe_editor/README.md`

## ✨ Lokal testen

```bash
# DDEV starten
ddev start

# Caches leeren
ddev exec vendor/bin/typo3 cache:flush

# Frontend öffnen
open https://typo3-inst.localhost

# Backend Login
open https://typo3-inst.localhost/typo3
```

## 🔐 Sicherheit

✅ Alle Schreibvorgänge via TYPO3 DataHandler
✅ CSRF Schutz via TYPO3 FormProtection
✅ AI Keys nur serverseitig
✅ Authentifizierter Backend User erforderlich
✅ TYPO3 User Permissions werden respektiert

---

**Status**: ✅ Bereit für Railway Deployment
**Commit**: e37b2b23
**Branch**: main
