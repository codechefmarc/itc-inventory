<?php

/**
 * Device class for managing devices in the database.
 */
class Device {
  private $conn;
  private $table_name = "devices";

  public $id;
  public $serial_number;
  public $tracking_number;
  public $model_number;

  public function __construct($db) {
    $this->conn = $db;
  }

  /**
   * Create a new device in the database.
   */
  public function create() {
    $query = "INSERT INTO " . $this->table_name . "
              (serial_number, tracking_number, model_number)
              VALUES (:serial_number, :tracking_number, :model_number)";

    $stmt = $this->conn->prepare($query);

    // Sanitize inputs.
    $this->serial_number = htmlspecialchars(strip_tags($this->serial_number));
    $this->tracking_number = htmlspecialchars(strip_tags($this->tracking_number));
    //$this->model_number = htmlspecialchars(strip_tags($this->model_number));

    // Bind parameters.
    $stmt->bindParam(':serial_number', $this->serial_number);
    $stmt->bindParam(':tracking_number', $this->tracking_number);
    $stmt->bindParam(':model_number', $this->model_number);

    if ($stmt->execute()) {
      $this->id = $this->conn->lastInsertId();
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get a device by its ID.
   */
  public function getById($id) {
    $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Get a device by its tracking number.
   */
  public function getByTrackingNumber($tracking_number) {
    $query = "SELECT * FROM " . $this->table_name . " WHERE tracking_number = :tracking_number LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':tracking_number', $tracking_number);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Get a device by its tracking number.
   */
  public function getBySerialNumber($serial_number) {
    $query = "SELECT * FROM " . $this->table_name . " WHERE serial_number = :serial_number LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':serial_number', $serial_number);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Get a device by its tracking number.
   */
  public function getByEither($value) {
    $query = "SELECT * FROM " . $this->table_name . "
      WHERE serial_number = :value
      OR tracking_number = :value
      LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':value', $value);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Get all devices from the database.
   */
  public function getAll() {
    $query = "SELECT * FROM " . $this->table_name . " ORDER BY model_number ASC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  /**
   * Get all devices with their statuses.
   */
  public function getAllByStatus($status_id) {
    $query = "SELECT * FROM " . $this->table_name . " WHERE status_id = :status_id ORDER BY model_number ASC";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':status_id', $status_id);
    $stmt->execute();
    return $stmt;
  }

  /**
   * Check if a tracking number already exists in the database.
   */
  public function trackingNumberExists($tracking_number) {
    $query = "SELECT id FROM " . $this->table_name . " WHERE tracking_number = :tracking_number LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':tracking_number', $tracking_number);
    $stmt->execute();

    return $stmt->rowCount() > 0;
  }

  /**
   * Check if a serial number already exists in the database.
   */
  public function serialNumberExists($serial_number) {
    $query = "SELECT id FROM " . $this->table_name . " WHERE serial_number = :serial_number LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':serial_number', $serial_number);
    $stmt->execute();

    return $stmt->rowCount() > 0;
  }

  /**
   * Update device information.
   */
  public function update() {
    $query = "UPDATE " . $this->table_name . "
              SET serial_number = :serial_number,
                  tracking_number = :tracking_number,
                  model_number = :model_number
              WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    // Sanitize inputs.
    $this->serial_number = htmlspecialchars(strip_tags($this->serial_number));
    $this->tracking_number = htmlspecialchars(strip_tags($this->tracking_number));
    $this->model_number = htmlspecialchars(strip_tags($this->model_number));

    // Bind parameters.
    $stmt->bindParam(':serial_number', $this->serial_number);
    $stmt->bindParam(':tracking_number', $this->tracking_number);
    $stmt->bindParam(':model_number', $this->model_number);
    $stmt->bindParam(':id', $this->id);

    return $stmt->execute();
  }

  /**
   * Delete device by ID.
   */
  public function delete() {
    $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $this->id);
    return $stmt->execute();
  }

  /**
   * Get the device's SRJC tag or serial number.
   *
   * @param string $tracking_number
   * @param string $serial_number
   * @return string
   */
  public function jcOrSerial($tracking_number, $serial_number) {
    return !empty($tracking_number) ? 'SRJC ' . htmlspecialchars($tracking_number) : 'Serial ' . htmlspecialchars($serial_number);
  }

}
