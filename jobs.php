<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Duy Kiet Nguyen">
    <meta name="keywords" content="jobs, conservation, EcoSphere, careers">
    <meta name="description" content="Browse and search current conservation job opportunities at EcoSphere.">
    <title>Current Opportunities | EcoSphere</title>
    <link rel="stylesheet" href="./styles/style.css">
    <style>
        .search-container {
            margin: 20px 0;
            text-align: center;
        }
        .search-container input[type="text"] {
            padding: 8px 12px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .search-container input[type="submit"] {
            padding: 8px 16px;
            background-color: #217690;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-container input[type="submit"]:hover {
            background-color: #023d4f;
        }
        .job-listing {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>
    <?php include './header.inc';?>
    <main>
        <h1>Current Opportunities</h1>
        <p>Join the G05 team and make a tangible impact on our environment. Browse or search our available conservation projects and roles below.</p>

        <!-- Search Bar Form -->
        <div class="search-container">
            <form method="GET" action="jobs.php">
                <input type="text" name="search" placeholder="Search by job title or keyword..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <input type="submit" value="Search">
                <?php if (isset($_GET['search']) && trim($_GET['search']) !== ''): ?>
                    <a href="jobs.php" style="margin-left: 10px; text-decoration: none; color: #217690;">Clear Search</a>
                <?php endif; ?>
            </form>
        </div>

        <?php
        require_once("settings.php");
        $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

        if (!$conn) {
            // Fallback connection without DB name to create it if needed
            $conn = @mysqli_connect($host, $user, $pwd);
            if ($conn) {
                mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $sql_db");
                mysqli_select_db($conn, $sql_db);
            }
        }

        if ($conn) {
            // Create jobs table if it does not exist
            $createTableQuery = "CREATE TABLE IF NOT EXISTS jobs (
                job_id INT AUTO_INCREMENT PRIMARY KEY,
                job_ref VARCHAR(6) NOT NULL,
                job_title VARCHAR(100) NOT NULL,
                short_desc TEXT NOT NULL,
                salary VARCHAR(50) NOT NULL,
                reporting_line VARCHAR(100) NOT NULL,
                key_responsibilities TEXT NOT NULL,
                essential_requirements TEXT NOT NULL,
                preferable_requirements TEXT NOT NULL
            );";
        }
        ?>
        
        <aside style="background-color: #f1f8e9; padding: 15px; border-radius: 8px; margin-top: 20px;">
            <h3>Why Work With G05?</h3>
            <p>We are dedicated to building a sustainable future. When you join the G05 Environmental Conservation Organisation, you receive:</p>
            <ul>
                <li>Flexible working arrangements to support work-life balance.</li>
                <li>Annual allowances for outdoor gear and professional development.</li>
                <li>Opportunities to travel to remote, pristine ecosystems across the country.</li>
            </ul>
        </aside>

    </main>
    <?php include './footer.inc';?>
</body>

</html>