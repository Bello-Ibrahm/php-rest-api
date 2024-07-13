<?php
// ini_set('display_errors', 1);

// Allow from any origin with the necessary headers and methods
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle OPTIONS method for CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include("../database/Database.php");
    include("../class/User.php");

    // Handle GET request
    $db = new Database();
    
    // Check database connection
    if (!$db->isConnected()) {
        http_response_code(500); // Server error
        echo json_encode([
            "status" => 0,
            "message" => "Lost connection to the database, try again"
        ]);
        exit;
    }
        
    $user = new User($db);
    $data = $user->allUser();

    if ($data !== false) {
        // Data retrieved successfully
        if (!empty($data)) {
            http_response_code(200);
            echo json_encode([
                "status" => 1,
                "message" => "Ok",
                "data" => $data
            ]);
        } else {
            // No data found
            http_response_code(400);
            echo json_encode([
                "status" => 0,
                "message" => "No user data found"
            ]);
        }
    } else {
        // Server error
        http_response_code(500);
        echo json_encode([
            "status" => 0,
            "message" => "Server error, try again!"
        ]);
    }
} else {
    // Method not allowed
    http_response_code(503);
    echo json_encode([
        "status" => 0,
        "message" => "Method not allowed"
    ]);
}