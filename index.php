<?php
if (file_exists('install')) {
    header('location: install/');
    exit();
}
    header("Location: admin/index.php");
    exit();


