<?php

/**
 * This file is specific to developers who are just starting to work with the SDK.
 */


ini_set('display_errors', 1);

require 'vendor/autoload.php';

use ZapMeSdk\Base;

$base = (new Base())
    ->withApi('YOUR_API_HERE')
    ->withSecret('YOUR_SECRET_KEY_HERE');

/** Examples of usage */

// Send a message.

$result = $base->sendMessage('PHONE', 'MESSAGE');

dump($result);
