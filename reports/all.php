<?php
$device_activity = $deviceActivity->getAllWithDevicesAndStatus();
$device_activity_title = "All Device Activity";

include_once 'views/activity_list.php';
