<?php
include("config.php");
include("funcs.php");
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
<?php
eyez_updater();
$checkServers = mysqli_query($conn, "SELECT * FROM eyez_servers ORDER BY RAND() LIMIT 2");
if ($checkServers->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($checkServers)) {
        $getIP = $row['ip'];
        $getPort = $row['port'];
        $getPlayers = $row['players'];
        $getMaxPlayers = $row['maxplayers'];
        $getType = $row['type'];
        $getMap = $row['map'];
        $getName = truncate_chars($row['hostname'], 35, '...');
        $getStatus = $row['status'];

        $getHTTP = $_SERVER['HTTP_HOST'];

        if ($getStatus == '1') {
            $getIcon = 'border-left: 3px solid green;';
        } else if ($getStatus == '0') {
            $getIcon = 'border-left: 3px solid red;';
        }

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/assets/maps/' . $getType . '/' . $getMap . '.jpg')) {
            $getImage = "<img src='assets/maps/$getType/$getMap.jpg' class='card-img-top img-fluid' alt='$getMap' /> ";
        } else if ($getType == 'samp' || $getType === 'mta') {
            $getImage = "<img src='//$getHTTP/eyez/assets/maps/gta/bg.jpg' class='card-img-top img-fluid' alt='Bulgaria' /> ";
        } else {
            $getImage = "<img src='//$getHTTP/eyez/assets/maps/map_no_response.jpg' class='card-img-top img-fluid' alt='Не е намерена карта' /> ";
        }

        echo "<div class='card m-1' style='width: 15rem;$getIcon'>
                  $getImage
                  <div class='card-body'>
                    <b class='card-title'>$getName</b>
                    <p class='card-text'>
                    IP: <span  onclick='prompt(\"$getName:\",\"$getIP:$getPort\"); return false;'>$getIP:$getPort</span><br />
                    Карта: $getMap<br />
                    Играчи: $getPlayers/$getMaxPlayers
                    </p>
                </div>
                </div>";
    }

} else {
    echo '<div class="text-cener alert alert-danger m-1">Няма добавени сървъри към eyeZ</div>';
}
?>

