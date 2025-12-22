<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'olxclone');
define('DB_USER', 'root');     // Default XAMPP username
define('DB_PASS', '');         // Default XAMPP password is empty

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Start session if not already started 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// PDO Database Connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Log the error (in a production environment, log to a file instead of displaying)
    error_log("Database Connection Error: " . $e->getMessage());
    
    // Display a user-friendly error message
    die("Connection failed. Please try again later or contact the administrator.");
}

/**
 * Helper function to execute prepared statements
 * 
 * @param string $sql SQL query with placeholders
 * @param array $params Parameters to bind
 * @return PDOStatement|false Returns the PDOStatement object or false on failure
 */
function executeQuery($sql, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        error_log("Query Error: " . $e->getMessage() . "\nSQL: $sql");
        return false;
    }
}

// Define base URL for easier reference
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/OLXCLONE');

// File upload configuration
define('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'] . '/OLXCLONE/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
$allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];

// Create uploads directory if it doesn't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}
?>
