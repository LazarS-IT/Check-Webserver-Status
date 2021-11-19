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

ini_set('sendmail_from', 'alerts@gowpcare.com');
ini_set('SMTP', '136.243.170.184');
ini_set('smtp_port', '465');

ini_set('allow_url_fopen', true); //enable fopen

$fileName = "domains_for_defaced.txt";
$fileNameEmails = "emails_for_defaced.txt";
$domains = file($fileName, FILE_IGNORE_NEW_LINES);
$emails = file($fileNameEmails, FILE_IGNORE_NEW_LINES);

function getDomainName($domains, $i)
{
    $plainName = explode("//", $domains[$i]);
    return $plainName[1];
}

function createFolder($folderName, $dir)
{
    if (file_exists($dir . "/" . $folderName)) {
        // do nothing because folder already exists
    } else {
        shell_exec("mkdir $folderName"); // if it doesn't exist create directory with the domain name
    }
}

function checkIfDefaced($domains, int $i, $emails)
{
    $dir = dirname(__FILE__);
    $folderName = getDomainName($domains, $i);
    createFolder($folderName, $dir);
    if (date('w') === "7") {
        shell_exec("cd $folderName && wget -O original-index.html $domains[$i]");
        $original = filesize("$dir/$folderName/original-index.html");
        // Note : add If that will ask if there is compare file if there isn't then create it.
        if(file_exists("$dir/$folderName/index-compare.html"))
        {
            // if it exists do nothing
        }
        else 
        {
            shell_exec("cd $folderName && wget -O index-compare.html $domains[$i]");
            $compare = filesize("$dir/$folderName/index-compare.html");
        }
    } else {
        shell_exec("cd $folderName && wget -O index-compare.html $domains[$i]");
        $original = filesize("$dir/$folderName/original-index.html");
        $compare = filesize("$dir/$folderName/index-compare.html");
    }

    //compare original index.html with the current index.html and check if there has been changes
    if ($original === $compare) {
        //don't do anything if filesize didn't change
    } else {
    // echo $emails[$i] . " " . $i . " this is domain name :: ".getDomainName($domains, $i);
       SendMail($emails[$i], getDomainName($domains, $i));  // if index.html has been changed then shoot up email to the client stating that something
        // has been changed
    }
}

function SendMail($email, $domain)
{
    $to = $email;
    $subject = "Website Alert";
    $message = "Your site ($domain) has been defaced, please contact your support.";
    $headers = 'From: alerts@gowpcare.com' . "\r\n" .
        'Reply-To: alerts@gowpcare.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    $wrapMessage = wordwrap($message, 70, "\n", true);
    return mail($to, $subject, $wrapMessage, $headers);
}

$i = 0;
foreach ($domains as $domain) // just go through domains and check them
{
    checkIfDefaced($domains, $i, $emails);
    $i++;
}
