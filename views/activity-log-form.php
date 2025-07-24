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
      <div class="form-group status-group">
        <label>Status:</label>
        <?php foreach ($statuses as $status_option) : ?>
          <?php
          $checked = NULL;
          if ($saved_status_id == $status_option['id']) {
            $checked = 'checked';
          }
          ?>

          <div class="status-option">
            <input
            type="radio"
            id="status_id_<?php echo $status_option['id']?>"
            name="status_id"
            value="<?php echo $status_option['id']; ?>"
            required
            <?php echo $checked; ?>
            >
            <label for="status_id_<?php echo $status_option['id']?>"><?php echo htmlspecialchars($status_option['status_name']); ?></label>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="form-row">
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
  <button type="submit">Log Device Activity</button>
</form>

<?php

include_once 'views/activity_list.php';
include_once 'views/footer.php';
