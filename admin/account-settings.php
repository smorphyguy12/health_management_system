<?php
require "../php/config.php";
session_start();

$message = '';

if (!isset($_SESSION['user_name'])) {
  header("Location: ../index.php");
  exit();
}
$fullname = $_SESSION['full_name'];
$username = $_SESSION['user_name'];
$img = $_SESSION['profile'];
$id = $_SESSION['id'];
$email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["upload"])) {
  $uploadDir = "../uploads/";
  $allowedExtensions = array("jpg", "jpeg", "png", "gif");
  $maxFileSize = 5 * 1024 * 1024;

  $fileName = $_FILES["upload"]["name"];
  $fileSize = $_FILES["upload"]["size"];
  $fileTmpName = $_FILES["upload"]["tmp_name"];
  $fileType = $_FILES["upload"]["type"];
  $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

  if (!in_array($fileExtension, $allowedExtensions)) {
    $message = "<div class='alert alert-warning' role='alert'>Error: Only JPG, JPEG, PNG, and GIF files are allowed.</div>";
  }

  if ($fileSize > $maxFileSize) {
    $message = "<div class='alert alert-warning' role='alert'>Error: File size exceeds the maximum limit (5MB).</div>";
  }

  if (move_uploaded_file($fileTmpName, $uploadDir . $fileName)) {
    $message = "<div class='alert alert-success' role='alert'>File uploaded successfully!</div>";
    $img = $uploadDir . $fileName;

    $stmt = $conn->prepare("UPDATE admin SET profile = ? WHERE id = ?");
    $stmt->bind_param("ss", $img, $id);
    $stmt->execute();

    $_SESSION['profile'] = $img;
  } else {
    $message = "<div class='alert alert-warning' role='alert'>Please select a file.</div>";
  }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $fullname = filter_var($_POST['fullname'], FILTER_SANITIZE_SPECIAL_CHARS);

  $stmt = $conn->prepare("SELECT * FROM admin WHERE id = ? OR full_name = ? OR email = ?");
  $stmt->bind_param("iss", $id, $fullname, $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $rows = $result->fetch_assoc();

  if ($stmt->affected_rows > 1) {
    if ($rows['email'] !== $email) {
      $message = "<div class='alert alert-warning'>The '$email' is already used. Please try again.</div>";
    }
    if ($rows['full_name'] !== $fullname) {
      $message = "<div class='alert alert-warning'>The '$fullname' is already used. Please try again.</div>";
    }
  } else {
    $stmt = $conn->prepare("UPDATE admin SET email = ?, full_name = ? WHERE id = ?");
    $stmt->bind_param("ssi", $email, $fullname, $id);

    if ($stmt->execute()) {
      if ($stmt->affected_rows > 0) {
        $message = "<div class='alert alert-success' role='alert'>Your information has been updated.</div>";

        $_SESSION['email'] = $email;
        $_SESSION['full_name'] = $fullname;
      } else {
        $message = "<div class='alert alert-warning' role='alert'>No information changes.</div>";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>HMS - Account Settings</title>

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
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Account</h4>

            <div class="row">
              <div class="col-md-12">
                <div class="col-md-12">
                  <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                      <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Account</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="account-settings-security.php"><i class="bx bx-shield-alt-2 me-1"></i> Security</a>
                    </li>
                  </ul>
                  <div class="card mb-4">
                    <form method="post" enctype="multipart/form-data" id="uploadForm">
                      <h5 class="card-header">Profile Details</h5>
                      <!-- Account -->
                      <div class="card-body">
                        <?php echo $message ?>
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                          <img src="../uploads/<?php echo $img ?>" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                          <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                              <span class="d-none d-sm-block">Upload new photo</span>
                              <i class="bx bx-upload d-block d-sm-none"></i>
                              <input type="file" id="upload" name="upload" class="account-file-input" hidden accept="image/png, image/jpeg, image/gif" />
                            </label>
                            <button type="submit" class="btn btn-primary me-2 mb-4">Upload</button>
                            <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                              <i class="bx bx-reset d-block d-sm-none"></i>
                              <span class="d-none d-sm-block">Reset</span>
                            </button>

                            <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 5MB</p>
                          </div>
                        </div>
                    </form>
                  </div>
                  <hr class="my-0" />
                  <div class="card-body">
                    <form method="POST">
                      <div class="row">
                        <input type="hidden" name="id" id="id" value="<?php echo $id ?>">
                        <div class="mb-3 col-md-6">
                          <label for="username" class="form-label">Full name</label>
                          <input class="form-control" type="text" id="fullname" name="fullname" placeholder="John" value="<?php echo $fullname ?>" autofocus />
                        </div>
                        <div class="mb-3 col-md-6">
                          <label for="email" class="form-label">E-mail</label>
                          <input class="form-control" type="text" id="email" name="email" placeholder="john.doe@example.com" value="<?php echo $email ?>" />
                        </div>
                        <div class="mt-2">
                          <button type="submit" class="btn btn-primary me-2">Save changes</button>
                          <button type="reset" class="btn btn-outline-secondary">Reset</button>
                        </div>
                    </form>
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