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

// Include error class
include("../classes/Error.php");

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
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
    if (empty($params['id']) || empty($params['name']) || empty($params['password']) || empty($params['role'])) {
        // Required error
        Err::_402(0, "id, name, password, and role are required");
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
        // Not found error
        Err::_404(0, "No record to update");
        exit;
    } else {
        // Not found error
        Err::_500();
        exit;
    }
} else {
    // Method not allowed
    Err::_405();
    exit;
}
