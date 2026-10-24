<?php
session_start();
require_once("settings.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$dbconn = @mysqli_connect($host, $user, $pwd, $sql_db);

if (!$dbconn) {
    die("<p>Unable to connect to database.</p>");
}

$query = "SELECT * FROM eoi";
$result = mysqli_query($dbconn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($dbconn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoSphere Alliance - Manage</title>
</head>

<body>

<h1>Manage</h1>
<h2>Welcome <?php echo htmlspecialchars($_SESSION['username']); ?></h2>

<?php if (mysqli_num_rows($result) > 0) { ?>

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

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['job_ref']); ?></td>
                <td><?php echo htmlspecialchars($row['NAME']); ?></td>
                <td><?php echo htmlspecialchars($row['surname']); ?></td>
                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                <td><?php echo htmlspecialchars($row['state']); ?></td>
            </tr>

        <?php } ?>

        </tbody>
    </table>

<?php } else { ?>

    <p>No EOIs found.</p>

<?php } ?>

</body>
</html>

<?php
mysqli_close($dbconn);
?>