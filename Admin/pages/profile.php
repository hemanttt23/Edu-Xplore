<!DOCTYPE html>
<html lang="en">
<?php $page_name = "Profile"; ?>
<head>
    <!-- header starts  -->
    <?php require_once '../admin_partials/Admin_header.php'; ?>
    <!-- header ends  -->
</head>

<?php

// Ensure user is logged in, redirect to login page if not
if (!isset($_SESSION['ID'])) {
    header('Location: login.php');
    exit();
}

// Get user information from the session
$userName = $_SESSION['userName'];
$userEmail = $_SESSION['userEmail'];
$userLevel = $_SESSION['userLevel'];

// Mapping user levels for display
$userLevelMapping = [
    0 => 'Super Admin',
    1 => 'Admin',
    2 => 'Head of Department',
    3 => 'Faculty',
    4 => 'Student'
];

$userLevelText = isset($userLevelMapping[$userLevel]) ? $userLevelMapping[$userLevel] : 'Unknown Level';
?>


<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>

    <!-- sidebar starts  -->
    <?php require_once '../admin_partials/Admin_sidebar.php'; ?>
    <!-- sidebar ends  -->

  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <?php require_once '../admin_partials/Admin_navbar.php'; ?>
    <!-- End Navbar -->

    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header pb-0">
              <h6>User Profile</h6>
            </div>
            <div class="card-body">
              <form>
                <div class="form-group">
                  <label for="userName">User Name</label>
                  <input type="text" class="form-control" id="userName" value="<?php echo $userName; ?>" readonly>
                </div>
                <div class="form-group">
                  <label for="userEmail">User Email</label>
                  <input type="email" class="form-control" id="userEmail" value="<?php echo $userEmail; ?>" readonly>
                </div>
                <div class="form-group">
                  <label for="userLevel">User Level</label>
                  <input type="text" class="form-control" id="userLevel" value="<?php echo $userLevelText; ?>" readonly>
                </div>
                <a class="btn btn-primary" href="">Complete Profile</a>
                <a class="btn btn-success" href="">Edit Profile</a>
              </form>
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
  <script src="../assets/js/plugins/chartjs.min.js"></script>

  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/argon-dashboard.min.js?v=2.0.4"></script>

</body>
</html>
