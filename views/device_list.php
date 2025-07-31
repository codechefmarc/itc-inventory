<?php

/**
 * @file
 * Device activity list.
 */
?>

<div class="device-list">
  <h2><?php echo $devices_title; ?></h2>
    <table>
        <thead>
            <tr>
                <th>SRJC Tag</th>
                <th>Serial Number</th>
                <th>Model Number</th>
                <th>Operations</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($devices->rowCount() > 0) : ?>
                <?php while ($row = $devices->fetch(PDO::FETCH_ASSOC)) : ?>
                  <?php
                  $model_number = !empty($row['model_number']) ? htmlspecialchars($row['model_number']) : '';
                  ?>
                  <tr>

                        <td><a href="search.php?q=<?php echo htmlspecialchars($row['tracking_number']); ?>"><?php echo htmlspecialchars($row['tracking_number']); ?></a></td>
                        <td><a href="search.php?q=<?php echo htmlspecialchars($row['serial_number']); ?>"><?php echo htmlspecialchars($row['serial_number']); ?></a></td>
                        <td><?php echo $model_number; ?></td>

                        <td>
                          <a class="action-icon" href="/device-edit.php?id=<?php echo htmlspecialchars($row['device_id']); ?>" title="Edit device"><i class="fa-solid fa-pen-to-square"></i></a>
                          <a class="action-icon" href="/device-delete.php?id=<?php echo htmlspecialchars($row['device_id']); ?>" title="Delete device"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="8" class="no-data">No devices found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
