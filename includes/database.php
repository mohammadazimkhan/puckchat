<?php
// Database Connection with Error Handling
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/logger.php';

class Database {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        $this->connect();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function connect() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
            ];
            
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            
            if (DEBUG_MODE) {
                Logger::info("Database connected successfully to " . DB_HOST);
            }
            
        } catch (PDOException $e) {
            $error = "Database connection failed: " . $e->getMessage();
            Logger::error($error);
            
            if (DEBUG_MODE) {
                die($error);
            } else {
                die("Database connection error. Please try again later.");
            }
        }
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    public function testConnection() {
        try {
            $stmt = $this->pdo->query("SELECT 1");
            return true;
        } catch (PDOException $e) {
            Logger::error("Database test failed: " . $e->getMessage());
            return false;
        }
    }
}

// Global database instance
$db = Database::getInstance();
$pdo = $db->getConnection();
?>