<?php
require "../php/config.php";
session_start();

$message = '';
$message1 = '';

if (!isset($_SESSION['email']) || !isset($_SESSION['user_name']) || !isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}
$id = $_SESSION['id'];
$email = $_SESSION['email'];
$username = $_SESSION['user_name'];
$img = $_SESSION['profile'];


if (isset($_POST['btn-username'], $_POST['username'])) {
    $username1 = $_POST['username'];

    $stmt = $conn->prepare("UPDATE admin SET user_name = ? WHERE id = ?");
    $stmt->bind_param("si", $username1, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $username = $username1;
        
        $message = "<div class='alert alert-success' role='alert'>Your username has been updated successfully.</div>";
    } else {
        $message = "<div class='alert alert-warning' role='alert'>No information were changed.</div>";
    }
    $stmt->close();
} else if (isset($_POST['btn-password'])) {
    $password = $_POST['new-password'];
    $hash_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hash_password, $id);

    if ($stmt->execute()) {
        $message1 = "<div class='alert alert-success' role='alert'>Your password has been updated successfully.</div>";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>QS - Account Security</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <?php include "sidebar.php"; ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <?php include "navbar.php"; ?>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Security</h4>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <ul class="nav nav-pills flex-column flex-md-row mb-3">
                                        <li class="nav-item">
                                            <a class="nav-link" href="account-settings.php"><i class="bx bx-user me-1"></i> Account</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-shield-alt-2 me-1"></i> Security</a>
                                        </li>
                                    </ul>
                                    <form method="post">
                                        <!-- username -->
                                        <div class="col-xxl">
                                            <div class="card mb-4">
                                                <div class="card-header d-flex align-items-center justify-content-between">
                                                    <h3 class="mb-0">Change Username</h3>
                                                </div>
                                                <div class="card-body">
                                                    <?php echo $message ?>
                                                    <div class="row mb-3">
                                                        <label class="col-sm-2 col-form-label" for="username">User name</label>
                                                        <div class="col-sm-10">
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                                <input type="text" class="form-control" id="username" name="username" placeholder="John_doe" aria-label="John_doe" value="<?php echo $username ?>" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row justify-content-end">
                                                        <div class="col-sm-10">
                                                            <button type="submit" class="btn btn-primary" name="btn-username" id="btn-username">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <form method="post" id="passwordForm">
                                        <!-- username -->
                                        <div class="col-xxl">
                                            <div class="card mb-4">
                                                <div class="card-header d-flex align-items-center justify-content-between">
                                                    <h3 class="mb-0">Change Password</h3>
                                                </div>
                                                <div class="card-body">
                                                    <?php echo $message1 ?>
                                                    <div class="row mb-3">
                                                        <label class="col-sm-2 col-form-label" for="new-password">New Password</label>
                                                        <div class="col-sm-10">
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i class="bx bx-key"></i></span>
                                                                <input type="password" class="form-control" id="new-password" name="new-password" placeholder="New Password " aria-label="New Password" required />
                                                            </div>
                                                            <div id="error-message-password" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-sm-2 col-form-label" for="confirm-password">Confirm Password</label>
                                                        <div class="col-sm-10">
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i class="bx bx-key"></i></span>
                                                                <input type="password" class="form-control" id="confirm-password" name="new-password" placeholder="Confirm Password " aria-label="Confirm Password" required />
                                                            </div>
                                                            <div id="error-message-confirm-password" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row justify-content-end">
                                                        <div class="col-sm-10">
                                                            <button type="submit" class="btn btn-primary" name="btn-password" id="btn-password">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- validation -->
                                        <script>
                                            document.getElementById('passwordForm').addEventListener('submit', function(event) {
                                                //Validation handler password
                                                var errorMessagepass = document.getElementById('error-message-password');
                                                var passwordInput = document.getElementById('new-password').value;

                                                if (passwordInput.length < 8) {
                                                    errorMessagepass.textContent = 'Password must 8 letters above.';
                                                    event.preventDefault();
                                                } else {
                                                    errorMessagepass.textContent = '';
                                                }

                                                //Validation handler confirm password
                                                var errorMessageconfirmpass = document.getElementById('error-message-confirm-password');
                                                var confirmPass = document.getElementById('confirm-password').value;

                                                if (confirmPass !== passwordInput) {
                                                    errorMessageconfirmpass.textContent = 'The password didn\'t match. Please try again';
                                                    event.preventDefault();
                                                } else {
                                                    errorMessageconfirmpass.textContent = '';
                                                }
                                            })
                                        </script>
                                        <!-- validation -->
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /Account -->
                    </div>
                </div>
            </div>
        </div>
        <!-- / Content -->

        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->
    </div>
    <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function closeAlert() {
            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                $(".alert").slideUp(500);
            });
        }

        $(document).ready(function() {
            closeAlert();
        });
    </script>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/pages-account-settings-account.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>