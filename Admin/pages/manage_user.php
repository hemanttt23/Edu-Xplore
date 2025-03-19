<!DOCTYPE html>
<html lang="en">
<?php $page_name = "Manage Users"; ?>

<head>
    <!-- header starts  -->
    <?php require_once '../admin_partials/Admin_header.php'; ?>
    <!-- header ends  -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<?php
require_once '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = $_POST['userName'];
    $email = $_POST['userEmail'];
    $departmentID = $_POST['departmentID'];
    $userLevel = $_POST['userLevel'];
    $password = $_POST['password'];
    $repassword = $_POST['re-pwd'];

    if ($password == $repassword) {
        $hash_pwd = hash('sha256', $password);

        // Using prepared statements to prevent SQL injection
        $query = "INSERT INTO `users` (`ID`,`userName`, `userEmail`, `departmentID` , `password`, `userLevel`) VALUES (NULL ,'$fname','$email', '$departmentID' ,'$hash_pwd', '$userLevel')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            header('Location: manage_user.php');
        } else {

        }
    } else {
        echo 'Password and Re-enter Password do not match';
    }
}

// Initialize the query to fetch all users
$query_allData = "SELECT * FROM `users`";

// Check if the search parameter 'q' is set
if (isset($_GET['q']) && !empty($_GET['q'])) {
    $q = mysqli_real_escape_string($conn, $_GET['q']); // Sanitize user input
    // Append the WHERE clause to filter by userName or userEmail
    $query_allData .= " WHERE userName LIKE '%$q%' OR userEmail LIKE '%$q%'";
}

// Order by userLevel
$query_allData .= " ORDER BY `userLevel` ASC";

// Count total results for pagination
$result_allData = mysqli_query($conn, $query_allData);
$total_row = mysqli_num_rows($result_allData);

// Pagination logic
$number_of_rows = 5;
$total_pages = ceil($total_row / $number_of_rows);
$page_number = isset($_GET['page_number']) ? $_GET['page_number'] : 1;
$start = ($page_number - 1) * $number_of_rows;

// Add LIMIT to the query for pagination
$query_allData .= " LIMIT $start, $number_of_rows";
$result_5rows = mysqli_query($conn, $query_allData);

// Pagination logic (same as before)
$prev_button = $page_number - 1;
$next_button = $page_number + 1;

$querydepartmentID = "SELECT * FROM `department`";
$resultdepartmentID = mysqli_query($conn, $querydepartmentID);

?>

