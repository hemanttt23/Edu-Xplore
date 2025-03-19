<!DOCTYPE html>
<html lang="en">
  
  <head>
    <!-- header starts  -->
    <?php require_once '../admin_partials/Admin_header.php'; ?>
    <?php $page_name = "Event Manager"; ?>
  <!-- header ends  -->
</head>

<!-- php start  -->
<?php
require_once '../../connection.php';
require_once "../../email.php";


if (isset($_GET['approve_ID'])) {
  // echo $_GET['approve_ID'];
  $approve_ID = $_GET['approve_ID'];
  $eventName = $_GET['event_name'];
  $query = "UPDATE `event_manager` SET Status = 'Approved' WHERE event_ID = $approve_ID";
  $result = mysqli_query($conn, $query);
  if ($result) {
    $query = "SELECT e.event_createdBy , u.userEmail  FROM event_manager e INNER JOIN users u WHERE e.event_createdBy = u.ID AND e.event_ID = $approve_ID";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $to = $row['userEmail'];
    $subject = "Event approved";
    $message = "Your event " . $eventName . " is approved";
    sendEmail($to, $subject, $message);
  }
}

if (isset($_GET['reject_ID'])) {
  $reject_ID = $_GET['reject_ID'];
  $eventName = $_GET['event_name'];
  $query = "UPDATE `event_manager` SET Status = 'Rejected' WHERE event_ID = $reject_ID";
  $result = mysqli_query($conn, $query);
  if ($result) {
    $query = "SELECT e.event_createdBy , u.userEmail  FROM event_manager e INNER JOIN users u WHERE e.event_createdBy = u.ID AND e.event_ID = $reject_ID";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $to = $row['userEmail'];
    $subject = "Event Rejected";
    $message = "Your event " . $eventName . " is Rejected";
    sendEmail($to, $subject, $message);
  }
}

