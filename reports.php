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
        <li><a href="?report=all">All Device Activity</a></li>
      </ul>
  </div>
  <div class="report-quick-facts">
    <h3>Quick Facts</h3>
    <ul>
      <li>Total Devices: <?php echo $device->getAll()->rowCount(); ?></li>

      <?php
      $statuses = $status->getAll();
      foreach ($statuses as $status) {
        print_r($status);
        $devices = $device->getAllByStatus($status['id']);
        echo "<li>" . htmlspecialchars($status['name']) . ": " . $devices->rowCount() . "</li>";
      }
      ?>
    </ul>
</div>



<?php

switch ($report_type) {
  case 'all':
    include_once 'reports/all.php';
    break;

  default:
    break;
}

include_once 'views/footer.php';
