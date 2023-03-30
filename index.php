<?php

/**
 * This file is specific to developers who are just starting to work with the SDK.
 */

ini_set('display_errors', 1);

require 'vendor/autoload.php';

use ZapMeSdk\Base as ZapMeSdk;

$base = (new ZapMeSdk())
    ->withApi('YOUR_API_KEY')
    ->withSecret('YOUR_SECRET_KEY');

/**
 * Example of sending a message:
 */

$result = $base->sendMessage('PHONE', 'MESSAGE');

dump($result);
