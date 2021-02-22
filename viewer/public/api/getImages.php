<?php
$method = $_SERVER['REQUEST_METHOD'];
$local_url = $_GET["localUrl"];
$real_url = $_GET["realUrl"];
$images = array_diff(scandir($real_url), array('.', '..'));

$images_array = [];

for ($i=2; $i < count($images) ; $i++) { 
    $image = (object) ['image' => $images[$i], 'path' => $local_url.'/'.$images[$i]];
    array_push($images_array, $image);
}

echo json_encode($images_array);

?>