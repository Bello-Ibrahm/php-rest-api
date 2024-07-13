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

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // Get POST data
    $params = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (empty($params['name']) || empty($params['email']) || empty($params['password'])) {
        http_response_code(400); // Bad request
        echo json_encode([
            "status" => 0,
            "message" => "name, email, and password are required"
        ]);
        exit;
    }
    

    // Instantiate User class
    $user = new User($db);

    // Check if email exist 
    $userEmail = Utility::checkEmailExist($db, $params['email']);

    if (!empty($userEmail)) {
        http_response_code(409); // Server error
        echo json_encode([
            "status" => 0,
            "message" => "Email exists, use another email"
        ]);
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
            http_response_code(500); // Server error
            echo json_encode([
                "status" => 0,
                "message" => "Failed to create user"
            ]);
        }
    }


    
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        "status" => 0,
        "message" => "Method not allowed"
    ]);
}
