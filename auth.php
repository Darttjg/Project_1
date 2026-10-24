<?php

function getBearerToken() {
    $headers = getallheaders();
    // Extract token from Authorization header
    if (isset($headers['Authorization']) && preg_match('/Bearer\s+(.*)$/i', $headers['Authorization'], $m)) {
        return trim($m[1]);
    }
    return null;
}

function isValidToken($token) {
    // valid tokens mapping token strings to usernames
    $validTokens = [
        'privleged-user'  => 'puser',
        'admin-demo-token' => 'admin'
    ];
    return $validTokens[$token] ?? false;
}

function sendJsonError($code, $message) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => $message]);
    exit;
}

function check_token() {
    $token = getBearerToken();
    if (!$token) {
        sendJsonError(401, 'Bearer token required');
    }
    
    $user = isValidToken($token);
    if (!$user) {
        sendJsonError(401, 'Invalid or expired token');
    }
    
    return $user; // Returns authenticated username
}