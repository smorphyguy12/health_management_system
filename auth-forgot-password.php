<?php
require "./php/config.php";
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_assoc();

    $token = bin2hex(random_bytes(16));
    $token_hash = hash("sha256", $token);
    $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

    $stmt = $conn->prepare("UPDATE admin SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?");
    $stmt->bind_param("sss", $token_hash, $expiry, $email);
    $stmt->execute();

    if ($stmt->affected_rows) {
      include "./php/mailer.php";

      $mail->setFrom("supp0rt.queuingsys@outlook.com", "Health Management Sytem");
      $mail->addAddress("{$rows['email']}", "{$rows['user_name']}");
      $mail->isHTML(true);
      $mail->Subject = "Password Reset Request";
      $mail->Body = <<<END

        Dear {$rows["user_name"]},
        <br><br>

        I hope this email finds you well. We have received a request for a password reset for your account on our Health Management System. As part of our security protocol, we take such requests seriously to ensure the safety and integrity of your account.
        <br><br>
        
        If you did not initiate this request, please disregard this email and contact our support team immediately at supp0rt.queuingsys@outlook.com. However, if you did request the password reset, please follow the instructions below to proceed:
        <br><br>
        
        1. Click on the following link to reset your password: ---> <a href='localhost/health_management_system/auth-reset-password.php?token=$token'>link</a> <---
        <br><br>
        
        2. You will be directed to a page where you can enter a new password for your account. Please choose a strong and unique password to enhance the security of your account.
        <br><br>
        
        3. Once you have entered your new password, click on the submit button to complete the password reset process.
        <br><br>
        
        Please note that the password reset link is valid for 30 minutes for security purposes. If you do not reset your password within this timeframe, you may need to request another password reset.
        <br><br>

        If you encounter any issues or require further assistance, please do not hesitate to contact our support team. We are here to help you.
        <br><br>
        
        Thank you for your cooperation and understanding.
        <br><br>
        
        Best regards,
        <br><br>
        
        Health Management System Team.
        END;
      try {
        $mail->send();
        $message = "<div class='alert alert-success' role='alert'>Message sent, please check your inbox.</div>";
      } catch (Exception $e) {
        $message = "<div class='alert alert-danger' role='alert'>Message could not be sent. Mailer error: {$mail->ErrorInfo}</div>";
      }
    } else {
      $message = "<div class='alert alert-danger' role='alert'>Email is not registered. Please sign up.</div>";
    }
  } else {
    $message = "<div class='alert alert-danger' role='alert'>Invalid email address. Please try again.'</div>";
  }
}
?>

<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>HMS - Forgot Password</title>

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
      <div class="authentication-inner py-4">
        <!-- Forgot Password -->
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
            <h4 class="mb-2">Forgot Password? 🔐</h4>
            <p class="mb-4">Enter your email and we'll send you instructions
              to reset your password</p>
            <form class="mb-3" action="" method="POST">
              <?php echo $message ?>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" autofocus required />
              </div>
              <button class="btn btn-primary d-grid w-100" type="submit">Send Reset Link</button>
            </form>
            <div class="text-center">
              <a href="index.php" class="d-flex align-items-center justify-content-center">
                <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                Back to login
              </a>
            </div>
          </div>
        </div>
        <!-- /Forgot Password -->
      </div>
    </div>
  </div>

  <!-- / Content -->
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