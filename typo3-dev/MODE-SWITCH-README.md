# TYPO3 Mode Switch - Anleitung

## 🔄 Übersicht

Dieses TYPO3 System unterstützt zwei Rendering-Modi:

1. **Headless Mode** - JSON-Ausgabe für moderne JavaScript-Frontends (React, Vue, Next.js)
2. **Standard Mode** - Traditionelle HTML-Ausgabe mit Fluid Templates

## 📋 Voraussetzungen

- DDEV installiert und konfiguriert
- TYPO3 12.4 läuft unter https://pixelcoda-typo3-dev.ddev.site/
- Zugriff auf die Datenbank

## 🚀 Mode wechseln

### Automatischer Wechsel (Empfohlen)

```bash
# Zum Projektverzeichnis wechseln
cd typo3-dev

# Script ausführen
./complete-mode-switch.sh

# Option 1 wählen für Headless Mode (JSON)
# Option 2 wählen für Standard Mode (HTML)
```

### Schnellwechsel ohne Interaktion

```bash
# Zu Headless Mode wechseln
cd typo3-dev
echo "1" | ./complete-mode-switch.sh

# Zu Standard Mode wechseln
cd typo3-dev
echo "2" | ./complete-mode-switch.sh
```

## 📊 Modi im Detail

### Headless Mode (JSON)

**Aktivierung:**
```bash
echo "1" | ./complete-mode-switch.sh
```

**Was passiert:**
- Headless Extension wird aktiviert
- Templates werden auf JSON-Ausgabe konfiguriert
- TYPO3 gibt strukturierte JSON-Daten aus

**Testen:**
```bash
# JSON-Ausgabe prüfen
curl https://pixelcoda-typo3-dev.ddev.site/ | jq

# Oder im Browser öffnen (zeigt JSON)
open https://pixelcoda-typo3-dev.ddev.site/
```

**Verwendung:**
- Für React/Vue/Next.js Frontends
- API-basierte Anwendungen
- Headless CMS Szenarien

### Standard Mode (HTML)

**Aktivierung:**
```bash
echo "2" | ./complete-mode-switch.sh
```

**Was passiert:**
- Headless Extension wird deaktiviert
- Fluid Templates werden aktiviert
- TYPO3 gibt normales HTML aus

**Testen:**
```bash
# HTML-Ausgabe prüfen
curl https://pixelcoda-typo3-dev.ddev.site/ | head -20

# Im Browser öffnen (zeigt HTML-Seite)
open https://pixelcoda-typo3-dev.ddev.site/
```

**Verwendung:**
- Traditionelle Websites
- SEO-optimierte Seiten
- Server-side Rendering

## 🛠️ Manuelle Konfiguration

### Aktuellen Mode prüfen

```bash
# In config.yaml nachschauen
grep "renderingMode:" config/sites/main/config.yaml
```

### Cache leeren

```bash
# TYPO3 Cache komplett leeren
cd typo3-dev
rm -rf var/cache/*
ddev exec typo3 cache:flush
```

### DDEV neu starten

```bash
cd typo3-dev
ddev restart
```

## 📁 Wichtige Dateien

| Datei | Beschreibung |
|-------|-------------|
| `complete-mode-switch.sh` | Haupt-Script zum Mode-Wechsel |
| `config/sites/main/config.yaml` | Site-Konfiguration mit renderingMode |
| `config/system/PackageStates.php.headless` | Extension-Status für Headless Mode |
| `config/system/PackageStates.php.standard` | Extension-Status für Standard Mode |

## 🔍 Debugging

### Problem: Es wird immer JSON ausgegeben

```bash
# Lösung: Standard Mode erzwingen
echo "2" | ./complete-mode-switch.sh
```

### Problem: Es wird immer HTML ausgegeben

```bash
# Lösung: Headless Mode erzwingen
echo "1" | ./complete-mode-switch.sh
```

### Problem: Änderungen werden nicht übernommen

```bash
# Komplette Bereinigung
cd typo3-dev
rm -rf var/cache/* var/log/*
ddev restart
./complete-mode-switch.sh
```

## 🎯 Schnellreferenz

```bash
# Headless Mode (JSON) aktivieren
cd typo3-dev && echo "1" | ./complete-mode-switch.sh

# Standard Mode (HTML) aktivieren
cd typo3-dev && echo "2" | ./complete-mode-switch.sh

# Status prüfen
grep "renderingMode:" config/sites/main/config.yaml

# Cache leeren
rm -rf var/cache/* && ddev exec typo3 cache:flush

# DDEV neu starten
ddev restart
```

## 📝 Hinweise

- Das Script startet DDEV automatisch neu für einen sauberen Zustand
- Änderungen sind sofort nach dem Script-Durchlauf aktiv
- Backend-Login bleibt in beiden Modi gleich
- Die Datenbank wird automatisch angepasst

## ⚠️ Wichtig

- **Nicht** manuell in den Templates im Backend ändern
- **Immer** das Script verwenden für konsistente Konfiguration
- Nach größeren Änderungen DDEV neu starten

## 🆘 Support

Bei Problemen:
1. Cache leeren
2. DDEV neu starten
3. Script erneut ausführen
4. Browser-Cache leeren (Cmd+Shift+R)

---

*Erstellt für das pixelcoda-headless-search-starter Projekt*
