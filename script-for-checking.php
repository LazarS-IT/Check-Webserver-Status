<?php

// set email server parameters
ini_set('sendmail_from', 'support@gowpcare.com' );
ini_set('SMTP', '127.0.0.1' );
ini_set('smtp_port', '25' );

ini_set('allow_url_fopen', true); //enable fopen

// define list of webservers to check
$fileName = "domains.txt";
$webservers = file($fileName, FILE_IGNORE_NEW_LINES);// array('www.example.com', 'www.example2.com');

?>