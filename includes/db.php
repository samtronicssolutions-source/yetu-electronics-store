<?php
require_once 'config.php';

class Database {
    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $port;
    private $conn;
    private $driver;

    public function __construct() {
        $this->host = DB_HOST;
        $this->user = DB_USER;
        $this->pass = DB_PASS;
        $this->dbname = DB_NAME;
        $this->port = DB_PORT;
        
        // Detect database driver
        $this->driver = getenv('DB_DRIVER') ?: (DB_PORT == '5432' ? 'pgsql' : 'mysql');
    }

    public function connect() {
        $this->conn = null;
        
        try {
            if ($this->driver == 'pgsql') {
                // PostgreSQL connection for Render
                $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
                $this->conn = new PDO($dsn, $this->user, $this->pass);
            } else {
                // MySQL connection for local development
                $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname}";
                $this->conn = new PDO($dsn, $this->user, $this->pass);
                $this->conn->exec("set names utf8");
            }
            
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch(PDOException $e) {
            // Log error but don't display in production
            if (getenv('APP_ENV') === 'development') {
                echo "Connection error: " . $e->getMessage();
            }
            error_log("Database connection error: " . $e->getMessage());
        }
        
        return $this->conn;
    }
}

// Global database connection
$db = new Database();
$pdo = $db->connect();
?>
