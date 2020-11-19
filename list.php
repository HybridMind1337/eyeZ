<?php
include("config.php");
include("funcs.php");
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
<?php
    eyez_updater();
    $checkServers = mysqli_query($conn, "SELECT * FROM eyez_servers ORDER by type DESC");
    if ($checkServers->num_rows > 0) {
        echo '<div class="table-responsive">
    <table class="table text-center table-striped table-bordered table-hover">
        <thead class="thead-dark">
        <tr>
            <th>Статус</th>
            <th>Име на сървъра</th>
            <th>IP Адрес</th>
            <th>Играчи</th>
            <th>Карта</th>
            <th>Връзки</th>
        </tr>
        </thead>
        <tbody>';
        while ($row = mysqli_fetch_assoc($checkServers)) {
            $getIP = $row['ip'];
            $getPort = $row['port'];
            $getPlayers = $row['players'];
            $getMaxPlayers = $row['maxplayers'];
            $getType = $row['type'];
            $getMap = $row['map'];
            $getName = $row['hostname'];
            $getStatus = $row['status'];

            $getHTTP = $_SERVER['HTTP_HOST'];

            @$per_cent = floor(($getPlayers / $getMaxPlayers) * 100);

            $bg = "";
            if ($per_cent < 0 || $per_cent > 35) {
                $bg = "bg-primary";
            }
            if ($per_cent > 50) {
                $bg = "bg-warning";
            }
            if ($per_cent > 80) {
                $bg = "bg-danger";
            }

            if ($getStatus == '1') {
                $getIcon = '<span class="badge badge-pill badge-success"><i class="fas fa-check"></i> Online</span>';
            } else if ($getStatus == '0') {
                $getIcon = '<span class="badge badge-pill badge-danger"><i class="fas fa-times"></i> Offline</span>';
            }

            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/assets/maps/' . $getType . '/' . $getMap . '.jpg')) {
                $getImage = "<img src='//$getHTTP/assets/maps/$getType/$getMap.jpg' width='25' height='18' alt='$getMap' /> ";
            } else if ($getType == 'samp' || $getType === 'mta') {
                $getImage = "<img src='//$getHTTP/eyez/assets/maps/gta/bg.jpg' width='25' height='18' alt='Bulgaria' /> ";
            } else {
                $getImage = "<img src='//$getHTTP/eyez/assets/maps/map_no_response.jpg' width='25' height='18' alt='Не е намерена карта' /> ";
            }
            ?>
            <tr>
                <td><?php echo $getIcon; ?></td>
                <td><img src='assets/img/<?php echo $getType; ?>.png' alt='Тип на играта'/> <?php echo $getName; ?></td>
                <td><span class='badge badge-pill badge-dark' onclick='prompt("<?php echo $getName; ?>:","<?php echo $getIP . ':' . $getPort; ?>"); return false;'><?php echo $getIP . ':' . $getPort; ?></span>
                </td>
                <td>
                    <div class="progress" style="height: 20px;">
                        <span style="position:absolute;margin-top: 10px;margin-left: 25px;color: #fff;text-shadow: 1px 0px 1px rgba(150, 150, 150, 1);font-weight: bold;"><?php echo $getPlayers; ?>/<?php echo $getMaxPlayers; ?></span>
                        <div class="progress-bar progress-bar-striped progress-bar-animated <?php echo $bg; ?>"
                             role="progressbar" style="width: <?php echo $per_cent; ?>%;"
                             aria-valuenow="<?php echo $per_cent; ?>" aria-valuemin="0" aria-valuemax="100">

                        </div>
                    </div>
                </td>
                <td><?php echo $getImage; ?><?php echo $getMap; ?></td>
                <td>
                    <a href="https://www.gametracker.com/server_info/<?php echo $getIP . ':' . $getPort; ?>/"><img
                                src="//<?php echo $getHTTP; ?>/eyez/assets/img/gt.png" alt="Сървъра в GameTracker.COM"/></a>
                    <a href="steam://connect/<?php echo $getIP . ':' . $getPort; ?>"><img src="//<?php echo $getHTTP; ?>/eyez/assets/img/steam.png"
                                                                                          alt="Влез чрез Steam "/></a>
                </td>
            </tr>
            <?php
        }
        echo '</tbody></table></div>';
        mysqli_free_result($checkServers);

        $getNums = mysqli_query($conn, "SELECT COUNT(*) as numservers, SUM(players) as numplayers, SUM(maxplayers) as slots FROM eyez_servers");
        $row = mysqli_fetch_assoc($getNums);
        $getAllServ = $row['numservers'];
        $getPlayers = $row['numplayers'];
        $getAllPlayers = $row['slots'];

        @$per_cent = floor(($getPlayers / $getAllPlayers) * 100);

        $bg = "";
        if ($per_cent < 0 || $per_cent > 35) {
            $bg = "bg-primary";
        }
        if ($per_cent > 50) {
            $bg = "bg-warning";
        }
        if ($per_cent > 80) {
            $bg = "bg-danger";
        }

        echo "
        <div class='progress' style='height: 20px;'>
          <div class='progress-bar progress-bar-striped progress-bar-animated $bg' role='progressbar' style='width: $per_cent%' aria-valuenow='$per_cent' aria-valuemin='0' aria-valuemax='100'>
          $per_cent%
            </div>
        </div>
        <div class='text-center alert alert-primary m-3'>Имаме $getAllServ сървъра, $getPlayers играча и $getAllPlayers слота</div>";

        mysqli_free_result($getNums);
    } else {
        echo '<div class="text-cener alert alert-danger m-1">Няма добавени сървъри към eyeZ</div>';
    }
    ?>
