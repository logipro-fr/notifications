<?php

use Symfony\Component\ErrorHandler\ErrorHandler;

require dirname(__DIR__) . '/vendor/autoload.php';
ErrorHandler::register(null, false);
