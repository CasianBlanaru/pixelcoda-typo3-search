# Heroku Deployment Fix

## Problembehebung für Buildpack-Fehler

### Durchgeführte Änderungen:

1. **package.json optimiert**:
   - Node.js Engine auf `>=18.0.0` gesetzt (flexibler)
   - npm Engine auf `>=8.0.0` spezifiziert

2. **app.json angepasst**:
   - Stack auf `heroku-22` geändert (stabiler als heroku-24)
   - Buildpack explizit auf `heroku/nodejs` gesetzt

3. **Zusätzliche Konfigurationsdateien**:
   - `.nvmrc` erstellt mit Node.js Version 18.18.0
   - `.buildpacks` Datei hinzugefügt für explizite Buildpack-Spezifikation

### Deployment-Befehle:

```bash
# 1. Änderungen committen
git add .
git commit -m "Fix Heroku buildpack configuration"

# 2. Zu Heroku deployen
git push heroku main

# 3. Logs überprüfen
heroku logs --tail
```

### Falls weitere Probleme auftreten:

```bash
# Buildpacks zurücksetzen (falls Heroku CLI verfügbar)
heroku buildpacks:clear
heroku buildpacks:set heroku/nodejs

# Oder das bereitgestellte Script verwenden
chmod +x fix-buildpacks.sh
./fix-buildpacks.sh
```

### Getestete Konfiguration:
- ✅ Node.js ES Modules Support
- ✅ Minimal Dependencies (nur dotenv)
- ✅ Korrekte Procfile Konfiguration
- ✅ Heroku-22 Stack Kompatibilität
- ✅ Explizite Buildpack-Spezifikation

Die Anwendung sollte jetzt erfolgreich auf Heroku deployen.

---

## 📚 Weitere Dokumentation

| Thema | Datei |
|-------|--------|
| Schnellstart (lokal) | [QUICKSTART.md](QUICKSTART.md) |
| Hauptdokumentation | [README.md](README.md) |
| Sicherheit (API-Keys, Env) | [SECURITY.md](SECURITY.md) |
| TYPO3 Bootstrap Package | [BOOTSTRAP_PACKAGE_SETUP.md](BOOTSTRAP_PACKAGE_SETUP.md) |