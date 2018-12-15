<?php

$mobile = $_GET["phone"];
$name = $_GET["first_name"];

// $tmp = array();
$msg = $mobile."\n".$name."\nTime: ".$_GET["hr"].":".$_GET["min"].":".$_GET["sec"]."\n-------------\n";

for ($i=0; $i <20 ; $i++) { 
    # code...
    // $tmp.array_push($_GET[$i]);
    $msg .= $i."->".$_GET[$i]."\n";
}

$myfile = fopen("testfile.txt", "a");

fwrite($myfile, $msg."\n-----------------\n");
fclose($myfile);

?>