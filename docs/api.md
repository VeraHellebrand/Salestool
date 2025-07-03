# SalesTool REST API Documentation

## Base URL

```
http://localhost:8000/api/v1/
```

---

## Endpoints

### 1. List All Tariffs

- **URL:** `/api/v1/tariffs`
- **Method:** `GET`
- **Description:** Returns a list of all tariffs.
- **Response Example:**

```json
{
  "status": "ok",
  "tariffs": [
    {
      "id": 1,
      "code": "neo_modry",
      "name": "NEO Modrý",
      "description": "Testovací tarif",
      "price_no_vat": 100.0,
      "vat_percent": 21,
      "price_with_vat": 121.0,
      "currency": "CZK",
      "is_active": true
    }
    // ... další tarify ...
  ]
}
```

---

### 2. Get Tariff Detail

- **URL:** `/api/v1/tariffs/<code>`
- **Method:** `GET`
- **Description:** Returns detail of a tariff by code (e.g. `neo_modry`).
- **Response Example (success):**

```json
{
  "status": "ok",
  "tariff": {
    "id": 1,
    "code": "neo_modry",
    "name": "NEO Modrý",
    "description": "Testovací tarif",
    "price_no_vat": 100.0,
    "vat_percent": 21,
    "price_with_vat": 121.0,
    "currency": "CZK",
    "is_active": true
  }
}
```

- **Response Example (not found):**

```json
{
  "status": "error",
  "message": "Tariff not found"
}
```

- **Response Example (invalid code):**

```json
{
  "status": "error",
  "message": "Invalid code"
}
```

---

### 3. List All Addresses

- **URL:** `/api/v1/addresses`
- **Method:** `GET`
- **Description:** Returns a list of all addresses.
- **Response Example:**

```json
{
  "status": "ok",
  "addresses": [
    {
      "id": 1,
      "customerId": 2,
      "street": "Ulice 1",
      "city": "Praha",
      "zip": "11000",
      "country": "CZ"
    }
    // ... další adresy ...
  ]
}
```

---

### 4. Get Address Detail

- **URL:** `/api/v1/addresses/<id>`
- **Method:** `GET`
- **Description:** Returns detail of an address by ID.
- **Response Example (success):**

```json
{
  "status": "ok",
  "address": {
    "id": 1,
    "customerId": 2,
    "street": "Ulice 1",
    "city": "Praha",
    "zip": "11000",
    "country": "CZ"
  }
}
```

- **Response Example (not found):**

```json
{
  "status": "error",
  "message": "Address not found"
}
```

---

## Error Handling

- All errors return JSON with `status: error` and a `message` field.
- HTTP status codes: `200` (OK), `400` (invalid code), `404` (not found), `500` (internal error).

---

## Notes
- All responses are in JSON.
- All endpoints are read-only (GET).
- For testing, use e.g. `curl http://localhost:8000/api/v1/tariffs` or Postman.
