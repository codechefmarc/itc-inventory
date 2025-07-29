<?php

/**
 * @file
 * Edit device form.
 */

session_start();

//phpcs:disable DrupalPractice

require_once 'config.php';
include_once 'views/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if (empty($_GET['q'])) {
    $messages->addError("Please provide an SRJC tag or serial number.");
    header('Location: /search.php');
    exit;

  }

  $device_info = $device->getByEither($_GET['q'] ?? 0);
  print_r($device_info);

}
// $activity_info = $deviceActivity->getById($_GET['id'] ?? 0);
// $saved_status_id = $activity_info['status_id'] ?? NULL;

// if (!$activity_info) {
//   $messages->addError("Activity not found.");
//   header('Location: /');
//   exit;
// }

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {

//   if (empty($_GET['id'])) {
//     $messages->addError("Activity not found.");
//     header('Location: /');
//     exit;
//   }
//   $deviceActivity->id = $_GET['id'];
//   $deviceActivity->notes = sanitize_input($_POST['notes']);
//   $deviceActivity->status_id = sanitize_input($_POST['status_id']);
//   $deviceActivity->update();
//   $messages->addSuccess("Activity updated successfully.");
//   header('Location: /');
//   exit;
// }

?>
<h2>Search</h2>

<form method="POST" action="" class="search-form">

<h3>Find Device</h3>
  <div class="form-row">
      <div class="form-group">
        <label for="track_or_serial">SRJC tag or serial number</label>
        <input type="text" id="track_or_serial" name="q" autofocus="autofocus">
      </div>
    </div>
    <button type="submit" class="button">Search</button>
</form>

<?php include_once 'views/footer.php';
