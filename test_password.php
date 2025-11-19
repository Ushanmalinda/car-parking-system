<?php
// Test password hashing and verification

// Generate new hash for admin123
$password = 'admin123';
$new_hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Password Testing</h2>";
echo "<p><strong>Password:</strong> admin123</p>";
echo "<p><strong>New Generated Hash:</strong> $new_hash</p>";

// The hash from database
$db_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
echo "<p><strong>Database Hash:</strong> $db_hash</p>";

// Test verification
$verify_result = password_verify($password, $db_hash);
echo "<p><strong>Verification Result:</strong> " . ($verify_result ? 'SUCCESS ✓' : 'FAILED ✗') . "</p>";

// Test with new hash
$verify_new = password_verify($password, $new_hash);
echo "<p><strong>New Hash Verification:</strong> " . ($verify_new ? 'SUCCESS ✓' : 'FAILED ✗') . "</p>";

echo "<hr>";
echo "<h3>Database Check</h3>";

// Check database connection and user
require_once 'config/config.php';
require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

if ($conn) {
    echo "<p>✓ Database connection successful</p>";
    
    // Check if users table exists
    $result = $conn->query("SHOW TABLES LIKE 'users'");
    if ($result->num_rows > 0) {
        echo "<p>✓ Users table exists</p>";
        
        // Get admin user
        $stmt = $conn->prepare("SELECT id, username, password, full_name, role, status FROM users WHERE username = 'admin'");
        $stmt->execute();
        $user_result = $stmt->get_result();
        
        if ($user_result->num_rows > 0) {
            $user = $user_result->fetch_assoc();
            echo "<p>✓ Admin user found</p>";
            echo "<p><strong>ID:</strong> {$user['id']}</p>";
            echo "<p><strong>Username:</strong> {$user['username']}</p>";
            echo "<p><strong>Full Name:</strong> {$user['full_name']}</p>";
            echo "<p><strong>Role:</strong> {$user['role']}</p>";
            echo "<p><strong>Status:</strong> {$user['status']}</p>";
            echo "<p><strong>Password Hash:</strong> {$user['password']}</p>";
            
            // Test password verification
            $test_verify = password_verify('admin123', $user['password']);
            echo "<p><strong>Password Verification:</strong> " . ($test_verify ? 'SUCCESS ✓' : 'FAILED ✗') . "</p>";
            
            if (!$test_verify) {
                echo "<div style='background: #fff3cd; padding: 10px; border: 1px solid #ffc107; margin: 10px 0;'>";
                echo "<strong>⚠ Password verification failed!</strong><br>";
                echo "The password hash in the database doesn't match 'admin123'.<br>";
                echo "You need to update the password hash in the database.<br>";
                echo "<strong>Use this SQL command:</strong><br>";
                echo "<code>UPDATE users SET password = '$new_hash' WHERE username = 'admin';</code>";
                echo "</div>";
            }
        } else {
            echo "<p>✗ Admin user not found in database</p>";
            echo "<p>Please run the database/car_parking_system.sql file to create the default admin user</p>";
        }
        $stmt->close();
    } else {
        echo "<p>✗ Users table does not exist</p>";
        echo "<p>Please import the database/car_parking_system.sql file</p>";
    }
} else {
    echo "<p>✗ Database connection failed</p>";
    echo "<p>Error: " . $db->getError() . "</p>";
}

$db->closeConnection();
?>