if (isset($_GET['rework_ID'])) {
  $rework_ID = $_GET['rework_ID'];
  $eventName = $_GET['event_name'];
  $query = "UPDATE `event_manager` SET Status = 'Rework' WHERE event_ID = $rework_ID";
  $result = mysqli_query($conn, $query);
  if ($result) {
    $query = "SELECT e.event_createdBy , u.userEmail  FROM event_manager e INNER JOIN users u WHERE e.event_createdBy = u.ID AND e.event_ID = $rework_ID";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $to = $row['userEmail'];
    $subject = "Event Rework";
    $message = "Your event " . $eventName . " have some paramaters that can be improved.";
    sendEmail($to, $subject, $message);
  }
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Escape the inputs to avoid SQL syntax errors
  $eventName = mysqli_real_escape_string($conn, $_POST['event_name']);
  $eventDescription = mysqli_real_escape_string($conn, $_POST['event_description']);
  $eventType = mysqli_real_escape_string($conn, $_POST['event_type']);
  $eventDate = mysqli_real_escape_string($conn, $_POST['event_date']);
  $eventEndDate = mysqli_real_escape_string($conn, $_POST['event_end_date']);
  $eventTime = mysqli_real_escape_string($conn, $_POST['event_time']);
  $eventCredits = mysqli_real_escape_string($conn, $_POST['event_credits']);
  $eventObjectives = mysqli_real_escape_string($conn, $_POST['event_objectives']);
  $eventTargetAudience = mysqli_real_escape_string($conn, $_POST['event_targetAudience']);
  $eligibility = mysqli_real_escape_string($conn, $_POST['eligibility']);
  $fees = mysqli_real_escape_string($conn, $_POST['fees']);
  $stakeholder = mysqli_real_escape_string($conn, $_POST['stakeholder']);
  $status = mysqli_real_escape_string($conn, $_POST['status']);
  $reworkstatus = mysqli_real_escape_string($conn, $_POST['reworkstatus']);
  $eventCreatedBy = mysqli_real_escape_string($conn, $_POST['event_createdBy']);
  $userLevel = mysqli_real_escape_string($conn, $_POST['userLevel']);
  $departmentID = mysqli_real_escape_string($conn, $_POST['departmentID']);
  $otherdepartment = mysqli_real_escape_string($conn, $_POST['otherdepartment']);

  // Handle file upload
  $file = $_FILES['file'];
  $fileName = $_FILES['file']['name'];
  $tmpName = $_FILES['file']['tmp_name'];
  $error = $_FILES['file']['error'];
  $size = $_FILES['file']['size'];

  $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
  $explodeName = explode('.', $fileName);
  $extension = end($explodeName);

  if (in_array($extension, $allowedExtensions)) {
    if ($size <= 2097152) { // Limit file size to 2MB
      $newName = uniqid('event_', true) . '.' . $extension;
      $finalDestination = '../../imgs/' . $newName;
      move_uploaded_file($tmpName, $finalDestination);

      // Prepare SQL query
      $query = "INSERT INTO `event_manager`(
              `event_ID`, `event_name`, `event_description`, `event_type`, 
              `event_date`, `event_time`, `event_credits`, `event_objectives`, 
              `event_targetAudience`, `elgibility`, `fees`, `Status`, 
              `rework_status`, `event_createdBy`, `event_createdDate`, 
              `userLevel`, `event_stakeholder`, `event_file`, 
              `event_end_date`, `departmentID`, `otherdepartment`
          ) VALUES (
              NULL, '$eventName', '$eventDescription', '$eventType', 
              '$eventDate', '$eventTime', '$eventCredits', '$eventObjectives', 
              '$eventTargetAudience', '$eligibility', '$fees', '$status', 
              '$reworkstatus', '$eventCreatedBy', NOW(), '$userLevel', 
              '$stakeholder', '$newName', '$eventEndDate', '$departmentID', '$otherdepartment'
          )";

      // Execute the query
      if (mysqli_query($conn, $query)) {
        $_SESSION['success_message'] = 'Successfully created event'; // Set the success message
        header('Location: ' . $_SERVER['PHP_SELF']); // Redirect after insertion
        exit();
      } else {
        echo 'Database insertion failed: ' . mysqli_error($conn);
      }
    } else {
      echo 'File size is too large. Limit is 2MB.';
    }
  } else {
    echo 'Invalid file type. Allowed types are jpg, jpeg, png, and pdf.';
  }
}

// Query for events based on user level
$userLevel = $_SESSION['userLevel'];
$userID = $_SESSION['ID']; // Ensure you use the correct session ID

if ($userLevel == 3) {
    // User level 3 sees only events they created
    $query_5rows = "SELECT * FROM `event_manager` WHERE `event_createdBy` = $userID ORDER BY `event_createdDate` DESC";
} else {
    // Other users see all events
    $query_5rows = "SELECT * FROM `event_manager` ORDER BY `event_createdDate` DESC";
}

$result_5rows = mysqli_query($conn, $query_5rows);
$total_row = mysqli_num_rows($result_5rows);
$number_of_rows = 5;
$total_pages = ceil($total_row / $number_of_rows);

$page_number = isset($_GET['page_number']) ? $_GET['page_number'] : 1;
$start = ($page_number - 1) * $number_of_rows;

$query_5rows .= " LIMIT $start, $number_of_rows";
$result_5rows = mysqli_query($conn, $query_5rows);


// Calculation for the buttons
$prev_button = $page_number - 1;
$next_button = $page_number + 1;


// Query for events based on user level
$userLevel = $_SESSION['userLevel'];
if ($userLevel == 3) {
  $eventCreatedBy = $_SESSION['ID'];
  $query_5rows = "SELECT * FROM `event_manager` WHERE `event_createdBy` = '$eventCreatedBy' ORDER BY `event_createdDate` DESC";
} else {
  $query_5rows = "SELECT * FROM `event_manager` ORDER BY `event_createdDate` DESC";
}

$querydepartmentID = "SELECT * FROM `department`";
$resultdepartmentID = mysqli_query($conn, $querydepartmentID);

?>

