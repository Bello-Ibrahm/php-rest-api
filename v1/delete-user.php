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

// Include error class
include("../class/Error.php");

// Check if the request method is DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    include("../database/Database.php");
    include("../class/User.php"); 
    include("../class/Utility.php");

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
    if (empty($params['id'])) {
        // Required error
        Err::_402(0, "User ID is required");
        exit;
    }
    
    // Instantiate User class
    $user = new User($db);

    // Delete user
    $result = $user->deleteUser($params['id']);
    if ($result) {
        http_response_code(204); // Deleted
    } else if ($result === null) {
        // Not found error
        Err::_404(0, "No record to delete");
        exit;
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
