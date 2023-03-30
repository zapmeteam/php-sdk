<?php

/**
 * This file is specific to developers who are just starting to work with the SDK.
 */


ini_set('display_errors', 1);

require 'vendor/autoload.php';

use ZapMeSdk\Base as ZapMeSdk;

$base = (new ZapMeSdk())
    ->withApi(getenv('ZAPME_TEST_API'))
    ->withSecret(getenv('ZAPME_TEST_SECRET'));

/**
 * Example of sending a message...
 */

$result = $base->sendMessage('PHONE', 'MESSAGE');

dump($result);
