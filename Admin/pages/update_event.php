<!DOCTYPE html>
<html lang="en">
<?php
$page_name = "Update Event";
require_once '../../connection.php';

if (!isset($_GET['id'])) {
    header('Location: event_manager.php');
    exit();
}

$eventId = $_GET['id'];
$event = null;

// Fetch the event details from the database
$query = "SELECT * FROM `event_manager` WHERE `event_ID` = '$eventId'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $event = mysqli_fetch_assoc($result);
} else {
    header('Location: event_manager.php'); // Redirect if event not found
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eventName = mysqli_real_escape_string($conn, $_POST['event_name']);
    $eventDescription = mysqli_real_escape_string($conn, $_POST['event_description']);
    $eventType = mysqli_real_escape_string($conn, $_POST['event_type']);
    $eventTime = mysqli_real_escape_string($conn, $_POST['event_time']);
    $eventCredits = mysqli_real_escape_string($conn, $_POST['event_credits']);
    $eventObjectives = mysqli_real_escape_string($conn, $_POST['event_objectives']);
    $eventTargetAudience = mysqli_real_escape_string($conn, $_POST['event_targetAudience']);
    $elgibility = mysqli_real_escape_string($conn, $_POST['elgibility']);
    $fees = mysqli_real_escape_string($conn, $_POST['fees']);
    $stakeholder = mysqli_real_escape_string($conn, $_POST['stakeholder']);
    $Status = mysqli_real_escape_string($conn, $_POST['Status']); // Make sure this field exists
    $eventCreatedBy = mysqli_real_escape_string($conn, $_POST['event_createdBy']);
    $eventDuration = $_POST['eventDuration'];

    // Handle event dates based on duration
    if ($eventDuration == 'single') {
        $eventDate = mysqli_real_escape_string($conn, $_POST['event_date']);
        $eventEndDate = null;
    } else {
        $eventDate = mysqli_real_escape_string($conn, $_POST['event_start_date']);
        $eventEndDate = mysqli_real_escape_string($conn, $_POST['event_end_date']);
    }

    // Handle file upload (optional)
    $file = $_FILES['file'];
    $finalDestination = $event['event_file']; // Retain the existing file if no new file is uploaded

    if ($file['error'] == 0) { // Only process if a file was uploaded
        $fileName = $_FILES['file']['name'];
        $tmpName = $_FILES['file']['tmp_name'];
        $size = $_FILES['file']['size'];

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $explodeName = explode('.', $fileName);
        $extension = end($explodeName);

        if (in_array($extension, $allowedExtensions) && $size <= 2097152) { // Limit file size to 2MB
            $newName = uniqid('event_', true) . '.' . $extension;
            $finalDestination = '../../imgs/' . $newName;
            if (!move_uploaded_file($tmpName, $finalDestination)) {
                echo 'Error uploading file.';
                exit();
            }
        } else {
            echo 'Invalid file format or file size exceeds limit.';
            exit();
        }
    }

    // Prepare SQL query to update the event
    $updateQuery = "UPDATE `event_manager` SET 
                    `event_name` = '$eventName', 
                    `event_description` = '$eventDescription', 
                    `event_type` = '$eventType', 
                    `event_date` = '$eventDate',
                    `event_end_date` = '$eventEndDate', 
                    `event_time` = '$eventTime', 
                    `event_credits` = '$eventCredits', 
                    `event_objectives` = '$eventObjectives', 
                    `event_targetAudience` = '$eventTargetAudience', 
                    `elgibility` = '$elgibility', 
                    `fees` = '$fees', 
                    `event_stakeholder` = '$stakeholder', 
                    `event_createdBy` = '$eventCreatedBy', 
                    `event_file` = '$finalDestination',
                    `Status` = '$Status'  -- Make sure Status is included
                    WHERE `event_ID` = '$eventId'";

    // Execute the update query
    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION['success_message'] = 'Event updated successfully.'; 
        header('Location: event_manager.php'); 
        exit();
    } else {
        echo 'Error updating event: ' . mysqli_error($conn);
    }
}
?>



<head>
    <?php require_once '../admin_partials/Admin_header.php'; ?>
</head>

