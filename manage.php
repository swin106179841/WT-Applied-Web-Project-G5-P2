<?php
session_start();
require_once("settings.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$dbconn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$dbconn) {
    die("Unable to connect to database.");
}

$query = "SELECT * FROM eoi";

// search by jobref name and surname
if (isset($_POST['searchbutton']) && !empty($_POST['search'])) {

    $search = mysqli_real_escape_string($dbconn, $_POST['search']);

    $query = "
        SELECT * FROM eoi
        WHERE job_ref LIKE '%$search%'
        OR NAME LIKE '%$search%'
        OR surname LIKE '%$search%'
    ";
}

// delete by jobref
if (isset($_POST['deletebutton']) && !empty($_POST['delete'])) {

    $delete = mysqli_real_escape_string($dbconn, $_POST['delete']);

    $deleteQuery = "DELETE FROM eoi WHERE job_ref = '$delete'";

    if (!mysqli_query($dbconn, $deleteQuery)) {
        die("Delete failed: " . mysqli_error($dbconn));
    }
}

// update status
if (isset($_POST['updateStatus'])) {

    $eoi_id = mysqli_real_escape_string($dbconn, $_POST['eoi_id']);
    $status = mysqli_real_escape_string($dbconn, $_POST['status']);

    $updateQuery = "
        UPDATE eoi
        SET STATUS = '$status'
        WHERE id = '$eoi_id'
    ";

    if (!mysqli_query($dbconn, $updateQuery)) {
        die("Status update failed: " . mysqli_error($dbconn));
    }

    header("Location: manage.php");
    exit();
}

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

<h1>Manage EOIs</h1>

<h2>Welcome <?php echo htmlspecialchars($_SESSION['username']); ?></h2>


<h4>Search by Job Reference No., First Name OR Last Name</h4>

<form method="post" action="manage.php">
    <input type="text" name="search" placeholder="Job Ref, First Name or Surname">
    <input type="submit" name="searchbutton" value="Search">
</form>

<br>


<h4>Delete all EOI's by Job Reference No.</h4>

<form method="post" action="manage.php"
      onsubmit="return confirm('Delete ALL EOIs for this job reference?');">
    <input type="text" name="delete" placeholder="Type a Job Ref to Delete">
    <input type="submit" name="deletebutton" value="Delete">

</form>
<br>

<h4>Change EOI Status by ID No.</h4>

<form method="post" action="manage.php">

    <input type="number" name="eoi_id"
           placeholder="Enter EOI ID" required>
    <select name="status" required>
        <option value="">Select Status</option>
        <option value="New">New</option>
        <option value="Current">Current</option>
        <option value="Final">Final</option>
    </select>
    <input type="submit" name="updateStatus" value="Update Status">
</form>

<br>

<?php if (mysqli_num_rows($result) > 0) { ?>

<table border="1">

    <thead>
        <tr>
            <th>EOI ID</th>
            <th>Reference</th>
            <th>First Name</th>
            <th>Surname</th>
            <th>Date of Birth</th>
            <th>Gender</th>
            <th>Street</th>
            <th>Suburb</th>
            <th>State</th>
            <th>Postcode</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Skills</th>
            <th>Other Skills</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['job_ref']); ?></td>
            <td><?php echo htmlspecialchars($row['NAME']); ?></td>
            <td><?php echo htmlspecialchars($row['surname']); ?></td>
            <td><?php echo htmlspecialchars($row['dob']); ?></td>
            <td><?php echo htmlspecialchars($row['gender']); ?></td>
            <td><?php echo htmlspecialchars($row['street']); ?></td>
            <td><?php echo htmlspecialchars($row['suburb']); ?></td>
            <td><?php echo htmlspecialchars($row['state']); ?></td>
            <td><?php echo htmlspecialchars($row['postcode']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['phone']); ?></td>
            <td><?php echo htmlspecialchars($row['skills']); ?></td>
            <td><?php echo htmlspecialchars($row['other_skills']); ?></td>
            <td><?php echo htmlspecialchars($row['STATUS']); ?></td>
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