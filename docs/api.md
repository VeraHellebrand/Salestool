
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

#### Example Request
```http
GET /api/v1/tariffs HTTP/1.1
```

#### Example Response
```json
{
  "status": "ok",
  "tariffs": [
    {
      "id": 1,
      "code": "neo_modry",
      "name": "Tarif NEO Modrý",
      "description": "4 GB dat s rychlostí Max 5G, neomezené volání a SMS, ...",
      "price_no_vat": 495.04,
      "vat_percent": 21,
      "price_with_vat": 599,
      "currency": "CZK"
    }
  ]
}
```

---

### Get Tariff Detail
- **URL:** `/api/v1/tariffs/{id}`
- **Method:** `GET`
- **Description:** Returns detail of a tariff by ID (e.g. `1`).

#### Example Request
```http
GET /api/v1/tariffs/1 HTTP/1.1
```

#### Example Response (success)
```json
{
  "status": "ok",
  "tariff": {
    "id": 1,
    "code": "neo_modry",
    "name": "Tarif NEO Modrý",
    "description": "4 GB dat s rychlostí Max 5G, neomezené volání a SMS, ...",
    "price_no_vat": 495.04,
    "vat_percent": 21,
    "price_with_vat": 599,
    "currency": "CZK"
  }
}
```

#### Example Response (not found)
```json
{
  "status": "error",
  "message": "Tariff not found"
}
```

---

## Customers

### List All Customers
- **URL:** `/api/v1/customers`
- **Method:** `GET`
- **Description:** Returns a list of all customers.

#### Example Request
```http
GET /api/v1/customers HTTP/1.1
```

---

### Get Customer Detail
- **URL:** `/api/v1/customers/{id}`
- **Method:** `GET`
- **Description:** Returns detail of a customer by ID.

#### Example Request
```http
GET /api/v1/customers/1 HTTP/1.1
```

---

### Create Customer
- **URL:** `/api/v1/customers`
- **Method:** `POST`
- **Body:**
```json
{
  "first_name": "Jan",
  "last_name": "Novák",
  "email": "jan.novak@example.com",
  "phone": "+420123456789"
}
```

---

### Update Customer
- **URL:** `/api/v1/customers/{id}`
- **Method:** `PUT`
- **Body:**
```json
{
  "first_name": "Jana",
  "last_name": "Nová",
  "email": "jana.nova@example.com",
  "phone": "+420123456789"
}
```

---

## Calculations

### List All Calculations
- **URL:** `/api/v1/calculations`
- **Method:** `GET`
- **Description:** Returns a list of all calculations.

#### Example Request
```http
GET /api/v1/calculations HTTP/1.1
```

---

### Get Calculation Detail
- **URL:** `/api/v1/calculations/{id}`
- **Method:** `GET`
- **Description:** Returns detail of a calculation by ID.

#### Example Request
```http
GET /api/v1/calculations/1 HTTP/1.1
```

---

### Create Calculation
- **URL:** `/api/v1/calculations`
- **Method:** `POST`
- **Body:**
```json
{
  "customerId": 1,
  "tariffId": 2,
  "priceWithVat": 1
}
```

---

### Update Calculation Status
- **URL:** `/api/v1/calculations/{id}`
- **Method:** `PATCH`
- **Body:**
```json
{
  "status": "new"
}
```

---

## Error Handling

All errors return JSON with the fields `status: error` and `message`. Validation errors also include an `errors` object.

HTTP status codes used:
- `200` (OK)
- `400` (validation or input error)
- `404` (not found)
- `409` (duplicate)
- `422` (validation error)
- `500` (internal error)

---

## Notes

All responses are in JSON format.
For testing, use e.g. `curl` or Postman. The [Postman collection](SalesTool.postman_collection.json) contains all endpoints and example requests.
Enum values (e.g. calculation status) are validated and error messages are descriptive.
