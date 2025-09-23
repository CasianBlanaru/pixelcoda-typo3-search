# 🚀 Pixelcoda Search Platform - Schnellstart

## Übersicht
Die Pixelcoda Search Platform integriert TYPO3 Headless mit einer modernen Suchinfrastruktur basierend auf Meilisearch.

## ✅ Aktueller Status

### Was bereits läuft:
- ✅ **TYPO3 mit DDEV**: https://pixelcoda-typo3-dev.ddev.site
- ✅ **Such-API**: http://localhost:8787
- ✅ **Datenbank-Services**: PostgreSQL, Meilisearch, Redis
- ✅ **Demo-Seite**: demo/index.html

## 🎯 Schnellstart (5 Minuten)

### 1. Services starten
```bash
# Datenbank-Services starten
docker-compose up -d postgres meilisearch redis

# API starten
yarn workspace @pixelcoda/api dev
# ODER mit expliziten ENV-Variablen:
API_READ_KEY=pc_read_dev_key API_WRITE_KEY=pc_write_dev_key yarn workspace @pixelcoda/api tsx src/index.ts
```

### 2. TYPO3 Backend
```bash
# TYPO3 ist bereits gestartet mit DDEV
open https://pixelcoda-typo3-dev.ddev.site/typo3

# Login:
# Benutzer: admin
# Passwort: admin
```

### 3. Demo-Seite öffnen
```bash
open demo/index.html
```

## 📝 TYPO3 Konfiguration

### Search Plugin einbinden:
1. Im TYPO3 Backend einloggen
2. Neue Seite erstellen oder bestehende bearbeiten
3. Neues Content Element hinzufügen
4. "Pixelcoda Search" aus der Plugin-Kategorie wählen
5. Speichern

### JSON-Output testen:
```bash
# Ersetze [SEITEN-ID] mit der tatsächlichen ID
curl https://pixelcoda-typo3-dev.ddev.site/?type=834&id=[SEITEN-ID]
```

## 🔧 Entwicklung

### API-Endpunkte:
- **Health Check**: GET http://localhost:8787/health
- **Search**: POST http://localhost:8787/v1/search/{project}
- **Suggestions**: POST http://localhost:8787/v1/suggest/{project}
- **Ask (AI)**: POST http://localhost:8787/v1/ask/{project}

### Beispiel-Suchanfrage:
```bash
curl -X POST http://localhost:8787/v1/search/demo \
  -H "Content-Type: application/json" \
  -H "X-API-Key: pc_read_dev_key" \
  -d '{
    "query": "TYPO3",
    "collections": ["pages", "news"],
    "limit": 10
  }'
```

## 📂 Projektstruktur

```
pixelcoda-headless-search-starter/
├── apps/
│   ├── api/            # Such-API (Hono.js)
│   ├── worker/         # Background Jobs
│   ├── widgets/        # React Widgets
│   └── typo3-connector/# TYPO3 Extension
├── typo3-dev/
│   └── packages/
│       └── pixelcoda_search/  # TYPO3 Plugin
├── demo/
│   └── index.html      # Demo-Seite
└── docker-compose.yml  # Services
```

## 🐛 Troubleshooting

### API startet nicht:
```bash
# Prozesse beenden
pkill -f "tsx src/index.ts"

# Neu starten mit Logs
NODE_ENV=development yarn workspace @pixelcoda/api tsx src/index.ts
```

### TYPO3 Cache leeren:
```bash
cd typo3-dev
rm -rf var/cache/*
ddev exec typo3 cache:flush
```

### Docker Services prüfen:
```bash
docker-compose ps
docker-compose logs -f api
```

## 🚀 Nächste Schritte

1. **Inhalte indexieren**: TYPO3-Inhalte in Meilisearch indexieren
2. **Frontend anpassen**: Widget-Styles und Funktionen erweitern
3. **AI-Features**: OpenAI/Ollama für intelligente Antworten konfigurieren
4. **Production Build**: Docker-Images für Deployment vorbereiten

## 📚 Weitere Dokumentation

- [TYPO3 Headless Docs](https://docs.typo3.org/p/friendsoftypo3/headless/main/en-us/)
- [Meilisearch Docs](https://docs.meilisearch.com/)
- [Hono.js Docs](https://hono.dev/)

## 💡 Support

Bei Fragen oder Problemen:
- GitHub Issues erstellen
- Logs prüfen: `docker-compose logs`
- TYPO3 Logs: `typo3-dev/var/log/`
