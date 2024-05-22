<?php 
require "../php/config.php";

    if (isset($_GET['id']) ? $_GET['id']: '') {
        $id = $_GET['id'];

        $stmt = $conn->prepare("DELETE FROM  WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: ./list-courses.php");
    }
?>