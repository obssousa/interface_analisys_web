<?php
$method = $_SERVER['REQUEST_METHOD'];
$getFile = json_decode(file_get_contents("php://input"),true);
$site = $getFile["site"];
$path = dirname(__FILE__).'../../Samples/'.$site;
$allDirs = [];

$dir = new DirectoryIterator($path);
foreach ($dir as $fileinfo) {
    if (!$fileinfo->isDot()) {
        array_push($allDirs, $fileinfo->getFileName());
    }
}

if ($method == 'POST') {
    echo json_encode($allDirs);
}
?>