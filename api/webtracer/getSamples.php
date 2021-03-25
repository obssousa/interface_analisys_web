<?php
$method = $_SERVER['REQUEST_METHOD'];

$real = dirname(__FILE__).'/../../';

echo $real;

$dir = new DirectoryIterator($real);

if ($method == 'GET') {
    echo json_encode($dir);
}
?>