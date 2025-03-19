<?php
require_once '../../connection.php';

// Fetch the event ID from the query string
if (!isset($_GET['id'])) {
    header('Location: manage_user.php');
    exit();
}

$Id = $_GET['id'];
$user = null;

// Fetch the user details from the database
$query = "SELECT * FROM `users` WHERE `ID` = '$Id'";  // Corrected the table name to 'users'
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);  // Renamed variable to match fetched data
} else {
    header('Location: manage_user.php'); // Redirect if user not found
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['userId'];
    $fname = $_POST['userName'];
    $email = $_POST['userEmail'];
    $userLevel = $_POST['userLevel'];
    $departmentID = $_POST['departmentID'];
    $password = $_POST['password'];
    $repassword = $_POST['re-pwd'];

    if (!empty($password)) {
        if ($password == $repassword) {
            $hash_pwd = hash('sha256', $password);
            $passwordUpdate = ", `password` = '$hash_pwd'";
        } else {
            echo 'Password and Re-enter Password do not match';
            exit;
        }
    } else {
        $passwordUpdate = '';  // Initialize the variable for password update
    }

    // Corrected update query
    $updateQuery = "UPDATE `users` 
                    SET `userName` = '$fname', 
                        `userEmail` = '$email', 
                        `departmentID` = '$departmentID', 
                        `userLevel` = '$userLevel' 
                        $passwordUpdate 
                    WHERE `ID` = '$userId'";

    $result = mysqli_query($conn, $updateQuery);  // Use the correct query variable

    if ($result) {
        header('Location: manage_user.php');
    } else {
        echo 'Error updating user!';
    }
}


$querydepartmentID = "SELECT * FROM `department`";
$resultdepartmentID = mysqli_query($conn, $querydepartmentID);


?>

<!DOCTYPE html>
<html lang="en">
<?php $page_name = "Update User"; ?>

<head>
    <!-- Include header -->
    <?php require_once '../admin_partials/Admin_header.php'; ?>
</head>

<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-primary position-absolute w-100"></div>

    <!-- Include sidebar -->
    <?php require_once '../admin_partials/Admin_sidebar.php'; ?>

    <main class="main-content position-relative border-radius-lg">
        <!-- Navbar -->
        <?php require_once '../admin_partials/Admin_navbar.php'; ?>
        <!-- End Navbar -->

        <div class="container ms-3">
            <div class="row align-items-center mb-1">
                <div class="col-lg-6">
                    <h3 class="text-white">Update User</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 my-1">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mt-2">Update User Information</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="update_user.php?id=<?php echo $Id; ?>" method="POST">  <!-- Pass ID in the form action -->
                                <input type="hidden" name="userId" value="<?php echo $user['ID']; ?>">  <!-- Changed to correct variable -->

                                <div class="mb-3">
                                    <label for="userName" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="userName" name="userName" value="<?php echo $user['userName']; ?>" required>  <!-- Corrected array syntax -->
                                </div>

                                <div class="mb-3">
                                    <label for="userEmail" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="userEmail" name="userEmail" value="<?php echo $user['userEmail']; ?>" required>  <!-- Corrected array syntax -->
                                </div>

                                <div class="mb-3">
                                    <label for="userLevel" class="form-label">User Level</label>
                                    <select class="form-control" id="userLevel" name="userLevel" required>
                                        <option value="0" <?php echo $user['userLevel'] == 0 ? 'selected' : ''; ?>>0</option>
                                        <option value="1" <?php echo $user['userLevel'] == 1 ? 'selected' : ''; ?>>1</option>
                                        <option value="2" <?php echo $user['userLevel'] == 2 ? 'selected' : ''; ?>>2</option>
                                        <option value="3" <?php echo $user['userLevel'] == 3 ? 'selected' : ''; ?>>3</option>
                                        <option value="4" <?php echo $user['userLevel'] == 4 ? 'selected' : ''; ?>>4</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="userDepartment" class="form-label">User Department</label>
                                    <select class="form-control" name="departmentID" id="departmentID" required>
                                        <option value="Select User Department">Select User Department</option>
                                        <?php while ($row = mysqli_fetch_assoc($resultdepartmentID)) { ?>
                                            <option value="<?php echo $row['ID'] ?>"><?php echo $row['departmentName'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="Password" class="form-label">New Password (leave blank if you don't want to change it)</label>
                                    <input type="password" class="form-control" id="Password" name="password">
                                </div>

                                <div class="mb-3">
                                    <label for="RePassword" class="form-label">Re-Enter New Password</label>
                                    <input type="password" class="form-control" id="RePassword" name="re-pwd">
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Update User</button>
                                    <a href="manage_user.php" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php require_once '../admin_partials/Admin_footer.php'; ?>

    </main>

    <!-- Core JS Files -->
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/chartjs.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=2.0.4"></script>

</body>

</html>
