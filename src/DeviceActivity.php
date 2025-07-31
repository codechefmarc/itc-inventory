<?php

/**
 * Class for updating device activity in the database.
 */
class DeviceActivity {
  private $conn;
  private $table_name = "device_activity";

  public $id;
  public $device_id;
  public $status_id;
  public $date_added;
  public $notes;
  public $user;

  public function __construct($db) {
    $this->conn = $db;
  }

  /**
   * Create a new device activity entry in the database.
   */
  public function create() {
    $query = "INSERT INTO " . $this->table_name . "
              (device_id, status_id, date_added, notes, user)
              VALUES (:device_id, :status_id, :date_added, :notes, :user)";

    $stmt = $this->conn->prepare($query);

    $this->date_added = date('Y-m-d H:i:s');

    // Bind parameters.
    $stmt->bindParam(':device_id', $this->device_id);
    $stmt->bindParam(':status_id', $this->status_id);
    $stmt->bindParam(':date_added', $this->date_added);
    $stmt->bindParam(':notes', $this->notes);
    $stmt->bindParam(':user', $this->user);

    if ($stmt->execute()) {
      $this->id = $this->conn->lastInsertId();
      return TRUE;
    }
    return FALSE;
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

  /**
   * Get all device activity entries with associated devices and statuses.
   */
  public function getAllWithDevicesAndStatus() {
    $query = "SELECT
                de.id as entry_id,
                de.date_added,
                de.notes,
                de.user,
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
   * Get device activity for today.
   */
  public function getTodayDeviceActivity() {
    $query = "SELECT
                de.id as entry_id,
                de.date_added,
                de.notes,
                de.user,
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
   * Get a device activity entry by its ID.
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
                de.user,
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
   * Get count of devices by their latest status.
   */
  public function countDevicesByLatestStatus() {
    $query = "
        SELECT
        s.id AS status_id,
        s.status_name,
            s.description,
            COUNT(*) AS device_count
        FROM device_activity de
        INNER JOIN (
            SELECT device_id, MAX(date_added) AS latest_date
            FROM device_activity
            GROUP BY device_id
        ) latest ON de.device_id = latest.device_id AND de.date_added = latest.latest_date
        INNER JOIN statuses s ON de.status_id = s.id
        GROUP BY s.status_name
        ORDER BY device_count DESC
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
   * Get the latest device activity entries for a specific status.
   */
  public function getLatestByStatusId($status_id) {
    $query = "
        SELECT
            de.id AS entry_id,
            de.device_id,
            de.date_added,
            de.notes,
            de.user,
            d.serial_number,
            d.tracking_number,
            d.model_number,
            s.status_name,
            s.description AS status_description
        FROM device_activity de
        INNER JOIN (
            SELECT device_id, MAX(date_added) AS latest_date
            FROM device_activity
            GROUP BY device_id
        ) latest ON de.device_id = latest.device_id AND de.date_added = latest.latest_date
        INNER JOIN devices d ON de.device_id = d.id
        INNER JOIN statuses s ON de.status_id = s.id
        WHERE de.status_id = :status_id
        ORDER BY de.date_added DESC
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':status_id', $status_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
  }

  /**
   * Get device activity entries by date.
   */
  public function getByDate($date) {
    $query = "SELECT
                de.id as entry_id,
                de.date_added,
                de.notes,
                de.user,
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
              WHERE DATE(de.date_added) = :date
              ORDER BY de.date_added DESC";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':date', $date);
    $stmt->execute();
    return $stmt;
  }

}
