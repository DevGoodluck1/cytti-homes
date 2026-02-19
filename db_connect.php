<?php
/**
 * Database Connection for InfinityFree
 * Using MySQLi with a Database Singleton Class
 */

// InfinityFree Database Credentials
$host = "sql213.infinityfree.com";
$user = "if0_41198744";
$pass = "PD84JL9Doz";
$db   = "if0_41198744_cytti";
$port = 3306;

/**
 * Database Singleton Class
 * Provides methods for executing queries
 */
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        global $host, $user, $pass, $db, $port;
        
        $this->conn = new mysqli($host, $user, $pass, $db, $port);
        
        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
        
        // Set charset to UTF-8
        $this->conn->set_charset("utf8mb4");
    }
    
    /**
     * Get singleton instance of Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Get the mysqli connection
     */
    public function getConnection() {
        return $this->conn;
    }
    
    /**
     * Execute a query and return all results
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->executeStatement($sql, $params);
        $result = $stmt->get_result();
        $rows = [];
        
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        
        $stmt->close();
        return $rows;
    }
    
    /**
     * Execute a query and return single result
     */
    public function fetchOne($sql, $params = []) {
        $stmt = $this->executeStatement($sql, $params);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $stmt->close();
        return $row;
    }
    
    /**
     * Insert data into a table
     */
    public function insert($table, $data) {
        $columns = array_keys($data);
        $values = array_values($data);
        
        $columnList = implode(', ', $columns);
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        
        $sql = "INSERT INTO $table ($columnList) VALUES ($placeholders)";
        
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }
        
        // Bind parameters dynamically
        $types = str_repeat('s', count($values));
        $stmt->bind_param($types, ...$values);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $insertId = $stmt->insert_id;
        $stmt->close();
        
        return $insertId;
    }
    
    /**
     * Update data in a table
     */
    public function update($table, $data, $where, $whereParams = []) {
        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "$column = ?";
        }
        
        $setClause = implode(', ', $setParts);
        $sql = "UPDATE $table SET $setClause WHERE $where";
        
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }
        
        $values = array_values($data);
        $allParams = array_merge($values, $whereParams);
        
        $types = str_repeat('s', count($allParams));
        $stmt->bind_param($types, ...$allParams);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        
        return $affectedRows;
    }
    
    /**
     * Delete data from a table
     */
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM $table WHERE $where";
        
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }
        
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        
        return $affectedRows;
    }
    
    /**
     * Execute a prepared statement
     */
    private function executeStatement($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error . " SQL: " . $sql);
        }
        
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error . " SQL: " . $sql);
        }
        
        return $stmt;
    }
    
    /**
     * Get last insert ID
     */
    public function getLastInsertId() {
        return $this->conn->insert_id;
    }
    
    /**
     * Get affected rows
     */
    public function getAffectedRows() {
        return $this->conn->affected_rows;
    }
    
    /**
     * Close connection
     */
    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
    
    // Prevent cloning
    private function __clone() {}
    
    // Prevent unserialization
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

// Create mysqli connection for backward compatibility (if needed)
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
