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
    if (date('w') === "7") {
        if (!file_exists($dir . "/" . $folderName)) { // check if there already exists folder by name (example.com)
            shell_exec("mkdir $folderName && cd $folderName"); // if there isn't folder make it and cd into it
            shell_exec("wget -O original-index.html $domains[$i]");
           // $original = filesize("$folderName/original-index.html");
            shell_exec("cd ..");
        }
        else {
            echo "\r\n HELO DOS DIS WORK?" . $domains[$i] . "\r\n";
            shell_exec("cd $folderName && wget -O original-index.html $domains[$i]");
           // $original = filesize("$folderName/original-index.html");
            shell_exec("cd ..");
        }
    } else {
        if (!file_exists($dir . "/" . $folderName)) {
            shell_exec("mkdir $folderName && cd $folderName");
            shell_exec("wget -O index-compare.html $domains[$i]");
            //$compare = filesize("$folderName/index-compare.html");
            shell_exec("cd ..");
        }
        else {
            shell_exec("cd $folderName && wget -O index-compare.html $domains[$i]");
            //$compare = filesize("$folderName/index-compare.html");
            shell_exec("cd ..");
        }
    }

    $original = filesize("$folderName/original-index.html");
    $compare = filesize("$folderName/index-compare.html");
    //compare original index.html with the current index.html and check if there has been changes
    if($original === $compare)
    {
        //don't do anything if filesize didn't change
        echo "\r\nthis is same shit\r\n";
    }
    else
    {
        echo "\r\nSOME SHIT HAS BEEN CHANGED\r\n";
    }
}

$i = 0;
foreach($domains as $domain) // just go through domains and check them
{
    checkIfDefaced($domains, $i);
    $i++;
}
