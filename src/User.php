<?php

/**
 * User class for managing users.
 */
class User {

  /**
   * Gets first letters of a user's name for display.
   */
  public function shortUser($user) {
    $words = explode(' ', htmlspecialchars($user));
    $firstLetters = '';
    foreach ($words as $word) {
      $firstLetters .= $word[0];
    }

    return $firstLetters;
  }

}
