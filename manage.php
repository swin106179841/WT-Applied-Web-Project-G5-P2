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
    $query = "SELECT * FROM eoi WHERE job_ref LIKE '%$search%' OR NAME LIKE '%$search%' OR surname LIKE '%$search%'";
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
    $updateQuery = "UPDATE eoi SET STATUS = '$status' WHERE id = '$eoi_id'";
    if (!mysqli_query($dbconn, $updateQuery)) {
        die("Status update failed: " . mysqli_error($dbconn));
    }
    header("Location: manage.php");
    exit();
}

// Sort the EOI
if (isset($_POST['sortbutton'])) {
    $allowedFields = array("id", "job_ref", "NAME", "surname", "STATUS");
    $sortfield = $_POST['sortfield'];
    $sortorder = $_POST['sortorder'];
    if (in_array($sortfield, $allowedFields)
        && ($sortorder == "ASC" || $sortorder == "DESC")) {
        $query = "SELECT * FROM eoi ORDER BY $sortfield $sortorder";
    }
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
    <style>
        body {
            margin: 0;
            margin-top: 6em;
            padding: 0;
            font-family: sans-serif;
            margin-top: 6em;
        }

        header {
            border-bottom: 5px solid #217690;
            position: fixed;
            top: 0;
            box-shadow: 0px 2px 7px 5px #999;
            width: 100vw;
        }

        nav {
            height: 5em;
            background-color: #023d4f;
        }

        nav ul {
            height: 100%;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        nav li {
            height: 5em;
            width: 25%;
            min-width: 75px;
        }

        nav a {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: white;
            text-decoration: none;
            font-size: large;
            font-weight: bold;
            transition: background-color 500ms;
        }

        nav a:hover {
            text-decoration: underline;
            background-color: #217690;
        }

        nav a img {
            object-fit: cover;
            height: 90%;
        }
        main {
            margin-left: 1em;
            margin-right: 1em;
        }
        footer {
            clear:both;
            margin-left: 1em;
            margin-right: 1em;
        }
        table {
            margin-bottom: 3em;
        }
    </style>
</head>
<body>
<?php require_once "header.inc";?>
<main>
<h1>Manage EOIs</h1>
<h2>Welcome <?php echo htmlspecialchars($_SESSION['username']); ?></h2>

<h4>Search by Job Reference Number, First Name OR Last Name</h4>
<form method="post" action="manage.php">
    <input type="text" name="search" placeholder="Job Ref, First Name or Surname">
    <input type="submit" name="searchbutton" value="Search">
</form>

<br>

<h4>Delete all EOI's by Job Reference Number</h4>
<form method="post" action="manage.php"
      onsubmit="return confirm('Delete ALL EOIs for this job reference?');">
    <input type="text" name="delete" placeholder="Type a Job Ref to Delete">
    <input type="submit" name="deletebutton" value="Delete">

</form>
<br>

<h4>Change EOI Status by ID Number</h4>
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

<h4>Sort the Results using the EOI</h4>
<form method="post" action="manage.php">
    <select name="sortfield" required>
        <option value="id">EOI ID</option>
        <option value="job_ref">Job Reference</option>
        <option value="NAME">First Name</option>
        <option value="surname">Surname</option>
        <option value="STATUS">Status</option>
    </select>
    <select name="sortorder">
        <option value="ASC">Ascending</option>
        <option value="DESC">Descending</option>
    </select>
    <input type="submit" name="sortbutton" value="Sort">
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
</main>
<?php require_once "footer.inc";?>
</body>
</html>

<?php
mysqli_close($dbconn);
?>