# 🚀 Pixelcoda Search Platform - Schnellstart

Die Pixelcoda Search Platform ist eine moderne, API-basierte Suchplattform mit KI-Unterstützung für TYPO3 und andere CMS-Systeme.

## 🎯 Schnellstart (5 Minuten)

### 1. Repository klonen
```bash
git clone git@github.com:CasianBlanaru/typo3-search.git
cd typo3-search
```

### 2. Abhängigkeiten installieren
```bash
npm install
```

### 3. API starten (Simple API – Demo ohne Datenbank)
```bash
npm run dev
```

Die API läuft unter **http://localhost:8787**. Öffne z. B. `http://localhost:8787/health` oder die Demo-Seite:

```bash
open index.html
# oder: open demo/index.html
```

---

## 🔧 Optionen: Volle API mit Datenbank

Wenn du die **volle Such-API** mit PostgreSQL und Meilisearch nutzen willst:

### Umgebung
```bash
cp env.example .env
# .env anpassen: DATABASE_URL, MEILI_KEY, API_READ_KEY, API_WRITE_KEY
```

### Services starten
```bash
docker-compose up -d postgres meilisearch redis
node scripts/migrate.js
```

### Standalone-API starten
```bash
cd standalone-api && npm install && npm run dev
# oder aus dem Root: npm run api:dev
```

Siehe [README.md](README.md) für Details zu Worker, Widgets und TYPO3.

---

## 📝 TYPO3 Integration (Optional)

### TYPO3 mit DDEV starten
```bash
cd typo3-dev
ddev start
ddev composer install
```

Dann im Browser das **Install-Tool** aufrufen und die Datenbank anlegen:
- **https://pixelcoda-typo3-dev.ddev.site/typo3/install.php**
- DB: Host `db`, User/Passwort `db`, Datenbank `db`

Falls `config/system/settings.php` fehlt oder der **encryptionKey**-Fehler erscheint, siehe [typo3-dev/README.md](typo3-dev/README.md).

### Plugin aktivieren
1. TYPO3 Backend öffnen (z. B. admin/admin)
2. Admin Tools → Extensions → „pixelcoda_search“ aktivieren
3. Content-Element „Pixelcoda Search“ zu einer Seite hinzufügen

Bootstrap Package und Frontend-Setup: [BOOTSTRAP_PACKAGE_SETUP.md](BOOTSTRAP_PACKAGE_SETUP.md).

---

## 🔧 API-Endpunkte

- **Health**: `GET http://localhost:8787/health`
- **Suche**: `POST http://localhost:8787/v1/search/{project}`
- **Vorschläge**: `POST http://localhost:8787/v1/suggest/{project}`
- **KI-Antworten**: `POST http://localhost:8787/v1/ask/{project}`

### Beispiel (Suche)
```bash
curl -X POST http://localhost:8787/v1/search/demo \
  -H "Content-Type: application/json" \
  -d '{"q": "TYPO3", "limit": 10}'
```

### Beispiel (KI-Antwort)
```bash
curl -X POST http://localhost:8787/v1/ask/demo \
  -H "Content-Type: application/json" \
  -d '{"q": "Was ist Pixelcoda Search?"}'
```

---

## 📂 Projektstruktur

```
typo3-search/
├── simple-api.js       # Demo-API (npm run dev)
├── standalone-api/     # Volle Hono-API (npm run api:dev)
├── apps/
│   ├── api/            # Alternative Such-API (Hono)
│   ├── worker/         # Background Jobs / Ingest
│   ├── widgets/        # React Widgets
│   └── typo3-connector/
├── typo3-dev/          # DDEV TYPO3-Umgebung
│   └── packages/pixelcoda_search/
├── demo/               # Demo-Frontend
├── scripts/migrate.js  # DB-Migration
└── docker-compose.yml  # Postgres, Meilisearch, Redis
```

---

## 🐛 Fehlerbehebung

### API startet nicht?
```bash
# Port prüfen
lsof -i :8787

# Simple API direkt starten
node simple-api.js
```

### Port 8787 belegt?
```bash
lsof -i :8787 | grep LISTEN
kill -9 <PID>
```

### Docker/DB-Probleme?
```bash
docker-compose ps
docker-compose logs -f postgres
```

---

## 🚀 Nächste Schritte

1. **Umgebungsvariablen**: `.env` aus `env.example` anlegen (siehe [SECURITY.md](SECURITY.md) für sichere Keys).
2. **Inhalte indexieren**: Dokumente über die API hinzufügen.
3. **KI-Provider**: OpenAI/Ollama in `.env` konfigurieren.
4. **Deployment**: Heroku-Setup siehe [HEROKU_DEPLOYMENT_FIX.md](HEROKU_DEPLOYMENT_FIX.md).

---

## 📚 Dokumentation

| Thema | Datei |
|-------|--------|
| Hauptdokumentation | [README.md](README.md) |
| TYPO3 DDEV-Setup | [typo3-dev/README.md](typo3-dev/README.md) |
| Bootstrap Package (TYPO3) | [BOOTSTRAP_PACKAGE_SETUP.md](BOOTSTRAP_PACKAGE_SETUP.md) |
| Heroku Deployment | [HEROKU_DEPLOYMENT_FIX.md](HEROKU_DEPLOYMENT_FIX.md) |
| Sicherheit & Keys | [SECURITY.md](SECURITY.md) |

GitHub: https://github.com/CasianBlanaru/typo3-search
