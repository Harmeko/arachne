<?php

include_once 'DOM_parser.php';

$urlList = ["http://www.coca-colacompany.com/contact-us/index/"];

foreach ($urlList as $url) {

    if(!$fp = fopen($url ,"r" )) { return false; }

    $content = "";

    while(!feof($fp)) {
        $content .= fgets($fp, 1024);
    }

    fclose($fp);

    preg_match_all("/(([0-9]{2}.?){4}[0-9]{2})/", $content, $numbers, PREG_SET_ORDER); // only FR numbers like 01xxxxxxxx
    // (([0-9]{2,}.?)*) if international, but as it is gets any set of numbers

    foreach ($numbers as $number) {
        echo $url . " : " . $number[0] . "<br/>";
    }
}

?>