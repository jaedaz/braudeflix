<?php
ob_start(); // تشغيل التخزين المؤقت للمخرجات
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // بدء الجلسة إذا لم تكن قد بدأت بعد
}

date_default_timezone_set("Asia/Jerusalem"); // ضبط منطقة التوقيت

try {
    $con = new PDO("mysql:dbname=braudeflix;host=localhost", "root", "");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch (PDOException $e) {
    exit("Connection failed: " . $e->getMessage());
}
?>
