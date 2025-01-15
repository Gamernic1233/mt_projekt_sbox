<?php
namespace Src;

use PDO;

class Database {
    private $connection;

    public function __construct() {
        $host = getenv('DB_HOST', true) ?: 'db';
        $db = getenv('DB_DATABASE', true) ?: 'my_database';
        $user = getenv('DB_USERNAME', true) ?: 'admin';
        $pass = getenv('DB_PASSWORD', true) ?: 'admin';

        $dsn = "pgsql:host=$host;port=5432;dbname=$db";
        $this->connection = new PDO($dsn, $user, $pass);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getConnection() {
        return $this->connection;
    }
}
