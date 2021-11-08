<?php

$fileName = "domains.txt";
$domains = file($fileName,FILE_IGNORE_NEW_LINES);

function checkIfDefaced($domains)
{
    $ch = curl_init();
    curl_close($ch);
}