<!-- php ends  -->

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>

  <!-- sidebar starts  -->
  <?php require_once '../admin_partials/Admin_sidebar.php'; ?>
  <!-- sidebar ends  -->

  <main class="main-content position-relative border-radius-lg ">

    <!-- Navbar -->
    <?php require_once '../admin_partials/Admin_navbar.php'; ?>
    <!-- End Navbar -->


    <div class="container ms-3">
      <div class="row align-items-center mb-3">
        <div class="col-lg-6">
          <h3 class="text-white">Event Manager</h3>
        </div>
        <div class="col-lg-6 text-end">
          <!-- Button trigger modal -->
          <button type="button" class="btn btn-dark btn-lg me-3" data-bs-toggle="modal" data-bs-target="#host_event">
            Host Event
          </button>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12 my-3">
          <form action="#" method="GET">
            <div class="input-group">
              <input type="text" name="" id="Search" placeholder="Search" class="form-control me-3">
              <button type="submit" class="btn text-white me-3">Search..</button>
            </div>
          </form>
        </div>
      </div>
      <!-- Display success message -->
      <?php
      if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-secondary alert-dismissible fade show text-white" role="alert">
                  <strong>Success: </strong>' . $_SESSION['success_message'] . '
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        unset($_SESSION['success_message']);
      }
      ?>
    </div>
    </div>

    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Events Created</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Sr.no
                      </th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                        Created Date
                      </th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Event
                        Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">
                        Event Decription</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">
                        Event Status</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">View
                        Details</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                        Actions
                      </th>
                      <th class="text-secondary opacity-7"></th>
                    </tr>
                  </thead>
                  <?php while ($row = mysqli_fetch_assoc($result_5rows)) { ?>
                    <tr>
                      <td class="text-center">
                        <h6 class="text-sm mb-0"><?php echo $row['event_ID'] ?></h6>
                      </td>
                      <td class="text-center align-middle">
                        <h6 class="mb-0 text-sm"><?php echo substr($row['event_createdDate'], 0, 10); ?></h6>
                      </td>
                      <td class="text-center">
                        <h6 class="mb-0 text-sm"><?php echo $row['event_name'] ?></h6>
                      </td>
                      <td class="text-center text-wrap align-middle text-sm">
                        <h6 class="mb-0 text-sm"><?php echo $row['event_description'] ?></h6>
                      </td>
                      <td class="text-center text-wrap align-middle text-sm">
                        <h6 class="mb-0 text-sm"><?php echo $row['Status'] ?></h6>
                      </td>
                      <td class="text-center align-middle">
                        <a class="btn btn-primary py-2 px-3" href="view_events.php?id=<?php echo $row['event_ID'] ?>">
                          <i class="fa-solid fa-eye"></i>
                        </a>
                      </td>
                      <td class="text-center align-middle">
                        <!-- edit button -->
                        <?php if ($_SESSION['userLevel'] == 3 || ($_SESSION['userLevel'] == 1 && $row['event_createdBy'] == $_SESSION['ID']) || ($_SESSION['userLevel'] == 2 && $row['event_createdBy'] == $_SESSION['ID'])) { ?>
                          <a class="btn btn-secondary py-2 px-3" href="update_event.php?id=<?php echo $row['event_ID']; ?>"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                          </a>
                        <?php } ?>

                        <?php if ($_SESSION['userLevel'] == 1 || $_SESSION['userLevel'] == 2) { ?>
                          <?php
                          // Debugging output to check the status
                          echo "<!-- Debugging Status: " . htmlspecialchars($row['Status']) . " -->";?>
                          <?php if ($row['Status'] == 'Approved' || $row['Status'] == 'Rejected'  || $row['Status'] == 'Rework') { ?>
                             <!-- Buttons disabled due to status -->
                             <button class="btn btn-success py-2 px-3" disabled>
                              <i class="fa-solid fa-check"></i>
                            </button>
                            <button  class="btn btn-warning py-2 px-3" href="#" disabled>
                              <i class="fa-solid fa-arrows-rotate"></i>
                            </button>
                            <button class="btn btn-danger py-2 px-3" disabled>
                              <i class="fa-solid fa-circle-xmark"></i>
                            </button>
                          <?php } else { ?>
                             <!-- approve btn -->
                             <button class="btn btn-success py-2 px-3"
                              onclick="approve(<?php echo $row['event_ID'] ?>,'<?php echo htmlspecialchars($row['event_name']) ?>')"
                              data-bs-toggle="tooltip" data-bs-placement="top" title="Approve Event">
                              <i class="fa-solid fa-check"></i>
                            </button>
                            <!-- rework btn -->
                            <button class="btn btn-warning py-2 px-3"  onclick="rework(<?php echo $row['event_ID'] ?>,'<?php echo htmlspecialchars($row['event_name']) ?>')" data-bs-toggle="tooltip" data-bs-placement="top"
                              title="Rework on event">
                              <i class="fa-solid fa-arrows-rotate"></i>
                            </button>
                            <!-- reject btn -->
                            <button class="btn btn-danger py-2 px-3"
                              onclick="reject(<?php echo $row['event_ID'] ?>,'<?php echo htmlspecialchars($row['event_name']) ?>')"
                              data-bs-toggle="tooltip" data-bs-placement="top" title="Reject Event">
                              <i class="fa-solid fa-circle-xmark"></i>
                            </button>
                          <?php } ?>
                        <?php } ?>

                      </td>
                    </tr>
                  <?php } ?>


                </table>

              </div>
              <nav aria-label="Page navigation example">
                <ul class="pagination">
                  <?php
                  if (!$prev_button < 1) {
                    echo '<li class="page-item">
                     <a class="page-link " href="event_manager.php?page_number=' . $prev_button . '" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                  </li>';
                  }

                  for ($i = 1; $i <= $total_pages; $i++) {
                    if ($page_number == $i) {
                      echo '<li class="page-item"><a class="page-link active" href="event_manager.php?page_number=' . $i . '">' . $i . '</a></li>';
                    } else {
                      echo '<li class="page-item"><a class="page-link" href="event_manager.php?page_number=' . $i . '">' . $i . '</a></li>';
                    }
                  }



                  if ($page_number < $total_pages) {
                    echo ' <li class="page-item">
                      <a class="page-link" href="event_manager.php?page_number=' . $next_button . '" aria-label="Next">
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
      </div>
      <!-- footer start  -->
      <?php require_once '../admin_partials/Admin_footer.php' ?>
      <!-- footer ends  -->
    </div>
  </main>
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

  <!-- Create Host event Modal -->
  <div class="modal fade" id="host_event" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 850px;">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Event Information</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="#" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="eventName" class="form-label">Event Name</label>
              <input type="text" class="form-control" id="eventName" name="event_name" required>
            </div>
            <div class="mb-3">
              <label for="eventDescription" class="form-label">Event Description</label>
              <textarea class="form-control" id="eventDescription" name="event_description" rows="3"
                required></textarea>
            </div>
            <div class="mb-3">
              <label for="eventType" class="form-label">Event Type</label>
              <select class="form-control" id="eventType" name="event_type" required>
                <option>Select Event Type</option>
                <option>Cultural</option>
                <option>Sports</option>
                <option>Technical</option>
                <option>Fest</option>
                <option>Hackathon</option>
                <option>Paper Presentation</option>
                <option>Seminar</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Event Duration</label>
              <div>
                <input type="radio" name="eventDuration" id="singleDay" value="single" checked> Single Day
                <input type="radio" name="eventDuration" id="multiDay" value="multi"> Multi-Day
              </div>
            </div>
            <div class="mb-3" id="singleDayDate">
              <label for="eventDate" class="form-label">Event Date</label>
              <input type="date" class="form-control" id="eventDate" name="event_date">
            </div>
            <div class="mb-3 d-none" id="multiDayDate">
              <label for="startDate" class="form-label">Start Date</label>
              <input type="date" class="form-control" id="startDate" name="event_date">
              <label for="endDate" class="form-label">End Date</label>
              <input type="date" class="form-control" id="endDate" name="event_end_date">
            </div>
            <div class="mb-3">
              <label class="form-label">Event Department</label>
              <div>
                <input type="radio" name="departmentOption" id="SameDepartment" value="single" checked> Same Department
                <input type="radio" name="departmentOption" id="OtherDepartmentRadio" value="multi"> Other Department
              </div>
            </div>
            <div class="mb-3 d-none" id="OtherDepartmentDropdown">
              <label for="OtherDepartmentSelect" class="form-label">Other Department</label>
              <select name="otherdepartment" id="OtherDepartmentSelect" class="form-control">
                <?php while ($row = mysqli_fetch_assoc($resultdepartmentID)) { ?>
                  <option value="<?php echo $row['ID'] ?>"><?php echo $row['departmentName'] ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="mb-3">
              <label for="event_time" class="form-label">Event Time</label>
              <input type="time" class="form-control" id="event_time" name="event_time" required>
            </div>
            <div class="mb-3">
              <label for="eventCredits" class="form-label">Event Credits</label>
              <input type="number" class="form-control" id="eventCredits" name="event_credits" required>
            </div>
            <div class="mb-3">
              <label for="eventObjectives" class="form-label">Event Objectives</label>
              <textarea class="form-control" id="eventObjectives" name="event_objectives" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label for="eventTargetAudience" class="form-label">Event Target Audience</label>
              <input type="text" class="form-control" id="eventTargetAudience" name="event_targetAudience" required>
            </div>
            <div class="mb-3">
              <label for="eligibility" class="form-label">Eligibility</label>
              <textarea class="form-control" id="eligibility" name="eligibility" required></textarea>
            </div>
            <div class="mb-3">
              <label for="fees" class="form-label">Fees</label>
              <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" class="form-control" id="fees" name="fees" required value="0">
              </div>
            </div>
            <div class="mb-3">
              <label for="stakeholder" class="form-label">Stakeholder</label>
              <input type="text" class="form-control" id="stakeholder" name="stakeholder" required>
            </div>
            <div class="mb-3">
              <label for="file" class="form-label">Event file</label>
              <input type="file" class="form-control" id="file" name="file" accept=".jpg , .png , .jpeg , .pdf">
            </div>
            <!-- Hidden fields -->
            <input type="hidden" id="status" name="status" value="Pending">
            <input type="hidden" id="reworkstatus" name="reworkstatus" value="">
            <input type="hidden" id="eventCreatedBy" name="event_createdBy" value="<?php echo $_SESSION['ID']; ?>">
            <input type="hidden" id="userLevel" name="userLevel" value="<?php echo $_SESSION['userLevel']; ?>">
            <input type="hidden" id="departmentID" name="departmentID" value="<?php echo $_SESSION['departmentID']; ?>">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Create Event</button>
        </div>
      </div>
      </form>
    </div>
  </div>

  <!-- JavaScript to Toggle Date Inputs -->
  <script>
    document.getElementById('singleDay').addEventListener('change', function () {
      document.getElementById('singleDayDate').classList.remove('d-none');
      document.getElementById('multiDayDate').classList.add('d-none');
    });

    document.getElementById('multiDay').addEventListener('change', function () {
      document.getElementById('singleDayDate').classList.add('d-none');
      document.getElementById('multiDayDate').classList.remove('d-none');
    });
    document.getElementById('SameDepartment').addEventListener('change', function () {
      document.getElementById('OtherDepartmentDropdown').classList.add('d-none');
    });

    document.getElementById('OtherDepartmentRadio').addEventListener('change', function () {
      document.getElementById('OtherDepartmentDropdown').classList.remove('d-none');
    });
    function approve(event_ID, event_name) {
      window.location.replace("event_manager.php?approve_ID=" + event_ID + "&event_name=" + event_name);
      // console.log('Clicked');
    }
    function reject(event_ID, event_name) {
      window.location.replace("event_manager.php?reject_ID=" + event_ID + "&event_name=" + event_name);
      // console.log('Clicked');
    }
    function rework(event_ID, event_name) {
      window.location.replace("event_manager.php?rework_ID=" + event_ID + "&event_name=" + event_name);
      // console.log('Clicked');
    }
  </script>
</body>

</html>