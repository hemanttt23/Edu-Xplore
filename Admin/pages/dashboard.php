<?php
require_once '../../connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<?php $page_name = "DashBoard"; ?>

<head>
    <?php require_once '../admin_partials/Admin_header.php'; ?>
</head>

<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-primary position-absolute w-100"></div>

    <?php require_once '../admin_partials/Admin_sidebar.php'; ?>

    <main class="main-content position-relative border-radius-lg">
        <?php require_once '../admin_partials/Admin_navbar.php'; ?>

        <div class="container-fluid ">
            <div class="row">
                <div class="col-12">
                    <h4 class="mb-4 text-white">Approved Events</h4>

                    <?php
                    // Pagination settings
                    $limit = 2;  // Show 3 events per page
                    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;

                    // Fetch total number of approved events
                    $total_query = "SELECT COUNT(*) as total FROM event_manager WHERE STATUS = 'Approved'";
                    $total_result = mysqli_query($conn, $total_query);
                    $total_row = mysqli_fetch_assoc($total_result);
                    $total_records = $total_row['total'];
                    $total_pages = ceil($total_records / $limit);

                    // Fetch paginated events
                    $query = "SELECT * FROM event_manager WHERE STATUS = 'Approved' LIMIT $limit OFFSET $offset";
                    $result = mysqli_query($conn, $query);

                    $querydepartmentID = "SELECT * FROM `department`";
                    $resultdepartmentID = mysqli_query($conn, $querydepartmentID);

                    $eventID = "SELECT `event_ID` , `event_name` FROM `event_manager` WHERE STATUS = 'Approved'";
                    $approved = mysqli_query($conn, $eventID);

                    // registration insert 
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
                        $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
                        $department = mysqli_real_escape_string($conn, $_POST['department']);
                        $year1 = mysqli_real_escape_string($conn, $_POST['year1']);
                        $registered_event = mysqli_real_escape_string($conn, $_POST['registered_event']);
                    
                        $sql = "INSERT INTO registration (first_name, last_name, department, year1 , registered_event) 
                                VALUES ('$first_name', '$last_name', '$department', '$year1' , '$registered_event')";
                    
                        if (mysqli_query($conn, $sql)) {
                            echo "<script>
                                alert('Registration Successful!');
                                window.location.href='dashboard.php'; 
                            </script>";
                        } else {
                            echo "<script>
                                alert('Error: " . mysqli_error($conn) . "');
                                window.history.back();
                            </script>";
                        }
                    
                        mysqli_close($conn);
                    }
                    ?>
                    
                    ?>

                    <div class="row">
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        $imagePath = '../../imgs/' . basename($row['event_file']);
    ?>
        <div class="col-md-6 mb-4">
            <div class="card shadow-lg border-0">
                <?php if (!empty($row['event_file']) && file_exists($imagePath)) { ?>
                    <img src="<?= $imagePath ?>" class="card-img-top" alt="Event Image" style="height: 200px; object-fit: cover;">
                <?php } else { ?>
                    <img src="../../imgs/default.jpg" class="card-img-top" alt="Default Image" style="height: 200px; object-fit: cover;">
                <?php } ?>

                <div class="card-body">
                    <h5 class="card-title text-primary"><?= $row['event_name'] ?></h5>
                    <div class="row">
                        <div class="col-4"><strong>Date:</strong> <?= $row['event_date'] ?></div>
                        <div class="col-4"><strong>Time:</strong> <?= date("H:i", strtotime($row['event_time'])) ?></div>
                        <div class="col-4"><strong>Eligibility:</strong> <?= $row['elgibility'] ?></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-4"><strong>Type:</strong> <?= $row['event_type'] ?></div>
                        <div class="col-4"><strong>Credits:</strong> <?= $row['event_credits'] ?></div>
                        <div class="col-4"><strong>Fees:</strong> <?= $row['fees'] ?></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6"><strong>Target Audience:</strong> <?= $row['event_targetAudience'] ?></div>
                        <div class="col-6"><strong>Event Coordinator:</strong> <?= $row['event_stakeholder'] ?></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12"><strong>Objective:</strong> <?= $row['event_objectives'] ?></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12"><strong>Description:</strong> <?= $row['event_description'] ?></div>
                    </div>

                    <?php if (isset($_SESSION['userLevel']) && ($_SESSION['userLevel'] == 4)) { ?>
                        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#registration">
                            Register Now
                        </button>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>


                    <!-- Pagination -->
                    <nav>
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a></li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
    </main>

    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/chartjs.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=2.0.4"></script>
</body>

<!-- Modal -->
<div class="modal fade" id="registration" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Event Registration</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="eventRegistrationForm" method="POST" action="#">
                    <input type="hidden" class="form-control" id="id" name="id">

                    <div class="mb-2">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <select class="form-control" id="department" name="department" required>
                            <option value="" disabled selected>Select Department</option>
                            <?php
                            include 'db_connection.php';
                            $query = "SELECT ID, departmentName FROM department";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<option value="' . $row['departmentName'] . '">' . $row['departmentName'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="year1" class="form-label">Year</label>
                        <select class="form-select" id="year1" name="year1" required>
                            <option value="" disabled selected>Select an Year</option>
                            <option value="1st year">1st year</option>
                            <option value="2nd year">2nd year</option>
                            <option value="3rd year">3rd year</option>
                            <option value="4th year">4th year</option>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="registered_event" class="form-label">Registered Event</label>
                        <select class="form-select" id="registered_event" name="registered_event" required>
                            <option value="" disabled selected>Select an event</option>
                            <?php
                            $queryEvents = "SELECT event_ID, event_name FROM event_manager WHERE STATUS = 'Approved'";
                            $approved = mysqli_query($conn, $queryEvents);
                            while ($row = mysqli_fetch_assoc($approved)) {
                                echo '<option value="' . $row['event_name'] . '">' . $row['event_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


</html>