<?php
include("../../config.php");

if ($_SESSION['admin'] == 0) {
    header('Location: ../index.php');
    exit;
}

$id = (int)$_GET['id'];

$delServ = mysqli_query($conn, "DELETE FROM eyez_servers WHERE id='$id'");

mysqli_free_result($delServ);

header("Location: ../control.php");

