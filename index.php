<?php

// This file is specific to developers who are just starting to work with the SDK.

ini_set('display_errors', 1);

require 'vendor/autoload.php';

use ZapMeSdk\Base as ZapMeSdk;

$base = (new ZapMeSdk())
    ->withApi('YOUR_API_HERE')
    ->withSecret('YOUR_SECRET_KEY_HERE');

$result = $base->sendMessage('PHONE', 'MESSAGE');

dump($result);
