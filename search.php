<?php

/**
 * @file
 * Edit device form.
 */

session_start();

//phpcs:disable DrupalPractice

require_once 'config.php';
$search_type = NULL;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  if (isset($_GET['q']) && empty($_GET['q'])) {
    $messages->addError("Please provide an SRJC tag or serial number.");
  }

  if (isset($_GET['date']) && empty($_GET['date'])) {
    $messages->addError("Please provide a valid date.");
  }

  if (isset($_GET['q'])) {
    $device_info = $device->getByEither($_GET['q'] ?? 0);
    if (!$device_info) {
      $messages->addError("Device not found for " . htmlspecialchars($_GET['q']));
    }
    if ($device_info) {
      $search_type = 'device';
      $device_activity = !empty($device_info) ? $deviceActivity->getByDeviceId($device_info['id']) : [];
    }
  }

  if (isset($_GET['date'])) {
    $search_type = 'date';
    $device_activity = $deviceActivity->getByDate($_GET['date'] ?? 0);
  }

}

include_once 'partials/header.php';

?>
<h2>Search</h2>
<div class="search-container">

  <form method="GET" action="" class="search-form">

    <div class="form-row">
        <div class="form-group">
          <label for="track_or_serial">SRJC tag or serial number:</label>
          <input type="text" id="track_or_serial" name="q" autofocus="autofocus" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
        </div>
      </div>
      <button type="submit" class="button">Search</button>
      <a href="/search.php" class="button">Clear</a>
  </form>

  <form method="GET" action="" class="search-date-form">

    <div class="form-row">
        <div class="form-group">
          <label for="date">Date:</label>
          <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($_GET['date'] ?? ''); ?>">
        </div>
      </div>
      <button type="submit" class="button">Search</button>
      <a href="/search.php" class="button">Clear</a>
  </form>
</div>
<?php

if (!empty($device_info) && $search_type == 'device') {
  $device_activity_title = "All device activity for " . $device->jcOrSerial($device_info['tracking_number'], $device_info['serial_number']);
  include_once 'partials/activity_log.php';
}

if ($search_type == 'date') {
  $device_activity_title = "Device activity for " . format_date_only($_GET['date']);
  include_once 'partials/activity_log.php';
}


include_once 'partials/footer.php';
