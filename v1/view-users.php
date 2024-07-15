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

// Include error class
include("../class/Error.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    include("../database/Database.php");
    include("../class/User.php");

    // Handle GET request
    $db = new Database();
    
    // Check database connection
    if (!$db->isConnected()) {
        // Server error
        Err::_500(0, "Database connection failed, try again");
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
            Err::_404(0, "No user data found");
            exit;
        }
    } else {
        // Server error
        Err::_500();
        exit;
    }
} else {
    // Method not allowed
    Err::_405();
    exit;
}