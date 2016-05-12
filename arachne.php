<?php

$urlList = [];

foreach ($urlList as $url) {

    if(!$fp = fopen($url ,"r" )) { return false; }

    $content = "";

    while(!feof($fp)) {
        $content .= fgets($fp, 1024);
    }

    fclose($fp);

    preg_match_all("/(([0-9]{2}.?){5})/", $content, $numbers, PREG_SET_ORDER); // uniquement FR
    // (([0-9]{2,}.?)*) si international, mais capte plus que les tel

    foreach ($numbers as $number) {
        echo $url . " : " . $number;
    }
}

?>