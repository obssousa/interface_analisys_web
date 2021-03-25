<?php
$method = $_SERVER['REQUEST_METHOD'];

$path = dirname(__FILE__).'../../Samples';
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