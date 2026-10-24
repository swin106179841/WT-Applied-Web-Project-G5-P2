
<?php
include './settings.php';

$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Database connection failed.");
}

$sql = "SELECT * FROM about";
$result = mysqli_query($conn, $sql);

$members = [];

while ($row = mysqli_fetch_assoc($result)) {
    $members[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="author" content="Group 5">
    <meta name="keywords" content="EcoSphere, environment, conservation, team">
    <meta name="description" content="Meet the members of Group 5 and learn about our team.">

    <title>About Us - EcoSphere</title>

    <link rel="stylesheet" href="./styles/style.css">

    <style>
        .about-intro {
            font-style: italic;
        }
    </style>
</head>

<body>

    <?php include './header.inc'; ?>

    <main>

        <h1>About Our Team</h1>

        <p class="about-intro" style="text-align: center;">
            Meet Group 5, the team behind the EcoSphere website project.
        </p>
         <!-- Group photo goes here -->
        <section class="group-photo">
            <h2>Meet Group 5</h2>

            <figure>
                <img src="./assets/Group_photo.jpg"
                     alt="Group 5 members together"
                     style="max-width: 100%; height: auto;">

                <figcaption>Group 5 - EcoSphere Website Project</figcaption>
            </figure>
        </section>

        <section>
            <h2>Group Information</h2>

            <ul>
                <li>
                    Group 5
                    <ul>
                        <li>Tutorial Day: Wednesday</li>
                        <li>Tutorial Time: 2:30 PM - 4:30 PM</li>
                    </ul>
                </li>
            </ul>
        </section>

        <section>
            <h2>Member Contributions</h2>

            <?php foreach ($members as $member) { ?>
                <h3><?php echo htmlspecialchars($member['Name']); ?></h3>

                <p>
                    Student ID:
                    <?php echo htmlspecialchars($member['Student ID']); ?>
                </p>

                <p>
                    Contribution:
                    <?php echo nl2br(htmlspecialchars($member['Contribution Statement'])); ?>
                </p>
            <?php } ?>
        </section>

        <section>
            <h2>Member Quotes</h2>

            <?php foreach ($members as $member) { ?>
                <h3><?php echo htmlspecialchars($member['Name']); ?></h3>

                <p>
                    <strong>Quote:</strong>
                    <?php echo htmlspecialchars($member['Quote']); ?>
                </p>
            <?php } ?>
        </section>

        <section>
            <h2>Our Group</h2>

            <figure class="group-figure">
                <img src="./assets/group-photo.jpg"
                     alt="Group 5 members standing together">
                <figcaption>Group 5 - EcoSphere Website Project</figcaption>
            </figure>
        </section>

        <section>
            <h2>Fun Facts About Our Team</h2>

            <table class="team-table">
                <caption>Fun Facts About Group 5 Members</caption>

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Student ID</th>
                        <th>Fun Fact</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($members as $member) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($member['Name']); ?></td>
                            <td class="student-id">
                                <?php echo htmlspecialchars($member['Student ID']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($member['Fun Fact']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>

    </main>

    <?php include './footer.inc'; ?>

    <?php mysqli_close($conn); ?>

</body>
</html>
