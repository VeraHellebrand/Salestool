# Salestool

Interní nástroj pro správu zákaznických tarifů a kalkulací.  
Vytvořeno jako ukázkový projekt v rámci výběrového řízení.

---

## 🛠️ Technologie

- [Nette Framework](https://nette.org/)
- [Dibi](https://dibiphp.com/)
- SQLite – jednoduchá databáze pro lokální použití
- PHP 8.3+

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

3. Spusť migrace pro vytvoření databáze:

```bash
php scripts/migrate.php
```

4. (Volitelně) spusť lokální server:

```bash
php -S localhost:8000 -t public
```

Aplikace poběží na `http://localhost:8000`.

---

## 📂 Struktura

- `app/` – aplikační logika (enumy, služby…)
- `config/` – konfigurační soubory
- `migrations/` – SQL migrace
- `scripts/` – pomocné skripty (např. migrate.php)
- `public/` – web root (index.php)
- `tests/` – testy (zatím prázdné)

---

## ✅ Poznámka

Tento projekt je určen jako výukový a referenční.  
Postupně budou přidány další části: práce s klienty, adresami a výpočty.
