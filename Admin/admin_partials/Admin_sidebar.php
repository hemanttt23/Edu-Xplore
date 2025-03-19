<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">

    <?php
    require_once '../../connection.php';

    $level = $_SESSION['userLevel'];
    // Use prepared statements to avoid SQL injection
    $query = "SELECT * FROM pages WHERE page_userlevel LIKE '%$level%'";
    $result = mysqli_query($conn, $query);

    // // Error handling for the query
    // if (!$result) {
    //     die("Query failed: " . mysqli_error($conn));
    // }
    ?>

    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="../pages/dashboard.php" target="_blank">
            <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Edu-Xplore</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="../pages/profile.php">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>
                <!-- Dynamic menu items -->
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo ($row['page_path']); ?>">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1"><?php echo ($row['page_name']); ?></span>
                        </a>
                    </li>
                    <?php } ?>

                <li class="nav-item">
                    <a class="nav-link" href="../../frontend/handlelogout.php">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-collection text-info text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">LogOut</span>
                    </a>
                </li>
                </ul>
            </div>
        </aside>
