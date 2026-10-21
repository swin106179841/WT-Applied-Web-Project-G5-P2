<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Taya Ford">
    <meta name="keywords" content="Manage, PHP, EOI, Table, EcoSphere Alliance, Environment, Community">
    <meta name="description" content="Login page for EcoSphere Alliance EOI">
    <title>EcoSphere Alliance-Login Page</title>
</head>

<body>
    <form method="post" action="manage.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required><br>

        <input type="hidden" name="token" value="abc123">
        <input type="submit" value="Login">
    </form>
</body>
</html>