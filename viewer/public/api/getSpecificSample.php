<?php
$method = $_SERVER['REQUEST_METHOD'];
$jsonURL = $_GET["url"];
$handle = file_get_contents($jsonURL.'/' . 'trace.json');
$str = json_decode("[".ltrim($handle, ',')."]");
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