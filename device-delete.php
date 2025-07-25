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

if (!$device_info) {
  $messages->addError("Device not found.");
  header('Location: /');
  exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (empty($_GET['id'])) {
    $messages->addError("Device not found.");
    header('Location: /');
    exit;
  }
  $device->id = $_GET['id'];
  $device_activity = $deviceActivity->getByDeviceId($device->id);
  // Delete device activity logs.
  if ($device_activity->rowCount() > 0) {
    $row = $device_activity->fetch(PDO::FETCH_ASSOC);
    while ($row = $device_activity->fetch(PDO::FETCH_ASSOC)) {
      $deviceActivity->id = $row['id'];
      $deviceActivity->delete();
    }
  }
  // Delete the device.
  $device->delete();
  $messages->addSuccess("Device and associated activity logs deleted.");
  header('Location: /');
  exit;
}

?>
<h2>Delete Device</h2>

<p>Note, this will delete the device and all associated activity logs.</p>

<h3>Device info</h3>
<ul>
  <li>SRJC Tag: <?php echo htmlspecialchars($device_info['tracking_number'] ?? ''); ?></li>
  <li>Serial Number: <?php echo htmlspecialchars($device_info['serial_number'] ?? ''); ?></li>
  <li>Model Number: <?php echo htmlspecialchars($device_info['model_number'] ?? ''); ?></li>
</ul>

<form method="POST" action="" class="device-delete-form">
  <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">
  <button type="submit" class="button severe-action">Delete Device</button>
  <a href="/" class="button">Cancel</a>
</form>

<?php include_once 'views/footer.php';
