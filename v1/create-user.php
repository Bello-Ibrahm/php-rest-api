<?php
// ini_set('display_errors', 1);

// Allow from any origin with the necessary headers and methods
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle OPTIONS method for CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Include error class
include("../classes/Error.php");

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("../database/Database.php");
    include("../classes/User.php"); 
    include("../classes/Utility.php");

    // Initialize database connection
    $db = new Database();

    // Check database connection
    if (!$db->isConnected()) {
        // Server error
        Err::_500(0, "Database connection failed, try again");
        exit;
    }

    // Get POST data
    $params = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (empty($params['name']) || empty($params['email']) || empty($params['password'])) {
        // Required error
        Err::_402(0, "name, email, and password are required");
        exit;
    }
    

    // Instantiate User class
    $user = new User($db);

    // Check if email exist 
    $userEmail = Utility::checkEmailExist($db, $params['email']);

    if (!empty($userEmail)) {
        // Conflict error
        Err::_409(0, "Email exists, use another email");
        exit;
    } else {
        // Create user
        $result = $user->createUser($params['name'], $params['email'], $params['password']);
        if ($result) {
            http_response_code(201); // Created
            echo json_encode([
                "status" => 1,
                "message" => "User created successfully"
            ]);
        } else {
            // Server error
            Err::_500();
            exit;
        }
    }    
} else {
    // Method Not Allowed
    Err::_405();
    exit;
}
