<?php
require "../php/config.php";
session_start();

$message = '';

if (!isset($_SESSION['user_name'])) {
  header("Location: ../index.php");
  exit();
}

$username = $_SESSION['user_name'];
$img = $_SESSION['profile'];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $fileName = $_FILES["profileUpload"]["name"];
  $fileTmpName = $_FILES["profileUpload"]["tmp_name"];
  $fileSize = $_FILES["profileUpload"]["size"];
  $fileError = $_FILES["profileUpload"]["error"];
  $fileType = $_FILES["profileUpload"]["type"];

  $allowedExtensions = array("jpg", "jpeg", "png", "gif");

  $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

  $uploadDir = "../uploads/";

  if (!empty($fileName)) {
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (in_array($fileExtension, $allowedExtensions)) {
      if ($fileError === 0) {
        if ($fileSize <= 5 * 1024 * 1024) {
          if (move_uploaded_file($fileTmpName, $uploadDir . $fileName)) {
            $profile = $fileName;
          } else {
            $message .= "<div class='alert alert-warning' role='alert'>Please select a file.</div>";
          }
        } else {
          $message .= "<div class='alert alert-danger' role='alert'>File is too large. Max size allowed is 5MB.</div>";
        }
      } else {
        $message .= "<div class='alert alert-danger' role='alert'>Error occurred while uploading the file.</div>";
      }
    } else {
      $message .= "<div class='alert alert-danger' role='alert'>File type not allowed. Allowed formats: JPG, JPEG, PNG, GIF.</div>";
    }
  }

  if (empty($message)) {
    $student_id = filter_var($_POST['student_id'], FILTER_SANITIZE_SPECIAL_CHARS);
    $fullname = filter_var($_POST['fullname'], FILTER_SANITIZE_SPECIAL_CHARS);
    $dob = filter_var($_POST['dob'], FILTER_SANITIZE_SPECIAL_CHARS);
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_SPECIAL_CHARS);
    $course = filter_var($_POST['course'], FILTER_SANITIZE_SPECIAL_CHARS);
    $con_info = filter_var($_POST['con-info'], FILTER_SANITIZE_SPECIAL_CHARS);

    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ? OR full_name = ?");
    $stmt->bind_param("ss", $student_id, $fullname);
    $stmt->execute();
    $results = $stmt->get_result();

    if ($results->num_rows > 0) {
      while ($existingUser = $results->fetch_assoc()) {
        if ($existingUser['student_id'] == $student_id) {
          $message .= "<div class='alert alert-warning' role='alert'>The student ID '$student_id' is already used. Please try again.</div>";
        }
        if ($existingUser['full_name'] == $fullname) {
          $message .= "<div class='alert alert-warning' role='alert'>The full name '$fullname' is already used. Please try again.</div>";
        }
      }
    } else {
      $stmt = $conn->prepare("INSERT INTO students (student_id, profile_stud, full_name, date_of_birth, gender, course, contact_information) VALUES (?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("sssssss", $student_id, $profile, $fullname, $dob, $gender, $course, $con_info);

      if ($stmt->execute()) {
        $message .= "<div class='alert alert-success'>The student has been registered successfully.</div>";
      } else {
        $message .= "<div class='alert alert-danger'>Something is wrong. Please contact the administrator for this issue.</div>";
      }
    }
  }
}

$stmt = $conn->prepare("SELECT * FROM course");
$stmt->execute();
$course_list = $stmt->get_result();
$conn->close();
?>

<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>HMS - Add Students</title>

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
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Student / </span>Add Students</h4>
            <form method="post" enctype="multipart/form-data">
              <!-- add students -->
              <div class="col-xxl">
                <div class="card mb-4">
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">Add Students</h3>
                  </div>
                  <div class="card-body">
                    <?php echo $message ?>
                    <input type="hidden" name="email" id="email">
                    <div class="row mb-3">
                      <label class="col-sm-2 col-form-label" for="student_id">Student ID</label>
                      <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                          <span class="input-group-text"><i class="bx bx-id-card"></i></span>
                          <input type="text" class="form-control" id="student_id" name="student_id" placeholder="123456-1" aria-label="123456-1" aria-describedby="basic-icon-default-fullname2" required />
                        </div>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label class="col-sm-2 col-form-label" for="firstname">Full name</label>
                      <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                          <span class="input-group-text"><i class="bx bx-user"></i></span>
                          <input type="text" class="form-control" id="fullname" name="fullname" placeholder="John Doe" aria-label="John Doe" required />
                        </div>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label class="col-sm-2 col-form-label" for="dob">Date of Birth</label>
                      <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                          <span class="input-group-text"><i class="bx bxs-baby-carriage"></i></span>
                          <input type="date" class="form-control" id="dob" name="dob" required />
                        </div>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label class="col-sm-2 col-form-label" for="gender">Gender</label>
                      <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                          <span class="input-group-text"><i class="bx bx-male"></i></span>
                          <select name="gender" class="form-select" id="gender">
                            <option selected disabled>select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Others">Other's</option>
                          </select>
                        </div>
                        <div id="error-message-gender" class="text-danger"></div>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="course" class="col-sm-2 col-form-label">Course</label>
                      <div class="col-md-10">
                        <div class="input-group input-group-merge">
                          <span class="input-group-text"><i class="bx bx-list-ul"></i></span>
                          <select class="form-select" id="course" name="course" required>
                            <option selected disabled>select course</option>
                            <?php
                            while ($rows = $course_list->fetch_assoc()) {
                              echo "
                                  <option value='{$rows['course']}'>{$rows['course']}</option>
                                ";
                            }
                            ?>
                          </select>
                        </div>
                        <div id="error-message-course" class="text-danger"></div>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label class="col-sm-2 col-form-label" for="con-info">Contact Information</label>
                      <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                          <span class="input-group-text"><i class="bx bxs-contact"></i></span>
                          <input type="number" id="con-info" name="con-info" class="form-control" placeholder="0987654321" required />
                        </div>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="profileUpload" class="col-sm-2 col-form-label">Student Profile (optional)</label>
                      <div class="col-sm-10">
                        <input class="form-control" type="file" name="profileUpload" id="profileUpload" accept="image/jpg, image/png, image/jpeg, image/gif" onchange="previewImage(event)">
                        <?php if (!empty($rows['profile_stud'])) : ?>
                          <img id="profileImage" class="mt-3 mb-3" alt="Profile Picture" width="200" height="200">
                        <?php else : ?>
                          <img id="profileImage" src="../assets/img/icons/que_icon/profile.png" class="mt-3 mb-3" alt="Profile Picture" width="200" height="200">
                        <?php endif; ?>
                        <p class="text-muted">Allowed JPEG, JPG, GIF or PNG. Max size of 5MB</p>

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
  <script>
    function previewImage(event) {
      var input = event.target;
      var reader = new FileReader();

      reader.onload = function() {
        var dataURL = reader.result;
        var imgElement = document.getElementById('profileImage');
        imgElement.src = dataURL;
      };

      var file = input.files[0];
      reader.readAsDataURL(file);
    }
  </script>
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

      //Validation handler select gender
      var errorMessageGender = document.getElementById('error-message-gender');
      var selectedOptionGender = document.getElementById('gender').value;

      if (selectedOptionGender === 'select gender') {
        errorMessageGender.textContent = 'Please select a gender.';
        event.preventDefault();
      } else {
        errorMessageGender.textContent = '';
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