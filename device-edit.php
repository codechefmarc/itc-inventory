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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (empty($_GET['id'])) {
    $messages->addError("Device not found.");
    header('Location: /');
    exit;
  }
  $device->id = $_GET['id'];
  $device->tracking_number = sanitize_input($_POST['tracking_number']);
  $device->serial_number = sanitize_input($_POST['serial_number']);
  $device->model_number = sanitize_input($_POST['model_number']);
  $device->update();
  $messages->addSuccess("Device updated successfully.");
  header('Location: /');
  exit;
}

?>
<h2>Edit Device</h2>
<form method="POST" action="" class="device-edit-form">

    <div class="form-row">
      <div class="form-group">
        <label for="tracking_number">SRJC Tag:</label>
        <input type="text" id="tracking_number" name="tracking_number" value="<?php echo htmlspecialchars($device_info['tracking_number'] ?? ''); ?>" autofocus="autofocus">
      </div>
      <div class="form-group">
        <label for="serial_number">Serial Number:</label>
        <input type="text" id="serial_number" name="serial_number" value="<?php echo htmlspecialchars($device_info['serial_number'] ?? ''); ?>">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label for="model_number">Model Number:</label>
        <input type="text" id="model_number" name="model_number" value="<?php echo htmlspecialchars($device_info['model_number'] ?? ''); ?>">
      </div>
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">
    <button type="submit">Update Device</button>
</form>