<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-primary position-absolute w-100"></div>

    <!-- sidebar starts  -->
    <?php require_once '../admin_partials/Admin_sidebar.php'; ?>
    <!-- sidebar ends  -->

    <main class="main-content position-relative border-radius-lg">
        <!-- Navbar -->
        <?php require_once '../admin_partials/Admin_navbar.php'; ?>
        <!-- End Navbar -->

        <div class="container ms-3">
            <div class="row align-items-center mb-3">
                <div class="col-lg-6">
                    <h3 class="text-white">Manage Users</h3>
                </div>
                <div class="col-lg-6 text-end">
                    <!-- Button trigger modal -->
                    <?php $loggedInUserLevel = $_SESSION['userLevel']; ?>
                    <?php if ($loggedInUserLevel == 0): // Check if the user is superAdmin ?>
                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Add Bulk user
                        </button>
                    <?php endif; ?>

                    <button type="button" class="btn btn-dark me-3" data-bs-toggle="modal" data-bs-target="#addUser">
                        Add user
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 my-3">
                    <form action="manage_user.php" method="GET">
                        <div class="input-group">
                            <input type="text" name="q" id="Search" placeholder="Search by name or email"
                                class="form-control me-3">
                            <button type="submit" class="btn text-white me-3">Search..</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-lg-6 offset"></div>
                <div class="col-lg-6">
                    <!-- Confirmation Message Section -->
                    <div id="confirmationMessage" class="alert alert-info d-none" role="alert">
                        <span class="text-white">Are you sure you want to delete this user?</span>
                        <button id="confirmDelete" class="btn btn-danger btn-sm ms-5 mt-3">Yes</button>
                        <button id="cancelDelete" class="btn btn-secondary btn-sm ms-2 mt-3">No</button>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h5>All Users</h5>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                                Sr.no</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                                User Name</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                                User Email</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                                User Level</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                                User Department</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                                Password</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                                Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($result_5rows)) { ?>
                                            <tr>
                                                <td class="text-center">
                                                    <h6><?php echo $row['ID']; ?></h6>
                                                </td>
                                                <td class="text-center">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['userName']; ?></h6>
                                                </td>
                                                <td class="text-center">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['userEmail']; ?></h6>
                                                </td>
                                                <td class="text-center">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['userLevel']; ?></h6>
                                                </td>
                                                <td class="text-center text-wrap">
                                                    <h6 class="mb-0 text-sm">
                                                        <?php
                                                        if ($row['departmentID'] == 1) {
                                                            echo "Information Technology";
                                                        } elseif ($row['departmentID'] == 2) {
                                                            echo "Computer Science";
                                                        } elseif ($row['departmentID'] == 3) {
                                                            echo "Civil Engineering";
                                                        } elseif ($row['departmentID'] == 4) {
                                                            echo "Mechanical Engineering";
                                                        } elseif ($row['departmentID'] == 5) {
                                                            echo "Electronic and Computer Science";
                                                        } elseif ($row['departmentID'] == 6) {
                                                            echo "Humanities and Applied Science";
                                                        } else {
                                                            echo "Unknown Department"; // In case departmentID is not recognized
                                                        }
                                                        ?>
                                                    </h6>
                                                </td>
                                                <td class="text-center">
                                                    <h6 class="mb-0 text-sm"><?php echo substr($row['password'], 0, 10); ?>
                                                    </h6>
                                                </td>
                                                <td class="text-center">
                                                    <a class="btn btn-primary py-2 px-3"
                                                        href="update_user.php?id=<?php echo $row['ID']; ?>"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Edit User">
                                                        <i class="fa-solid fa-pen-to-square"></i></a>

                                                    <a class="btn btn-danger py-2 px-3 delete-btn"
                                                        href="delete_user.php?id=<?php echo $row['ID']; ?>"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Delete User">
                                                        <i class="fa-solid fa-user-slash"></i>
                                                    </a>

                                                </td>

                                            <?php } ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                    <?php
                                    if (!$prev_button < 1) {
                                        echo '<li class="page-item">
                     <a class="page-link " href="manage_user.php?page_number=' . $prev_button . '" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                  </li>';
                                    }

                                    for ($i = 1; $i <= $total_pages; $i++) {
                                        if ($page_number == $i) {
                                            echo '<li class="page-item"><a class="page-link active" href="manage_user.php?page_number=' . $i . '">' . $i . '</a></li>';
                                        } else {
                                            echo '<li class="page-item"><a class="page-link" href="manage_user.php?page_number=' . $i . '">' . $i . '</a></li>';
                                        }
                                    }



                                    if ($page_number < $total_pages) {
                                        echo ' <li class="page-item">
                      <a class="page-link" href="manage_user.php?page_number=' . $next_button . '" aria-label="Next">
                          <span aria-hidden="true">&raquo;</span>
                      </a>
                   </li>';
                                    }
                                    ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- footer start  -->
                <?php require_once '../admin_partials/Admin_footer.php'; ?>
                <!-- footer ends  -->
            </div>

            <!-- Modal for Adding User -->
            <div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
                <div class="modal-dialog" style="max-width: 700px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="addUserLabel">Add User</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <?php $loggedInUserLevel = $_SESSION['userLevel']; ?>

                        <div class="modal-body p-4">
                            <form action="#" method="POST">
                                <div class="mb-3">
                                    <label for="userName" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="userName" name="userName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="userEmail" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="userEmail" name="userEmail" required>
                                </div>
                                <div class="mb-3">
                                    <label for="userLevel" class="form-label">User Level</label>
                                    <select class="form-control" id="userLevel" name="userLevel" required>
                                        <?php
                                        // Display options based on the logged-in user's userLevel
                                        if ($loggedInUserLevel == 0) {
                                            // userLevel 0 can add all user levels
                                            echo '<option value="0">0</option>';
                                            echo '<option value="1">1</option>';
                                            echo '<option value="2">2</option>';
                                            echo '<option value="3">3</option>';
                                            echo '<option value="4">4</option>';
                                        } elseif ($loggedInUserLevel == 1) {
                                            // userLevel 1 can add user levels 2, 3, and 4 but not 0
                                            echo '<option value="2">2</option>';
                                            echo '<option value="3">3</option>';
                                            echo '<option value="4">4</option>';
                                        } elseif ($loggedInUserLevel == 2) {
                                            // userLevel 2 can add user levels 3 and 4
                                            echo '<option value="3">3</option>';
                                            echo '<option value="4">4</option>';
                                        } elseif ($loggedInUserLevel == 3) {
                                            // userLevel 3 can add only user level 4
                                            echo '<option value="4">4</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="userDepartment" class="form-label">User Department</label>
                                    <select class="form-control" name="departmentID" id="departmentID">
                                        <option value="Select User Department">Select User Department</option>
                                        <?php while ($row = mysqli_fetch_assoc($resultdepartmentID)) { ?>
                                            <option value="<?php echo $row['ID'] ?>"><?php echo $row['departmentName'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="Password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="Password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="RePassword" class="form-label">Re-Enter Password</label>
                                    <input type="password" class="form-control" id="RePassword" name="re-pwd" required>
                                </div>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add User</button>
                            </form>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <script>
        $(document).ready(function () {
            $('.delete-btn').on('click', function (e) {
                e.preventDefault(); // Prevent the default action of the link
                var deleteUrl = $(this).attr('href'); // Get the URL from the link
                $('#confirmationMessage').removeClass('d-none');

                // Handle confirmation
                $('#confirmDelete').off('click').on('click', function () {
                    window.location.href = deleteUrl; // Redirect to delete URL
                });

                // Handle cancellation
                $('#cancelDelete').off('click').on('click', function () {
                    $('#confirmationMessage').addClass('d-none'); // Hide the confirmation message
                });
            });
        });
    </script>



    <!--   Core JS Files   -->
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/chartjs.min.js"></script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../assets/js/argon-dashboard.min.js?v=2.0.4"></script>

</body>

</html>