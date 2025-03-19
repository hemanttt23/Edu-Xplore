<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../../connection.php';

    $fname = $_POST['userName'];
    $email = $_POST['userEmail'];
    $userLevel = $_POST['userLevel'];
    $password = $_POST['password'];
    $repassword = $_POST['re-pwd'];

    if ($password == $repassword) {

        $hash_pwd = hash('sha256', $password);

        $query = "INSERT INTO `users` (`ID`, `userName`, `userEmail`, `password`, `userLevel`) VALUES (NULL, '$fname', '$email', '$hash_pwd' , '$userLevel');";

        $result = mysqli_query($conn, $query);

        if ($result) {
            header('Location: manage_user.php');
        }

    } else {
        echo 'Password and Re-enter Password does not match';
    }

}
?>