<?php

/**
 * creating function that will write email and domain
 * of the user which will be later used to monitor website
 * and send notification to the user's email
 */
function writeToFile($fileName)
{
    $emails = $_POST['email'];
    $domains = $_POST['domain'];
    if(!file_exists($fileName))
        fopen($fileName, "w");
    $emailsPlusDomains = $emails.' '.$domains;
    fwrite($fileName,$emailsPlusDomains);
}
/**
 * when user submits info calls the writeToFile function
 * that writes email of an user and domain to txt file
 */
if(isset($_POST['sbt']))
{
    $emails = $_POST['email'];
    $domains = $_POST['domain'];
    $fileWithDomens = fopen("emails-and-domens.txt","w") or die("unable to open file.");
    $emailsPlusDomains = $emails.' '.$domains;
    fwrite($fileWithDomens, $emailsPlusDomains);
}

    ?>