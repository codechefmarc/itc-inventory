<?php

/**
 * @file
 * Acts as the main entry point for the application.
 */

//phpcs:disable DrupalPractice

require_once 'config.php';

$message = '';
$message_type = '';

// Handle form submission.
if ($_POST) {
  if (
    (!empty($_POST['serial_number'])
    || !empty($_POST['tracking_number']))
    // && !empty($_POST['model_number'])
    && !empty($_POST['status_id'])) {

    // if ($device->trackingNumberExists($_POST['tracking_number'])) {
    //   $enteredDevice = $device->getByTrackingNumber(sanitize_input($_POST['tracking_number']));
    //   $device->id = $enteredDevice['id'];

    // }

    // print_r($device->getByTrackingNumber(sanitize_input($_POST['tracking_number'])));
    // die;


    // Check if tracking number already exists.
    // if ($device->trackingNumberExists($_POST['tracking_number'])) {
    //   $message = "Error: Tracking number already exists!";
    //   $message_type = "error";
    // }
    // // Check if serial number already exists.
    // elseif ($device->serialNumberExists($_POST['serial_number'])) {
    //   $message = "Error: Serial number already exists!";
    //   $message_type = "error";
    // }
    // else {
      // Create device first.
    $status->saved_status_id = $_POST['status_id'];
    $device->serial_number = sanitize_input($_POST['serial_number']);
    $device->tracking_number = sanitize_input($_POST['tracking_number']);
    //$device->model_number = sanitize_input($_POST['model_number']);

    if ($device->create()) {
      // Create device entry.
      $deviceEntry->device_id = $device->id;
      $deviceEntry->status_id = (int) $_POST['status_id'];

      if ($deviceEntry->create()) {
        $message = "Device added to inventory successfully!";
        $message_type = "success";
      }
      else {
        $message = "Error: Device created but failed to add to inventory.";
        $message_type = "error";
      }
    }
    else {
      $message = "Error: Unable to create device record.";
      $message_type = "error";
    }
    //}
  }
  else {
    $message = "Please fill in all fields.";
    $message_type = "error";
  }
}

// Get all statuses for dropdown.
$statuses_result = $status->getAll();
$statuses = [];
while ($row = $statuses_result->fetch(PDO::FETCH_ASSOC)) {
  $statuses[] = $row;
}

// Get all device entries for display.
$entries_result = $deviceEntry->getAllWithDevicesAndStatus();

// Include the view.
include 'views/inventory_form.php';
