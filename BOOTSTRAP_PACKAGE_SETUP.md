# Bootstrap Package Installation und Konfiguration

## Übersicht

Das [TYPO3 Bootstrap Package](https://www.bootstrap-package.com/) wurde erfolgreich in Ihr pixelcoda TYPO3-Projekt integriert. Diese Dokumentation beschreibt die durchgeführten Änderungen und wie Sie das System verwenden können.

## Was wurde installiert

### 1. Composer-Abhängigkeiten
- **bk2k/bootstrap-package**: Version ^14.0 - Das Haupt-Bootstrap-Package
- **scssphp/scssphp**: SCSS-Compiler für das Bootstrap Package
- **georgringer/news**: Aktualisiert auf Version ^12.0 für PHP 8.4-Kompatibilität

### 2. TYPO3-Extensions aktiviert
- `bootstrap_package` wurde in `PackageStates.php` aktiviert
- Abhängigkeiten im Site Package (`ext_emconf.php`) hinzugefügt

## Durchgeführte Konfigurationen

### 1. TypoScript-Konfiguration

#### Constants (`Configuration/TypoScript/constants.typoscript`)
```typoscript
plugin.bootstrap_package.settings {
    scss {
        primary = #577760
        secondary = #6c757d
        # ... weitere Farben
    }
    
    googleFont {
        font = Source Sans Pro
        weight = 300,400,600,700
    }
}
```

#### Setup (`Configuration/TypoScript/setup.typoscript`)
- Bootstrap Package wird im Standard-Modus importiert
- Headless-Modus bleibt für JSON-API erhalten
- Custom CSS wird eingebunden

### 2. Fluid-Templates

#### Layout (`Resources/Private/Layouts/Page/Default.html`)
- Bootstrap-kompatible HTML-Struktur
- Responsive Meta-Tags
- Accessibility-Features (Skip-Links)
- Semantic HTML5-Elemente

#### Template (`Resources/Private/Templates/Page/Default.html`)
- Bootstrap-Navigation mit Navbar
- Responsive Grid-Layout (8/4 Spalten)
- Integrierte Suchfunktion
- Sidebar mit Cards
- Footer mit Copyright

### 3. Styling

#### Custom CSS (`Resources/Public/Css/custom.css`)
- CSS-Custom-Properties für Farbschema
- Responsive Design-Anpassungen
- Print-Styles
- Accessibility-Verbesserungen
- Animationen

## Dual-Mode-Unterstützung

Das System unterstützt weiterhin beide Modi:

### Standard-Modus (HTML)
- Verwendet Bootstrap Package für moderne, responsive Layouts
- Vollständige Bootstrap 5-Komponenten verfügbar
- SEO-optimiert mit strukturierten Daten

### Headless-Modus (JSON)
- JSON-API für Frontend-Frameworks
- Kompatibel mit React, Vue, Angular, etc.
- Separate Content-Rendering für APIs

## Verwendung

### 1. TYPO3-Backend
1. Loggen Sie sich ins TYPO3-Backend ein
2. Gehen Sie zu "Template" → "TypoScript Template"
3. Wählen Sie Ihre Root-Seite
4. Das Bootstrap Package ist automatisch verfügbar

### 2. Frontend-Entwicklung
- Bootstrap 5-Klassen sind verfügbar
- Responsive Breakpoints: xs, sm, md, lg, xl, xxl
- Komponenten: Cards, Navbar, Forms, Buttons, etc.

### 3. Anpassungen

#### Farben ändern
Bearbeiten Sie `Configuration/TypoScript/constants.typoscript`:
```typoscript
plugin.bootstrap_package.settings.scss {
    primary = #IhreFarbe
}
```

#### Templates anpassen
- Layouts: `Resources/Private/Layouts/Page/`
- Templates: `Resources/Private/Templates/Page/`
- Partials: `Resources/Private/Partials/Page/`

#### CSS erweitern
Bearbeiten Sie `Resources/Public/Css/custom.css` für zusätzliche Styles.

## Verfügbare Features

### Bootstrap-Komponenten
- ✅ Responsive Grid-System
- ✅ Navigation (Navbar)
- ✅ Cards und Panels
- ✅ Forms und Input-Groups
- ✅ Buttons und Button-Groups
- ✅ Alerts und Badges
- ✅ Modal-Dialoge
- ✅ Carousel/Slider
- ✅ Accordion
- ✅ Tabs

### TYPO3-Integration
- ✅ Content-Elemente mit Bootstrap-Styling
- ✅ Responsive Images
- ✅ SEO-Optimierung
- ✅ Mehrsprachigkeit
- ✅ Backend-Layouts
- ✅ TypoScript-Konfiguration

### pixelcoda-Features
- ✅ Suchfunktion integriert
- ✅ Dual-Mode (HTML/JSON)
- ✅ API-Kompatibilität
- ✅ Custom Styling

## Nächste Schritte

1. **Logo hinzufügen**: Platzieren Sie Ihr Logo in `Resources/Public/Images/logo.png`
2. **Favicon erstellen**: Fügen Sie `favicon.ico` in `Resources/Public/Icons/` hinzu
3. **Content erstellen**: Nutzen Sie das TYPO3-Backend für Inhalte
4. **Styling anpassen**: Bearbeiten Sie die CSS-Dateien nach Ihren Wünschen
5. **Backend-Layouts**: Erstellen Sie zusätzliche Layouts in `Configuration/TSConfig/`

## Troubleshooting

### Cache leeren
```bash
cd typo3-dev
vendor/bin/typo3 cache:flush
```

### Extensions neu aktivieren
```bash
vendor/bin/typo3 extension:activate bootstrap_package
```

### Composer-Abhängigkeiten aktualisieren
```bash
composer update
```

## Weitere Ressourcen

- [Bootstrap Package Dokumentation](https://www.bootstrap-package.com/)
- [Bootstrap 5 Dokumentation](https://getbootstrap.com/docs/5.3/)
- [TYPO3 Dokumentation](https://docs.typo3.org/)
- [Fluid Template Engine](https://docs.typo3.org/other/typo3/view-helper-reference/main/en-us/)

## Support

Bei Fragen oder Problemen:
1. Prüfen Sie die TYPO3-Logs: `var/log/`
2. Aktivieren Sie den Debug-Modus in `config/system/settings.php`
3. Konsultieren Sie die offizielle Dokumentation
4. Nutzen Sie die TYPO3-Community-Foren

---

## 📚 Weitere Dokumentation

| Thema | Datei |
|-------|--------|
| Schnellstart & API | [QUICKSTART.md](QUICKSTART.md) |
| Hauptprojekt & Architektur | [README.md](README.md) |
| TYPO3 DDEV-Setup | [typo3-dev/README.md](typo3-dev/README.md) |
| Railway Deployment | [RAILWAY_DEPLOYMENT.md](RAILWAY_DEPLOYMENT.md) |
| Sicherheit & API-Keys | [SECURITY.md](SECURITY.md) |

---

**Installation abgeschlossen!** 🎉

Das Bootstrap Package ist jetzt vollständig in Ihr TYPO3-System integriert und bereit für die Verwendung.
