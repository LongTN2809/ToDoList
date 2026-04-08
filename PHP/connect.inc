<?php
try{
  $pdo = new PDO("mysql:host=localhost; dbname=todolist" , "root" , "");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e){
    header("Content-type: application/json");
    echo json_encode(["err" => "Can't connect to database"]);
    exit;
}
?>