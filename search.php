<?php

/**
 * @file
 * Edit device form.
 */

session_start();

//phpcs:disable DrupalPractice

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  if (isset($_GET['q']) && empty($_GET['q'])) {
    $messages->addError("Please provide an SRJC tag or serial number.");
  }

  $device_info = $device->getByEither($_GET['q'] ?? 0);
  if (!$device_info && isset($_GET['q'])) {
    $messages->addError("Device not found for " . htmlspecialchars($_GET['q']));
  }

  if ($device_info) {
    $device_activity = !empty($device_info) ? $deviceActivity->getByDeviceId($device_info['id']) : [];
  }

}

include_once 'views/header.php';

?>
<h2>Search</h2>

<form method="GET" action="" class="search-form">

  <div class="form-row">
      <div class="form-group">
        <label for="track_or_serial">SRJC tag or serial number</label>
        <input type="text" id="track_or_serial" name="q" autofocus="autofocus" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
      </div>
    </div>
    <button type="submit" class="button">Search</button>
    <a href="/search.php" class="button">Clear</a>
</form>

<?php if (!empty($device_info)) {
  $device_activity_title = "All device activity for " . $device->jcOrSerial($device_info['tracking_number'], $device_info['serial_number']);
  include_once 'views/activity_list.php';
}
include_once 'views/footer.php';
