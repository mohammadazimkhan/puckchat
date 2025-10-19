<?php
// Test database connection
require_once '../includes/config.php';
require_once '../includes/database.php';

try {
    echo "<h2>Database Connection Test</h2>";
    echo "<p style='color: green;'>✅ Database connected successfully!</p>";
    echo "<p>Host: " . DB_HOST . "</p>";
    echo "<p>Database: " . DB_NAME . "</p>";
    echo "<p>User: " . DB_USER . "</p>";
    
    // Test query
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    
    echo "<h3>Tables in database:</h3>";
    echo "<ul>";
    foreach($tables as $table) {
        echo "<li>" . $table[0] . "</li>";
    }
    echo "</ul>";
    
} catch(Exception $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
}
?>