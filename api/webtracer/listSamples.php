<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "dashboard";
$id = '';
$con = mysqli_connect($host, $user, $password, $dbname);

$samplesdir = realpath('./webtracer/Samples');
$samples = array_diff(scandir($samplesdir), array('.', '..'));
$sampleOnServer = 'http:\\\\localhost:80\\webtracer\\Samples';

foreach ($samples as $key => $sample) {
    $sampledire = $samplesdir . '\\' . $sample;
    $userid = scandir($sampledire);
    $sampledir = $sampleOnServer . '\\' . $sample . '\\' . $userid[2];
    $real_path = $samplesdir . '\\' . $sample . '\\' . $userid[2];
    $sampledir = addcslashes($sampledir, "\\");
    $real_path = addcslashes($real_path, "\\");
    $query = mysqli_query($con, "SELECT * FROM testetable WHERE sample_name= '$sample'");
    if (mysqli_num_rows($query) <= 0) {
        $sql = "INSERT INTO testetable (url_path, sample_name, real_path) VALUES ('$sampledir', '$sample', '$real_path')";
        $kolp = mysqli_query($con, $sql);
    }
}
