<?php
session_start();
ob_start(); // ← bắt hết output lại
include("../connect.inc");
ob_clean(); // ← xoá sạch output trước khi gửi JSON
header("Content-type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$method = $_SERVER['REQUEST_METHOD'];

// Lấy id của user
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => "Unauthorized"]);
    exit;
}
$user_id = $_SESSION['user_id'];

// Hàm lấy data để load lại các task 
// Dùng cho việc load task ban đầu hoặc load lại task khi cần sort
function getData($res)
{
    $data = [];
    foreach ($res as $row) { // Vi res bay gio la array tu fetchAll nen dung vong for de duyet thang
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
    // Sort task
    if (isset($_GET['sortValue']) && $_GET['sortValue']) {
        // Validate chỉ cho phép 2 giá trị hợp lệ để sort
        // in_array kiểm giá trị có tồn tại trong array hay không và trả về true / false
        // Cú pháp: in_array(value , array);
        $sortValue = in_array($_GET['sortValue'], ["ASC", "DESC"]) ? $_GET['sortValue'] : "ASC";  // nếu không hợp lệ thì mặc định giá trị trả về

        $sortTask = $pdo->prepare("SELECT * FROM task WHERE userId = ? ORDER BY priority $sortValue");
        $sortTask->execute([$user_id]);
        $result = $sortTask->fetchAll();

        if ($result) {
            getData($result);
            exit;
        } else {
            echo json_encode(["error" => 'Have an error with sort feature!']);
        }

    } else {

        $getTaskByUserId = $pdo->prepare("SELECT * FROM task WHERE userId = ? LIMIT 10"); // Lấy nhiều task => fetchAll
        // execute nhan 1 array , dung cho truong hop co nhieu ? de them vao cau lenh sql theo thu tu
        $getTaskByUserId->execute([$user_id]);
        $result = $getTaskByUserId->fetchAll();
        if ($result) {
            getData($result);
            exit;
        } else {
            echo json_encode(['error' => "Data can not be loaded!"]);
        }
        
    }

}



// Insert Data
if ($method === 'POST') {

    if (!$data || !isset($data['title'])) {
        $response = ["err" => "Data is not valid"];
        echo json_encode($response);
        exit;
    }

    try {

        $complete = $data['completed'] ? 1 : 0;
        $insertQuery = $pdo->prepare("INSERT INTO task ( userId , title , complete , priority)
                        VALUES (? , ?, ?, ?)");
        $insertQuery->execute([$user_id, $data['title'], $complete, $data['priority']]);

        $isInsertSuccess = $insertQuery->rowCount() > 0; // rowCount trả về số dòng và so sánh với 0 => return true / false

        if ($isInsertSuccess) {
            // Lấy id vừa insert
            $newId = $pdo->lastInsertId();

            // Lấy thời gian tạo của task vừa được thêm vào
            $selectTime = $pdo->prepare("SELECT created_at FROM task WHERE id = ?");
            $selectTime->execute([$newId]);
            $time = $selectTime->fetch();
            $response = [
                "id" => $newId,
                "userId" => $user_id,
                "title" => $data['title'],
                "completed" => $data['completed'],
                "create_Time" => $time['created_at'],
                "priority" => $data['priority']
            ];
            echo json_encode($response);
        }

    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }


}

// Delete data

if ($method === "DELETE") {
    try {

        $taskId = $_GET['id'];
        $deleteQuery = $pdo->prepare("DELETE FROM task WHERE id = ?");
        $deleteQuery->execute([$taskId]);
        $isDeleteSuccess = $deleteQuery->rowCount() > 0;

        if ($isDeleteSuccess) {
            echo json_encode(["delete" => true]);
        }

    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Patch data
if ($method === "PATCH") {
    $taskId = $_GET['id'];
    try {
        // Update status của task
        if ($data['typeEdit'] === "status") {
            $status = $data['completed'] ? 1 : 0;

            // Câu lệnh update
            $updateQuery = $pdo->prepare("UPDATE task SET complete = ? WHERE id = ?");
            $updateQuery->execute([$status, $taskId]);
            $isUpdateSuccess = $updateQuery->rowCount() > 0;

            // Lấy status để debug — đổi sang PDO
            $getStatus = $pdo->prepare("SELECT complete FROM task WHERE id = ?");
            $getStatus->execute([$taskId]);
            $row = $getStatus->fetch();

            if ($isUpdateSuccess)
                echo json_encode(["status" => $row['complete']]);

        } else {

            $newTitle = $data['title'];
            $updateQuery = $pdo->prepare("UPDATE task SET title = ? WHERE id = ?");
            $updateQuery->execute([$newTitle, $taskId]);

            echo json_encode(["msg" => "Update title successfully!"]);

        }
    } catch (PDOException $e) {
        echo json_encode(["err" => $e->getMessage()]);
    }



}
?>