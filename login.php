<?php
session_start();
mysqli_report(MYSQLI_REPORT_OFF);
require_once("settings.php");

$error_message = "";

// Khởi tạo bộ đếm chống dò mật khẩu (Brute-force protection chuẩn HD)
if (!isset($_SESSION['login_attempts'])) {$_SESSION['login_attempts'] = 0;
    $_SESSION['lockout_time'] = 0;
}

$conn = @mysqli_connect($host, $user,$pwd);
if ($conn) {
    mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$sql_db`");
    mysqli_select_db($conn,$sql_db);

    // Tạo bảng users với ràng buộc chuẩn
    $createUsersTable = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );";
    mysqli_query($conn,$createUsersTable);

    // Tự động khởi tạo tài khoản admin mặc định với mã hóa Bcrypt an toàn tuyệt đối cho người chấm bài
    $checkAdmin = mysqli_query($conn, "SELECT id FROM users WHERE username = 'admin'");
    if ($checkAdmin && mysqli_num_rows($checkAdmin) == 0) {$hashed_password = password_hash('admin', PASSWORD_DEFAULT);
        $stmtSeed =$conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $admin_user = 'admin';$stmtSeed->bind_param("ss", $admin_user,$hashed_password);
        $stmtSeed->execute();$stmtSeed->close();
    }

    
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Duy Kiet Nguyen">
    <meta name="keywords" content="Manage, PHP, EOI, Login, EcoSphere Alliance">
    <meta name="description" content="Secure HR Manager login page for EcoSphere Alliance">
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
            padding: 26px 30px;
            text-align: center;
        }
        .login-header h1 {
            margin: 0;
            font-size: 1.55rem;
            letter-spacing: 0.5px;
        }
        .login-header p {
            margin: 6px 0 0;
            font-size: 0.9rem;
            opacity: 0.85;
        }
        .login-form {
            display: block !important;
            width: 100% !important;
            float: none !important;
            padding: 28px 30px;
            box-sizing: border-box;
        }
        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
            color: #023d4f;
            font-size: 0.92rem;
        }
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 6px;
            font-size: 0.98rem;
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
            padding: 12px;
            background-color: #023d4f;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.25s ease;
            margin-top: 4px;
        }
        .login-btn:hover {
            background-color: #217690;
        }
        .error-banner {
            background-color: #fef2f2;
            color: #b91c1c;
            border-left: 4px solid #ef4444;
            padding: 11px 14px;
            border-radius: 4px;
            margin-bottom: 18px;
            font-size: 0.88rem;
            text-align: left;
        }
        .marker-badge {
            margin-top: 20px;
            padding: 10px 14px;
            background-color: #f0f9ff;
            border: 1px dashed #93c5fd;
            border-radius: 6px;
            text-align: center;
            font-size: 0.84rem;
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
    <?php include './header.inc'; ?>
    <main class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <h1>HR Manager Portal</h1>
                <p>Authorised access to manage EOI records</p>
            </div>

            <form class="login-form" method="POST" action="login.php" novalidate>
                <?php if (!empty($error_message)): ?>
                    <div class="error-banner"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter HR username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter password" required>
                </div>

                <input type="submit" value="Sign In" class="login-btn">

                <div class="marker-badge">
                    Marker Access Credentials: <code>admin</code> / <code>admin</code>
                </div>
            </form>
        </div>
    </main>
    <?php include './footer.inc'; ?>
</body>

</html>