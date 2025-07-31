<?php

/**
 * @file
 * This file sets up the database connection and includes necessary class files.
 */

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload classes using Composer.
require_once __DIR__ . '/vendor/autoload.php';

// Include all class files.
require_once 'src/Database.php';
require_once 'src/User.php';
require_once 'src/Status.php';
require_once 'src/Device.php';
require_once 'src/DeviceActivity.php';
require_once 'src/MessageManager.php';

// Initialize database connection.
$database = new Database();
$db = $database->connect();

// Initialize class instances.
$user = new User();
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
  return date('n/j/y g:i A', strtotime($date));
}

/**
 * Format date for display without time.
 *
 * @param string $date
 *   The date string to format.
 *
 * @return string
 *   Formatted date string.
 */
function format_date_only($date) {
  return date('n/j/y', strtotime($date));
}

/**
 * Generate a dark color based on the first two letters of a string.
 *
 * @param string $letters
 *   The letters to base the color on.
 *
 * @return string
 *   The generated HSL color.
 */
function dark_color_from_letters($letters) {
  $hash = crc32(strtoupper($letters));
  $hue = $hash % 360;
  $saturation = 70 + ($hash % 20);
  $lightness = 20 + ($hash % 10);
  return "hsl($hue, $saturation%, $lightness%)";
}
