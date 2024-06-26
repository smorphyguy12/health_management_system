<?php
require "../php/config.php";
session_start();

if (!isset($_SESSION['user_name'])) {
  header("Location: ../index.php");
  exit();
}
$username = $_SESSION['user_name'];
$img = $_SESSION['profile'];

$message = '';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $course = filter_var($_POST['course'], FILTER_SANITIZE_SPECIAL_CHARS);
  $acronym = filter_var($_POST['acronym'], FILTER_SANITIZE_SPECIAL_CHARS);

  $stmt = $conn->prepare("SELECT * FROM course WHERE course = ?");
  $stmt->bind_param("s", $course);
  $stmt->execute();
  $results = $stmt->get_result();

  if ($results->num_rows > 0) {
    $courseExists = false;

    while ($existingCourse = $results->fetch_assoc()) {
      if ($existingCourse['course'] == $course) {
        $courseExists = true;
      }
      
      if ($courseExists) {
        $message = "<div class='alert alert-warning' role='alert'>The course '$course' is already added. Please try again.</div>";
      }
    }
  } else {
    $stmt = $conn->prepare("INSERT INTO course (course, acronym) VALUES (?, ?)");
    $stmt->bind_param("ss", $course, $acronym);

    if ($stmt->execute()) {
      $message = "<div class='alert alert-success'>The courses has been added successfully.</div>";
    } else {
      $message = "<div class='alert alert-danger'>Something is wrong. Please contact administrator for this issue.</div>";
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>HMS - Add Courses</title>

  <meta name="description" content />

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

  <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />

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
      <!-- Sidebar -->
      <?php include "sidebar.php"; ?>
      <!-- Sidebar -->
      <!-- Layout container -->
      <div class="layout-page">
        <?php include "navbar.php"; ?>
        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Courses / </span>Add Courses</h4>
            <form method="post">
              <!-- add students -->
              <div class="col-xxl">
                <div class="card mb-4">
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">Add Courses</h3>
                  </div>
                  <div class="card-body">
                    <?php echo $message ?>
                    <div class="row mb-3">
                      <label class="col-sm-2 col-form-label" for="course">Name</label>
                      <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                          <span class="input-group-text"><i class="bx bx-list-ul"></i></span>
                          <input type="text" class="form-control" id="course" name="course" placeholder="Bachelor of Science in Information Technology" aria-label="John Doe" required />
                        </div>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label class="col-sm-2 col-form-label" for="acronym">Acronym</label>
                      <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                          <span class="input-group-text"><i class="bx bx-user"></i></span>
                          <input type="text" class="form-control" id="acronym" name="acronym" placeholder="BSIT" aria-label="BSIT" required />
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
  <!-- Validation -->
  <script>
    document.querySelector('form').addEventListener('submit', function(event) {
      //Validation handler select course
      var errorMessage = document.getElementById('error-message-course');
      var selectedOption = document.getElementById('course').value;

      if (selectedOption === 'select course') {
        errorMessage.textContent = 'Please select a course.';
        event.preventDefault();
      } else {
        errorMessage.textContent = '';
      }
    })
  </script>
  <!-- Validation -->
  <!-- / Layout wrapper -->
  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script src="../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

  <script src="../assets/vendor/js/menu.js"></script>
  <!-- endbuild -->

  <!-- Vendors JS -->
  <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

  <!-- Main JS -->
  <script src="../assets/js/main.js"></script>

  <!-- Page JS -->
  <script src="../assets/js/dashboards-analytics.js"></script>

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>