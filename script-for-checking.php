<?php

// set email server parameters

use Consolidation\AnnotatedCommand\Cache\NullCache;

ini_set('sendmail_from', 'support@gowpcare.com');
ini_set('SMTP', '127.0.0.1');
ini_set('smtp_port', '465');

ini_set('allow_url_fopen', true); //enable fopen

$fileName = "emails-and-domains.txt";
$webservers = file($fileName, FILE_IGNORE_NEW_LINES); // convert file to array of domains


function sendEmail($subject, string $message, $webservers)
{
    $i = 0;
    $j = 0;
    $limit = sizeof($webservers);
    while ($i < $limit) {
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

function getResponse($url, $webservers)
{
    $ch = curl_init(); // create cURL handle (ch)
    if (!$ch) // sends an email if curl can't initialise
    {
        $subject = "Web Server Checking Script";
        $message = "The server checking script issued and error when it tried to process" . $url .
            ". Curl did not initialise correctly and issued and error - " . curl_error(curl_init()) .
            "The script has died and not completed any more tasks.";
        sendEmail($subject, $message, $webservers);
        die();
    }

    //setting cURL options
    $ret = curl_setopt($ch, CURLOPT_URL, $url . "/");
    $ret = curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
    $ret = curl_setopt($ch, CURLOPT_HEADER, true);
    $ret = curl_setopt($ch, CURLOPT_NOBODY, true);
    $ret = curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    $ret = curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    //execute
    $ret = curl_exec($ch);

    if (empty($ret)) {
        // some kind of error happened
        $subject = "Web Server Checking Script";
        $message = "The server checking script issued an error when it tried to process" . $url .
            ". Curl was trying to execute and issued the error - " . curl_error($ch) .
            "Further URLs will be tried.";
        sendEmail($subject, $message, $webservers);
        curl_close($ch); //close cURL handler
    } else {
        $info = curl_getinfo($ch); //get header info - output is array
        curl_close($ch);

        if (empty($info['http_code'])) {
            $subject = "Web Server Checking Script Error";
            $message = "The server checking script issued an error when it tried to process " . $url . "\r\nNo HTTP code was returned";
            sendEmail($subject, $message, $webservers);
        } else {
            //load the HTTP code descriptions
            $dir = dirname(__FILE__);
            $http_codes = parse_ini_file("$dir/http-response-code.ini"); //Change the path

            // result - code number and description
            $result = $info['http_code'] . " " . $http_codes[$info['http_code']];
            return $result; // result contained a code, so return it
        }
        return Null; //info was empty so return nothing
    }
    return Null; // ret was empty so return nothing
}

// main script init
$domainFile = "domains.txt";
$domainsOnly = file($domainFile, FILE_IGNORE_NEW_LINES);
foreach ($domainsOnly as $domain) {
    $status = getResponse($domain, $webservers); // get the status of domain
    if (empty($status)) {
        // nothing happens here beacuse function returned nothing
    } else {
        if (strstr($status, "200")) // search for the error code that means everything is ok
        {
            //if found dont do anything, go to the next one
        } else {
            $timeStamp = date("m/d/Y H:i:s a", time()); // get the time error occured
            $error = $domain . " - " . $status . " status error detected"; // set error msg with domain and resposne code
            $message = "At - " . $timeStamp . " - a http response error was detected on" . $domain
                . "\r\nInstaed of a 200 OK response, the server returned " . $status;
            sendEmail($error, $message, $webservers);
        }
    }
}
