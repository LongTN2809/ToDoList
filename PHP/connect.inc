<?php
try {

    $host = $_ENV['MYSQLHOST'] ?? "localhost";
    $db   = $_ENV['MYSQLDATABASE'] ?? "todolist";
    $user = $_ENV['MYSQLUSER'] ?? "root";
    $pass = $_ENV['MYSQLPASSWORD'] ?? "";
    $port = $_ENV['MYSQLPORT'] ?? 3306;

    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch(PDOException $e){
    header("Content-Type: application/json");
    echo json_encode(["err" => "Can't connect to database"]);
    exit;
}
?>