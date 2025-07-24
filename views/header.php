<?php

/**
 * @file
 * Header file.
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo APP_NAME; ?></title>
  <link rel="stylesheet" href="assets/css/styles.css">
  <script src="https://kit.fontawesome.com/d6c74f4872.js" crossorigin="anonymous"></script>
</head>
<body>
  <div class="container">
    <h1><?php echo APP_NAME; ?></h1>

    <?php
    if ($messages->hasMessages()) {
      echo $messages->display();
    }
