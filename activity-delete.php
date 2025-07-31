<?php

/**
 * @file
 * Delete activity form.
 */

session_start();

//phpcs:disable DrupalPractice

require_once 'config.php';
include_once 'partials/header.php';

$activity_info = $deviceActivity->getById($_GET['id'] ?? 0);
$device_info = $device->getById($activity_info['device_id'] ?? 0);

if (!$activity_info) {
  $messages->addError("Activity ID not found.");
  header('Location: /');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (empty($_GET['id'])) {
    $messages->addError("Device not found.");
    header('Location: /');
    exit;
  }
  $deviceActivity->id = $_GET['id'];

  $deviceActivity->delete();
  $messages->addSuccess("Activity deleted.");
  header('Location: /');
  exit;
}

?>
<h2>Delete Activity</h2>

<h3>Activity info</h3>
<ul>
  <li>SRJC Tag: <?php echo htmlspecialchars($device_info['tracking_number'] ?? ''); ?></li>
  <li>Serial Number: <?php echo htmlspecialchars($device_info['serial_number'] ?? ''); ?></li>
  <li>Model Number: <?php echo htmlspecialchars($device_info['model_number'] ?? ''); ?></li>
  <li>Status: <?php echo htmlspecialchars($activity_info['status_name'] ?? ''); ?></li>
  <li>Date Added: <?php echo format_date($activity_info['date_added'] ?? ''); ?></li>
  <li>Notes: <?php echo htmlspecialchars($activity_info['notes'] ?? ''); ?></li>
</ul>

<form method="POST" action="" class="activity-delete-form">
  <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">
  <button type="submit" class="button severe-action">Delete Activity</button>
  <a href="/" class="button">Cancel</a>
</form>

<?php include_once 'partials/footer.php';
