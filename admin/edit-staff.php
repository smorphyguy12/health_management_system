<?php
require "../php/config.php";
session_start();

if (!isset($_SESSION['user_name'])) {
    header("Location: ../index.php");
    exit();
}

$user_name = $_SESSION['user_name'];
$img = $_SESSION['profile'] ?? '';  // Check if profile image is set
$full_name = $_SESSION['full_name'];

$message = '';

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    $stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    } else {
        $message = "<div class='alert alert-danger' role='alert'>Failed to prepare statement.</div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['id'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_SPECIAL_CHARS);
    $fullname = filter_var($_POST['fullname'], FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    $stmt = $conn->prepare("SELECT * FROM admin WHERE (email = ? OR user_name = ? OR full_name = ?) AND id != ?");
    if ($stmt) {
        $stmt->bind_param("sssi", $email, $username, $fullname, $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($existingUser = $result->fetch_assoc()) {
                if ($existingUser['email'] === $email) {
                    $message = "<div class='alert alert-warning' role='alert'>The email '$email' is already used. Please try again.</div>";
                    break;
                }
                if ($existingUser['user_name'] === $username) {
                    $message = "<div class='alert alert-warning' role='alert'>The username '$username' is already used. Please try again.</div>";
                    break;
                }
                if ($existingUser['full_name'] === $fullname) {
                    $message = "<div class='alert alert-warning' role='alert'>The fullname '$fullname' is already used. Please try again.</div>";
                    break;
                }
            }
        } else {
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE admin SET full_name = ?, email = ?, password = ?, user_name = ? WHERE id = ?");
                $stmt->bind_param("ssssi", $fullname, $email, $hashed_password, $username, $id);
            } else {
                $stmt = $conn->prepare("UPDATE admin SET full_name = ?, email = ?, user_name = ? WHERE id = ?");
                $stmt->bind_param("sssi", $fullname, $email, $username, $id);
            }
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $message = "<div class='alert alert-success' role='alert'>The staff has been edited successfully.</div>";

                $rows['user_name'] = $username;
                $rows['full_name'] = $fullname;
                $rows['email'] = $email;
            } else {
                $message = "<div class='alert alert-warning' role='alert'>No information was changed.</div>";
            }
            $stmt->close();
        }
    } else {
        $message = "<div class='alert alert-danger' role='alert'>Failed to prepare statement.</div>";
    }
} else if (isset($_POST['change_pass_btn'])) {
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("si", $hashed_password, $id);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success' role='alert'>The password has been updated.</div>";
        } else {
            $message = "<div class='alert alert-warning' role='alert'>Something went wrong while updating the password. Please contact the administrator.</div>";
        }
        $stmt->close();
    } else {
        $message = "<div class='alert alert-danger' role='alert'>Failed to prepare statement.</div>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum=1.0" />
    <title>HMS - Edit Staff</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php include "sidebar.php"; ?>
            <div class="layout-page">
                <?php include "navbar.php"; ?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Staff / </span>Edit Staff</h4>
                        <div class="col-xxl">
                            <div class="card mb-4">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h3 class="mb-0">Edit Staff</h3>
                                </div>
                                <div class="card-body">
                                    <?php echo $message ?>
                                    <form method="post">
                                        <div class="row mb-3">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($rows['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <label class="col-sm-2 col-form-label" for="fullname">Fullname</label>
                                            <div class="col-sm-10">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="John Doe" aria-label="John Doe" value="<?php echo htmlspecialchars($rows['full_name'], ENT_QUOTES, 'UTF-8'); ?>" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="email">Email</label>
                                            <div class="col-sm-10">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                                    <input type="email" class="form-control" id="email" name="email" placeholder="john@doe.com" aria-label="john@doe.com" value="<?php echo htmlspecialchars($rows['email'], ENT_QUOTES, 'UTF-8'); ?>" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="username">Username</label>
                                            <div class="col-sm-10">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                    <input type="text" class="form-control" id="username" name="username" placeholder="john_doe" aria-label="john_doe" value="<?php echo htmlspecialchars($rows['user_name'], ENT_QUOTES, 'UTF-8'); ?>" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-end">
                                            <div class="col-sm-10">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <a href="manage-staff.php" class="btn btn-secondary">Back</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <form method="post">
                                <div class="col-xxl">
                                    <div class="card mb-4">
                                        <div class="card-header d-flex align-items-center justify-content-between">
                                            <h3 class="mb-0">Edit Password</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <label class="col-sm-2 col-form-label" for="password">Password</label>
                                                <div class="col-sm-10">
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-company2" class="input-group-text"><i class="bx bx-key"></i></span>
                                                        <input type="text" id="password" name="password" class="form-control"value="slsuhms2024" placeholder="Enter new password" aria-describedby="basic-icon-default-company2" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-end">
                                                <div class="col-sm-10">
                                                    <button type="submit" class="btn btn-primary" name="change_pass_btn">Submit</button>
                                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
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
    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            var errorMessage = document.getElementById('error-message-service');
            var selectedOption = document.getElementById('service').value;

            if (selectedOption === 'select services') {
                errorMessage.textContent = 'Please select a service.';
                event.preventDefault();
            } else {
                errorMessage.textContent = '';
            }
        })
    </script>
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>
    <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/dashboards-analytics.js"></script>
</body>

</html>
