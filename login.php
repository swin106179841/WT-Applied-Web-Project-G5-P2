<?php
session_start();
mysqli_report(MYSQLI_REPORT_OFF);
require_once("settings.php");

$error_message = "";

// Kết nối MySQL và tự động tạo database/bảng users nếu chưa có
$conn = @mysqli_connect($host, $user,$pwd);
if ($conn) {
    mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$sql_db`");
    mysqli_select_db($conn,$sql_db);

    $createUsersTable = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    );";
    mysqli_query($conn,$createUsersTable);

    // Tạo tài khoản mặc định admin / admin (mã hóa bằng password_hash)
    $checkAdmin = mysqli_query($conn, "SELECT * FROM users WHERE username = 'admin'");
    if ($checkAdmin && mysqli_num_rows($checkAdmin) == 0) {$hashed_password = password_hash('admin', PASSWORD_DEFAULT);
        $stmtSeed =$conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $admin_user = 'admin';$stmtSeed->bind_param("ss", $admin_user,$hashed_password);
        $stmtSeed->execute();$stmtSeed->close();
    }

    // Xử lý khi người dùng nhấn nút Login
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!empty($username) && !empty($password)) {
            $stmt =$conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);$stmt->execute();
            $result =$stmt->get_result();

            if ($row =$result->fetch_assoc()) {
                if (password_verify($password,$row['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] =$row['username'];
                    
                    header("Location: manage.php");
                    exit();
                } else {
                    $error_message = "Invalid username or password.";
                }
            } else {
                $error_message = "Invalid username or password.";
            }
            $stmt->close();
        } else {
            $error_message = "Please enter both username and password.";
        }
    }
    mysqli_close($conn);
} else {
    $error_message = "Database connection error. Please check settings.php.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Duy Kiet Nguyen">
    <meta name="keywords" content="Manage, PHP, EOI, Login, EcoSphere Alliance">
    <meta name="description" content="Secure login page for EcoSphere Alliance HR Management">
    <title>HR Manager Login | EcoSphere Alliance</title>
    <link rel="stylesheet" href="./styles/style.css">
    <style>
        body {
            background-color: #f4f8f9;
        }
        .login-wrapper {
            min-height: calc(100vh - 16em);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2em 1em;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(2, 61, 79, 0.12);
            overflow: hidden;
            border: 1px solid #e1ecef;
        }
        .login-header {
            background: linear-gradient(135deg, #023d4f 0%, #217690 100%);
            color: #ffffff;
            padding: 28px 30px;
            text-align: center;
        }
        .login-header h1 {
            margin: 0;
            font-size: 1.6rem;
            letter-spacing: 0.5px;
        }
        .login-header p {
            margin: 8px 0 0;
            font-size: 0.9rem;
            opacity: 0.85;
        }
        /* Ghi đè CSS form toàn cục trong style.css */
        .login-form {
            display: block !important;
            width: 100% !important;
            float: none !important;
            padding: 30px;
            box-sizing: border-box;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #023d4f;
            font-size: 0.95rem;
        }
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 6px;
            font-size: 1rem;
            background-color: #f8fafc;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }
        .form-group input[type="text"]:focus,
        .form-group input[type="password"]:focus {
            outline: none;
            border-color: #217690;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(33, 118, 144, 0.15);
        }
        .login-btn {
            width: 100%;
            padding: 13px;
            background-color: #023d4f;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.25s ease, transform 0.1s ease;
            margin-top: 5px;
        }
        .login-btn:hover {
            background-color: #217690;
        }
        .login-btn:active {
            transform: scale(0.99);
        }
        .error-banner {
            background-color: #fef2f2;
            color: #b91c1c;
            border-left: 4px solid #ef4444;
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            text-align: left;
        }
        .marker-badge {
            margin-top: 22px;
            padding: 10px 14px;
            background-color: #f0f9ff;
            border: 1px dashed #93c5fd;
            border-radius: 6px;
            text-align: center;
            font-size: 0.85rem;
            color: #0369a1;
        }
        .marker-badge code {
            font-weight: bold;
            background: #e0f2fe;
            padding: 2px 6px;
            border-radius: 4px;
            color: #023d4f;
        }
    </style>
</head>

<body>
    <?php include './header.inc';?>
    <main class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <h1>HR Manager Portal</h1>
                <p>Sign in to manage Expressions of Interest</p>
            </div>

            <form class="login-form" method="POST" action="login.php" novalidate>
                <?php if (!empty($error_message)): ?>
                    <div class="error-banner"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <input type="submit" value="Sign In" class="login-btn">

                <div class="marker-badge">
                    Marker Access Credentials: <code>admin</code> / <code>admin</code>
                </div>
            </form>
        </div>
    </main>
    <?php include './footer.inc';?>
</body>
</html>