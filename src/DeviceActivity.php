<?php

/**
 * Class for updating device activity in the database.
 */
class DeviceActivity {
  private $conn;
  private $table_name = "device_entries";

  public $id;
  public $device_id;
  public $status_id;
  public $date_added;
  public $notes;

  public function __construct($db) {
    $this->conn = $db;
  }

  /**
   * Create a new device activity entry in the database.
   */
  public function create() {
    $query = "INSERT INTO " . $this->table_name . "
              (device_id, status_id, date_added, notes)
              VALUES (:device_id, :status_id, :date_added, :notes)";

    $stmt = $this->conn->prepare($query);

    $this->date_added = date('Y-m-d H:i:s');

    // Bind parameters.
    $stmt->bindParam(':device_id', $this->device_id);
    $stmt->bindParam(':status_id', $this->status_id);
    $stmt->bindParam(':date_added', $this->date_added);
    $stmt->bindParam(':notes', $this->notes);

    if ($stmt->execute()) {
      $this->id = $this->conn->lastInsertId();
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get all device activity entries with associated devices and statuses.
   */
  public function getAllWithDevicesAndStatus() {
    $query = "SELECT
                de.id as entry_id,
                de.date_added,
                de.notes,
                d.id as device_id,
                d.serial_number,
                d.tracking_number,
                d.model_number,
                s.id as status_id,
                s.status_name,
                s.description as status_description
              FROM " . $this->table_name . " de
              JOIN devices d ON de.device_id = d.id
              JOIN statuses s ON de.status_id = s.id
              ORDER BY de.date_added DESC";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  /**
   * Get all device activity entries with associated devices and statuses.
   */
  public function getTodayDeviceActivity() {
    $query = "SELECT
                de.id as entry_id,
                de.date_added,
                de.notes,
                d.id as device_id,
                d.serial_number,
                d.tracking_number,
                d.model_number,
                s.id as status_id,
                s.status_name,
                s.description as status_description
              FROM " . $this->table_name . " de
              JOIN devices d ON de.device_id = d.id
              JOIN statuses s ON de.status_id = s.id
              WHERE DATE(de.date_added) = CURDATE()
              ORDER BY de.date_added DESC";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  /**
   * Get a device activity entry by its ID with associated device and status.
   */
  public function getById($id) {
    $query = "SELECT
                de.*,
                d.serial_number,
                d.tracking_number,
                d.model_number,
                s.status_name,
                s.description as status_description
              FROM " . $this->table_name . " de
              JOIN devices d ON de.device_id = d.id
              JOIN statuses s ON de.status_id = s.id
              WHERE de.id = :id
              LIMIT 1";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Get all device activity entries for a specific device.
   */
  public function getByDeviceId($device_id) {
    $query = "SELECT
                de.id as entry_id,
                de.date_added,
                de.notes,
                d.id as device_id,
                d.serial_number,
                d.tracking_number,
                d.model_number,
                s.id as status_id,
                s.status_name,
                s.description as status_description
              FROM " . $this->table_name . " de
              JOIN devices d ON de.device_id = d.id
              JOIN statuses s ON de.status_id = s.id
              WHERE de.device_id = :device_id
              ORDER BY de.date_added DESC";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':device_id', $device_id);
    $stmt->execute();
    return $stmt;
  }

  /**
   * Get all device activity entries for a specific status.
   */
  public function getByStatusId($status_id) {
    $query = "SELECT
                de.*,
                d.serial_number,
                d.tracking_number,
                d.model_number
              FROM " . $this->table_name . " de
              JOIN devices d ON de.device_id = d.id
              WHERE de.status_id = :status_id
              ORDER BY de.date_added DESC";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':status_id', $status_id);
    $stmt->execute();
    return $stmt;
  }

  /**
   * Update device information.
   */
  public function update() {
    $query = "UPDATE " . $this->table_name . "
              SET status_id = :status_id,
                  notes = :notes
              WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    // Sanitize inputs.
    $this->status_id = htmlspecialchars(strip_tags($this->status_id));
    $this->notes = htmlspecialchars(strip_tags($this->notes));

    // Bind parameters.
    $stmt->bindParam(':status_id', $this->status_id);
    $stmt->bindParam(':notes', $this->notes);
    $stmt->bindParam(':id', $this->id);

    return $stmt->execute();
  }

  /**
   * Delete a device activity entry by its ID.
   */
  public function delete() {
    $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $this->id);
    return $stmt->execute();
  }

}
