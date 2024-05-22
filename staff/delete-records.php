<?php
    require "../php/config.php";

    if (isset($_GET['student_health_id'], $_GET['emergency_id'])) {
        $emergency_id = $_GET['emergency_id'];
        $student_health_id = $_GET['student_health_id'];

        $stmt = $conn->prepare("DELETE shi, eci FROM student_health_information AS shi INNER JOIN emergency_contact_information AS eci ON shi.student_id = eci.student_id WHERE shi.student_health_id = ? AND eci.emergency_id = ?");
        $stmt->bind_param("ii", $student_health_id, $emergency_id);
        $stmt->execute();

        header("Location: ./manage-records.php");
    }
?>
