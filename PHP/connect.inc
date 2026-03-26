<?php
$conn = mysqli_connect("localhost", "root", "", "todolist");
if(!$conn){
    header("Content-type: application/json");
    echo json_encode(["err" => "Can't connect to database"]);
    exit;
}
?>