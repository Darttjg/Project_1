<?php
// index.php

require_once 'db.php';
require_once 'auth.php';

$db = get_db();

// Basic request routing
$method = $_SERVER['REQUEST_METHOD'];
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path_parts = explode('/', trim($request_uri, '/'));

$resource = $path_parts[0] ?? '';
$param1 = $path_parts[1] ?? null;
$param2 = $path_parts[2] ?? null;
$param3 = $path_parts[3] ?? null;
$param4 = $path_parts[4] ?? null;

header('Content-Type: application/json');

// Route Handling
switch ($resource) {
    case 'car':
        handle_car_resource($method, $param1, $param2, $db);
        break;
    case 'lot':
        handle_lot_resource($method, $param1, $param2, $param3, $param4, $db);
        break;
    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Endpoint not found']);
        break;
}

function handle_car_resource($method, $param1, $param2, $db) {
    switch ($method) {
        case 'GET':
            if ($param1 !== null) {
                // GET car/{plate}
                $stmt = $db->prepare("SELECT * FROM parking WHERE plate = ?");
                $stmt->execute([$param1]);
                $car = $stmt->fetch();
                if ($car) {
                    echo json_encode(['success' => true, 'data' => $car]);
                } else {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'error' => 'Car not found']);
                }
            } else {
                // GET car (all cars)
                $stmt = $db->query("SELECT * FROM parking");
                $cars = $stmt->fetchAll();
                echo json_encode(['success' => true, 'data' => $cars]);
            }
            break;

        case 'POST':
            // POST car (adds a new car)
            $input = json_decode(file_get_contents('php://input'), true);
            if (!isset($input['plate'], $input['make'], $input['color'], $input['year'], $input['lot'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Missing required car fields (plate, make, color, year, lot)']);
                exit;
            }
            $stmt = $db->prepare("INSERT INTO parking (plate, make, color, year, lot) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$input['plate'], $input['make'], $input['color'], $input['year'], $input['lot']]);
            http_response_code(201);
            echo json_encode(['success' => true, 'message' => 'Car added successfully', 'id' => $db->lastInsertId()]);
            break;

        case 'PUT':
            if ($param1 !== null && $param2 !== null) {
                //PUT /car/{lot}/{make}
                if (is_numeric($param1)) {
                    $new_lot = (int)$param1;
                    $make = $param2;
                    $stmt = $db->prepare("UPDATE parking SET lot = ? WHERE make = ?");
                    $stmt->execute([$new_lot, $make]);
                    echo json_encode(['success' => true, 'message' => "Updated cars of make '$make' to lot $new_lot"]);
                } else { 
                    //PUT /car/{plate}/{color}
                    $plate = $param1;
                    $color = $param2;
                    $input = json_decode(file_get_contents('php://input'), true);
                    $new_plate = $input['plate'] ?? $plate;
                    $stmt = $db->prepare("UPDATE parking SET plate = ?, color = ? WHERE plate = ?");
                    $stmt->execute([$new_plate, $color, $plate]);
                    echo json_encode(['success' => true, 'message' => 'Car updated successfully']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Invalid PUT parameters for car']);
            }
            break;

        case 'DELETE':
            //DELETE /car/{plate}
            check_token();
            if ($param1 !== null) {
                $stmt = $db->prepare("DELETE FROM parking WHERE plate = ?");
                $stmt->execute([$param1]);
                if ($stmt->rowCount() > 0) {
                    echo json_encode(['success' => true, 'message' => 'Car deleted successfully']);
                } else {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'error' => 'Car not found']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Car plate required for deletion']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            break;
    }
}

function handle_lot_resource($method, $param1, $param2, $param3, $param4, $db) {
    switch ($method) {
        case 'GET':
            //GET /lot/{lot}/{make}/{color}/{year}
            if ($param1 !== null && $param2 !== null && $param3 !== null && $param4 !== null) {
                $stmt = $db->prepare("SELECT * FROM parking WHERE lot = ? AND make = ? AND color = ? AND year = ?");
                $stmt->execute([(int)$param1, $param2, $param3, (int)$param4]);
                $cars = $stmt->fetchAll();
                echo json_encode(['success' => true, 'data' => $cars]);
            } else {
                // GET lot (all lots)
                $stmt = $db->query("SELECT DISTINCT lot FROM parking WHERE lot IS NOT NULL");
                $lots = $stmt->fetchAll(PDO::FETCH_COLUMN);
                echo json_encode(['success' => true, 'data' => $lots]);
            }
            break;

        case 'POST':
            //POST /lot
            $input = json_decode(file_get_contents('php://input'), true);
            if (!isset($input['lot'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Lot number required']);
                exit;
            }
            $lot = (int)$input['lot'];
            $stmt = $db->prepare("INSERT INTO parking (plate, make, color, year, lot) VALUES ('N/A', 'N/A', 'N/A', 0, ?)");
            $stmt->execute([$lot]);
            http_response_code(201);
            echo json_encode(['success' => true, 'message' => 'Parking lot created successfully']);
            break;

        case 'DELETE':
            //DELETE /lot/{lot}
            check_token();
            if ($param1 !== null) {
                $lot = (int)$param1;
                $stmt = $db->prepare("DELETE FROM parking WHERE lot = ?");
                $stmt->execute([$lot]);
                echo json_encode(['success' => true, 'message' => "Lot $lot and associated cars deleted successfully"]);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Lot number required for deletion']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            break;
    }
}