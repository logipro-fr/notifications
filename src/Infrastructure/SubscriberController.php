<?php

$input = file_get_contents('php://input');
if ($input === false) {
    echo 'Error: could not read input';
    return;
}

$subscription = json_decode($input, true);
if (json_last_error() !== JSON_ERROR_NONE || !is_array($subscription)) {
    echo 'Error: invalid JSON';
    return;
}

if (!isset($subscription['endpoint'])) {
    echo 'Error: not a subscription';
    return;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        // create a new subscription entry in your database (endpoint is unique)
        break;
    default:
        echo "Error: method not handled";
        return;
}
