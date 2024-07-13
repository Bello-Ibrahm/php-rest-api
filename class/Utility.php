<?php
// Autoload dependencies using Composer
require_once "../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;


class Utility {
    private static $dbCon;
    private static $email;

    private static $password;
    private static $hashedPWD;

    private static $jwt;
    private static $secret_key;
    private static $payload;
    private static $signature;

    public static function userLogin($dbCon, $email)
    {
        self::$dbCon = $dbCon;
        self::$email = $email;
        $tableName = "users";

        $stmt = "SELECT * FROM ".$tableName." WHERE email = :email;";
        self::$dbCon->query($stmt);

        self::$dbCon->bind(':email', self::$email);
        
        if (self::$dbCon->execute())
        {
            if (self::$dbCon->rowCount() > 0) {
                return self::$dbCon->get();
            }
            return null;
        }
        return false;
    }

    public static function checkEmailExist($dbCon, $email)
    {
        self::$dbCon = $dbCon;
        self::$email = $email;
        $tableName = "users";

        // Checks if email exist in the database by returning number of row
        $stmt = "SELECT * FROM ".$tableName . " WHERE email = :email;";
        self::$dbCon->query($stmt);
        self::$dbCon->bind(":email", self::$email);

        if (self::$dbCon->execute())
        {
            return (self::$dbCon->rowCount() > 0) ? self::$dbCon->rowCount() : [];
        }
        return false;
    }

    public static function confirmPassword($password, $hashedPWD){
        self::$password = $password;
        self::$hashedPWD = $hashedPWD;
        // Uncomment one of the below lines based on the hashed algorithmn used to encrypt password
        // $h_input_pwd = hash('SHA256', $password); // SHA256 
        // $h_input_pwd = md5($password); // md5
        // $confirmed = $h_input_pwd === $hashedPWD; 
        
        return password_verify(self::$password, self::$hashedPWD); // return True if confirmed otherwise false
    }

    public static function encodeJWT($payload, $secret_key, $signature){
        self::$payload = $payload;
        self::$secret_key = $secret_key;
        self::$signature = $signature;
        
        return JWT::encode(self::$payload, self::$secret_key, self::$signature);
    }

    public static function decodeJWT($jwt, $secret_key){
        self::$jwt = $jwt;
        self::$secret_key = $secret_key;
        
        try {
            // Decode JWT token
            // $decoded = JWT::decode($jwt, $secret_key, array('HS384'));
            if (self::$jwt === null ) {
                return false;
            }
            $decoded = JWT::decode(self::$jwt, new Key(self::$secret_key, 'HS384'));
            return $decoded;
        } catch (SignatureInvalidException $e) {
            // Invalid signature
            return null;
        } catch (Exception $e) {
            return null; // Other exeption
        }
    }
}