<?php

class Err {
    private static $status;
    private static $message;

    public static function _400(int $status=null, string $message=null) {
        self::$status = $status ?? 0; 
        self::$message = $message ?? "Bad Request";

        http_response_code(400); // Bad Request
        echo json_encode([
            "status" => self::$status,
            "message" => self::$message
        ]);
    }

    public static function _401(int $status=null, string $message=null) {
        self::$status = $status ?? 0; 
        self::$message = $message ?? "Unauthorized";

        http_response_code(401); // Unauthorized
        echo json_encode([
            "status" => self::$status,
            "message" => self::$message
        ]);
    }

    public static function _402(int $status=null, string $message=null) {
        self::$status = $status ?? 0; 
        self::$message = $message ?? "Field Required";

        http_response_code(402); // Field Required
        echo json_encode([
            "status" => self::$status,
            "message" => self::$message
        ]);
    }

    public static function _403(int $status=null, string $message=null) {
        self::$status = $status ?? 0; 
        self::$message = $message ?? "Forbidden";

        http_response_code(403); // Forbidden
        echo json_encode([
            "status" => self::$status,
            "message" => self::$message
        ]);
    }

    public static function _404(int $status=null, string $message=null) {
        self::$status = $status ?? 0; 
        self::$message = $message ?? "Not Found";

        http_response_code(404); // Not Found
        echo json_encode([
            "status" => self::$status,
            "message" => self::$message
        ]);
    }

    public static function _405(int $status=null, string $message=null) {
        self::$status = $status ?? 0; 
        self::$message = $message ?? "Method not allowed";

        http_response_code(405); // Not allowed
        echo json_encode([
            "status" => self::$status,
            "message" => self::$message
        ]);
    }

    public static function _409(int $status=null, string $message=null) {
        self::$status = $status ?? 0; 
        self::$message = $message ?? "Conflict";

        http_response_code(409); // Conflict
        echo json_encode([
            "status" => self::$status,
            "message" => self::$message
        ]);
    }

    public static function _415(int $status=null, string $message=null) {
        self::$status = $status ?? 0; 
        self::$message = $message ?? "Unsupported file";

        http_response_code(409); // Unsupported media type
        echo json_encode([
            "status" => self::$status,
            "message" => self::$message
        ]);
    }

    public static function _500(int $status=null, string $message=null) {
        self::$status = $status ?? 0; 
        self::$message = $message ?? "Server Error, try again";

        http_response_code(500); // Internal Server Error
        echo json_encode([
            "status" => self::$status,
            "message" => self::$message
        ]);
    }

    public static function _501(int $status=null, string $message=null) {
        self::$status = $status ?? 0; 
        self::$message = $message ?? "Bad implementation";

        http_response_code(501); // Bad implementation
        echo json_encode([
            "status" => self::$status,
            "message" => self::$message
        ]);
    }

    public static function _502(int $status=null, string $message=null) {
        self::$status = $status ?? 0; 
        self::$message = $message ?? "Bad gateway";

        http_response_code(502); // Bad gateway
        echo json_encode([
            "status" => self::$status,
            "message" => self::$message
        ]);
    }

    public static function _503(int $status=null, string $message=null) {
        self::$status = $status ?? 0; 
        self::$message = $message ?? "Service Unavailable";

        http_response_code(503); // Service Unavailable
        echo json_encode([
            "status" => self::$status,
            "message" => self::$message
        ]);
    }
}
