<?php

/**
 * @file
 * Edit activity form.
 */

session_start();

//phpcs:disable DrupalPractice

require_once 'config.php';
include_once 'views/header.php';

$activity_info = $deviceActivity->getById($_GET['id'] ?? 0);
$saved_status_id = $activity_info['status_id'] ?? NULL;

if (!$activity_info) {
  $messages->addError("Activity not found.");
  header('Location: /');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if (empty($_GET['id'])) {
    $messages->addError("Activity not found.");
    header('Location: /');
    exit;
  }
  $deviceActivity->id = $_GET['id'];
  $deviceActivity->notes = sanitize_input($_POST['notes']);
  $deviceActivity->status_id = sanitize_input($_POST['status_id']);
  $deviceActivity->update();
  $messages->addSuccess("Activity updated successfully.");
  header('Location: /');
  exit;
}

?>
<h2>Edit Activity</h2>
<form method="POST" action="" class="activity-edit-form">

    <div class="form-row">
      <?php include_once 'views/status_select.php'; ?>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label for="notes">Notes:</label>
        <input type="text" id="notes" name="notes" value="<?php echo htmlspecialchars($activity_info['notes'] ?? ''); ?>">
      </div>

      <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">
    </div>
    <button type="submit" class="button">Update Activity</button>
    <a href="/" class="button">Cancel</a>
</form>

<?php include_once 'views/footer.php';
