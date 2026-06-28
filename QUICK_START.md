# 🚀 Quick Start - Lokales Testing

## Problem: "CSS wird nicht geladen" / "Toolbar erscheint nicht"

**Das ist normal!** Die Toolbar erscheint **nur** wenn Sie im TYPO3 Backend eingeloggt sind.

## ✅ Korrekter lokaler Test-Ablauf

### Schritt 1: TYPO3 Backend öffnen

```bash
open "https://typo3-inst.localhost/typo3"
```

**Login mit einem Backend-User** (z.B. `admin`, `pixelcoda`)

> ⚠️ **WICHTIG**: Lassen Sie dieses Tab geöffnet!

### Schritt 2: Frontend im **gleichen Browser** öffnen

```bash
open "http://localhost:3000"
```

> ⚠️ **WICHTIG**: Muss im **gleichen Browser** sein wegen Cookie!

### Schritt 3: Was Sie jetzt sehen sollten

✅ **Toolbar am oberen Rand** mit:
- Edit Button
- Save Button
- AI Button
- Add Button (+)

✅ **Status-Text**: "Editieren aktivieren"

## 🧪 Test im Browser Console (F12)

Wenn die Toolbar nicht erscheint, prüfen Sie:

```javascript
// 1. Backend-Cookie vorhanden?
document.cookie.includes('be_typo_user')
// MUSS true sein!

// 2. Wenn false: Sie sind nicht im Backend eingeloggt!
// → Gehen Sie zurück zu Schritt 1

// 3. Wenn true aber keine Toolbar:
console.log(window.location.hostname)
// Frontend MUSS auf localhost laufen
// Backend MUSS typo3-inst.localhost sein
```

## 🔄 Railway Deployment Status

Railway baut gerade neu:
- Commit: `92f7ab11`
- Status: Building...
- Cached `fe-test` Verzeichnis wird gecleaned

Der Build wird ca. 2-3 Minuten dauern.

## ❓ FAQ

### Q: "Warum wird das CSS nicht geladen?"
**A**: Weil Sie nicht im Backend eingeloggt sind. Die Komponente lädt CSS nur wenn `be_typo_user` Cookie vorhanden ist.

### Q: "Ich sehe keine Toolbar"
**A**: 
1. Sind Sie im Backend eingeloggt? → Check `document.cookie`
2. Sind beide Tabs im gleichen Browser?
3. Browser-Cache geleert? → Strg+Shift+R

### Q: "Railway Build schlägt fehl mit fe-test error"
**A**: Railway nutzt gecachten Build. Ich habe gerade einen leeren Commit gepusht um den Cache zu clearen. Warten Sie 2-3 Minuten bis Build fertig ist.

### Q: "Wie teste ich ob alles funktioniert?"
**A**: 
1. Backend Login → Cookie wird gesetzt
2. Frontend aufrufen → Toolbar erscheint
3. "Edit" klicken → Felder werden editierbar
4. Text ändern → Automatisches Speichern nach 1,5s
5. F5 drücken → Änderung ist persistent

## 📋 Nächste Schritte

1. ✅ Lokal testen (Backend Login + Frontend öffnen)
2. ⏳ Railway Build abwarten (2-3 Minuten)
3. ✅ Railway testen (siehe `JETZT_TESTEN.md`)

---

**Wichtigste Regel**: 
> Ohne Backend-Login → Kein Cookie → Keine Toolbar → Das ist **by design**!
