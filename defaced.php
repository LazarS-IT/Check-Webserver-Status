<?php

/**
 * script for checking if website was defaced (if index.php file was changed in some way)
 * 
 * Need to figure out how to get the size of index.php from original site weekly and then compare it
 * to the current index.php file of the website on weekdays
 * 
 * So I need:
 * 1. get original index.php (probably once every week on Sunday) 
 * 2. compare original index.php file with the current php file (this will be done throughout the week)
 * 3. if the size of index.php file was changed send email to the website admin
 * 
 * might not work with just checking the size of index.php, will have to take a look at that
 */

$fileName = "domains.txt";
$domains = file($fileName, FILE_IGNORE_NEW_LINES);

function getDomainName($domains, $i)
{
    $plainName = explode("//", $domains[$i]);
    return $plainName[1];
}

function checkIfDefaced($domains, int $i)
{
    $dir = dirname(__FILE__);
    $folderName = getDomainName($domains, $i);
    if (date("D") === "Sun") {
        if (!$dir . "/" . $folderName) {
            shell_exec("mkdir $folderName && cd $folderName");
            shell_exec("wget -O original-index.html $domains[$i]");
            shell_exec("cd ..");
        }
    } else {
        if (!$dir . "/" . $folderName)
            shell_exec("cd $folderName && wget -O infex-compare.html");
    }
}

$i = 0;
getDomainName($domains, 0);