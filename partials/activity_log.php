<?php

/**
 * @file
 * Device activity list.
 */

$device_activity = $deviceActivity->getTodayDeviceActivity();
$device_activity_title = "Today's Device Activity";
?>

<div class="activity-list">
  <h2><?php echo $device_activity_title; ?></h2>
    <table>
        <thead>
            <tr>
                <th>SRJC Tag</th>
                <th>Serial Number</th>
                <th>Model Number</th>
                <th>User</th>
                <th>Notes</th>
                <th>Status</th>
                <th>Date Logged</th>
                <th>Device</th>
                <th>Activity</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($device_activity->rowCount() > 0) : ?>
                <?php while ($row = $device_activity->fetch(PDO::FETCH_ASSOC)) : ?>
                  <?php
                  $model_number = !empty($row['model_number']) ? htmlspecialchars($row['model_number']) : '';
                  $notes = !empty($row['notes']) ? htmlspecialchars($row['notes']) : '';
                  $device_number = $device->jcOrSerial($row['tracking_number'], $row['serial_number']);
                  ?>
                  <tr>

                        <td><a href="search.php?q=<?php echo htmlspecialchars($row['tracking_number']); ?>"><?php echo htmlspecialchars($row['tracking_number']); ?></a></td>
                        <td><a href="search.php?q=<?php echo htmlspecialchars($row['serial_number']); ?>"><?php echo htmlspecialchars($row['serial_number']); ?></a></td>
                        <td><?php echo $model_number; ?></td>
                        <td>
                        <?php if ($row['user']) : ?>
                          <span class="short-user" style="background-color: <?php echo dark_color_from_letters($user->shortUser($row['user'])); ?>;" title="<?php echo $row['user']; ?>"><?php echo $user->shortUser($row['user']); ?>
                          </span>
                        <?php endif; ?>
                      </td>
                        <td class="notes">
                          <?php
                          if ($notes) : ?>
                            <dialog class="dialog-note" id="note-<?php echo htmlspecialchars($row['entry_id']); ?>" class="note-dialog">
                              <h2>Notes for <?php echo $device_number; ?></h2>
                              <p><?php echo $notes; ?></p>
                              <a href="#!">Close</a>
                            </dialog>
                            <a class="action-icon" href="#note-<?php echo htmlspecialchars($row['entry_id']); ?>" title="View note"><i class="fa-solid fa-file-lines"></i></a>
                            <div class="note-preview"><?php echo $notes; ?></div>

                          <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge <?php echo $status->getStatusBadgeClass($row['status_name']); ?>"
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
                          <a class="action-icon" href="/activity-edit.php?id=<?php echo htmlspecialchars($row['entry_id']); ?>" title="Edit activity"><i class="fa-solid fa-pen-to-square"></i></a>
                          <a class="action-icon" href="/activity-delete.php?id=<?php echo htmlspecialchars($row['entry_id']); ?>" title="Delete activity"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="8" class="no-data">No activities found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
