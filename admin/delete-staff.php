<?php
    require "../php/config.php";

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $stmt = $conn->prepare("DELETE FROM admin WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        header("Location: ./manage-staff.php");
    }
?>
