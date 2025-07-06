# SalesTool REST API Documentation

## Base URL

```
http://localhost:8000/api/v1/
```

---

## Endpoints Overview

- [Tariffs](#tariffs)
- [Customers](#customers)
- [Calculations](#calculations)

---

## Tariffs

### List All Tariffs

- **URL:** `/api/v1/tariffs`
- **Method:** `GET`
- **Description:** Returns a list of all tariffs.

#### Response Example
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
  ]
}
```

---

### Get Tariff Detail

- **URL:** `/api/v1/tariffs/{code}`
- **Method:** `GET`
- **Description:** Returns detail of a tariff by code (e.g. `neo_modry`).

#### Response Example (success)
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

#### Response Example (not found)
```json
{
  "status": "error",
  "message": "Tariff not found"
}
```

#### Update Tariff

- **URL:** `/api/v1/tariffs/{code}`
- **Method:** `PATCH`
- **Body:**
```json
{
  "vat_percent": 10
}
```

---

## Customers

### List All Customers

- **URL:** `/api/v1/customers`
- **Method:** `GET`
- **Description:** Returns a list of all customers.

### Get Customer Detail

- **URL:** `/api/v1/customers/{id}`
- **Method:** `GET`
- **Description:** Returns detail of a customer by ID.

### Create Customer

- **URL:** `/api/v1/customers`
- **Method:** `POST`
- **Body:**
```json
{
  "first_name": "Jan",
  "last_name": "Novák",
  "email": "jan.novak2@example.com",
  "phone": "+420123456789"
}
```

### Update Customer

- **URL:** `/api/v1/customers/{id}`
- **Method:** `PATCH`
- **Body:**
```json
{
  "phone": "+420123111111"
}
```

---

## Calculations

### List All Calculations

- **URL:** `/api/v1/calculations`
- **Method:** `GET`
- **Description:** Returns a list of all calculations.

### Get Calculation Detail

- **URL:** `/api/v1/calculations/{id}`
- **Method:** `GET`
- **Description:** Returns detail of a calculation by ID.

### Create Calculation

- **URL:** `/api/v1/calculations`
- **Method:** `POST`
- **Body:**
```json
{
  "customerId": 3,
  "tariffId": 2,
  "priceWithVat": 1210.0
}
```

#### Response Example (success)
```json
{
  "status": "ok",
  "calculation": {
    "id": 5,
    "customerId": 3,
    "tariffId": 2,
    "priceWithVat": 1210.0,
    "priceNoVat": 1000.0,
    "vatPercent": 21,
    "currency": "CZK",
    "status": "new",
    "createdAt": "2025-07-06T12:34:56+02:00"
  }
}
```

#### Response Example (validation error)
```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "customerId": "Customer does not exist",
    "tariffId": "Tariff does not exist",
    "priceWithVat": "Must be a positive number"
  }
}
```

### Update Calculation Status

- **URL:** `/api/v1/calculations/{id}`
- **Method:** `PATCH`
- **Body:**
```json
{
  "status": "accepted"
}
```

---

## Error Handling

- All errors return JSON with `status: error` and a `message` field. Validation errors include an `errors` object.
- HTTP status codes: `200` (OK), `400` (validation or input error), `404` (not found), `500` (internal error).

---

## Notes

- All responses are in JSON.
- For testing, use e.g. `curl` or Postman. The [Postman collection](SalesTool.postman_collection.json) contains all endpoints and example requests.
- Enum values (e.g. calculation status) are validated and errors are descriptive.
