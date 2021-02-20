<?php
include("../config.php");
include("../funcs.php");
include("../vendor/autoload.php");

if ($_SESSION['admin'] == 0) {
    header('Location: ../index.php');
    exit;
}

if (isset($_POST['add_server'])) {

    if (empty($_POST['ip'])) {
        msg("add_server.php", "danger", "<i class='fas fa-exclamation-triangle'></i> Моля, попълнете IP адреса на сървъра");
    } elseif (empty($_POST['type'])) {
        msg("add_server.php", "danger", "<i class='fas fa-exclamation-triangle'></i> Моля, изберете тип на сървъра");
    } elseif (!checkIP($_POST['ip'])) {
        msg("add_server.php", "danger", "<i class='fas fa-exclamation-triangle'></i> Моля, въведете валиден IP адрес");
    }

    $getIP = mysqli_real_escape_string($conn, $_POST['ip']);
    $getType = mysqli_real_escape_string($conn, $_POST['type']);

    $GameQ = new \GameQ\GameQ();
    if ($getType == 'teamspeak3') {
        $GameQ->addServer([
            'type' => $getType,
            'host' => $getIP,
            'options' => [
                'query_port' => 10011,
            ],
        ]);
    } else {
        $GameQ->addServer([
            'type' => $getType,
            'host' => $getIP,
        ]);
    }

    $GameQ->setOption('timeout', 5);
    $GameQ->addFilter('normalize');
    $results = $GameQ->process();

    $get = $results[$getIP];

    $getName = $get['gq_hostname'];
    $getStatus = $get['gq_online'];
    $getMaxPlayers = $get['gq_maxplayers'];
    $getPlayers = $get['gq_numplayers'];
    $getTime = time();

    if ($getType == 'teamspeak3') {
        $getMap = "TeamSpeak";
    } else if ($getType == 'mta') {
        $getMap = "MTA";
    } else {
        $getMap = $get['gq_mapname'];
    }

    if ($getStatus == 1) {
        $addServer = mysqli_query($conn, "INSERT INTO `eyez_servers` (`ip`, `players`, `maxplayers`, `type`, `map`, `hostname`, `status`, `last_update`) VALUES ('$getIP' ,'$getPlayers','$getMaxPlayers','$getType','$getMap','$getName','$getStatus', '$getTime')");
        mysqli_free_result($addServer);
        msg('add_server.php', 'success', '<i class="fas fa-check"></i> Сървъра е добавен към системата на eyeZ');
    } else {
        msg('add_server.php', 'danger', '<i class="fas fa-exclamation-triangle"></i> Сървъра не работи.');
    }

}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>eyeZ &bull; Добавяне на сървър</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="assets/main.css">
</head>
<body>
<div class="container border">
    <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-4 pt-1">
                <a class="text-muted" href="https://webocean.info">WEBOcean.INFO</a>
            </div>
            <div class="col-4 text-center">
                <a class="blog-header-logo text-dark" href="#"><i class="fas fa-eye"></i> eyeZ</a>
            </div>
            <div class="col-4 d-flex justify-content-end align-items-center">
                <a class="btn btn-sm btn-outline-dark" href="#">Администрация</a>
            </div>
        </div>
    </header>

    <div class="nav-scroller py-1 mb-2">
        <nav class="nav d-flex justify-content-between">
            <a class="p-2 text-muted" href="control.php">Начало</a>
            <a class="p-2 text-muted" href="add_server.php">Добави сървър</a>
            <a class="p-2 text-muted" href="logout.php">Изход</a>
        </nav>
    </div>

    <main role="main pb-5">
        <div class="row">
            <div class="col-md-12 blog-main pb-5">
                <?php echo showMessage(); ?>
                <br/>
                <form method="POST" action="add_server.php">
                    <div class="form-group">
                        <label>IP адрес</label>
                        <input type="text" name="ip" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Игра</label>
                        <select name="type" class="custom-select">
                            <option selected>Избери игра</option>
                            <option value="cs16">Counter-Strike 1.6</option>
                            <option value="csgo">Counter-Strike Global Offensive</option>
                            <option value="samp">San Andreas Multiplayer</option>
                            <option value="mta">Multi Theft Auto</option>
                            <option value="minecraft">MineCraft</option>
                            <option value="tf2">Team Fortress 2</option>
                            <option value="teamspeak3">TeamSpeak 3</option>
                        </select>
                    </div>
                    <button type="submit" name="add_server" class="btn btn-primary">Добави</button>
                </form>
            </div>
        </div>
    </main>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php echo deleteSession('alert'); ?>
