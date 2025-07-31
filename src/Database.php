<?php

/**
 * Database connection class. Currently using DDEV defaults.
 */
class Database {
  private $host = 'db';
  private $dbName = 'db';
  private $username = 'db';
  private $password = 'db';
  private $conn;

  /**
   * Connect to the database.
   */
  public function connect() {
    $this->conn = NULL;
    try {
      $this->conn = new PDO(
          "mysql:host=" . $this->host . ";dbname=" . $this->dbName,
          $this->username,
          $this->password
      );
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e) {
      echo "Connection error: " . $e->getMessage();
    }
    return $this->conn;
  }

}
