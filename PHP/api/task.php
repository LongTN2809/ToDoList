<?php
session_start();
ob_start(); // ← bắt hết output lại
include("../connect.inc");
ob_clean(); // ← xoá sạch output trước khi gửi JSON
header("Content-type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$method = $_SERVER['REQUEST_METHOD'];


// Hàm lấy data
function getData($res)
{
    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = [
            "id" => $row['id'],
            "userId" => $row['userId'],
            "title" => $row['title'],
            "completed" => $row['complete'],
            "create_Time" => $row['created_at'],
            "priority" => $row['priority']
        ];
    }
    echo json_encode($data);

}

// Load data
if ($method === 'GET') {
    $userId = (int) ($_GET['userId'] ?? 1);

    if (isset($_GET['sortValue']) && $_GET['sortValue']) {
        $sortValue = in_array($_GET['sortValue'], ['ASC', 'DESC']) ? $_GET['sortValue'] : 'ASC';
        $result = mysqli_query($conn, "SELECT * FROM task WHERE userId = $userId ORDER BY priority $sortValue");
    } else {
        $result = mysqli_query($conn, "SELECT * FROM task WHERE userId = $userId LIMIT 10");
    }

    if ($result) {
        getData($result);
        exit;
    }
}

// Sort task
if (isset($_GET['sortValue']) && $_GET['sortValue']) {
    $sortValue = $_GET['sortValue'];
    $userId = $data['userId'];
    echo json_encode(["Sort value" => $sortValue, "User id" => $userId]);
    // error_log("==== MY DEBUG ====");
    // error_log("Sort value " . $sortValue);
    // error_log("User id " . $userId);
    $result = mysqli_query($conn, "SELECT * FROM task WHERE userId = 1 ORDER BY priority $sortValue");
    if ($result) {
        getData($result);
        exit;
    } else {
        echo json_encode(["error" => mysqli_error($conn)]);
    }

}

// Insert Data
if ($method === 'POST') {
    if (!$data || !isset($data['userId'], $data['title'])) {
        $response = ["err" => "Data is not valid"];
        echo json_encode($response);
        exit;
    }
    $complete = $data['completed'] ? 1 : 0;

    $insertQuery = "INSERT INTO task ( userId , title , complete , priority)
                        VALUES ('" . $data['userId'] . "' , '" . $data['title'] . "' , '" . $complete . "' , '" . $data['priority'] . "')";
    $isQuerySuccess = mysqli_query($conn, $insertQuery);

    if ($isQuerySuccess) {
        $newId = mysqli_insert_id($conn);
        $selectTime = "SELECT created_at FROM task WHERE id = '" . $newId . "'"; // Lay thoi gian tao cua task vua duoc them vao
        $result = mysqli_query($conn, $selectTime);
        $row = mysqli_fetch_assoc($result);
        $response = [
            "id" => $newId,
            "userId" => $data['userId'],
            "title" => $data['title'],
            "completed" => $data['completed'],
            "create_Time" => $row['created_at'],
            "priority" => $data['priority']
        ];
        echo json_encode($response);
    } else {
        $response = ["err" => mysqli_error($conn)];
        echo json_encode($response);
    }
}

// Delete data
if ($method === "DELETE") {
    $taskId = $_GET['id'];
    $deleteQuery = "DELETE FROM task WHERE id = '$taskId'";
    $res = mysqli_query($conn, $deleteQuery);
    if ($res) {
        echo json_encode(["delete" => true]);
    } else {
        echo json_encode(["err" => mysqli_error($conn)]);
    }
}

// Patch data
if ($method === "PATCH") {

    $taskId = $_GET['id'];

    if ($data['typeEdit'] === "status") {
        $status = $data['completed'] ? 1 : 0;
        $updateQuery = "UPDATE task SET complete = '$status' WHERE id = '$taskId'";
        $res = mysqli_query($conn, $updateQuery);
        $getStatus = "SELECT complete FROM task WHERE id = '$taskId'";
        $newStatus = mysqli_query($conn, $getStatus);
        $row = mysqli_fetch_assoc($newStatus);
        if ($res)
            echo json_encode(["status" => $row['complete']]);
        else
            echo json_encode(["err" => mysqli_error($conn)]);
    } else {
        $newTitle = $data['title'];
        $updateQuery = "UPDATE task SET title = '$newTitle' WHERE id = '$taskId'";
        $res = mysqli_query($conn, $updateQuery);
        if ($res)
            echo json_encode(["msg" => "Update title successfully!"]);
        else
            echo json_encode(["err" => mysqli_error($conn)]);
    }

}
?>