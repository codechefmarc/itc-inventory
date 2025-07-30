<?php

/**
 * @file
 * Main inventory form.
 */

include_once 'views/header.php';

?>
<h2>Log Device Activity</h2>
<form method="POST" action="" class="activity-log-form">
  <div class="form-data-entry">
    <div class="form-row">
      <?php include_once 'views/status_select.php'; ?>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="notes">Optional Notes:</label>
        <input type="text" id="notes" name="notes">
      </div>
    </div>

    <div class="form-row tracking-number-row">
      <div class="form-group">
        <label for="tracking_number">SRJC Tag:</label>
        <input type="text" id="tracking_number" name="tracking_number" autofocus="autofocus">
      </div>
      <div class="form-group">
        <label for="serial_number">Serial Number:</label>
        <input type="text" id="serial_number" name="serial_number">
      </div>
    </div>

  </div>
  <button type="submit" class="button">Log Device Activity</button>
</form>

<?php
$device_activity = $deviceActivity->getTodayDeviceActivity();
$device_activity_title = "Today's Device Activity";
include_once 'views/activity_list.php';
include_once 'views/footer.php';
