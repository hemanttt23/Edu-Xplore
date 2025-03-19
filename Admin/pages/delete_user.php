<?php
$id = $_GET['id'] ? $_GET['id'] : 0;
require_once '../../connection.php';

$query = "DELETE FROM `users` WHERE id = $id";
$result = mysqli_query($conn, $query);

if ($result) {
    header('Location: manage_user.php');
} else {
   
}
?>
