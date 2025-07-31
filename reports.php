<?php

/**
 * @file
 * Edit device form.
 */

session_start();

//phpcs:disable DrupalPractice

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  if (isset($_GET['report']) && empty($_GET['report'])) {
    $messages->addError("Please select a report type.");
  }

  $report_type = $_GET['report'] ?? NULL;

}

include_once 'views/header.php';

?>
<h2>Reports</h2>

<div class="report-container">
  <div class="report-selection">
    <h3>Select a report</h3>
      <ul>
        <li><a href="?report=all_activity">All Device Activity</a></li>
        <li><a href="?report=all_devices">All Devices</a></li>
        <li><a href="?report=inactive">Inactive Devices</a></li>
      </ul>
  </div>

  <?php
  $status_counts = $deviceActivity->countDevicesByLatestStatus();
  ?>

  <div class="report-quick-facts">
    <h3>Quick Facts</h3>
    <ul>
      <li>Total Devices: <?php echo $device->getAll()->rowCount(); ?></li>
    </ul>
    <h3>Devices by Status</h3>
    <small>Click on a status to view current devices.</small>
    <ul>
    <?php foreach ($status_counts as $row) : ?>
        <li>

      <a class="status-badge <?php echo get_status_badge_class($row['status_name']); ?>" href="?report=current_by_status&status_id=<?php echo htmlspecialchars($row['status_id']); ?>">
      <?php echo htmlspecialchars($row['status_name']) . " (" . $row['device_count'] . ")"; ?>
</a>
      </li>
    <?php endforeach; ?>

    </ul>
  </div>
</div>



<?php

switch ($report_type) {
  case 'all_activity':
    $device_activity = $deviceActivity->getAllWithDevicesAndStatus();
    $device_activity_title = "All Device Activity";
    include_once 'views/activity_list.php';
    break;

  case 'all_devices':
    $devices = $device->getAll();
    $devices_title = "All Devices";
    include_once 'views/device_list.php';
    break;

  case 'inactive':
    $devices = $device->findInactiveDevices();
    $devices_title = "Inactive Devices";
    include_once 'views/device_list.php';
    break;

  case 'current_by_status':
    if (isset($_GET['status_id']) && is_numeric($_GET['status_id'])) {
      $status_id = (int) $_GET['status_id'];
      $device_activity = $deviceActivity->getLatestByStatusId($status_id);
      $device_activity_title = "Devices with current status: " . htmlspecialchars($status->getById($status_id)['status_name']);
      include_once 'views/activity_list.php';
    }
  default:
    break;
}

include_once 'views/footer.php';
