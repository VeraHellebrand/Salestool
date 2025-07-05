-- Assumptions: tariffs (ID 1-4) have base prices >= calculation price, customers (ID 1-3)
INSERT INTO calculations (customer_id, tariff_id, price_no_vat, vat_percent, price_with_vat, currency, status, created_at, updated_at)
VALUES
    (1, 1, 400.00, 21, 484.00, 'CZK', 'new', '2025-07-04 10:15:00', NULL),
    (2, 2, 600.00, 21, 726.00, 'CZK', 'accepted', '2025-07-04 10:20:00', '2025-07-05 09:00:00'),
    (3, 3, 800.00, 21, 968.00, 'CZK', 'rejected', '2025-07-04 10:25:00', '2025-07-05 09:10:00'),
    (1, 4, 200.00, 21, 242.00, 'CZK', 'pending', '2025-07-04 10:30:00', '2025-07-05 09:20:00');

