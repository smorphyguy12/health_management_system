<?php
require "../php/config.php";
session_start();

if (!isset($_SESSION['user_name'])) {
  header("Location: ../index.php");
  exit();
}

$user_name = $_SESSION['user_name'];
$img = $_SESSION['profile'];
$full_name = $_SESSION['full_name'];

$message = '';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $username = filter_var($_POST['username'], FILTER_SANITIZE_SPECIAL_CHARS);
  $fullname = filter_var($_POST['fullname'], FILTER_SANITIZE_SPECIAL_CHARS);
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $role = 'staff';
  $password = $_POST['password'];
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ? OR user_name = ? OR full_name = ?");
  $stmt->bind_param("sss", $email, $username, $fullname);
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
    $stmt = $conn->prepare("INSERT INTO admin (full_name, email, password, user_name, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullname, $email, $hashed_password, $username, $role);

    if ($stmt->execute()) {
      $message = "<div class='alert alert-success' role='alert'>The staff has been added successfully.</div>";
    } else {
      $message = "<div class='alert alert-danger' role='alert'>Something is wrong. Please contact administrator for this issue.</div>";
    }
  }

  $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum=1.0" />
  <title>HMS - Add Staff</title>
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
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Staff / </span>Add Staff</h4>
            <form method="post">
              <div class="col-xxl">
                <div class="card mb-4">
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">Add Staff</h3>
                  </div>
                  <div class="card-body">
                    <?php echo $message ?>
                    <div class="row mb-3">
                      <label class="col-sm-2 col-form-label" for="fullname">Fullname</label>
                      <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                          <span class="input-group-text"><i class="bx bx-user"></i></span>
                          <input type="text" class="form-control" id="fullname" name="fullname" placeholder="John Doe" aria-label="John Doe" aria-describedby="basic-icon-default-fullname2" required />
                        </div>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label class="col-sm-2 col-form-label" for="email">Email</label>
                      <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                          <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                          <input type="email" class="form-control" id="email" name="email" placeholder="John@Doe" aria-label="John@Doe" aria-describedby="basic-icon-default-fullname2" required />
                        </div>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label class="col-sm-2 col-form-label" for="username">Username</label>
                      <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                          <span class="input-group-text"><i class="bx bx-user"></i></span>
                          <input type="text" class="form-control" id="username" name="username" placeholder="John_Doe" aria-label="John_Doe" aria-describedby="basic-icon-default-fullname2" required />
                        </div>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label class="col-sm-2 col-form-label" for="password">Password</label>
                      <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                          <span class="input-group-text"><i class="bx bx-key"></i></span>
                          <input type="text" id="password" name="password" class="form-control" placeholder="PASSWORD DEFAULT: slsuhms2024" value="slsuhms2024" required />
                        </div>
                      </div>
                    </div>
                    <div class="row justify-content-end">
                      <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
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
