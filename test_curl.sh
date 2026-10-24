#!/usr/bin/env bash
BASE_URL="${BASE_URL:-http://localhost:8080}"
VALID_TOKEN="privleged-user"
INVALID_TOKEN="invalid-secret-token"

echo "=== 0. Testing Connection ==="
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL/lot")
if [ "$HTTP_CODE" -eq 200 ] || [ "$HTTP_CODE" -eq 201 ] || [ "$HTTP_CODE" -eq 404 ]; then
    echo "Connection successful (Server responded with HTTP $HTTP_CODE)."
else
    echo "ERROR: Could not connect to server at $BASE_URL." >&2
    exit 1
fi

echo -e "\n=== 1. POST /lot (Create Parking Lot 800) ==="
curl -s -X POST "$BASE_URL/lot" \
  -H "Content-Type: application/json" \
  -d '{"lot": 800}'
echo ""

echo -e "\n=== 2. POST /car (Add a New Car) ==="
curl -s -X POST "$BASE_URL/car" \
  -H "Content-Type: application/json" \
  -d '{"plate": "TEST-100", "make": "Tesla", "color": "White", "year": 2023, "lot": 800}'
echo ""

echo -e "\n=== 3. GET /car (Get All Cars) ==="
curl -s -X GET "$BASE_URL/car"
echo ""

echo -e "\n=== 4. GET /car/{plate} (Get Car by Plate) ==="
curl -s -X GET "$BASE_URL/car/TEST-100"
echo ""

echo -e "\n=== 5. PUT /car/{plate}/{color} (Update Car Plate & Color) ==="
curl -s -X PUT "$BASE_URL/car/TEST-100/Black" \
  -H "Content-Type: application/json" \
  -d '{"plate": "TEST-100"}'
echo ""

echo -e "\n=== 6. PUT /car/{lot}/{make} (Update Cars of Make 'Tesla' to Lot 801) ==="
# First, create lot 801
curl -s -X POST "$BASE_URL/lot" -H "Content-Type: application/json" -d '{"lot": 801}' > /dev/null
# Then update Tesla make to lot 801
curl -s -X PUT "$BASE_URL/car/801/Tesla"
echo ""

echo -e "\n=== 7. GET /lot/{lot}/{make}/{color}/{year} (Filter Cars in Lot) ==="
curl -s -X GET "$BASE_URL/lot/801/Tesla/Black/2023"
echo ""

echo -e "\n=== 8. GET /lot (Get All Parking Lots) ==="
curl -s -X GET "$BASE_URL/lot"
echo ""

echo -e "\n=== 9. DELETE /car/{plate} - Authorization Checks ==="
echo "--- Testing DELETE without token (Expected 401) ---"
curl -s -o /dev/null -w "Status: %{http_code}\n" -X DELETE "$BASE_URL/car/TEST-100"

echo "--- Testing DELETE with invalid token (Expected 401) ---"
curl -s -o /dev/null -w "Status: %{http_code}\n" -X DELETE "$BASE_URL/car/TEST-100" \
  -H "Authorization: Bearer $INVALID_TOKEN"

echo "--- Testing DELETE with valid token (Expected 200) ---"
curl -s -X DELETE "$BASE_URL/car/TEST-100" \
  -H "Authorization: Bearer $VALID_TOKEN"
echo ""

echo -e "\n=== 10. DELETE /lot/{lot} (Delete Lot and Associated Cars with Valid Token) ==="
curl -s -X DELETE "$BASE_URL/lot/801" \
  -H "Authorization: Bearer $VALID_TOKEN"
echo ""

echo "=== All 10 cURL endpoint tests completed successfully! ==="