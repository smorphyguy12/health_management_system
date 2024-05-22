<?php
session_start();
require "../php/config.php";

if (!isset($_SESSION['user_name'])) {
  header("Location: ../index.php");
  exit();
}
$username = $_SESSION['user_name'];
$img = $_SESSION['profile'];

$search = isset($_GET['search']) ? $_GET['search']: '';

$stmt = "SELECT * FROM students WHERE full_name LIKE '%$search%' OR gender LIKE '%$search%' OR contact_information LIKE '%$search%' OR student_id LIKE '%$search%' OR course LIKE '%$search%'";
$result = $conn->query($stmt);
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>HMS - List of Students</title>

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
        <?php include "search.php"; ?>
        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Student Settings / </span>List of Students</h4>
            <div class="card">
              <h5 class="card-header">List of Students</h5>
              <?php
              if ($result->num_rows > 0) {
                echo "<div class='table-responsive text-wrap'>
                    <table class='table table-hover table-striped'>
                    <thead>
                        <tr>
                            <th>Profile</th>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Birthday</th>
                            <th>Gender</th>
                            <th>Course</th>
                            <th>Contact Information</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class='table-border-bottom-0'>";
                while ($rows = $result->fetch_assoc()) {
                  echo "<tr>
                        <td>
                          <ul class='list-unstyled users-list m-0 avatar-group d-flex align-items-center'>
                            <li data-bs-toggle='tooltip' data-popup='tooltip-custom' data-bs-placement='top' class='avatar avatar-lg pull-up'title='{$rows['full_name']}'>
                              <img src='" . ($rows['profile_stud'] == NULL ? '../assets/img/icons/que_icon/profile.png' : '../uploads/' . $rows['profile_stud']) . "' alt='Avatar' class='rounded-circle' />
                            </li>
                          </ul>
                        </td>
                        <td>{$rows['student_id']}</td>
                        <td>{$rows['full_name']}</td>
                        <td>{$rows['date_of_birth']}</td>
                        <td>{$rows['gender']}</td>
                        <td>{$rows['course']}</td>
                        <td>{$rows['contact_information']}</td>
                        <td>
                            <div class='dropdown'>
                                <button type='button' class='btn p-0 dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                                    <i class='bx bx-dots-vertical-rounded'></i>
                                </button>
                                <div class='dropdown-menu'>
                                    <a class='dropdown-item' href='edit-students.php?id={$rows['id']}'><i class='bx bx-edit me-1'></i> Edit</a>
                                    <a class='dropdown-item' href='delete-students.php?id={$rows['id']}'><i class='bx bx-trash me-1'></i> Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>";
                }
                echo "</tbody>
                </table>
                </div>";
              } else {
                echo "<p class='text-center text-danger'>No records found.</p>";
              }
              ?>
            </div>
          </div>
          <!-- / Content -->

          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>


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