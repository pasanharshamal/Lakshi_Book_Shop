<?php
require_once 'includes/db.php';

echo "<h1>Lakshi Book Shop - Database Check</h1>";

if ($conn->connect_error) {
    echo "<p style='color:red;'>❌ Connection Failed: " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color:green;'>✅ Connection Successful!</p>";

    $result = $conn->query("SELECT COUNT(*) as count FROM products");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "<p>📦 Total Books in Database: <strong>" . $row['count'] . "</strong></p>";
        if ($row['count'] == 0) {
            echo "<p style='color:orange;'>⚠️ Warning: Your 'products' table is empty. Please re-import <strong>database.sql</strong> into phpMyAdmin.</p>";
        } else {
            echo "<p style='color:green;'>✅ Found " . $row['count'] . " books. Your shop should display them correctly.</p>";
        }
    } else {
        echo "<p style='color:red;'>❌ Error querying 'products' table. Did you import <strong>database.sql</strong>?</p>";
    }
}

echo "<hr><p><a href='index.php'>Return to Home</a> | <a href='shop.php'>Go to Shop</a></p>";
?>
