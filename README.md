# Salestool

Interní nástroj pro správu zákaznických tarifů a kalkulací.  
Vytvořeno jako ukázkový projekt v rámci výběrového řízení.

---


## 🛠️ Technologie

- [Nette Framework](https://nette.org/)
- [Dibi](https://dibiphp.com/)
- SQLite – jednoduchá databáze pro lokální použití
- **PHP 8.3+ (doporučeno 8.3.22)**
- **Composer** – správce PHP závislostí (https://getcomposer.org/)
- **GNU Make** – doporučeno pro pohodlné spouštění příkazů (`make migrate`, `make serve`, ...)

> **Poznámka:**
> Pro pohodlné používání všech příkazů v projektu je doporučeno mít nainstalovaný nástroj `make` (GNU Make) a Composer. Na Linuxu a macOS jsou běžně dostupné, na Windows lze použít například prostředí Git Bash nebo nainstalovat balíček make a Composer (https://getcomposer.org/).
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


3. Ujisti se, že existují složky `database/` a `log/` v kořenovém adresáři projektu. Vytvoř je (je potřeba je mít před spuštěním migrací a pro správné logování):

```bash
mkdir database log
```

4. Spusť migrace pro vytvoření databáze (doporučeno přes Makefile):

```bash
make migrate
```

nebo přímo:

```bash
php scripts/migrate.php
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


## 📂 Struktura projektu

- `app/` – aplikační logika (entity, DTO, služby, validátory, repozitáře, factory, enumy)
- `config/` – konfigurační soubory Nette a Dibi
- `migrations/` – SQL migrace pro vytvoření a naplnění databáze
- `scripts/` – pomocné skripty (např. migrate.php pro migrace)
- `public/` – web root (index.php, vstupní bod aplikace)
- `database/` – SQLite databáze (vytváří se automaticky při migraci)
- `log/` – logy aplikace (chyby, Tracy)
- `tests/` – testy

---

## 📝 Stručný popis fungování projektu

Projekt je postaven na architektuře Nette + Dibi a využívá moderní principy návrhu:

- **REST API**: Veškerá komunikace probíhá přes REST API endpointy (např. /api/v1/calculation, /api/v1/tariff).
- **DTO a Input objekty**: Data z požadavků se mapují do Input DTO, odpovědi se vrací přes Output DTO (vždy jako pole/array).
- **Service, Factory, Validator, Repository**: Každá doména (např. Calculation) má vlastní službu, továrnu, validátor a repozitář pro čistotu a testovatelnost kódu.
- **Imutabilní entity**: Doménové objekty (např. Calculation) jsou neměnné (immutable).
- **SQLite**: Data jsou ukládána do SQLite databáze přes Dibi.
- **Migrace**: Struktura a seed dat jsou spravovány SQL migracemi.
- **Logování a chybové hlášky**: Všechny chyby a důležité akce jsou logovány do složky `log/` a odpovědi API jsou v angličtině.
- **PHPStan**: Kód je staticky analyzován na maximální úrovni.

Celý tok dat:

### Tok dat v doméně Calculation

**GET (seznam/detail):**
1. **Request** (GET, JSON) → **Presenter** → **Factory** (volá Repository) → **Entity** → **Mapper** → **Output DTO** → **Presenter** → **Response** (JSON)
   - Data se z databáze načtou jako entity, přes mapper se převedou na Output DTO (pole) pro API odpověď.

**PATCH (změna statusu):**
1. **Request** (PATCH, JSON) → **Presenter** → **Service** (volá Repository, Validator) → **Entity** (aktualizace) → **Repository** (uložení) → **Presenter** → **Mapper** → **Output DTO** → **Response** (JSON)
   - Vstupní data se validují, entita se aktualizuje a uloží, výsledek se převede na Output DTO (pole) pro API odpověď.

Veškeré převody na pole (array) probíhají pouze v Output DTO třídách. V ostatních částech aplikace (service, repository, entity, validátor) se pracuje s objekty.


---

## 🧪 Testování API

API lze snadno otestovat pomocí nástroje Postman, HTTPie, curl nebo jiného REST klienta.

- Pro GET endpointy stačí otevřít např.:
  - `GET http://localhost:8000/api/v1/calculations`
  - `GET http://localhost:8000/api/v1/calculations/1`
  - `GET http://localhost:8000/api/v1/tariffs`
- Pro POST/PATCH/PUT endpointy doporučujeme použít Postman nebo curl, kde lze snadno posílat JSON payloady.


---

## API testování v Postmanu

V adresáři `docs/` najdete soubor:

```
docs/salesTool.postman_collection.json
```

Tento soubor obsahuje kolekci všech důležitých API dotazů pro testování aplikace v Postmanu.

### Jak kolekci použít

1. Otevřete Postman.
2. Klikněte na tlačítko **Import** (vlevo nahoře).
3. Vyberte soubor `docs/SalesTool.postman_collection.json` a potvrďte import.
4. V levém panelu Collections najdete kolekci **SalesTool** se všemi připravenými dotazy.
5. Upravte si případně URL (např. port nebo hostname) podle svého prostředí.

Nyní můžete jednoduše testovat všechny endpointy API.

---

## 📖 REST API

Podrobná dokumentace API je v [docs/api.md](docs/api.md).

---

