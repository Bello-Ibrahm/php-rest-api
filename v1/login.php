<?php
// ini_set('display_errors', 1);

// Allow from any origin with the necessary headers and methods
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle OPTIONS method for CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Return response for preflight request
    header('HTTP/1.1 204 No Content');
    header('Access-Control-Max-Age: 86400'); // 1 day
    exit;
}

// Set Content-Type header for JSON responses
header('Content-Type: application/json; charset=UTF-8');

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
    if (empty($params['email']) || empty($params['password'])) {
        // Required error
        Err::_402(0, "email and password are required");
        exit;
    }

    // Instantiate User class
    $user = new User($db);

    $result = Utility::userLogin($db, $params['email']);
    if ($result) {
        if (Utility::confirmPassword($params['password'], $result->password)) {
            // Generate JWT upon successful login
            $userData = array(
                "id" => $result->id,
                "name" => $result->name,
                "email" => $result->email,
                "role" => $result->role
            );

            $secret_key = $_ENV['SECRET_KEY'];
            $issuer_claim = "An-Nur_Info-Tech"; // Issuer of the token
            $audience_claim = "Testing_purpose"; // Audience of the token
            $issuedat_claim = time(); // Time when the token was issued
            $notbefore_claim = $issuedat_claim + 10; // Token can't be used before this time
            $expire_claim = $issuedat_claim + 300; // Token expires in 5 minutes (60 * 60 * 24 * 30 => Token expires in 30 days 30 days) 

            $token = array(
                "iss" => $issuer_claim,
                "aud" => $audience_claim,
                "iat" => $issuedat_claim,
                "nbf" => $notbefore_claim,
                "exp" => $expire_claim,
                "data" => $userData
            );

            $jwt = Utility::encodeJWT($token, $secret_key, 'HS384');
            header("Authorization: Bearer {$jwt}"); 

            http_response_code(200);
            echo json_encode([
                "status" => 1,
                "message" => "Login successfully"
            ]);
        } else {
            // Not found error
            Err::_404(0, "Invalid password");
            exit;
        }
    } else {
        // Not found error
        Err::_404(0, "Invalid email");
        exit;
    }
    
} else {
    // Method not allowed
    Err::_405();
    exit;
}
?>
