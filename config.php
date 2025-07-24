<?php

/**
 * @file
 * This file sets up the database connection and includes necessary class files.
 */

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include all class files.
require_once 'src/Database.php';
require_once 'src/Status.php';
require_once 'src/Device.php';
require_once 'src/DeviceActivity.php';
require_once 'src/MessageManager.php';

// Initialize database connection.
$database = new Database();
$db = $database->connect();

// Initialize class instances.
$status = new Status($db);
$device = new Device($db);
$deviceActivity = new DeviceActivity($db);
$messages = MessageManager::getInstance();

// Application configuration.
define('APP_NAME', 'ITC Checkout Laptop Inventory');
define('APP_VERSION', '1.0');

/**
 * Sanitization.
 */
function sanitize_input($input) {
  return htmlspecialchars(strip_tags(trim($input)));
}

/**
 * Format date for display.
 *
 * @param string $date
 *   The date string to format.
 *
 * @return string
 *   Formatted date string.
 */
function format_date($date) {
  return date('M j, Y g:i A', strtotime($date));
}

/**
 * Get status badge class based on status name.
 *
 * @param string $status_name
 *   The name of the status.
 *
 * @return string
 *   The CSS class for the status badge.
 */
function get_status_badge_class($status_name) {
  $status_lower = strtolower(str_replace(' ', '', $status_name));
  return 'status-' . $status_lower;
}
