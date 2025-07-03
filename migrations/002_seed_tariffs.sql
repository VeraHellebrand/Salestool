INSERT INTO tariffs (code, name, description, price_no_vat, vat_percent, price_with_vat, currency, is_active, created_at, updated_at) VALUES
       (
           'neo_modry',
           'Tarif NEO Modrý',
           '4 GB dat s rychlostí Max 5G, neomezené volání a SMS, 3 dny neomezených dat s Daty naplno, O2 Security a O2 Connect v ceně.',
           495.04, 21, 599, 'CZK', 1,
           '2025-07-04 00:00:00', NULL
       ),
       (
           'neo_stribrny',
           'Tarif NEO Stříbrný',
           'Neomezená data s rychlostí 10 Mb/s, neomezené volání a SMS, 5 dní neomezených dat s Daty naplno, O2 Security a O2 Connect v ceně.',
           784.30, 21, 949, 'CZK', 1,
           '2025-07-04 00:00:00', NULL
       ),
       (
           'neo_platinovy',
           'Tarif NEO Platinový',
           'Neomezená data s nejvyšší rychlostí Max 5G, neomezené volání a SMS, Data naplno každý den, O2 Security a O2 Connect v ceně.',
           1114.88, 21, 1349, 'CZK', 1,
           '2025-07-04 00:00:00', NULL
       ),
       (
           'muj_prvni_tarif',
           'Tarif Můj první tarif',
           'Pro všechny, co rádi chatují. 3 GB dat, 120 minut do všech sítí, O2 Connect, 1 den Dat naplno, O2 Security, rychlost Max 5G.',
           288.43, 21, 349, 'CZK', 1,
           '2025-07-04 00:00:00', NULL
       );
