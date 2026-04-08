<?php
session_start();
ob_start(); // Bắt toàn bộ output
include("../connect.inc");
ob_clean(); // Xoá output trước khi trả JSON
header("Content-type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? "";
 // Lưu lại tên gốc mà người dùng muốn đặt trước khi lower
// LOGIN và REGISTER
if ($method == 'POST') {
    $username = $data['username'] ?? "";
    $username = mb_strtolower($data['username'], 'UTF-8'); // Chuyển username thành chữ thường để dễ so sánh vì mysql không phân biệt hoa / thường
    $password = $data['password'] ?? "";
    $passwordConfirm = $data['passwordConfirm'] ?? "";
    switch ($action) {

        case 'login':
            // Kiểm tra dữ liệu đầu vào
            if (!$username || !$password) {
                echo json_encode(["success" => false, "msg" => "Missing data"]);
                exit;
            }

            // Prepare statement để tránh SQL Injection
            $getUserByUsername = $pdo->prepare("SELECT * FROM user WHERE username = ?");

            // execute nhận 1 array để truyền dữ liệu vào dấu ?
            $getUserByUsername->execute([$username]);

            // Vì chỉ lấy 1 user nên dùng fetch()
            $user = $getUserByUsername->fetch();

            // Kiểm tra password hash
            if ($user && password_verify($password, $user['PASSWORD'])) {

                // Tạo session cho user
                $_SESSION['user_id'] = $user['id'];

                echo json_encode([
                    'success' => true,
                    'msg' => "Login successfully!"
                ]);

            } else {

                echo json_encode([
                    'success' => false,
                    'msg' => "Invalid username or password!"
                ]);
            }

            break;

        case 'register':



            // Kiểm tra dữ liệu đầu vào
            if (!$username || !$password || !$passwordConfirm) {
                echo json_encode(["success" => false, "msg" => "Missing data!"]);
                exit;
            }

            if ($password !== $passwordConfirm) {
                echo json_encode(["success" => false, "msg" => "Password confirm is invalid!"]);
                exit;
            }

            // Hash password
            $hashPassword = password_hash($password, PASSWORD_ARGON2ID);

            // Prepare statement để insert user
            $insertUser = $pdo->prepare(
                "INSERT INTO user (username, password) VALUES (?, ?)"
            );

            // execute truyền dữ liệu theo thứ tự dấu ?
            $resultInsertUser = $insertUser->execute([$username, $hashPassword]);

            if ($resultInsertUser) {

                // Lấy id user vừa insert
                $user_id = $pdo->lastInsertId();

                // Tạo session cho user
                $_SESSION['user_id'] = $user_id;

                echo json_encode([
                    "success" => true,
                    "msg" => "Register successfully!"
                ]);
            }

            break;
        default:

            echo json_encode([
                "success" => false,
                "msg" => "Invalid action"
            ]);

            break;
    }
}

if ($method == "GET" && $action == "logout") {
    // Huỷ session khi logout
    session_destroy();

    echo json_encode([
        "success" => true
    ]);
}

// Lấy thông tin user hiện tại
if ($method == "GET" && $action == "getUser") {

    // Nếu chưa đăng nhập thì không cho truy cập
    if (!isset($_SESSION['user_id'])) {

        echo json_encode([
            'success' => false,
            'msg' => "Chưa đăng nhập ai cho vô vậy má !!!!!!"
        ]);

        exit;
    }

    // Lấy user_id từ session
    $user_id = $_SESSION['user_id'];

    // Prepare statement để lấy thông tin user
    $getUserById = $pdo->prepare(
        "SELECT id, username FROM user WHERE id = ?"
    );

    // Truyền id vào dấu ?
    $getUserById->execute([$user_id]);

    // Lấy 1 user
    $user = $getUserById->fetch();

    echo json_encode([
        "user_id" => $user_id,
        "success" => true,
        "user" => $user
    ]);
}

?>