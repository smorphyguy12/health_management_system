<?php
require "./php/config.php";

if ($_SERVER['REQUEST_METHOD'] === "GET") {
  $token = $_GET['token'];
  $token_hash = hash("sha256", $token);

  $sql = $conn->prepare("SELECT * FROM admin WHERE reset_token_hash = ?");
  $sql->bind_param("s", $token_hash);
  $sql->execute();
  $rows = $sql->get_result()->fetch_assoc();

  if ($rows['reset_token_hash'] === null) {
    header("Location: pages-misc-error-session.php");
    exit();
  }

  if (strtotime($rows['reset_token_expires_at']) <= time()) {
    header("Location: pages-misc-error-session-expired.php");
    exit();
  }
  $sql->close();
} elseif ($_SERVER['REQUEST_METHOD'] === "POST") {
  $token = $_POST['token'];
  $token_hash = hash("sha256", $token);

  $password = $_POST['password'];
  $hash_password = password_hash($password, PASSWORD_DEFAULT);

  $stmt = $conn->prepare("UPDATE admin SET password = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE reset_token_hash = ?");
  $stmt->bind_param("ss", $hash_password, $token_hash);

  if ($stmt->execute()) {
    echo "<script>alert('Your password has been reset successfully. You can log-in now.'); window.location.href='./index.php';</script>";
    exit();
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

  <title>HMS - Reset Password</title>

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
              <a href="index.html" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">
                  <img src="./assets/img/icons/que_icon/queuing-icon.png" class="rounded" width="100" viewBox="0 0 25 42" />
                </span>
              </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-2">Reset Password 🔓</h4>
            <p class="mb-4">Please create your new password</p>

            <form class="mb-3" method="POST">
              <input type="hidden" name="token" id="token" value="<?= htmlspecialchars($token) ?>">
              <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                  <label class="form-label" for="password">New Password</label>
                </div>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control" name="password" placeholder="Enter your new password" aria-describedby="password" required />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
                <div id="error-message-pass" class="text-danger"></div>
              </div>
              <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                  <label class="form-label" for="confirma_pass">Confirm Password</label>
                </div>
                <div class="input-group input-group-merge">
                  <input type="password" id="confirm_pass" class="form-control" name="confirm_pass" placeholder="Enter your confirm password" aria-describedby="confirmation_password" required />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  <div id="error-message-confirm-pass" class="text-danger"></div>
                </div>
              </div>
              <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Reset Password</button>
              </div>
            </form>
          </div>
        </div>
        <!-- /Register -->
      </div>
    </div>
  </div>

  <!-- / Content -->

  <!-- validation -->
    <script>
      document.querySelector('form').addEventListener('submit', function(event) {
        //Validation handler password
        var errorMessagepass = document.getElementById('error-message-pass');
        var passwordInput = document.getElementById('password').value;

        if (passwordInput.length < 8) {
          errorMessagepass.textContent = 'Password must 8 letters above.';
          event.preventDefault();
        } else {
          errorMessagepass.textContent = '';
        }

        //Validation handler confirm password
        var errorMessageconfirmpass = document.getElementById('error-message-confirm-pass');
        var confirmPass = document.getElementById('confirm_pass').value;

        if (confirmPass !== passwordInput) {
          errorMessageconfirmpass.textContent = 'The password didn\'t match. Please try again';
          event.preventDefault();
        } else {
          errorMessageconfirmpass.textContent = '';
        }
      })
    </script>
  <!-- validation -->

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