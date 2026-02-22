<?php
/**
 * Database Connection for Supabase PostgreSQL
 * Using PostgreSQL with a Database Singleton Class
 * 
 * IMPORTANT: Include this file AFTER config.php
 * Uses environment variables for database credentials
 */

// Get database credentials from environment variables (for Supabase)
$host = getenv('DB_HOST') ?: 'aws-0-eu-west-1.pooler.supabase.com';
$user = getenv('DB_USER') ?: 'postgres';
$pass = getenv('DB_PASS') ?: 'Stalker@2024##';
$db   = getenv('DB_NAME') ?: 'postgres';
$port = (int)(getenv('DB_PORT')) ?: 6543;

/**
 * Database Singleton Class for PostgreSQL
 * Provides methods for executing queries
 */
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        global $host, $user, $pass, $db, $port;
        
        // Build connection string for PostgreSQL
        $connectionString = "host=$host port=$port dbname=$db user=$user password=$pass";
        
        // Suppress error display, we'll handle it ourselves
        error_reporting(E_ALL);
        
        try {
            $this->conn = pg_connect($connectionString);
            
            if (!$this->conn) {
                throw new Exception("Database connection failed");
            }
            
            // Set client encoding to UTF-8
            pg_set_client_encoding($this->conn, "UTF8");
            
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                error_log("PostgreSQL database connection established successfully");
            }
            
        } catch (Exception $e) {
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                error_log("Database Connection Error: " . $e->getMessage());
            }
            throw $e;
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
     * Get the pg connection
     */
    public function getConnection() {
        return $this->conn;
    }
    
    /**
     * Convert MySQL-style ? placeholders to PostgreSQL $1, $2, etc.
     */
    private function convertPlaceholders($sql, $params) {
        if (empty($params)) {
            return [$sql, $params];
        }
        
        // Check if SQL contains ? placeholders
        if (strpos($sql, '?') === false) {
            return [$sql, $params];
        }
        
        // Replace ? with $1, $2, $3, etc.
        $paramIndex = 1;
        $newSql = '';
        for ($i = 0; $i < strlen($sql); $i++) {
            if ($sql[$i] === '?') {
                $newSql .= '$' . $paramIndex;
                $paramIndex++;
            } else {
                $newSql .= $sql[$i];
            }
        }
        
        return [$newSql, $params];
    }
    
    /**
     * Execute a query and return all results
     */
    public function fetchAll($sql, $params = []) {
        // Convert ? placeholders to $1, $2, etc. for PostgreSQL
        list($sql, $params) = $this->convertPlaceholders($sql, $params);
        
        if (empty($params)) {
            $result = pg_query($this->conn, $sql);
        } else {
            $result = pg_query_params($this->conn, $sql, $params);
        }
        
        if (!$result) {
            throw new Exception("Query failed: " . pg_last_error($this->conn) . " SQL: " . $sql);
        }
        
        $rows = [];
        while ($row = pg_fetch_assoc($result)) {
            $rows[] = $row;
        }
        pg_free_result($result);
        return $rows;
    }
    
    /**
     * Execute a query and return single result
     */
    public function fetchOne($sql, $params = []) {
        $rows = $this->fetchAll($sql, $params);
        return $rows[0] ?? null;
    }
    
    /**
     * Insert data into a table
     */
    public function insert($table, $data) {
        $columns = array_keys($data);
        $values = array_values($data);
        
        $columnList = implode(', ', $columns);
        
        // Create placeholders: $1, $2, $3, etc.
        $placeholders = [];
        for ($i = 1; $i <= count($values); $i++) {
            $placeholders[] = '$' . $i;
        }
        $placeholders = implode(', ', $placeholders);
        
        $sql = "INSERT INTO $table ($columnList) VALUES ($placeholders) RETURNING id";
        
        $result = pg_query_params($this->conn, $sql, $values);
        
        if (!$result) {
            throw new Exception("Insert failed: " . pg_last_error($this->conn) . " SQL: " . $sql);
        }
        
        $row = pg_fetch_assoc($result);
        $insertId = $row['id'] ?? null;
        pg_free_result($result);
        
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
        $paramIndex = 1;
        
        foreach (array_keys($data) as $column) {
            $setParts[] = "$column = $" . $paramIndex;
            $paramIndex++;
        }
        
        $setClause = implode(', ', $setParts);
        
        $values = array_values($data);
        $allParams = array_merge($values, $whereParams);
        
        $sql = "UPDATE $table SET $setClause WHERE $where";
        
        $result = pg_query_params($this->conn, $sql, $allParams);
        
        if (!$result) {
            throw new Exception("Update failed: " . pg_last_error($this->conn) . " SQL: " . $sql);
        }
        
        $affectedRows = pg_affected_rows($result);
        pg_free_result($result);
        
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
        
        $result = pg_query_params($this->conn, $sql, $params);
        
        if (!$result) {
            throw new Exception("Delete failed: " . pg_last_error($this->conn) . " SQL: " . $sql);
        }
        
        $affectedRows = pg_affected_rows($result);
        pg_free_result($result);
        
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            error_log("Delete successful. Table: $table, Affected rows: $affectedRows");
        }
        
        return $affectedRows;
    }
    
    /**
     * Get last insert ID
     */
    public function getLastInsertId() {
        $result = pg_query($this->conn, "SELECT lastval() as id");
        $row = pg_fetch_assoc($result);
        pg_free_result($result);
        return $row['id'] ?? null;
    }
    
    /**
     * Get affected rows
     */
    public function getAffectedRows() {
        return pg_affected_rows($this->conn);
    }
    
    /**
     * Close connection
     */
    public function close() {
        if ($this->conn) {
            pg_close($this->conn);
        }
    }
    
    // Prevent cloning
    private function __clone() {}
    
    // Prevent unerialization
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

// DO NOT create a global $conn here - let the Database class handle all connections
?>
