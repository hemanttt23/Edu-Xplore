<?php
session_start();
session_unset();
session_destroy();

// Clear session cookie
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, '/');
}

header("Location: login.php");
exit();

?>