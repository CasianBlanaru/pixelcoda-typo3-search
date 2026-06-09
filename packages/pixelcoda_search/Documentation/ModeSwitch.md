# pixelcoda Search - Mode Switch Funktionalität

## Übersicht

Das pixelcoda Search Plugin bietet jetzt eine integrierte Mode-Switch Funktionalität, die es ermöglicht, zwischen Headless und Standard Modus zu wechseln, ohne externe Shell-Scripts verwenden zu müssen.

## 🎯 Verfügbare Modi

### 📱 Headless Modus
- **JSON API Output** für moderne JavaScript-Frontends
- **React/Vue/Next.js kompatibel**
- **Bessere Performance** durch API-basierte Architektur
- **Flexible Frontend-Entwicklung**

### 📄 Standard Modus  
- **HTML Template Output** mit Fluid Templates
- **Traditionelles TYPO3** Rendering
- **SEO-optimiert** out-of-the-box
- **Einfache Integration** ohne zusätzliche Frontend-Entwicklung

## 🔧 Verwendung

### Backend-Modul

1. **Navigiere zu Tools → pixelcoda Search** im TYPO3 Backend
2. **Aktueller Status** wird angezeigt:
   - TYPO3 Rendering Modus
   - Plugin Modus
   - Synchronisationsstatus
3. **Modus wechseln**:
   - Wähle den gewünschten Modus aus der Dropdown-Liste
   - Klicke auf "🔄 Modus wechseln"
   - Bestätige die Aktion

### CLI-Kommando

```bash
# Interaktiver Modus-Wechsel
ddev exec typo3 pixelcoda:search:switch-mode

# Direkter Wechsel zu Headless
ddev exec typo3 pixelcoda:search:switch-mode headless

# Direkter Wechsel zu Standard
ddev exec typo3 pixelcoda:search:switch-mode standard

# Mit Force-Flag (ohne Bestätigung)
ddev exec typo3 pixelcoda:search:switch-mode headless --force
```

## ⚙️ Was passiert beim Modus-Wechsel?

### 1. Site Configuration Update
```yaml
# config/sites/main/config.yaml
renderingMode: headless  # oder standard
```

### 2. Plugin Configuration Update
```php
// LocalConfiguration.php
'EXTENSIONS' => [
    'pixelcoda_search' => [
        'default_mode' => 'headless', // oder classic
        // ...
    ]
]
```

### 3. PackageStates.php Switch
Falls vorhanden, werden die entsprechenden PackageStates.php Dateien verwendet:
- `config/system/PackageStates.php.headless`
- `config/system/PackageStates.php.standard`

### 4. Cache Clearing
- Alle TYPO3 Caches werden geleert
- `var/cache/*` wird gelöscht
- `typo3temp/var/*` wird gelöscht

## 🔍 System Status

Das Backend-Modul zeigt folgende Statusinformationen:

- **API Konfiguration**: ✓ Konfiguriert / ✗ Nicht konfiguriert
- **Headless Extension**: ✓ Geladen / - Nicht geladen
- **Cache Status**: Leer / Gefüllt
- **Modi Synchronisation**: ✓ Synchron / ⚠️ Nicht synchron

## 🚀 Schnellaktionen

### Backend-Modul
- **🧹 Cache leeren**: Alle Caches sofort leeren
- **🔗 API-Verbindung testen**: pixelcoda Search API testen

### CLI-Befehle
```bash
# Cache leeren
ddev exec typo3 cache:flush

# API-Verbindung testen
curl -H "Authorization: Bearer YOUR_API_KEY" \
     http://localhost:8787/v1/health

# Content indexieren
ddev exec typo3 pixelcoda:search:index
```

## 🔧 Konfiguration

### Extension Configuration
```php
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pixelcoda_search'] = [
    'api_url' => 'http://host.docker.internal:8787',
    'api_key' => 'pc_write_dev_key',
    'project_id' => 'typo3-dev',
    'default_mode' => 'classic', // classic|headless
    // ...
];
```

### TypoScript Constants
```typoscript
plugin.tx_pixelcodasearch_search {
    settings {
        mode = headless
        # oder
        mode = classic
    }
}
```

## 🐛 Troubleshooting

### Modi nicht synchronisiert
**Problem**: TYPO3 und Plugin verwenden unterschiedliche Modi

**Lösung**: 
1. Backend-Modul öffnen
2. Gewünschten Modus auswählen und wechseln
3. Cache leeren

### API-Verbindung fehlgeschlagen
**Problem**: pixelcoda Search API nicht erreichbar

**Lösung**:
1. API-URL und API-Key prüfen
2. pixelcoda Search Service starten: `docker-compose up -d`
3. Netzwerk-Konnektivität prüfen

### PackageStates.php Fehler
**Problem**: Extension-Aktivierung schlägt fehl

**Lösung**:
1. `config/system/PackageStates.php.headless` und `.standard` erstellen
2. Oder manuell Extensions aktivieren/deaktivieren

## 📚 Weitere Dokumentation

- [DualMode.md](DualMode.md) - Detaillierte Dual-Mode Dokumentation
- [README.md](../README.md) - Allgemeine Plugin-Dokumentation
- [TYPO3 Headless Documentation](https://docs.typo3.org/p/friendsoftypo3/headless/main/en-us/)

## 🔄 Migration von Shell-Script

Falls du bisher das `switch-typo3-mode.sh` Script verwendet hast:

### Vorher (Shell-Script)
```bash
cd typo3-dev
./switch-typo3-mode.sh
# Auswahl: 1 für Headless, 2 für Standard
```

### Nachher (CLI-Kommando)
```bash
cd typo3-dev
ddev exec typo3 pixelcoda:search:switch-mode
# Oder direkt: ddev exec typo3 pixelcoda:search:switch-mode headless
```

### Nachher (Backend-Modul)
1. TYPO3 Backend öffnen
2. Tools → pixelcoda Search
3. Modus auswählen und wechseln

Die neue Lösung bietet die gleiche Funktionalität, ist aber besser in TYPO3 integriert und bietet zusätzliche Features wie Statusanzeige und API-Tests.
