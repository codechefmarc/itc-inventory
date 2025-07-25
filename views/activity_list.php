<?php

/**
 * @file
 * Device activity list.
 */
?>

<div class="inventory-list">
  <h2>Device Activity</h2>
    <table>
        <thead>
            <tr>
                <th>SRJC Tag</th>
                <th>Serial Number</th>
                <th>Model Number</th>
                <th>Status</th>
                <th>Date Added</th>
                <th>Device</th>
                <th>Activity</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($device_activity->rowCount() > 0) : ?>
                <?php while ($row = $device_activity->fetch(PDO::FETCH_ASSOC)) : ?>
                  <?php
                  $model_number = !empty($row['model_number']) ? htmlspecialchars($row['model_number']) : '';
                  ?>
                  <tr>

                        <td><?php echo htmlspecialchars($row['tracking_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['serial_number']); ?></td>
                        <td><?php echo $model_number; ?></td>
                        <td>
                            <span class="status-badge <?php echo get_status_badge_class($row['status_name']); ?>"
                                  title="<?php echo htmlspecialchars($row['status_description']); ?>">
                                <?php echo htmlspecialchars($row['status_name']); ?>
                            </span>
                        </td>
                        <td><?php echo format_date($row['date_added']); ?></td>
                        <td>
                          <a class="action-icon" href="/device-edit.php?id=<?php echo htmlspecialchars($row['device_id']); ?>" title="Edit device"><i class="fa-solid fa-pen-to-square"></i></a>
                          <a class="action-icon" href="/device-delete.php?id=<?php echo htmlspecialchars($row['device_id']); ?>" title="Delete device"><i class="fa-solid fa-trash"></i></a>
                        </td>
                        <td>
                          <a class="action-icon" href="/activity-delete.php?id=<?php echo htmlspecialchars($row['entry_id']); ?>" title="Delete activity"><i class="fa-solid fa-trash"></i></a>
                        </td>
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
