<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../includes/config.php");


try {
    $stmt = $con->query("SELECT COUNT(*) as users_online FROM users WHERE isOnline = 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $result['users_online'];
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}