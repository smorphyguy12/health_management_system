<?php 
require "../php/config.php";

    if (isset($_GET['id']) ? $_GET['id']: '') {
        $id = $_GET['id'];

        $stmt = $conn->prepare("DELETE FROM course WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: ./list-courses.php");
    }
?>