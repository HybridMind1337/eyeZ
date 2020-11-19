<!doctype html>
<html lang="bg">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Инсталация на eyeZ</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body style="background:#333">
<div class="container"
     style="background:#dedede;border:1px solid #dedede;max-width:700px;margin-top: 50px;">
    <hr/>
    <div class="alert alert-info m-1 text-center">Добре дошли в инсталатора на eyeZ.</div>
    <hr/>
    <form method="post">
        <div class="form-group">
            <label>Хост</label>
            <input type="text" name="host" class="form-control" placeholder="Хост (localhost)">
        </div>
        <div class="form-group">
            <label>Потребител</label>
            <input type="text" name="user" class="form-control" placeholder="Потребител">
        </div>
        <div class="form-group">
            <label>Парола</label>
            <input type="password" name="pass" class="form-control" placeholder="Парола">
        </div>
        <div class="form-group">
            <label>База данни</label>
            <input type="text" name="db" class="form-control" placeholder="Име на базаданните">
        </div>
        <div class="form-group">
            <label>През колко секунди да проверява сървъра?</label>
            <input type="text" name="checker" class="form-control" placeholder="??">
            <small class="form-text text-muted">Подразбиране - 300</small>
        </div>
        <hr/>
        <div class="form-group">
            <label>Потребителско име</label>
            <input type="text" name="admin" class="form-control" placeholder="Потребителско име за вход в ACP-то">
            <small class="form-text text-muted">Потребителско име за вход в ACP-то</small>
        </div>
        <div class="form-group">
            <label>Парола за вход</label>
            <input type="test" name="acppass" class="form-control" placeholder="Парола за вход в ACP-то">
        </div>
        <button type="submit" name="install" class="btn btn-success">Инсталирай</button>
    </form>
    <hr/>
    <div class="alert alert-danger text-center m-1">Моля, след инсталацията изтрийте папката install.</div>
    <hr/>
    <?php
    error_reporting(0);
    if (isset($_POST['install'])) {
        $host = $_POST['host'];
        $user = $_POST['user'];
        $pass = $_POST['pass'];
        $db = $_POST['db'];
        $checker = (int)$_POST['checker'] == '' ? 300 : $_POST['checker'];
        $acp = $_POST['admin'];
        $acpass = $_POST['acppass'];

        if ($conn = mysqli_connect($host, $user, $pass, $db)) {
            mysqli_set_charset($conn, "UTF8");
            $filename = 'sql.sql';
            $templine = '';
            $lines = file($filename);
            foreach ($lines as $line) {
                if (substr($line, 0, 2) == '--' || $line == '')
                    continue;
                $templine .= $line;
                if (substr(trim($line), -1, 1) == ';') {
                    mysqli_query($conn, $templine);
                    $templine = '';
                }
            }
            $filename = "../config.php";
            $output = '
<?php
if (count(get_included_files()) == 1) {
    header("Location: ../index.php");
    exit;
}
session_start();

$Host = "' . $host . '";
$Root = "' . $user . '";
$Pass = "' . $pass . '";
$User = "' . $db . '";

$conn = mysqli_connect("$Host", "$Root", "$Pass", "$User");

mysqli_set_charset($conn, "UTF8");

if (!$conn) {
    echo "Грешка: Не мога да се свържа с базата данни:" . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

$admin_name = "' . $acp . '";
$pass = "' . $acpass . '";

$eyez_update = ' . $checker . ';
';
            $filehandle = fopen($filename, 'w');
            fwrite($filehandle, $output);
            fclose($filehandle);
            echo '<div class="alert alert-success text-center m-1">Системата е успешно инсталирана, моля изтрийте папката install</div>';
        } else {
            echo '<div class="alert alert-danger text-center m-1">Не мога да се свържа с базата данни</div>';
        }
    }
    ?>
    <p class="mt-3 mb-3 text-muted text-center"><i class="far fa-copyright"></i> eyeZ by
        WEBOcean.INFO <?php echo date("Yг"); ?></p>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
