<?php

/**
 * creating function that will write email and domain
 * of the user which will be later used to monitor website
 * and send notification to the user's email
 */
function writeToFile(string $fileName, string $emails, string $domains)
{
    if (!file_exists($fileName))
        fopen($fileName, "w");
    $file = fopen($fileName, "a");
    $emailsPlusDomains = $emails . ' ' . $domains;
    fwrite($file, $emailsPlusDomains);
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
