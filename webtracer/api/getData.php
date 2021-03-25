<?php
$method = $_SERVER['REQUEST_METHOD'];
$getFile = json_decode(file_get_contents("php://input"),true);
$site = $getFile["site"];
$userId = $getFile["userId"];
$path = dirname(__FILE__).'../../Samples/'.$site.'/'.$userId.'/trace.json';
$handle = file_get_contents($path);
$str = json_decode("[".substr($handle, 1)."]");
$lastTime = 0;
$byTimes = array();
$general = array();

foreach ($str as $event) {
    if($event->time > ($lastTime + 3)) {
        array_push($general, $byTimes);
        $lastTime = $event->time;
        $byTimes = [];
    } else {
       array_push($byTimes, $event);
    }
}

echo json_encode($general);

?>