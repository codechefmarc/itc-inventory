<?php

/**
 * @file
 * Status selection view for device activity logging.
 */
?>

<p class="status-label">Status:</p>
<div class="form-group status-group">

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
