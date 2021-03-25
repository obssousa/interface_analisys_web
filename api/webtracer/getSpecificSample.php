<?php
$method = $_SERVER['REQUEST_METHOD'];
$site = $_GET["site"];
$userId = $_GET["userId"];

$path = dirname(__FILE__).'../../Samples/' + $site + '/' + $userId;
$allDirs = [];

$dir = new DirectoryIterator($path);
foreach ($dir as $fileinfo) {
    if (!$fileinfo->isDot()) {
        array_push($allDirs, $fileinfo->getFileName());
    }
}

if ($method == 'GET') {
    echo json_encode($allDirs);
}
?>