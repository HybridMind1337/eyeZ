<?php
/*
 *
 *  Project Name: eyeZ - Game Server Monitoring
 *  Author: HybridMind <www.webocean.info>
 *  Version: 2.0.1
 *  License:  GPL-3.0
 *  Discord: HybridMind#6095
 *
 */

if (file_exists('install')) {
    header('location: install/');
    exit();
} else {
    header("Location: admin/index.php");
    exit();
}
