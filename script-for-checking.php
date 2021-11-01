<?php

// set email server parameters

use Consolidation\AnnotatedCommand\Cache\NullCache;

ini_set('sendmail_from', 'support@gowpcare.com');
ini_set('SMTP', '127.0.0.1');
ini_set('smtp_port', '25');

ini_set('allow_url_fopen', true); //enable fopen

$fileName = "emails-and-domains.txt";
$webservers = file($fileName, FILE_IGNORE_NEW_LINES); // convert file to array of domains


function sendEmail($subject, string $message, $webservers)
{
    $i = 0;
    $j = 0;
    $limit = sizeof($webservers);
    while ($limit < 3) {
        $wrapMessage = wordwrap($message, 70, "\n", true); // can't support more than 70 chars
        $to = explode(" ", $webservers[$i]);
        $headers = 'From: support@gowpcare.com' . "\r\n" .
            'Reply-To: support@gowpcare.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        return mail($to[$j], $subject, $wrapMessage, $headers); //send email
        $i++;
        $j += 2;
    }
}

