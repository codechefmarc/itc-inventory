<?php

/**
 * Status class for managing statuses in the database.
 */
class Status {
  private $conn;
  private $table_name = "statuses";

  public $id;
  public $status_name;
  public $description;

  public $saved_status_id;

  public function __construct($db) {
    $this->conn = $db;
  }

  /**
   * Get all statuses from the database.
   */
  public function getAll() {
    $query = "SELECT * FROM " . $this->table_name . " ORDER BY id ASC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  /**
   * Get a status by its ID.
   */
  public function getById($id) {
    $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Create a new status in the database.
   */
  public function create() {
    $query = "INSERT INTO " . $this->table_name . "
              (status_name, description)
              VALUES (:status_name, :description)";

    $stmt = $this->conn->prepare($query);

    // Sanitize inputs.
    $this->status_name = htmlspecialchars(strip_tags($this->status_name));
    $this->description = htmlspecialchars(strip_tags($this->description));

    // Bind parameters.
    $stmt->bindParam(':status_name', $this->status_name);
    $stmt->bindParam(':description', $this->description);

    if ($stmt->execute()) {
      $this->id = $this->conn->lastInsertId();
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Check if a status name already exists in the database.
   */
  public function statusNameExists($status_name) {
    $query = "SELECT id FROM " . $this->table_name . " WHERE status_name = :status_name LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':status_name', $status_name);
    $stmt->execute();

    return $stmt->rowCount() > 0;
  }

}
