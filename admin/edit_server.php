<?php
include("../config.php");
include("../funcs.php");

if ($_SESSION['admin'] == 0) {
    header('Location: ../index.php');
    exit;
}

$id = (int)$_GET['id'];
$findServ = mysqli_query($conn, "SELECT * FROM eyez_servers WHERE id='$id'");
$row = mysqli_fetch_assoc($findServ);

$getName = $row['hostname'];
$getIP = $row['ip'];
$getPort = $row['port'];
$getType = $row['type'];

if (isset($_POST['edit_server'])) {
    $newIP = $_POST['ip'];
    $newPort = $_POST['port'];
    $newType = $_POST['type'];

    $change = mysqli_query($conn, "UPDATE eyez_servers SET ip='$newIP', port='$newPort', type='$newType' WHERE id='$id'");
    msg('control.php', 'success', '<i class="fas fa-check"></i> Сървъра е успешно променен');
}
mysqli_free_result($findServ);
?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>eyeZ &bull; Промяна на сървър (<?php echo $getName; ?>)</title>
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
                    <a class="btn btn-sm btn-outline-secondary" href="#">Администрация</a>
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

        <main role="main">
            <div class="row">
                <div class="col-md-12 blog-main">
                    <h5 class="text-center"><span
                                class="badge badge-pill badge-primary">Промяна на <?php echo $getName; ?></span></h5>
                    <form method="post" action="">
                        <div class="form-group">
                            <label>IP адрес</label>
                            <input type="text" name="ip" value="<?php echo $getIP; ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Порт</label>
                            <input type="text" name="port" value="<?php echo $getPort; ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Тип</label>
                            <input type="text" name="type" value="<?php echo $getType; ?>" class="form-control">
                            <small class="form-text text-muted">Възможни типове: cs16, csgo, samp, mta, minecraft, tf2,
                                teamspeak3</small>
                        </div>
                        <button type="submit" name="edit_server" class="btn btn-primary">Промени</button>
                    </form>
                    <br/>
                    <?php echo showMessage(); ?>
                    <br/>
                </div>
            </div>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
<?php deleteSession('alert'); ?>