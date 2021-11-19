<?php

/**
 * creating function that will write email and domain
 * of the user which will be later used to monitor website
 * and send notification to the user's email
 */
function isItHttps($domains)
{
    $j = 0;
    $httpsString = "https://";
    if ($domains)
        for ($i = 0; $i < 8; $i++)
            if ($domains[$i] == $httpsString[$i])
                $j++;
            else break;
    if ($j == 8)
            return $domains;
    else
    {
        $httpsString .= $domains;
        return $httpsString;
    }
}

function writeToFile(string $fileName, string $emails)
{
    if (!file_exists($fileName))
        fopen($fileName, "w");
    $file = fopen($fileName, "a");
    fwrite($file, $emails . "\r\n");
}
function writeToFileOnlyDomains(string $fileName, string $domains)
{
    if (!file_exists($fileName))
        fopen($fileName, "w");
    $file = fopen($fileName, "a");
    $domains = isItHttps($domains);
    fwrite($file, $domains . "\r\n");
}
/**
 * when user submits info calls the writeToFile function
 * that writes email of an user and domain to txt file
 */
if (isset($_POST['submit'])) {
    $emails = $_POST['emailDefaced'];
    $domains = $_POST['domainDefaced'];
    $file1 = "domains_for_defaced.txt";
    $file2 = "emails_for_defaced.txt";
    writeToFile($file2, $emails);
    writeToFileOnlyDomains($file1,$domains);
    header("Location: https://gowpcare.com/");
    exit;
}
