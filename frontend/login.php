<?php
session_start();
$page_name = "Login Page";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #5e72e4;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 1000px;
            width: 100%;
            margin-bottom: 20px;
            margin-top: 30px;
        }

        .login-form h2 {
            font-weight: bold;
            margin-bottom: 20px;
        }

        .login-form .form-control {
            border-radius: 30px;
            padding: 10px 20px;
        }

        .login-form .btn-primary {
            border-radius: 30px;
            background-color: #5e72e4;
            padding: 10px 30px;
            border: none;
            margin-top: 20px;
        }

        .footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 10px 0;
            margin-top: auto;
            width: 100%;
        }

        .footer p {
            margin: 0;
            color: #6c757d;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="container d-flex justify-content-center align-items-center mt-5">
        <div class="row login-container">
            <!-- Error message  -->
            <?php
            if (isset($_SESSION['login_error'])) {
                echo '<div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Error: </strong>' . $_SESSION['login_error'] . '
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>';
                unset($_SESSION['login_error']);
            }
            ?>

            <!-- Login Form Section -->
            <div class="col-md-6 login-form">
                <h2>Welcome to My Portfolio</h2>
                <p>Please Login To Use the Platform</p>
                <form action="handlelogin.php" method="POST">
                    <div class="form-group">
                        <label for="email">Enter Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Enter Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember Me</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                    <p class="mt-2"><a href="#">Forgot Password? Click Here</a></p>
                </form>
            </div>

            <!-- Image Section -->
            <div class="col-md-6 text-center login-image">
                <img src="images/logo.jpg" alt="Login Image" class="img-fluid rounded">
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p> &copy; 2024 EduXplore | Created by Tech Curators</p>
        <p>Follow us on <a href="#">LinkedIn</a>, <a href="#">Instagram</a>, <a href="#">GitHub</a></p>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>