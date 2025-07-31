<?php

/**
 * @file
 * MessageManager class for handling messages.
 *
 * This class provides methods to add, retrieve, and display messages.
 */

/**
 * MessageManager class.
 *
 * This class manages messages in a session.
 */
class MessageManager {
  private static $instance = null;
  private $messages = [];

  // Message types.
  const SUCCESS = 'success';
  const ERROR = 'error';
  const WARNING = 'warning';
  const INFO = 'info';

  private function __construct() {
    $this->startSession();
    $this->loadMessages();
  }

  /**
   * Singleton pattern to ensure one instance.
   */
  public static function getInstance() {
    if (self::$instance === NULL) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Start session if not already started.
   */
  private function startSession() {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }

  /**
   * Load messages from session.
   */
  private function loadMessages() {
    if (isset($_SESSION['flash_messages'])) {
      $this->messages = $_SESSION['flash_messages'];
    }
  }

  /**
   * Save messages to session.
   */
  private function saveMessages() {
    $_SESSION['flash_messages'] = $this->messages;
  }

  /**
   * Add a message to the session.
   */
  public function add($message, $type = self::INFO, $persistent = FALSE) {
    $messageData = [
      'text' => $message,
      'type' => $type,
      'persistent' => $persistent,
      'timestamp' => time(),
    ];

    $this->messages[] = $messageData;
    $this->saveMessages();
  }

  /**
   * Add a success message.
   */
  public function addSuccess($message, $persistent = FALSE) {
    $this->add($message, self::SUCCESS, $persistent);
  }

  /**
   * Add a error message.
   */
  public function addError($message, $persistent = FALSE) {
    $this->add($message, self::ERROR, $persistent);
  }

  /**
   * Add a warning message.
   */
  public function addWarning($message, $persistent = FALSE) {
    $this->add($message, self::WARNING, $persistent);
  }

  /**
   * Add an info message.
   */
  public function addInfo($message, $persistent = FALSE) {
    $this->add($message, self::INFO, $persistent);
  }

  /**
   * Get all messages.
   */
  public function getAll() {
    return $this->messages;
  }

  /**
   * Get messages by type.
   */
  public function getByType($type) {
    return array_filter($this->messages, function ($msg) use ($type) {
        return $msg['type'] === $type;
    });
  }

  /**
   * Check if there are any messages.
   */
  public function hasMessages() {
    return !empty($this->messages);
  }

  /**
   * Check if there are messages of a specific type.
   */
  public function hasType($type) {
    return !empty($this->getByType($type));
  }

  /**
   * Display all messages.
   */
  public function display() {
    if (empty($this->messages)) {
      return '';
    }

    $html = '';

    $html .= '<div class="message-container">';

    foreach ($this->messages as $message) {
      $html .= $this->renderMessage($message);
    }

    $html .= '</div>';

    // Clear non-persistent messages after displaying.
    $this->clearNonPersistent();

    return $html;
  }

  /**
   * Display messages of a specific type.
   */
  public function displayType($type) {
    $messages = $this->getByType($type);

    if (empty($messages)) {
      return '';
    }

    $html = '';

    $html .= '<div class="message-container">';

    foreach ($messages as $message) {
      $html .= $this->renderMessage($message);
    }

    $html .= '</div>';

    return $html;
  }

  /**
   * Render a single message as HTML.
   */
  private function renderMessage($message) {
    $icons = [
      self::SUCCESS => '<i class="fa-solid fa-circle-check"></i>',
      self::ERROR => '<i class="fa-solid fa-circle-exclamation"></i>',
      self::WARNING => '<i class="fa-solid fa-triangle-exclamation"></i>',
      self::INFO => '<i class="fa-solid fa-circle-info"></i>',
    ];

    $icon = $icons[$message['type']] ?? '<i class="fa-solid fa-circle-info"></i>';

    return sprintf(
        '<div class="message message-%s" data-timestamp="%d">
            <span class="message-icon">%s</span>
            <span class="message-text">%s</span>
            <button class="message-close" onclick="this.parentElement.style.display=\'none\'">&times;</button>
        </div>',
        htmlspecialchars($message['type']),
        $message['timestamp'],
        $icon,
        htmlspecialchars($message['text'])
    );
  }

  /**
   * Clear all messages from the session.
   */
  public function clear() {
    $this->messages = [];
    unset($_SESSION['flash_messages']);
  }

  /**
   * Clear non-persistent messages from the session.
   */
  private function clearNonPersistent() {
    $this->messages = array_filter($this->messages, function ($msg) {
        return $msg['persistent'];
    });

    if (empty($this->messages)) {
      unset($_SESSION['flash_messages']);
    }
    else {
      $this->saveMessages();
    }
  }

  /**
   * Clear messages of a specific type from the session.
   */
  public function clearType($type) {
    $this->messages = array_filter($this->messages, function ($msg) use ($type) {
        return $msg['type'] !== $type;
    });

    if (empty($this->messages)) {
      unset($_SESSION['flash_messages']);
    }
    else {
      $this->saveMessages();
    }
  }

  /**
   * Clear all persistent messages from the session.
   */
  public function clearPersistent() {
    $this->messages = array_filter($this->messages, function ($msg) {
        return !$msg['persistent'];
    });

    if (empty($this->messages)) {
      unset($_SESSION['flash_messages']);
    }
    else {
      $this->saveMessages();
    }
  }

  /**
   * Count total messages.
   */
  public function count() {
    return count($this->messages);
  }

  /**
   * Count messages of a specific type.
   */
  public function countType($type) {
    return count($this->getByType($type));
  }

}
