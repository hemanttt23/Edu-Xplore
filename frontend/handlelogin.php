<?php
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
}

require_once '../connection.php';
$hash_pwd = hash('sha256', $password);

// Run the SQL query
$query = "SELECT * FROM `users` WHERE userEmail = '$email' AND password = '$hash_pwd'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['ID'] = $row['ID'];
    $_SESSION['userName'] = $row['userName'];
    $_SESSION['userEmail'] = $row['userEmail'];
    $_SESSION['userLevel'] = $row['userLevel'];
    $_SESSION['departmentID'] = $row['departmentID'];

    if($result){
        header('Location: ../admin/pages/dashboard.php');
    }
    exit();
} else {
    $_SESSION['login_error'] = 'Incorrect User email or Password';
    header('Location: login.php');
    exit();
}
?>
