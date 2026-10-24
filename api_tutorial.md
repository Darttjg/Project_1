---
marp: true
theme: default
paginate: true
---

# Project 1: Car Parking Tracker/Inventory REST API
### API Tutorial

Jason Dartt
ASE 230

---

## What The Car Parking API does

A REST API for managing parked vehicles and the parking lot they are in.

10 endpoints total: all 10 use the parking database the 2 DELETE endpoints require Bearer token authentication.

---

## Endpoints 1-5

| # | Method | Endpoint | Auth |
|---|---|---|---|
| 1 | GET | /car | none |
| 2 | GET | /car/{plate} | none |
| 3 | DELETE | /car/{plate} | Bearer token |
| 4 | POST | /car | none |
| 5 | PUT | /car/{plate, color} | none |

---

## Endpoints 6-10

| # | Method | Endpoint | Auth |
|---|---|---|---|
| 6 | GET | /lot/{make, color} | none |
| 7 | GET | /lot/| none |
| 8 | POST | /lot | none |
| 9 | PUT | /car/{lot, make} | none |
| 10 | DELETE | /lot/ | Bearer token |

---

## Database

Database: `parkingdb`
Table: `parking` — car, plate, make, color, year, lot

For Database formato got to `code/schema.sql`.

---

## GET /car

Request:
```bash
curl http://localhost:8080/car
```
gets all cars
Response:
```json
[{"car": 1,"plate": "ABC-1234","make": "Toyota","color": "Red","year": 2020,"lot": 101}]
```

---

## GET /car/{plate}

Request:
```bash
curl http://localhost:8080/car/ABC-1234
```

Response:
```json
{"car": 1,"plate": "ABC-1234","make": "Toyota","color": "Red","year": 2020,"lot": 101}
```

---
## DELETE /car/{plate} (secured)

Needs a Bearer token in the Authorization header to work.

```bash
curl -X DELETE "http://localhost:8080/car/ABC-1234" \
  -H "Authorization: Bearer john-demo-token"
```

Without a valid token, the server returns 401.
---

## POST /car

Request:
```bash
curl -X POST "http://localhost:8080/car" \
  -H "Content-Type: application/json" \
  -d '{"plate": "XYZ-9999", "make": "Honda", "color": "Blue", "year": 2022, "lot": 102}'
```

Response:
```json
{"success": true,"message": "Car added successfully","id": 2}
```

---

## PUT /car/{plate}/{color}

Needs a Bearer token in the Authorization header.

```bash
curl -X PUT "http://localhost:8080/car/XYZ-9999/Silver" \
  -H "Content-Type: application/json" \
  -d '{"plate": "XYZ-NEW"}'
```
Response:
```json
{"success": true,"message": "Car updtated successfully"}
```

---

## GET /lot/{lot}/{make}/{color}/{year}

Same pattern as students, on the `courses` table.

```bash
curl GET "http://localhost:8080/lot/102/Honda/Silver/2022"
```
Response:
```json
[{"car": 2,"plate": "XYZ-NEW","make": "Honda","color": "Silver","year": 2022,"lot": 102}]
```

---

## GET /lot

```bash
curl -X GET "http://localhost:8080/lot"
```
Response:
```json
{"success": true,"data": [101, 102]}
```

---

## POST /lot

```bash
curl -X POST "http://localhost:8080/lot" \
  -H "Content-Type: application/json" \
  -d '{"lot": 103}'
```

---

## PUT /car/{lot}/{make}

```bash
curl -X PUT "http://localhost:8080/car/103/Honda"
```
Response:
```json
{"success": true,"message": "Updated cars of make 'Honda' to lot 103"}
```

---

## DELETE /lot/{lot}

```bash
curl -s -X DELETE "http://localhost:8080/lot/103" \
  -H "Authorization: Bearer admin-demo-token"
```
Response:
```json
{"success": true,"message": "Lot 103 and associated cars deleted successfully"}
```

---

## How authentication works

`auth.php` checks the `Authorization` header for `Bearer admin-demo-token` or other expected tokens.
If it doesn't match, the request is rejected with a 401 error before
touching the database.

---

## Testing

- `code/test_curl.sh` — command line tests for all 10 endpoints
- `code/test_client.html` — browser test page for all 10 endpoints

---

## Next steps (Project 2)

The Parking API will later be rebuilt with Laravel, Eloquent ORM, and Docker. (definitly needed after the recent php exploits)

---

# Thank you
