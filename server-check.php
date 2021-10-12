<?php

use JetBrains\PhpStorm\Internal\ReturnTypeContract;

/**
 * creating function that will write email and domain
 * of the user which will be later used to monitor website
 * and send notification to the user's email
 */
function isItHttps($domains)
{
    $httpsString = "https://";
    if(strlen($domains) > 8)
    {
        for($i = 0; $i <8; $i++)
        {
            $domains[$i] == $httpsString[$i];
        }
    }
    if($i == 8)
        return $domains;
    else return $httpsString.=$domains;
}

function writeToFile(string $fileName, string $emails, string $domains)
{
    if (!file_exists($fileName))
        fopen($fileName, "w");
    $file = fopen($fileName, "a");
    isItHttps($domains);
    $emailsPlusDomains = $emails . ' ' . $domains;
    fwrite($file, $emailsPlusDomains . '\n');
}
/**
 * when user submits info calls the writeToFile function
 * that writes email of an user and domain to txt file
 */
if (isset($_POST['sbt'])) {
    $emails = $_POST['email'];
    $domains = $_POST['domain'];
    $file = "emails-and-domains.txt";
    writeToFile($file, $emails, $domains);
}
