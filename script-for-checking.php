<?php

// set email server parameters

use Consolidation\AnnotatedCommand\Cache\NullCache;

ini_set('sendmail_from', 'support@gowpcare.com');
ini_set('SMTP', '127.0.0.1');
ini_set('smtp_port', '25');

ini_set('allow_url_fopen', true); //enable fopen

