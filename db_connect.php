<?php
/**
 * Database Connection for InfinityFree
 * Using MySQLi with a Database Singleton Class
 * 
 * IMPORTANT: Include this file AFTER config.php
 */

// Get database credentials from config.php if available, otherwise use defaults
if (!defined('DB_HOST')) {
    // Fallback credentials (should match config.php)
    define('DB_HOST', 'sql213.infinityfree.com');
    define('DB_USER', 'if0_41198744');
    define('DB_PASS', 'PD84JL9Doz');
    define('DB_NAME', 'if0_41198744_cytti');
    define('DB_PORT', 3306);
}

$host = DB_HOST;
$user = DB_USER;
$pass = DB_PASS;
$db   = DB_NAME;
$port = DB_PORT;

/**
 * Database Singleton Class
 * Provides methods for executing queries
 */
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        global $host, $user, $pass, $db, $port;
        
        // Suppress mysqli error display, we'll handle it ourselves
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        
        try {
            $this->conn = new mysqli($host, $user, $pass, $db, $port);
            
            if ($this->conn->connect_error) {
                throw new Exception("Database connection failed: " . $this->conn->connect_error);
            }
            
            // Set charset to UTF-8
            $this->conn->set_charset("utf8mb4");
            
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                error_log("Database connection established successfully");
            }
            
        } catch (Exception $e) {
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                error_log("Database Connection Error: " . $e->getMessage());
            }
            throw $e; // Re-throw to let the calling code handle it
        }
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
            throw new Exception("Prepare failed: " . $this->conn->error . " SQL: " . $sql);
        }
        
        // Bind parameters dynamically
        $types = str_repeat('s', count($values));
        $stmt->bind_param($types, ...$values);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error . " SQL: " . $sql);
        }
        
        $insertId = $stmt->insert_id;
        $stmt->close();
        
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            error_log("Insert successful. Table: $table, Insert ID: $insertId");
        }
        
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
            throw new Exception("Prepare failed: " . $this->conn->error . " SQL: " . $sql);
        }
        
        $values = array_values($data);
        $allParams = array_merge($values, $whereParams);
        
        $types = str_repeat('s', count($allParams));
        $stmt->bind_param($types, ...$allParams);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error . " SQL: " . $sql);
        }
        
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            error_log("Update successful. Table: $table, Affected rows: $affectedRows");
        }
        
        return $affectedRows;
    }
    
    /**
     * Delete data from a table
     */
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM $table WHERE $where";
        
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error . " SQL: " . $sql);
        }
        
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error . " SQL: " . $sql);
        }
        
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            error_log("Delete successful. Table: $table, Affected rows: $affectedRows");
        }
        
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

// DO NOT create a global $conn here - let the Database class handle all connections
// This was causing duplicate connection issues
?>
