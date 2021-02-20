<?php
/**
 * @param $str
 * @param int $limit
 * @param false $bekind
 * @param null $maxkind
 * @param null $end
 * @return false|mixed|string
 */
function truncate_chars($str, $limit = 15, $bekind = false, $maxkind = NULL, $end = NULL)
{
    if (empty($str) || gettype($str) != 'string') {
        return false;
    }
    $end = empty($end) || gettype($end) != 'string' ? '...' : $end;
    $limit = intval($limit) <= 0 ? 15 : intval($limit);
    if (mb_strlen($str, 'UTF-8') > $limit) {
        if ($bekind == true) {
            $maxkind = $maxkind == NULL || intval($maxkind) <= 0 ? 5 : intval($maxkind);
            $chars = preg_split('/(?<!^)(?!$)/u', $str);
            $cut = mb_substr($str, 0, $limit, 'UTF-8');
            $buffer = '';
            $total = $limit;
            for ($i = $limit; $i < count($chars); $i++) {
                if (!($chars[$i] == "\n" || $chars[$i] == "\r" || $chars[$i] == " " || $chars[$i] == NULL || preg_match('/[\p{P}\p{N}]$/u', $chars[$i]))) {
                    if ($maxkind > 0) {
                        $maxkind--;
                        $buffer = $buffer . $chars[$i];
                    } else {
                        $buffer = !($chars[$i] == "\n" || $chars[$i] == "\r" || $chars[$i] == " " || $chars[$i] == NULL || preg_match('/[\p{P}\p{N}]$/u', $chars[$i])) ? '' : $buffer;
                        $total = !($chars[$i] == "\n" || $chars[$i] == "\r" || $chars[$i] == " " || $chars[$i] == NULL || preg_match('/[\p{P}\p{N}]$/u', $chars[$i])) ? 0 : ($total + 1);
                        break;
                    }
                    $total++;
                } else {
                    break;
                }
            }
            return $total == mb_strlen($str, 'UTF-8') ? $str : ($cut . $buffer . $end);
        }
        return mb_substr($str, 0, $limit, 'UTF-8') . $end;
    } else {
        return $str;
    }
}

/**
 * @param null $location
 * @param $alert
 * @param $message
 */
function msg($location = null, $alert, $message)
{

    $_SESSION['alert'] = array(
        'status' => true,
        'message' => $message,
        'alert' => $alert,
    );

    header('Location: ' . $location);
    exit();

}

function showMessage()
{
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert']['alert'];
        $message = $_SESSION['alert']['message'];
        echo "<div class=\"alert alert-$alert mt-1 mb-1\">";
        echo "<p class=\"card-text text-center\"> $message </p>";
        echo '</div>';
    }

}


/**
 * @param $name
 */
function deleteSession($name)
{
    if (isset($_SESSION[$name])) {
        unset($_SESSION[$name]);
    }
}

/**
 * @param $ip
 * @return bool
 */
function checkIP($ip): bool
{
    if (!filter_var($ip, FILTER_VALIDATE_IP, ['flags' => FILTER_FLAG_IPV4,]) && $ip === gethostbyname($ip)) {
        return true;
    }
}

include("vendor/autoload.php");
/**
 * @throws Exception
 */
function eyez_updater()
{
    global $conn, $GameQ, $eyez_update;
    $getServers = mysqli_query($conn, "SELECT * FROM eyez_servers");
    while ($row = mysqli_fetch_assoc($getServers)) {

        $getID = $row['id'];
        $getLastUP = $row['last_update'];
        $getType = $row['type'];
        $getIP = $row['ip'];

        if ($getLastUP < time()) {
            $nextUP = time() + $eyez_update;

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

            foreach ($results as $get) {

                $getName = $get['gq_hostname'];
                $getStatus = $get['gq_online'];
                $getMaxPlayers = $get['gq_maxplayers'];
                $getPlayers = $get['gq_numplayers'];

                if ($getType == 'teamspeak3') {
                    $getMap = "TeamSpeak";
                } else if ($getType == 'mta') {
                    $getMap = "MTA";
                } else {
                    $getMap = $get['gq_mapname'];
                }

                if ($getStatus == 1) {
                    $update = mysqli_query($conn, "UPDATE eyez_servers SET status='1',hostname='$getName',map='$getMap', players='$getPlayers',maxplayers='$getMaxPlayers',last_update='$nextUP' WHERE id='$getID'");
                } else {
                    $update = mysqli_query($conn, "UPDATE eyez_servers SET status='0', players='0',maxplayers='0',last_update='$nextUP' WHERE id='$getID'");
                }

            }
        }
    }
    @mysqli_free_result($update);
    @mysqli_free_result($getServers);
}