<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-primary position-absolute w-100"></div>

    <?php require_once '../admin_partials/Admin_sidebar.php'; ?>

    <main class="main-content position-relative border-radius-lg">
        <?php require_once '../admin_partials/Admin_navbar.php'; ?>

        <div class="container ms-3">
            <div class="row align-items-center mb-3">
                <div class="col-lg-6">
                    <h3 class="text-white">Update Event</h3>
                </div>
            </div>
            <div class="container-fluid py-4">
                <div class="row">
                    <div class="col-md-12 mx-auto">
                        <div class="card mb-4 p-3" style="height: auto;">
                            <div class="row g-0">
                                <!-- Right side content -->
                                <div class="col-md-12">
                                    <div class="card-body">
                                        <div class="col-md-8 me-3">
                                            <img src="<?php echo ($event['event_file']); ?>"
                                                class="img-fluid rounded-start" alt="Event Image" style="height: 100%;">
                                        </div>
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="event_ID"
                                                value="<?php echo $event['event_ID']; ?>">
                                            <input type="hidden" name="event_createdDate"
                                                value="<?php echo $event['event_createdDate']; ?>">
                                            <input type="hidden" name="Status"
                                                value="<?php echo $event['Status']; ?>">
                                            <input type="hidden" name="event_createdBy"
                                                value="<?php echo $event['event_createdBy']; ?>">
                                            <input type="hidden" name="departmentID"
                                                value="<?php echo $event['departmentID']; ?>">

                                            <div class="mb-3 mt-3">
                                                <label for="eventName" class="form-label">Event Name</label>
                                                <input type="text" class="form-control" id="eventName" name="event_name"
                                                    value="<?php echo $event['event_name']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="eventDescription" class="form-label">Event Description</label>
                                                <textarea class="form-control" id="eventDescription"
                                                    name="event_description" rows="3"
                                                    required><?php echo $event['event_description']; ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="eventObjectives" class="form-label">Event Objectives</label>
                                                <textarea class="form-control" id="eventObjectives"
                                                    name="event_objectives" rows="3"
                                                    required><?php echo $event['event_objectives']; ?></textarea>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <label for="eventType" class="form-label">Event Type</label>
                                                    <select class="form-control" id="eventType" name="event_type"
                                                        required>
                                                        <option value="<?php echo $event['event_type']; ?>" selected>
                                                            <?php echo $event['event_type']; ?></option>
                                                        <option>Cultural</option>
                                                        <option>Sports</option>
                                                        <option>Technical</option>
                                                        <option>Fest</option>
                                                        <option>Hackathon</option>
                                                        <option>Paper Presentation</option>
                                                        <option>Seminar</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Event Duration</label>
                                                <div>
                                                    <input type="radio" name="eventDuration" id="singleDay" value="single" <?php if(!$event['event_end_date']) echo 'checked'; ?>> Single Day
                                                    <input type="radio" name="eventDuration" id="multiDay" value="multi" <?php if($event['event_end_date']) echo 'checked'; ?>> Multi-Day
                                                </div>
                                            </div>
                                            <div class="row mb-3" id="singleDayDate" <?php if($event['event_end_date']) echo 'style="display:none;"'; ?>>
                                                <div class="col-md-6">
                                                    <label for="event_date" class="form-label">Event Date</label>
                                                    <input type="date" class="form-control" id="event_date"
                                                        name="event_date" value="<?php echo $event['event_date']; ?>" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3" id="multiDayDate" <?php if(!$event['event_end_date']) echo 'style="display:none;"'; ?>>
                                                <div class="col-md-6">
                                                    <label for="event_start_date" class="form-label">Start Date</label>
                                                    <input type="date" class="form-control" id="event_start_date"
                                                        name="event_start_date" value="<?php echo $event['event_date']; ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="event_end_date" class="form-label">End Date</label>
                                                    <input type="date" class="form-control" id="event_end_date"
                                                        name="event_end_date" value="<?php echo $event['event_end_date']; ?>">
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="event_time" class="form-label">Event Time</label>
                                                    <input type="time" class="form-control" id="event_time"
                                                        name="event_time" value="<?php echo $event['event_time']; ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="event_credits" class="form-label">Event Credits</label>
                                                    <input type="number" class="form-control" id="event_credits"
                                                        name="event_credits" value="<?php echo $event['event_credits']; ?>"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="event_targetAudience" class="form-label">Target Audience</label>
                                                <textarea class="form-control" id="event_targetAudience"
                                                    name="event_targetAudience" rows="3"
                                                    required><?php echo $event['event_targetAudience']; ?></textarea>
                                            </div>
                                            <div class="col-md-12">
                                                <label for="eligibility" class="form-label">Eligibility</label>
                                                <textarea class="form-control" id="elgibility" name="elgibility" rows="3" required><?php echo $event['elgibility']; ?></textarea>
                                            </div>
                                            <div class="row">
                                            <div class="col-md-6">
                                                <label for="fees" class="form-label">Fees</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">₹</span>
                                                    <input type="number" class="form-control" id="fees" name="fees" value="<?php echo $event['fees']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="stakeholder" class="form-label">Stakeholder</label>
                                                <input type="text" class="form-control" id="stakeholder"
                                                    name="stakeholder" value="<?php echo $event['event_stakeholder']; ?>"
                                                    required>
                                            </div>
                                             <!-- File Upload -->
                                             <div class="mb-3">
                                                <label for="file" class="form-label">Event Image/File</label>
                                                <input type="file" class="form-control" id="file" name="file">
                                            </div>
                                            </div>
                                        </div>
                                            </div>
                                                <div class="mb-3 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary">Update Event</button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php require_once '../admin_partials/Admin_footer.php'; ?>
    </main>

    <!-- JavaScript to toggle between single-day and multi-day event dates -->
    <script>
        document.getElementById('singleDay').addEventListener('change', function() {
            document.getElementById('singleDayDate').style.display = 'block';
            document.getElementById('multiDayDate').style.display = 'none';
        });

        document.getElementById('multiDay').addEventListener('change', function() {
            document.getElementById('singleDayDate').style.display = 'none';
            document.getElementById('multiDayDate').style.display = 'block';
        });
    </script>

</body>

</html>
