# pixelcoda_search Extension - Backend Aktivierung

## Problem: Extension wird nicht in der CLI-Liste angezeigt

Die Extension ist korrekt installiert, aber wird nicht automatisch von TYPO3 erkannt. Hier ist die manuelle Aktivierung über das Backend:

## 🔧 Lösung: Manuelle Backend-Aktivierung

### Schritt 1: TYPO3 Backend öffnen
1. Öffne: **http://pixelcoda-typo3-dev.ddev.site:8080/typo3**
2. Login: **admin / admin**

### Schritt 2: Extension Manager öffnen
1. Gehe zu **Admin Tools**
2. Klicke auf **Extensions**

### Schritt 3: Extension scannen
1. Klicke auf den Tab **"Installed Extensions"**
2. Klicke auf **"Scan for new extensions"** (Reload-Symbol oben rechts)
3. Oder klicke auf **"Get Extensions"** und dann **"Update from TER"**

### Schritt 4: pixelcoda_search suchen und aktivieren
1. Suche nach **"pixelcoda"** oder **"search"**
2. Die Extension sollte jetzt in der Liste erscheinen
3. Klicke auf das **"+"** Symbol um sie zu aktivieren

### Schritt 5: Wenn Extension nicht gefunden wird
Falls die Extension immer noch nicht erscheint:

1. **Gehe zu "Get Extensions" Tab**
2. **Klicke auf "Upload Extension .zip file"**
3. **Oder**: Gehe zu **Settings > Extension Configuration**
4. **Aktiviere "allowGlobalInstall"** falls verfügbar

## 🎯 Alternative: Direkte Plugin-Nutzung

Falls die Extension-Aktivierung nicht funktioniert, können wir das Plugin direkt nutzen:

### Schritt 1: TypoScript manuell hinzufügen
1. Gehe zu **Web > Template**
2. Wähle deine Root-Seite
3. Klicke auf **"Edit the whole template record"**
4. Füge im **Setup** Feld hinzu:

```typoscript
# pixelcoda Search Plugin direkt einbinden
plugin.tx_pixelcodasearch_search = USER
plugin.tx_pixelcodasearch_search {
    userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
    extensionName = PixelcodaSearch
    pluginName = Search
    vendorName = PixelCoda
    controller = PixelCoda\PixelcodaSearch\Controller\SearchController
    action = index
}

# Plugin als Content Element verfügbar machen
tt_content.pixelcoda_search = < plugin.tx_pixelcodasearch_search

# CSS und JavaScript einbinden
page {
    includeCSS.pixelcodaSearch = EXT:pixelcoda_search/Resources/Public/Css/search.css
    includeJSFooter.pixelcodaSearch = EXT:pixelcoda_search/Resources/Public/JavaScript/search.js
}
```

### Schritt 2: Content Element hinzufügen
1. Gehe zu **Web > Page**
2. Wähle eine Seite
3. Klicke auf **+ Content**
4. Wähle **"HTML"** oder **"Plain HTML"**
5. Füge folgenden Code ein:

```html
<div class="pixelcoda-search-wrapper">
    <f:cObject typoscriptObjectPath="plugin.tx_pixelcodasearch_search" />
</div>
```

## 🚀 Test der Funktionalität

Nach der Aktivierung solltest du sehen:
- ✅ Plugin loaded successfully!
- API Status: Connected ✅ oder Offline ❌
- Suchformular
- Funktionsfähige Suche

## 📞 Wenn nichts funktioniert

Falls alle Schritte fehlschlagen, führe folgendes aus:

```bash
cd typo3-dev/
ddev restart
ddev typo3 cache:flush
ddev composer dump-autoload
```

Dann versuche es erneut im Backend.

## 💡 Wichtiger Hinweis

Das Plugin ist technisch korrekt installiert. Das Problem liegt nur an der TYPO3-Erkennung. Im Backend sollte es definitiv funktionieren!
