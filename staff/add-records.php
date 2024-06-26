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

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $results = $stmt->get_result();
  $rows = $results->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  // Student Health Information
  $allergies = filter_var($_POST['allergies'], FILTER_SANITIZE_SPECIAL_CHARS);
  $medications = filter_var($_POST['medications'], FILTER_SANITIZE_SPECIAL_CHARS);
  $medical_conditions = filter_var($_POST['medical_conditions'], FILTER_SANITIZE_SPECIAL_CHARS);
  $immunization_record = filter_var($_POST['immunization_record'], FILTER_SANITIZE_SPECIAL_CHARS);
  $height = filter_var($_POST['height'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $weight = filter_var($_POST['weight'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $blood_type = filter_var($_POST['blood_type'], FILTER_SANITIZE_SPECIAL_CHARS);

  // Emergency Contact Information
  $parent_guardian = filter_var($_POST['parent_guardian'], FILTER_SANITIZE_SPECIAL_CHARS);
  $relationship = filter_var($_POST['relationship'], FILTER_SANITIZE_SPECIAL_CHARS);
  $phone_number = filter_var($_POST['phone_number'], FILTER_SANITIZE_SPECIAL_CHARS);
  $address = filter_var($_POST['address'], FILTER_SANITIZE_SPECIAL_CHARS);

  if (
    empty($allergies) || empty($medications) || empty($medical_conditions) || empty($immunization_record) ||
    empty($height) || empty($weight) || empty($blood_type) || empty($parent_guardian) || empty($relationship) ||
    empty($phone_number) || empty($address)
  ) {

    $message = "<div class='alert alert-danger text-center' role='alert'>Please fill out all fields.</div>";
  } else {
    $stmt = $conn->prepare("INSERT INTO student_health_information (student_id, allergies, medications, medical_conditions, immunization_record, height, weight, blood_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssdds", $rows['id'], $allergies, $medications, $medical_conditions, $immunization_record, $height, $weight, $blood_type);

    $stmt1 = $conn->prepare("INSERT INTO emergency_contact_information (student_id, parent_guardian, relationship, phone_number, address) VALUES (?, ?, ?, ?, ?)");
    $stmt1->bind_param("issss", $rows['id'], $parent_guardian, $relationship, $phone_number, $address);

    if ($stmt->execute() && $stmt1->execute()) {
      header("Location: ./manage-records.php");
      exit();
    } else {
      $message = "<div class='alert alert-danger text-center'>Something went wrong. Please contact the administrator for this issue.</div>";
    }

    $stmt->close();
    $stmt1->close();
  }
}
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>HMS - Add Students</title>
  <meta name="description" content="" />
  <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
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
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> Health Records / Add Health Records / </span>Add Records</h4>
            <?php echo $message; ?>
            <form method="post">
              <input type="hidden" name="id" id="id" value="<?php echo $rows['id']; ?>">
              <div class="row">
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Student Health Records</h5>
                    </div>
                    <div class="card-body">
                      <div class="mb-3">
                        <label class="form-label" for="allergies">Allergies</label>
                        <input type="text" class="form-control" id="allergies" name="allergies" placeholder="Enter Allergies, if any (e.g., Peanuts, Shellfish)" />
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="medications">Medications</label>
                        <input type="text" class="form-control" id="medications" name="medications" placeholder="Enter Medications, if any (e.g., Aspirin, Insulin)" />
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="medical_conditions">Medical Conditions</label>
                        <input type="text" class="form-control" id="medical_conditions" name="medical_conditions" placeholder="Enter Medical Conditions, if any (e.g., Diabetes, Asthma)" />
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="immunization_record">Immunization Records</label>
                        <input type="text" id="immunization_record" name="immunization_record" class="form-control" placeholder="Enter Immunization Record, if any (e.g., Tetanus, Influenza, Pfizer)" />
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="height">Height</label>
                        <input type="number" id="height" name="height" class="form-control" placeholder="Enter height" />
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="weight">Weight</label>
                        <input type="number" id="weight" class="form-control" name="weight" placeholder="Enter weight" />
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="blood_type">Blood Type</label>
                        <input type="text" id="blood_type" name="blood_type" class="form-control" placeholder="Enter Blood Type, if known" />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Emergency Contact Information</h5>
                    </div>
                    <div class="card-body">
                      <div class="mb-3">
                        <label class="form-label" for="parent_guardian">Parent/Guardian</label>
                        <input type="text" class="form-control" id="parent_guardian" name="parent_guardian" placeholder="Enter parent/guardian's name" />
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="relationship">Relationship</label>
                        <input type="text" id="relationship" name="relationship" class="form-control" placeholder="Enter relationship to student (e.g., parent, guardian, sibling, teacher, counselor, etc.)" />
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="phone_number">Phone Number</label>
                        <input type="number" id="phone_number" name="phone_number" class="form-control" placeholder="Enter phone number" />
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="address">Address</label>
                        <input type="text" id="address" name="address" class="form-control" placeholder="Enter address" />
                      </div>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a href="./health-records.php" class="btn btn-secondary">Back</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="content-backdrop fade"></div>
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
  <!-- / Layout wrapper -->
  <script src="../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="../assets/vendor/js/menu.js"></script>
  <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>
  <script src="../assets/js/main.js"></script>
  <script src="../assets/js/dashboards-analytics.js"></script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>