<?php
// ini_set('display_errors', 1);
require("../vendor/autoload.php");

/* 
*  PDO DATABASE CLASS
*/
// require_once('config/config.php');

class Database {
	private $host;
	private $user;
	private $pass;
	private $dbname;
	
	private $connection;
	private $error;
	private $stmt;
	private $dbconnected = false;
	
	public function __construct() {
		if (!isset($_ENV['DB_HOST'])) {
			try {
				// Load environment variables if not already loaded
				$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '../../');
				$dotenv->load();
			} catch (Dotenv\Exception\InvalidPathException $e) {
				// Handle dotenv exception (e.g., log error, fallback to default values)
				echo "Error loading .env file: " . $e->getMessage();
				exit();
			}
        }
        
        // Initialize properties
        $this->host = $_ENV['DB_HOST'];
        $this->user = $_ENV['DB_USER'];
        $this->pass = $_ENV['DB_PASS'];
        $this->dbname = $_ENV['DB_NAME'];

		// Set PDO Connection
		// $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8'; // mysql connection
		$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4'; // Note the charset utf8mb4 for MariaDB/MySQL
		$options = array (
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION 
		);

		// Create a new PDO instanace
		try {
			$this->connection = new PDO ($dsn, $this->user, $this->pass, $options);
			$this->dbconnected = true;

		}		// Catch any errors
		catch ( PDOException $e ) {
			$this->error = $e->getMessage() . PHP_EOL;
			// error_log('PDO Connection Error: ' . $e->getMessage());
		}
	}

	//Get the Error Message
	public function getDBError(){
		return $this->error;
	}

	//Check the connection status
	public function isConnected(){
		return $this->dbconnected;
	}
	
	// Prepare statement with query
	public function query($query) {
		$this->stmt = $this->connection->prepare($query);
	}		

	// Execute the prepared statement
	public function execute(){
		return $this->stmt->execute();
	}	
	
	// Get result set as array of objects
	public function getAll(){
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_OBJ);
	}

	// Get record row count
	public function rowCount(){
		return $this->stmt->rowCount();
	}	

	// Get single record as object
	public function get(){
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_OBJ);
	}	
	

	// Bind values
	public function bind($param, $value, $type = null) {
		if (is_null ($type)) {
			switch (true) {
				case is_int ($value) :
					$type = PDO::PARAM_INT;
					break;
				case is_bool ($value) :
					$type = PDO::PARAM_BOOL;
					break;
				case is_null ($value) :
					$type = PDO::PARAM_NULL;
					break;
				default :
					$type = PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}		
}