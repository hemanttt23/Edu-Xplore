<?php
require_once '../../connection.php'; // Database connection

// Check if event_ID is set in the URL
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM `event_manager` WHERE event_ID = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the event exists
    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        echo "Event not found.";
        exit;
    }
} else {
    echo "Invalid event ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php $page_name = "Event Details"; ?>

<head>
    <!-- header starts -->
    <?php require_once '../admin_partials/Admin_header.php'; ?>
    <!-- header ends -->
</head>

<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-primary position-absolute w-100"></div>

    <!-- sidebar starts -->
    <?php require_once '../admin_partials/Admin_sidebar.php'; ?>
    <!-- sidebar ends -->

    <main class="main-content position-relative border-radius-lg">
        <!-- Navbar -->
        <?php require_once '../admin_partials/Admin_navbar.php'; ?>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-12 mx-auto">
                    <div class="card mb-4 p-3" style="height: auto;">
                        <div class="row g-0">
                            <!-- Left side image -->
                            <div class="col-md-5 p-2">
                                    <a class="uk-inline" href="<?php echo ($event['event_file']); ?>">
                                        <img src="<?php echo ($event['event_file']); ?>" class="img-fluid rounded-start"
                                            alt="Event Image" style="max-width: 100%; height: auto; object-fit: cover;">
                                    </a>
                            </div>




                            <!-- Right side content -->
                            <div class="col-md-7">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 fs-3">
                                            <span><?php echo ($event['event_name']); ?></span>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <strong>Event Type:</strong>
                                            <span><?php echo ($event['event_type']); ?></span>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Start Date:</strong>
                                            <span><?php echo ($event['event_date']); ?></span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>End Date:</strong>
                                            <span><?php echo ($event['event_end_date']); ?></span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Time:</strong>
                                            <span><?php echo ($event['event_time']); ?></span>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Credits:</strong>
                                            <span><?php echo ($event['event_credits']); ?></span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Fees:</strong>
                                            <span><?php echo ($event['fees']); ?></span>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-12">
                                            <strong>Description:</strong>
                                            <span><?php echo ($event['event_description']); ?></span>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <strong>Objectives:</strong>
                                            <span><?php echo ($event['event_objectives']); ?></span>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <strong>Eligibility:</strong>
                                            <span><?php echo ($event['elgibility']); ?></span>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Target Audience:</strong>
                                            <span><?php echo ($event['event_targetAudience']); ?></span>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Faculty Co-ordinator:</strong>
                                            <span><?php echo ($event['event_stakeholder']); ?></span>
                                        </div>
                                        <!-- <div class="col-md-6">
                                            <strong>Created By:</strong>
                                            <span><?php echo ($event['event_createdBy']); ?></span>
                                        </div> -->
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer start  -->
        <?php require_once '../admin_partials/Admin_footer.php'; ?>
        <!-- footer ends  -->
    </main>

    <!-- Core JS Files -->
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=2.0.4"></script>
</body>

</html>