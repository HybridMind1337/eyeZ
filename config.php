
<?php
if (count(get_included_files()) == 1) {
    header("Location: ../index.php");
    exit;
}
session_start();

$Host = "localhost";
$Root = "testing";
$Pass = "testing";
$User = "testing";

$conn = mysqli_connect("$Host", "$Root", "$Pass", "$User");

mysqli_set_charset($conn, "UTF8");

if (!$conn) {
    echo "Грешка: Не мога да се свържа с базата данни:" . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

$admin_name = "testing";
$pass = "testing";

$eyez_update = 300;
