<?php

/**
 * @file
 * Acts as the main entry point for the application.
 */

session_start();

//phpcs:disable DrupalPractice

require_once 'config.php';

// Handle form submission.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if (
      (empty($_POST['serial_number'])
      && empty($_POST['tracking_number']))
      || empty($_POST['status_id'])) {
    $messages->addError("Please fill in the status and either an SRJC tag or serial number.");
    header('Location: /');
    exit;
  }

  if ($_POST['tracking_number'] && !$device->trackingNumberExists($_POST['tracking_number'])) {
    $device->tracking_number = sanitize_input($_POST['tracking_number']);
    $device->create();
  }
  elseif ($_POST['tracking_number']) {
    $device_info = $device->getByTrackingNumber(sanitize_input($_POST['tracking_number']));
    $device->id = $device_info['id'];
  }

  if ($_POST['serial_number'] && !$device->serialNumberExists($_POST['serial_number'])) {
    $device->serial_number = sanitize_input($_POST['serial_number']);
    $device->create();
  }
  elseif ($_POST['serial_number']) {
    $device_info = $device->getBySerialNumber(sanitize_input($_POST['serial_number']));
    $device->id = $device_info['id'];
  }

  $deviceActivity->device_id = $device->id;
  $deviceActivity->status_id = (int) $_POST['status_id'];

  $deviceActivity->notes = sanitize_input($_POST['notes'] ?? '');

  $_SESSION['saved_status_id'] = $_POST['status_id'];

  if ($deviceActivity->create()) {
    $messages->addSuccess("Device activity logged.");
  }
  header('Location: /');
  exit;
}

$saved_status_id = $_SESSION['saved_status_id'] ?? NULL;
unset($_SESSION['saved_status_id']);

// Include the view.
include 'views/activity_log_form.php';
