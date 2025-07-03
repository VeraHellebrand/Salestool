# Salestool

Interní nástroj pro správu zákaznických tarifů a kalkulací.  
Vytvořeno jako ukázkový projekt v rámci výběrového řízení.

---

## 🛠️ Technologie

- [Nette Framework](https://nette.org/)
- [Dibi](https://dibiphp.com/)
- SQLite – jednoduchá databáze pro lokální použití
- **PHP 8.3+ (doporučeno 8.3.22)**

> **Poznámka:**
> Projekt je nastaven pro PHP 8.3. Pokud používáš VS Code, doporučuji ponechat soubor `.vscode/settings.json` v repozitáři. Ten zajistí správnou kontrolu syntaxe a nápovědu podle této verze PHP.
> Pro běh projektu na serveru je nutné mít nainstalovanou odpovídající verzi PHP.

---

## 🚀 Spuštění projektu na lokále

1. Naklonuj repozitář:

```bash
git clone https://github.com/VeraHellebrand/Salestool.git
cd Salestool
```

2. Nainstaluj závislosti přes Composer:

```bash
composer install
```

3. Spusť migrace pro vytvoření databáze (doporučeno přes Makefile):

```bash
make migrate
```

nebo přímo:

```bash
php scripts/migrate.php
```

4. Ujisti se, že existuje složka `database/` v kořenovém adresáři projektu. Pokud ne, vytvoř ji:

```bash
mkdir -p database
```

5. Spusť lokální server (doporučeno přes Makefile):

```bash
make serve
```

nebo přímo:

```bash
php -S localhost:8000 -t public
```

Aplikace poběží na `http://localhost:8000`.
---

## ⚠️ Poznámky k databázi

- Databáze se vždy vytváří v souboru `database/database.sqlite`.
- Cesta k databázi je nastavena v `config/common.neon` i ve skriptu `scripts/migrate.php`.
- Pokud spouštíš migrace opakovaně a narazíš na chybu ohledně duplicity (UNIQUE constraint), smaž starý soubor databáze:


```bash
make reset-db
```
Tento příkaz smaže starou databázi a rovnou spustí všechny migrace znovu.

---

---

## 📂 Struktura

- `app/` – aplikační logika (enumy, služby…)
- `config/` – konfigurační soubory
- `migrations/` – SQL migrace
- `scripts/` – pomocné skripty (např. migrate.php)
- `public/` – web root (index.php)
- `tests/` – testy (zatím prázdné)

---

## 📖 REST API

Full API documentation is available in [docs/api.md](docs/api.md).

---

Tento projekt je určen jako výukový a referenční.  
Postupně budou přidány další části: práce s klienty, adresami a výpočty.
