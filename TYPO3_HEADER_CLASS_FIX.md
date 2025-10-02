# TYPO3 "Undefined array key header_class" Fehler - Lösung

## Problem

Der Fehler tritt auf, wenn das Bootstrap Package installiert wird, aber das erforderliche TCA-Feld `header_class` nicht in der `tt_content` Tabelle definiert ist:

```
(1/1) #1476107295 TYPO3\CMS\Core\Error\Exception
PHP Warning: Undefined array key "header_class" in /var/www/html/vendor/typo3/cms-core/Classes/DataHandling/DataHandler.php line 8477
```

## Ursache

Das Bootstrap Package erwartet ein `header_class` Feld in der TCA-Konfiguration für Content-Elemente, um Bootstrap-CSS-Klassen für Überschriften zu verwalten. Dieses Feld ist in Standard-TYPO3-Installationen nicht vorhanden.

## Lösung

### 1. TCA-Konfiguration hinzufügen

Erstellen Sie die Datei `Configuration/TCA/Overrides/tt_content.php` in Ihrem Site Package:

```php
<?php
declare(strict_types=1);
defined('TYPO3') || exit();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// Add header_class field to tt_content table
$tempColumns = [
    'header_class' => [
        'exclude' => true,
        'label' => 'LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.default', ''],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.h1', 'h1'],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.h2', 'h2'],
                // ... weitere Bootstrap-Klassen
            ],
            'default' => '',
        ],
    ],
];

ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);
ExtensionManagementUtility::addFieldsToPalette('tt_content', 'headers', 'header_class', 'after:header_layout');
```

### 2. Datenbankfeld hinzufügen

Erstellen Sie `ext_tables.sql` in Ihrem Site Package:

```sql
CREATE TABLE tt_content (
    header_class varchar(255) DEFAULT '' NOT NULL
);
```

### 3. Sprachdateien erstellen

Erstellen Sie `Resources/Private/Language/locallang_db.xlf`:

```xml
<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
<xliff version="1.0">
    <file source-language="en" datatype="plaintext">
        <body>
            <trans-unit id="tt_content.header_class">
                <source>Header CSS Class</source>
            </trans-unit>
            <trans-unit id="tt_content.header_class.default">
                <source>Default</source>
            </trans-unit>
            <!-- weitere Übersetzungen -->
        </body>
    </file>
</xliff>
```

### 4. TypoScript-Konfiguration

Fügen Sie zu Ihrem TypoScript-Setup hinzu:

```typoscript
lib.contentElement {
    settings {
        header {
            defaultHeaderType = 2
            class = TEXT
            class.field = header_class
            class.ifEmpty = 
        }
    }
}
```

### 5. Datenbank aktualisieren

Nach der Implementierung:

1. Gehen Sie ins TYPO3-Backend
2. **Admin Tools** → **Maintenance** → **Analyze Database Structure**
3. Führen Sie die Datenbankaktualisierung durch
4. Leeren Sie alle Caches

## Verfügbare Bootstrap-Klassen

Das Feld unterstützt folgende Bootstrap 5-Klassen:

- **Standard-Überschriften**: h1, h2, h3, h4, h5, h6
- **Display-Überschriften**: display-1, display-2, display-3, display-4
- **Text-Utilities**: lead, text-muted
- **Farbklassen**: text-primary, text-secondary, text-success, text-info, text-warning, text-danger

## Verwendung im Backend

Nach der Installation erscheint im Content-Element-Editor ein neues Feld "Header CSS Class" in der Überschriften-Palette. Redakteure können dort Bootstrap-Klassen für die Überschrift auswählen.

## Fluid-Template-Integration

In Ihren Fluid-Templates können Sie das Feld verwenden:

```html
<f:if condition="{data.header}">
    <h{data.header_layout} class="{data.header_class}">
        {data.header}
    </h{data.header_layout}>
</f:if>
```

## Troubleshooting

### Cache leeren
```bash
vendor/bin/typo3 cache:flush
```

### Datenbank-Schema prüfen
```bash
vendor/bin/typo3 database:updateschema
```

### Extension neu aktivieren
```bash
vendor/bin/typo3 extension:deactivate bootstrap_package
vendor/bin/typo3 extension:activate bootstrap_package
```

## Weitere Informationen

- [TYPO3 TCA Reference](https://docs.typo3.org/m/typo3/reference-tca/main/en-us/)
- [Bootstrap Package Documentation](https://www.bootstrap-package.com/)
- [Bootstrap 5 Typography](https://getbootstrap.com/docs/5.3/content/typography/)

## Community-Beitrag

Diese Lösung wurde entwickelt, um das häufige Problem mit dem fehlenden `header_class` Feld zu beheben. Wenn Sie diese Lösung hilfreich fanden, teilen Sie sie mit der TYPO3-Community!

---

**Status**: ✅ Gelöst - Das `header_class` Feld ist jetzt verfügbar und Bootstrap Package funktioniert korrekt.
