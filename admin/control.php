<?php
include("../config.php");
include("../funcs.php");

if ($_SESSION['admin'] == 0) {
    header('Location: ../index.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>eyeZ &bull; Контролен панел</title>
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

    <div class="jumbotron p-4 p-md-5 text-white rounded bg-dark">
        <div class="col-md-6 px-0">
            <h1 class="display-4 font-italic">За eyeZ</h1>
            <p class="lead my-3">
                <b>eyeZ</b> е изработка на екипа от WEBOcean.INFO, тя представлява малък и пъргав уеб базиран скрипт,
                който работи с GameQ от Austiq.
            </p>
            <p class="lead mb-0"><a href="https://webocean.info" class="text-white font-weight-bold">При проблеми,
                    посетете нашия уеб сайт</a></p>
        </div>
    </div>
    <main role="main">
        <div class="row">
            <div class="col-md-12 blog-main">
                <div class="table-responsive">
                    <table class="table text-center table-sm table-striped table-bordered table-hover">
                        <caption>Всички добавени сървъри към eyeZ</caption>
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Име</th>
                            <th scope="col">IP Адрес</th>
                            <th scope="col">Играчи</th>
                            <th scope="col">Карта</th>
                            <th scope="col">Функции</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $checkServers = mysqli_query($conn, "SELECT * FROM eyez_servers order by id DESC");
                        while ($row = mysqli_fetch_assoc($checkServers)) {
                            $getID = $row['id'];
                            $getIP = $row['ip'];
                            $getPort = $row['port'];
                            $getPlayers = $row['players'];
                            $getMaxPlayers = $row['maxplayers'];
                            $getType = $row['type'];
                            $getMap = $row['map'];
                            $getName = truncate_chars($row['hostname'], 60, '...');
                            $getStatus = $row['status'];

                            if ($getStatus == '1') {
                                $getIcon = '<span class="badge badge-pill badge-success"><i class="fas fa-check"></i></span>';
                            } else if ($getStatus == '0') {
                                $getIcon = '<span class="badge badge-pill badge-danger"><i class="fas fa-times"></i></span>';
                            }

                            echo "<tr>
                                    <th scope='row'><img src='../assets/img/$getType.png' alt='Тип на играта' /> $getIcon</th>
                                    <td>$getName</td>
                                    <td>$getIP:$getPort</td>
                                    <td>$getPlayers / $getMaxPlayers</td>
                                    <td>$getMap</td>
                                    <td><div class='btn-group btn-group-toggle' data-toggle='buttons'>
                                        <a class='btn btn-outline-danger btn-sm delete_server' href='inc/delsrv.php?id=$getID'><i class='far fa-trash-alt'></i></a>
                                        <a class='btn btn-outline-primary btn-sm' href='edit_server.php?id=$getID'><i class='fas fa-edit'></i></a>
                                        </div>
                                     </td>
                                </tr>";
                        }
                        mysqli_free_result($checkServers);
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $('.delete_server').click(function() {
        if(confirm('Сигурен ли сте, че искате да изтриете сървъра?')) {
            $.ajax({
                url:        $(this).attr('href'),
                type:       'GET',
                dataType:   'json',
                success:    function(data) {
                    alert(data['info']);
                    $('.ff-' + data['id']).remove();
                }
            });
            return false;
        } else {
            return false;
        }
    })
</script>
</body>
</html>
