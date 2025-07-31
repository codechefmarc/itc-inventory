<?php

/**
 * @file
 * Status selection view for device activity logging.
 */

// Get all statuses for dropdown.
$statuses_result = $status->getAll();
$statuses = [];
while ($row = $statuses_result->fetch(PDO::FETCH_ASSOC)) {
  $statuses[] = $row;
}

?>

<p class="status-label">Status:</p>
<div class="form-group status-group">

<?php foreach ($statuses as $status_option) : ?>

  <?php
  $checked = NULL;
  $saved_status_id = $_SESSION['saved_status_id'] ?? '';
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
