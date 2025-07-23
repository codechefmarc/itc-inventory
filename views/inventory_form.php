<?php

/**
 * @file
 * Main inventory form.
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1><?php echo APP_NAME; ?></h1>

        <?php if ($message) : ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-row">
              <div class="form-group">
                  <label for="tracking_number">SRJC Tag:</label>
                  <input type="text" id="tracking_number" name="tracking_number">
              </div>

              <div class="form-group">
                  <label for="serial_number">Serial Number:</label>
                  <input type="text" id="serial_number" name="serial_number">
              </div>

            </div>

            <div class="form-row">
                <!-- <div class="form-group">
                    <label for="model_number">Model Number:</label>
                    <input type="text" id="model_number" name="model_number">
                </div> -->

                <div class="form-group status-group">
                    <label>Status:</label>
Saved: <?php echo $status->saved_status_id; ?>
                    <?php foreach ($statuses as $status_option) : ?>
                      <div class="status-option">
                        <input type="radio" id="status_id_<?php echo $status_option['id']?>" name="status_id" value="<?php echo $status_option['id']; ?>" required>
                        <label for="status_id_<?php echo $status_option['id']?>"><?php echo htmlspecialchars($status_option['status_name']); ?></label>
                      </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit">Add Device to Inventory</button>
        </form>

        <div class="inventory-list">
            <h2>Current Inventory</h2>
            <table>
                <thead>
                    <tr>
                        <th>Entry ID</th>
                        <th>Device ID</th>
                        <th>Serial Number</th>
                        <th>Tracking Number</th>
                        <th>Model Number</th>
                        <th>Status</th>
                        <th>Date Added</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($entries_result->rowCount() > 0) : ?>
                        <?php while ($row = $entries_result->fetch(PDO::FETCH_ASSOC)) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['entry_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['device_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['serial_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['tracking_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['model_number']); ?></td>
                                <td>
                                    <span class="status-badge <?php echo get_status_badge_class($row['status_name']); ?>"
                                          title="<?php echo htmlspecialchars($row['status_description']); ?>">
                                        <?php echo htmlspecialchars($row['status_name']); ?>
                                    </span>
                                </td>
                                <td><?php echo format_date($row['date_added']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="no-data">No devices in inventory</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
