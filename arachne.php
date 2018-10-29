<?php
include_once 'vendor/autoload.php';
include_once 'DOM_parser.php';

use thiagoalessio\TesseractOCR\TesseractOCR;

// CAPTCHA BREAKER VERSION

$time_start = microtime(true);


$url = "http://challenge01.root-me.org/programmation/ch8/";

if (!$fp = fopen($url, "r")) {
    return false;
}

$content = "";

while (!feof($fp)) {
    $content .= fgets($fp, 1024);
}

preg_match('/src=\"data:image\/([a-zA-Z]*);base64,([^\"]*)\"/', $content, $matches);


$ifp = fopen('picture.png', 'wb');

fwrite($ifp, base64_decode($matches[2]));

fclose($ifp);

$resultOCR = '';


$image = new Imagick('picture.png');
$image->modulateImage(90, 0, 100);

// removing black dots in the image
$target = 'rgb(0,0,0)';
$fill = 'white';
$fuzz = 0.05 * $image->getQuantumRange()['quantumRangeLong'];
$image->opaquePaintImage($target, $fill, $fuzz, false, Imagick::CHANNEL_DEFAULT);

$image->writeImage('picture.png');

$str = (new TesseractOCR('picture.png'))
    ->whitelist(range(0, 9), range('A', 'Z'), range('a', 'z'))
    ->run();

$resultOCR = str_replace(' ', '', $str);

var_dump($resultOCR);

$data = array('cametu' => $resultOCR);

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
    )
);
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
//if ($result === FALSE)
//    var_dump("error");

preg_match('/<p>(.*)<\/p>/', $result, $match);
var_dump($match[1]);

$time_end = microtime(true);

$execution_time = ($time_end - $time_start);

echo 'Total Execution Time: ' . $execution_time . ' seconds';
