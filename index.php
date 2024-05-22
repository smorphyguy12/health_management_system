<?php
session_start();
require "./php/config.php";
$message = '';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $email_username = filter_var($_POST['email-username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ? OR user_name = ?");
  $stmt->bind_param("ss", $email_username, $email_username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $rows = $result->fetch_assoc();

    if (password_verify($password, $rows['password']) && $rows['role'] == 'admin') {
      $_SESSION['id'] = $rows['id'];
      $_SESSION['full_name'] = $rows['full_name'];
      $_SESSION['user_name'] = $rows['user_name'];
      $_SESSION['email'] = $rows['email'];
      $_SESSION['profile'] = $rows['profile'] !== null ? $rows['profile'] : "../assets/img/icons/que_icon/profile.png";

      header("Location: ./admin/index.php");
      exit();
    } else if (password_verify($password, $rows['password']) && $rows['role'] == 'staff') {
      $_SESSION['id'] = $rows['id'];
      $_SESSION['full_name'] = $rows['full_name'];
      $_SESSION['user_name'] = $rows['user_name'];
      $_SESSION['email'] = $rows['email'];
      $_SESSION['profile'] = $rows['profile'] !== null ? $rows['profile'] : "../assets/img/icons/que_icon/profile.png";

      header("Location: ./staff/index.php");
      exit();
    } else {
      $message = "<div class='alert alert-danger' role='alert'>Invalid username or password.</div>";
    }
  } else {
    $message = "<div class='alert alert-danger' role='alert'>Invalid username or password.</div>";
  }
  $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>HMS - Admin</title>
  <meta name="description" content />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="./assets/img/favicon/favicon.ico" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

  <!-- Icons. Uncomment required icon fonts -->
  <link rel="stylesheet" href="./assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="./assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="./assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="./assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <!-- Page CSS -->
  <!-- Page -->
  <link rel="stylesheet" href="./assets/vendor/css/pages/page-auth.css" />
  <!-- Helpers -->
  <script src="./assets/vendor/js/helpers.js"></script>

  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="./assets/js/config.js"></script>
</head>

<body>
  <!-- Content -->

  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Register -->
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
              <a href="index.php" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">
                  <img src="./assets/img/icons/que_icon/queuing-icon.png" class="rounded" width="100" viewBox="0 0 25 42" />
                </span>
              </a>
            </div>
            <!-- /Logo -->
            <h5 class="mb-2">Empowering Wellness, One Step at a Time!</h5>
            <h4 class="mb-2 pb-1"><b>Welcome to Health Management System💻</b></h4>
            <p class="mb-4">Please sign-in to your account</p>

            <form class="mb-3" method="POST">
              <?php echo $message ?>
              <div class="mb-3">
                <label for="email-username" class="form-label">Email or Username</label>
                <input type="text" class="form-control" id="email-username" name="email-username" placeholder="Enter your email or username" autofocus required />

              </div>
              <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                  <label class="form-label" for="password">Password</label>
                  <a href="auth-forgot-password.php">
                    <small>Forgot Password?</small>
                  </a>
                </div>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
                <div class="mb-3 pt-2">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
              </div>
            </form>
          </div>
        </div>
        <!-- /Register -->
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    function closeAlert() {
      $(".alert").fadeTo(5000, 500).slideUp(500, function() {
        $(".alert").slideUp(500);
      });
    }

    $(document).ready(function() {
      closeAlert();
    });
  </script>

  <!-- / Content -->

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script src="./assets/vendor/libs/jquery/jquery.js"></script>
  <script src="./assets/vendor/libs/popper/popper.js"></script>
  <script src="./assets/vendor/js/bootstrap.js"></script>
  <script src="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

  <script src="./assets/vendor/js/menu.js"></script>
  <!-- endbuild -->

  <!-- Vendors JS -->

  <!-- Main JS -->
  <script src="./assets/js/main.js"></script>

  <!-- Page JS -->

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>