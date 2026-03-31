<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $conn = mysqli_connect("localhost", "root", "", "todolist");
    $username = "Tăng Nhật Long";
    $password = password_hash("12345678", PASSWORD_DEFAULT);
    $email = "elilinhh@gmail.com";
    $query = "INSERT INTO user (username , password , email) VALUES('" . $username . "' , '" . $password . "', '" . $email . "')";
    if (mysqli_query($conn, $query)) {
        echo "Insert successfully!";
    } else {
        echo "Insert unsuccessfully!";
    }
    ?>
</body>

</html>