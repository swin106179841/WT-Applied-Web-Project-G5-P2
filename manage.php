<?php
session_start();
require_once 'settings.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Execute query
$sql = "SELECT * FROM eoi";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Taya Ford">
    <meta name="keywords" content="Manage, PHP, EOI, Table, EcoSphere Alliance, Environment, Community">
    <meta name="description" content="Manage page for EcoSphere Alliance EOI">
    <title>EcoSphere Alliance - Manage Page</title>
</head>

<body>
    <h1>Manage</h1>
    <h2>Welcome <?php echo htmlspecialchars($_SESSION['username']); ?></h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>EOI ID</th>
                    <th>Reference</th>
                    <th>First Name</th>
                    <th>Surname</th>
                    <th>Gender</th>
                    <th>State</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['eoi_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['reference']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['surname']); ?></td>
                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                        <td><?php echo htmlspecialchars($row['state']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No EOIs found.</p>
    <?php endif; ?>

</body>
</html>

<?php
mysqli_close($conn);
?>