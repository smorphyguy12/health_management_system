<?php
    $servername ="localhost";
    $username = "root";
    $password = "";
    $dbname = "health-management-system";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        echo "Database connection error!" . $conn->connect_error;    
    } else {
        //echo "Database connection successfully!";
    }
?>