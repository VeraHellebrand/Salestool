CREATE TABLE tariffs (
       id INTEGER PRIMARY KEY AUTOINCREMENT,
       code TEXT NOT NULL UNIQUE,
       name TEXT NOT NULL,
       description TEXT,
       price_no_vat REAL NOT NULL,
       vat_percent INTEGER NOT NULL DEFAULT 21,
       price_with_vat REAL NOT NULL,
       currency TEXT NOT NULL DEFAULT 'CZK'
);
