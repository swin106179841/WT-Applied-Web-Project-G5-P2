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
    <h1>Welcome <?= htmlspecialchars($_SESSION['username']) ?></h1>
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
                      