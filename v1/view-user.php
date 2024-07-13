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

    // Fetch user data by ID
    $userData = $user->getByID($params['id']);

    // Check if data is retrieved
    if ($userData !== false) {
        // Data retrieved successfully
        if (!empty($userData)) {
            http_response_code(200);
            echo json_encode([
                "status" => 1,
                "message" => "Ok",
                "data" => $userData
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
        http_response_code(500); // Server error
        echo json_encode([
            "status" => 0,
            "message" => "Server error, try again"
        ]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        "status" => 0,
        "message" => "Method not allowed"
    ]);
}
?>
