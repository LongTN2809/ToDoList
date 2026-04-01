<?php
session_start();
ob_start(); // ← bắt hết output lại
include("../connect.inc");
ob_clean(); // ← xoá sạch output trước khi gửi JSON
header("Content-type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? "";
// LOGIN và REGISTER
if ($method == 'POST') {

    switch ($action) {
        case 'login':
            $username = $data['username'] ?? "";
            $password = $data['password'] ?? "";
            if (!$username || !$password) {
                echo json_encode(["success" => false, "msg" => "Missing data"]);
                exit;
            }

            $checkUsername = "SELECT * FROM user WHERE username = '" . $username . "'";
            $result = mysqli_query($conn, $checkUsername);
            $user = mysqli_fetch_assoc($result);
            if ($user && password_verify($password, $user['PASSWORD'])) {
                $_SESSION['user_id'] = $user['id']; // Tao session rieng cho moi user
                echo json_encode(['success' => true, 'msg' => "Login successfully!"]);
            } else {
                echo json_encode(['success' => false, 'msg' => "Invalid username or password!"]);
            }

            break;
        case 'register':
            $username = $data['username'] ?? "";
            $password = $data['password'] ?? "";
            $passwordConfirm = $data['passwordConfirm'] ?? "";
            if (!$username || !$password || !$passwordConfirm) {
                echo json_encode(["success" => false, "msg" => "Missing data!"]);
                exit;
            }
            if ($password !== $passwordConfirm) {
                echo json_encode(["success" => false, "msg" => "Password confirm is invalid!"]);
                exit;
            }

            $hashPassword = password_hash($password , PASSWORD_ARGON2ID); // hass password

            $insertUser = "INSERT INTO user (username , password) VALUES ('".$username."' , '".$hashPassword."')";

            $resultInsertUser = mysqli_query($conn , $insertUser);
            if($resultInsertUser){
                $user_id = mysqli_insert_id($conn);
                $_SESSION['user_id'] = $user_id;
                echo json_encode(["success" => true , "msg" => "Register successfully!"]);
            }
            break;
        case 'logout':
        session_destroy();
        echo json_encode(["success" => true]);
        break;
        default:
            echo json_encode(["success" => false, "msg" => "Invalid action"]);
            break;
    }
}

//
if ($method == "GET" && $action == "getUser") {
    // Neu chua dang ky tai khoan thi khong vao duoc
    if (!isset($_SESSION['user_id'])) {
        echo json_encode((['success' => false, 'msg' => "Chưa đăng nhập ai cho vô vậy má !!!!!!"]));
        exit;
    }

    // Neu da dang ky thi lay user_id ra de select thong tin cua user do
    $id = $_SESSION['user_id'];

    $sql = "SELECT id, username FROM user WHERE id=$id";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    echo json_encode([
        "user_id" => $id,
        "success" => true,
        "user" => $user
    ]);
}

?>