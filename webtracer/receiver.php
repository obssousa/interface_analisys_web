<?php
function clean($string)
{
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function orderByTime($traces)
{
	//Stage 2
	echo "Stage 2";
    $major = (object) [];
    $pos = -1;
    for ($y = 0; $y < count($traces) - 1; $y--) {
        $major->time = -3;
        for ($x = 0; $x <= $y; $x++) {
            if ($major->time < $traces[$x]->time) {
                $major = copyTrace($traces[$x]);
                $pos = $x;
            }
        }
        $heat = copyTrace($traces[$y]);
        $traces[$y] = copyTrace($major);
        $traces[$pos] = copyTrace($heat);
    }

    //foreach (HeatPoint point in source) result.Insert(0, point);
    return $traces;
}

function copyTrace($trace)
{
    return (object) array('type' => $trace->type, 'image' => $trace->image,
        'time' => $trace->time, 'Id' => $trace->Id, 'Class' => $trace->Class, 'MouseId' => $trace->mouseId,
        'X' => $trace->X, 'Y' => $trace->Y, 'height' => $trace->height, 'scroll' => $trace->scroll,
        'keys' => $trace->keys, 'url' => $trace->Url);
}

function traceRemover($phase, $traces)
{
    if ($phase == 1) {
		//Stage 1
        $timer = 0;
        for ($x = 0; $x < count($traces); $x++) {
            if ($traces[$x]->time < $timer) {
                $traces[$x]->time += 0.1;
                if ($traces[$x]->time < $timer) {
                    unset($traces[$x]);
                    $x--;
                }
            }
        }
    } else {
		//Stage 3
        for ($x = 0; $x < count($traces); $x++) {
            $str1 = $traces[$x - 1]->url;
            $str2 = $traces[$x]->url;
            $str3 = $traces[$x + 1]->url;

            if ($str1 === $str3) {
                if ($str1 != $str2) {
                    unset($traces[$x]);
                    $x--;
                }
            }
        }
    }

    return $traces;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $metadata = json_decode($_POST['metadata']);
    $data = json_decode($_POST['data']);
    $Sample = clean($metadata->sample);
    if (!file_exists("Samples")) {
        mkdir("Samples");
    } else {
        if (!file_exists('Samples/' . $Sample)) {
            mkdir('Samples/' . $Sample);
        } else {
            if (!file_exists('Samples/' . $Sample . '/' . $metadata->userId)) {
                mkdir('Samples/' . $Sample . '/' . $metadata->userId);
            }
        }
    }

    // applying filters from c# for php
    $samplesdir = realpath('./Samples');
    $samples = scandir($samplesdir);
    $traces_paths = [];

    foreach ($samples as $key => $sample) {
        $sampledire = $samplesdir . '\\' . $sample;
        $userid = scandir($sampledire);
        $trace = $samplesdir . '\\' . $sample . '\\' . $userid[2] . '\\' . 'trace.json';
        array_push($traces_paths, $trace);
	}
	

    foreach ($traces_paths as $key => $path) {
		$openTrace = file_get_contents($path);
		$trace_data = json_decode($openTrace);
		
        $ordenados = orderByTime($trace_data);
        $ordenados = traceRemover(1, $ordenados);
        $ordenados = traceRemover(2, $ordenados);

        foreach ($ordenados as $key => $trace) {
            $json_array = array("type" => $trace->type, "image" => $trace->image, "time" => $trace->time, "Class" => $trace->Class, "Id" => $trace->Id, "MouseClass" => $trace->mouseClass, "MouseId" => $trace->mouseId, "X" => $trace->X, "Y" => $trace->Y, "keys" => $trace->Typed, "scroll" => $trace->scroll, "height" => $trace->height, "url" => $trace->url);
            $json_xmr = json_encode($json_array);
            $json_xmr = "," . $json_xmr;
            file_put_contents('Samples/' . $Sample . '/' . $metadata->userId . '/trace.json', $json_xmr . PHP_EOL, FILE_APPEND | LOCK_EX);
        }
    }

    if ($data->imageData != "NO") {
        if (!(file_exists('Samples/' . $Sample . '/' . $metadata->userId . '/' . $data->imageName))) {
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data->imageData));
            $source = imagecreatefromstring($imageData);
            $imageSave = imagejpeg($source, 'Samples/' . $Sample . '/' . $metadata->userId . '/' . $data->imageName, 100);
            imagedestroy($source);
        }
    }
    //if($metadata->type=="eye"){
    //    $txt = $data->X;
    //    file_put_contents('Samples/'.$Sample.'/'.$metadata->userId.'/traceX.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
    //    $txt = $data->Y;
    //    file_put_contents('Samples/'.$Sample.'/'.$metadata->userId.'/traceY.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
    //    $txt = $metadata->time;
    //    file_put_contents('Samples/'.$Sample.'/'.$metadata->userId.'/traceTime.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
    //}else{
    $txt = "<rawtrace type=\"" . $metadata->type . "\" image=\"" . $data->imageName . "\" time=\"" . $metadata->time . "\" Class=\"" . $data->Class . "\" Id=\"" . $data->Id . "\" MouseClass=\"" . $data->mouseClass . "\" MouseId=\"" . $data->mouseId . "\" X=\"" . $data->X . "\" Y=\"" . $data->Y . "\" keys=\"" . $data->Typed . "\" scroll=\"" . $metadata->scroll . "\" height=\"" . $metadata->height . "\" url=\"" . $metadata->url . "\" />";
    file_put_contents('Samples/' . $Sample . '/' . $metadata->userId . '/trace.xml', $txt . PHP_EOL, FILE_APPEND | LOCK_EX);
    $handle = fopen('Samples/' . $Sample . '/' . $metadata->userId . '/lastTime.txt', "w");

    $json_array = array("type" => $metadata->type, "image" => $data->imageName, "time" => $metadata->time, "Class" => $data->Class, "Id" => $data->Id, "MouseClass" => $data->mouseClass, "MouseId" => $data->mouseId, "X" => $data->X, "Y" => $data->Y, "keys" => $data->Typed, "scroll" => $metadata->scroll, "height" => $metadata->height, "url" => $metadata->url);
    $json_xmr = json_encode($json_array);
    $json_xmr = "," . $json_xmr;
    file_put_contents('Samples/' . $Sample . '/' . $metadata->userId . '/trace.json', $json_xmr . PHP_EOL, FILE_APPEND | LOCK_EX);

    $content = fwrite($handle, $metadata->time);
    fclose($handle);

    echo "received ";
}
