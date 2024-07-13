<?php
// ini_set('display_errors', 1);

// Allow from any origin with the necessary headers and methods
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle OPTIONS method for CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Check if the request method is DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
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
    if (empty($params['id'])) {
        http_response_code(400); // Bad request
        echo json_encode([
            "status" => 0,
            "message" => "User ID is required"
        ]);
        exit;
    }
    

    // Instantiate User class
    $user = new User($db);

    // Delete user
    $result = $user->deleteUser($params['id']);
    if ($result) {
        http_response_code(204); // Deleted
    } else if ($result === null) {
        http_response_code(400); // Invalid request
        echo json_encode([
            "status" => 0,
            "message" => "No record to delete"
        ]);
    } else {
        http_response_code(500); // Server error
        echo json_encode([
            "status" => 0,
            "message" => "Failed to delete user"
        ]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        "status" => 0,
        "message" => "Method not allowed"
    ]);
}
