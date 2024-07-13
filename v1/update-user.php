<?php
// ini_set('display_errors', 1);

// Allow from any origin with the necessary headers and methods
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle OPTIONS method for CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
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
    if (empty($params['id']) || empty($params['name']) || empty($params['password']) || empty($params['role'])) {
        http_response_code(400); // Bad request
        echo json_encode([
            "status" => 0,
            "message" => "id, name, password, and role are required"
        ]);
        exit;
    }
    

    // Instantiate User class
    $user = new User($db);

    // Update user
    $result = $user->updateUser($params['id'], $params['name'], $params['password'], $params['role']);
    if ($result) {
        http_response_code(200); // Updated
        echo json_encode([
            "status" => 1,
            "message" => "User updated successfully"
        ]);
    } else if ($result === null) {
        http_response_code(400); // Invalid request
        echo json_encode([
            "status" => 0,
            "message" => "No record to update"
        ]);
    } else {
        http_response_code(500); // Server error
        echo json_encode([
            "status" => 0,
            "message" => "Failed to update user"
        ]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        "status" => 0,
        "message" => "Method not allowed"
    ]);
}
