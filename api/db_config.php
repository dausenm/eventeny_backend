<?php
// Grab Railway-provided environment variables directly
$host = getenv("MYSQLHOST") ?: getenv("DB_HOST");
$port = getenv("MYSQLPORT") ?: getenv("DB_PORT") ?: 3306;
$db   = getenv("MYSQLDATABASE") ?: getenv("DB_NAME");
$user = getenv("MYSQLUSER") ?: getenv("DB_USER");
$pass = getenv("MYSQLPASSWORD") ?: getenv("DB_PASS");

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed", "details" => $e->getMessage()]);
    exit;
}
?>

