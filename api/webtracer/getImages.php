<?php
define ("SITEPATH","http://localhost:80/webtracer/Samples");
$method = $_SERVER['REQUEST_METHOD'];
$site = $_GET["site"];
$userId = $_GET["userId"];

$real = dirname(__FILE__).'/../../webtracer/Samples/' + $site + '/' + $userId;

echo $real;

$dir = new DirectoryIterator($real);
foreach ($dir as $fileinfo) {
    if (!$fileinfo->isDot()) {
        var_dump($fileinfo->getFilename());
    }
}


if ($method == 'GET') {
    echo json_encode($dir);
}
?>