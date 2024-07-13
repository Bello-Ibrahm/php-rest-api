<?php
// Set error reporting for development
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// Allow from any origin with the necessary headers and methods
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle OPTIONS method for CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit; // Return empty response for OPTIONS
}

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    // Database, User, and Utility class includes
    include("../database/Database.php");
    include("../class/User.php");
    include("../class/Utility.php"); 

    // Initialize database connection
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

    // Get Authorization header
    $jwt = null;
    if (isset($_SERVER['HTTP_AUTHORIZATION']) && strpos($_SERVER['HTTP_AUTHORIZATION'], 'Bearer ') === 0) {
        $jwt = substr($_SERVER['HTTP_AUTHORIZATION'], 7); // Remove 'Bearer ' from the token
    }

    // Validate JWT token
    $secret_key = $_ENV['SECRET_KEY'];
    $decoded = Utility::decodeJWT($jwt, $secret_key);

    if ($decoded) {
        // JWT token is valid
        // Get response with user data from JWT payload
        $userRole = $decoded->data->role;
        if ($userRole === 1) { // Admin access
            http_response_code(200);
            echo json_encode([
                "status" => 1,
                "message" => "Admin page access"
            ]); 
        } else if ($userRole === 0) { // Normal user
            http_response_code(200);
            echo json_encode([
                "status" => 1,
                "message" => "User page access"
            ]);
        } else {
            http_response_code(403);
            echo json_encode([
                "status" => 0,
                "message" => "Access restricted"
            ]);
        }
    } else {
        // JWT token is invalid or expired
        http_response_code(401); // Unauthorized
        echo json_encode([
            "status" => 0,
            "message" => "Invalid token"
        ]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        "status" => 0,
        "message" => "Method not allowed"
    ]);
}