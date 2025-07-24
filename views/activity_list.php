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
                <!-- <th>Entry ID</th>
                <th>Device ID</th> -->
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
                    <tr>
                        <!-- <td><?php echo htmlspecialchars($row['entry_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['device_id']); ?></td> -->
                        <td><?php echo htmlspecialchars($row['tracking_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['serial_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['model_number'] || ''); ?></td>
                        <td>
                            <span class="status-badge <?php echo get_status_badge_class($row['status_name']); ?>"
                                  title="<?php echo htmlspecialchars($row['status_description']); ?>">
                                <?php echo htmlspecialchars($row['status_name']); ?>
                            </span>
                        </td>
                        <td><?php echo format_date($row['date_added']); ?></td>
                        <td>
                          <a href="/device-edit.php?id=<?php echo htmlspecialchars($row['device_id']); ?>" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                          <a href="/device-delete.php?id=<?php echo htmlspecialchars($row['device_id']); ?>" title="Delete"><i class="fa-solid fa-trash"></i></a>
                        </td>
                        <td>
                          <a href="/activity-delete.php?id=<?php echo htmlspecialchars($row['entry_id']); ?>" title="Delete"><i class="fa-solid fa-trash"></i></a>
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
