<?php
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
$_POST = json_decode(file_get_contents("php://input"),true);
$site = $_POST["site"];
$userId = $_POST["userId"];

$path = dirname(__FILE__).'../../Samples/'.$site.'/'.$userId;
$allDirs = [];

$dir = new DirectoryIterator($path);
foreach ($dir as $fileinfo) {
    if (!$fileinfo->isDot()) {
        array_push($allDirs, 'http://localhost:80/webtracer/Samples/'.$site.'/'.$userId.'/'.$fileinfo->getFileName());
    }
}
if ($method == 'POST') {
    echo json_encode($allDirs);
}
?>