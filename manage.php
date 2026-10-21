<?php
session_start();
require_once 'settings.php';

// authentication
#if (!isset($_SESSION['user_id'])) {
    #header('Location: login.php');
   # exit();
#}
$sql = "SELECT * FROM EOI";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY $sort";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Taya Ford">
    <meta name="keywords" content="Manage, PHP, EOI, Table, EcoSphere Alliance, Environment, Community">
    <meta name="description" content="Manage page for EcoSphere Alliance EOI">
    <title>EcoSphere Alliance-Manage Page</title>
</head>

<body>
    <h1>Manage</h1>
    <!--<h1>Welcome <?= htmlspecialchars($_SESSION['username']) ?></h1>-->
    <!-- EOI Table -->
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>First Name</th>
                    <th>Surname</th>
                    <th>Gender</th>
                    <th>State</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['eoi_id'] ?></td>
                    <td><?= htmlspecialchars($row['reference']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['surname']) ?></td>
                    <td><?= htmlspecialchars($row['gender']) ?></td>
                    <td><?=htmlspecialchars($row['state'])?></td>
                    <td>
                        <form method="post" action="manage.php?action=status" style="display:inline;">
                            <input type="hidden" name="eoi_id" value="<?= $row['eoi_id'] ?>">
                            <select name="new_status">
                                <option value="New" <?= $row['status'] == 'New' ? 'selected' : '' ?>>New</option>
                                <option value="Current" <?= $row['status'] == 'Current' ? 'selected' : '' ?>>Current</option>
                                <option value="Final" <?= $row['status'] == 'Final' ? 'selected' : '' ?>>Final</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
            <?php else: ?>
                <p>No EOIs found.</p>
            <?php endif; ?>
                </tr>
</body>
</html>