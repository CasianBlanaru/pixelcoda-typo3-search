# pixelcoda Search Extension - TYPO3 12 Installation

## 🎯 Übersicht

Das `pixelcoda_search` Plugin ist eine KI-gestützte Suchplattform für TYPO3 12. Es unterstützt sowohl headless (JSON:API) als auch klassische (Fluid) Modi mit Vektorsuche, RAG-Antworten und Echtzeit-Indexierung.

## 📋 Voraussetzungen

- TYPO3 12.4.x
- PHP 8.1 - 8.3
- DDEV Entwicklungsumgebung
- pixelcoda Search API (läuft auf Port 8787)

## 🚀 Installation

### Schritt 1: Extension-Dateien überprüfen

Die Extension befindet sich bereits in:
```
public/typo3conf/ext/pixelcoda_search/
```

### Schritt 2: TYPO3 Backend öffnen

1. Öffne das TYPO3 Backend: http://pixelcoda-typo3-dev.ddev.site:8080/typo3
2. Login: admin / admin

### Schritt 3: Extension über Backend aktivieren

1. Gehe zu **Admin Tools > Extensions**
2. Wechsle zum Tab **Installed Extensions**
3. Suche nach "pixelcoda_search"
4. Falls nicht sichtbar, klicke auf **Scan for new extensions**
5. Aktiviere die Extension durch Klick auf das **+** Symbol

### Schritt 4: TypoScript Template einrichten

1. Gehe zu **Web > Template**
2. Wähle deine Root-Seite (normalerweise "Home" oder "Root")
3. Klicke auf **Edit the whole template record**
4. Im Tab **Includes** füge hinzu: **pixelcoda Search (pixelcoda_search)**

### Schritt 5: Plugin zu einer Seite hinzufügen

1. Gehe zu **Web > Page**
2. Wähle eine Seite aus
3. Klicke auf **+ Content** 
4. Wähle **Plugins > General Plugin**
5. Im Tab **Plugin** wähle: **pixelcoda Search**

### Schritt 6: API-Konfiguration

Die Extension ist bereits vorkonfiguriert mit folgenden Einstellungen:

```php
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pixelcoda_search'] = [
    'api_url' => 'http://host.docker.internal:8787',
    'api_key' => 'pc_write_dev_key',
    'hmac_secret' => 'dev_hmac_secret_key',
    'project_id' => 'typo3-dev',
    'enabled_tables' => ['pages', 'tt_content', 'tx_news_domain_model_news'],
    'default_mode' => 'classic',
    'enable_auto_index' => true,
    'enable_vector_search' => true,
    'debug_mode' => true
];
```

## 🔧 Manuelle Aktivierung (Falls Backend-Aktivierung fehlschlägt)

Falls die Extension im Extension Manager nicht erscheint, führe folgende Schritte aus:

### Via CLI:

```bash
# In das TYPO3-Verzeichnis wechseln
cd typo3-dev/

# PackageStates.php löschen (wird automatisch neu generiert)
rm -f public/typo3conf/PackageStates.php

# Caches leeren
ddev typo3 cache:flush

# Extension Setup ausführen
ddev typo3 extension:setup

# Autoload neu generieren
ddev composer dump-autoload
```

### Manuelle PackageStates.php Bearbeitung:

Falls nötig, füge folgende Zeile zur `public/typo3conf/PackageStates.php` hinzu:

```php
'pixelcoda_search' => [
    'packagePath' => 'typo3conf/ext/pixelcoda_search/',
],
```

## 🎨 Frontend-Ausgabe

Nach erfolgreicher Installation zeigt das Plugin:

- ✅ Plugin loaded successfully!
- API Status: Connected/Offline
- Suchformular mit Live-Suche
- Suchergebnisse mit Titel und Zusammenfassung

## 🔍 Fehlerbehebung

### Extension wird nicht erkannt:

1. Überprüfe, ob alle Dateien in `public/typo3conf/ext/pixelcoda_search/` vorhanden sind
2. Führe `ddev typo3 cache:flush` aus
3. Überprüfe die `PackageStates.php` Datei
4. Regeneriere Autoload: `ddev composer dump-autoload`

### API-Verbindung fehlschlägt:

1. Stelle sicher, dass die pixelcoda Search API läuft:
   ```bash
   curl http://localhost:8787/health
   ```
2. Überprüfe die API-Konfiguration in der Extension
3. Prüfe die Netzwerk-Verbindung zwischen TYPO3 und API

### Plugin zeigt nicht an:

1. Überprüfe, ob das TypoScript Template korrekt eingebunden ist
2. Leere alle Caches: `ddev typo3 cache:flush`
3. Überprüfe die Plugin-Konfiguration auf der Seite

## 📚 Weitere Konfiguration

### Headless-Modus aktivieren:

```typoscript
plugin.tx_pixelcodasearch_search.settings.mode = headless
```

### Suchkollektionen anpassen:

```typoscript
plugin.tx_pixelcodasearch_search.settings.collections = pages,tt_content,news
```

### Debug-Modus deaktivieren:

```typoscript
plugin.tx_pixelcodasearch_search.settings.showDebug = 0
```

## 🎉 Erfolg!

Nach erfolgreicher Installation solltest du:

1. Das Plugin im Extension Manager sehen
2. Das Plugin zu Seiten hinzufügen können  
3. Eine funktionierende Suchoberfläche im Frontend haben
4. API-Verbindung zum pixelcoda Search Service

## 📞 Support

Bei Problemen:
- Überprüfe die TYPO3 Logs: `var/log/typo3_*.log`
- Aktiviere den Debug-Modus für detaillierte Fehlermeldungen
- Kontaktiere pixelcoda Support: dev@pixelcoda.com
