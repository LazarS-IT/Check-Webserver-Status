<?php

/**
 * kada se klikne submit dugme cuvaj
 * email i domen u fajl
 */
if(isset($_POST['submit']))
{
    $emails = $_POST['email'];
    $domains = $_POST['domain'];
    $fileWithDomens = fopen("emails-and-domens.txt","w") or die("unable to open file.");
    $emailsPlusDomains = $emails.' '.$domains;
    fwrite($fileWithDomens, $emails);
    
}

echo "helo";
//echo $domains;

?>