<?php

/**
 * @file
 * Edit device form.
 */

session_start();

//phpcs:disable DrupalPractice

require_once 'config.php';

include_once 'views/header.php';

$device_info = $device->getById($_GET['id'] ?? 0);
print_r($device_info);
