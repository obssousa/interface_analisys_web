<?php
$method = $_SERVER['REQUEST_METHOD'];
$getFile = json_decode(file_get_contents("php://input"),true);
$site = $getFile["site"];
$userId = $getFile["userId"];
$lastTime = $getFile["time"];
$path = dirname(__FILE__).'../../Samples/'.$site.'/'.$userId.'/trace.json';
$handle = file_get_contents($path);
$str = json_decode("[".substr($handle, 1)."]");
$byTimes = array();
$general = array();

foreach ($str as $event) {
    if($event->time > $lastTime) {
        array_push($general, $event);
    }
}

echo json_encode($general);

?>