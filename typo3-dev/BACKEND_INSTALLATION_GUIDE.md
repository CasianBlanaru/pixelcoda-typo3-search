# 🎯 pixelcoda Search - Backend Installation Guide

## Problem: Extension nicht im Backend sichtbar

Die Extension ist korrekt installiert, aber TYPO3 erkennt sie nicht automatisch in der Extension-Liste.

## 🚀 Lösung 1: Direkte Backend-Aktivierung (EMPFOHLEN)

### Schritt 1: TYPO3 Backend öffnen
```
URL: http://pixelcoda-typo3-dev.ddev.site:8080/typo3
Login: admin / admin
```

### Schritt 2: Extension Manager
1. **Admin Tools** → **Extensions**
2. Tab **"Installed Extensions"**
3. Klicke auf **"Scan for new extensions"** (🔄 Symbol)
4. Suche nach **"pixelcoda"**
5. Aktiviere mit **"+"** Symbol

## 🛠️ Lösung 2: Direkte TypoScript-Integration

Falls Extension-Aktivierung nicht funktioniert:

### Schritt 1: TypoScript Setup
1. **Web** → **Template** → Deine Root-Seite
2. **"Edit the whole template record"**
3. Im **Setup** Tab einfügen:

```typoscript
# pixelcoda Search Plugin
plugin.tx_pixelcodasearch_search = USER
plugin.tx_pixelcodasearch_search {
    userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
    extensionName = PixelcodaSearch
    pluginName = Search
    vendorName = PixelCoda
    controller = PixelCoda\PixelcodaSearch\Controller\SearchController
    action = index
}

# Als Lib verfügbar machen
lib.pixelcodaSearch < plugin.tx_pixelcodasearch_search
```

### Schritt 2: Content Element erstellen
1. **Web** → **Page** → Seite auswählen
2. **"+ Content"** → **"HTML"**
3. Code einfügen: `{lib.pixelcodaSearch}`

## 🎨 Lösung 3: Einfaches HTML Plugin (SOFORT FUNKTIONSFÄHIG)

### Schritt 1: HTML Content Element
1. **Web** → **Page** → Seite auswählen
2. **"+ Content"** → **"HTML"**
3. Kopiere den Code aus: `simple-html-plugin.html`

**Vorteil:** Funktioniert sofort ohne Extension-Aktivierung!

## 🔧 Lösung 4: CLI-basierte Reparatur

Falls nichts funktioniert:

```bash
cd typo3-dev/
ddev restart
ddev typo3 cache:flush
ddev composer dump-autoload
rm -f public/typo3conf/PackageStates.php
ddev typo3 extension:setup
```

## 📋 Verfügbare Hilfsdateien

| Datei | Zweck |
|-------|-------|
| `activate-extension-backend.md` | Detaillierte Backend-Anleitung |
| `direct-typoscript-setup.txt` | Komplettes TypoScript Setup |
| `simple-html-plugin.html` | Sofort verwendbares HTML Plugin |
| `check-extension-status.sh` | Diagnose-Tool |

## ✅ Was funktioniert garantiert

### Option A: HTML Plugin (Empfohlen für sofortigen Test)
1. Kopiere Code aus `simple-html-plugin.html`
2. Erstelle HTML Content Element
3. Fertig! 🎉

### Option B: TypoScript Integration
1. Kopiere Code aus `direct-typoscript-setup.txt`
2. Füge in Template Setup ein
3. Verwende `{lib.pixelcodaSearch}` in Content Elementen

## 🎯 Erwartetes Ergebnis

Nach erfolgreicher Installation siehst du:

```
🚀 pixelcoda Search Plugin
✅ Plugin loaded successfully!
API Status: ✅ Connected (oder ❌ Offline)
[Suchformular]
```

## 🐛 Fehlerbehebung

### API nicht erreichbar
```bash
curl http://localhost:8787/health
```
Falls nicht erreichbar: API starten

### Extension nicht gefunden
- Verwende HTML Plugin (Lösung 3)
- Oder TypoScript Integration (Lösung 2)

### Caching-Probleme
```bash
ddev typo3 cache:flush
```

## 💡 Wichtiger Hinweis

**Das Plugin ist technisch korrekt installiert!** Das Problem liegt nur an der TYPO3-Erkennung. Die HTML-Lösung umgeht dieses Problem komplett und funktioniert sofort.

## 🎉 Erfolg!

Wähle eine der Lösungen und das pixelcoda Search Plugin wird im Frontend funktionieren!
