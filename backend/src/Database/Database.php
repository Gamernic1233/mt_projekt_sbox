<?php
namespace Backend\Database;

use PDO;

class Database {
    private $host;
    private $db;
    private $user;
    private $pass;

    public function __construct() {
        // Fetch environment variables for database connection
        $this->host = getenv('DB_HOST') ?: 'localhost';  // Default to 'localhost' if not set
        $this->db = getenv('DB_NAME') ?: 'my_database';  // Default to 'my_database' if not set
        $this->user = getenv('DB_USER') ?: 'admin';      // Default to 'admin' if not set
        $this->pass = getenv('DB_PASSWORD') ?: 'admin';  // Default to 'admin' if not set
    }

    public function getConnection() {
        // DSN for PostgreSQL (without charset)
        $dsn = "pgsql:host={$this->host};port=5432;dbname={$this->db};";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   // Error handling
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch results as associative arrays
        ];

        return new PDO($dsn, $this->user, $this->pass, $options);
    }
}
