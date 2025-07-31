<?php
session_start(); // Added session_start() at the beginning
include 'config.php';

$loginError = $registerError = $adminError = '';
$loginSuccess = $registerSuccess = '';

// Proses login user
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validasi input
    if (empty($username) || empty($password)) {
        $loginError = "Please fill in all fields.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // ✅ Baru di sini session user_id diset
                $_SESSION['user'] = [
                    'username' => $user['username'],
                    'id' => $user['id']
                ];

                // ✅ BARU SEKARANG BOLEH MUAT KERANJANG DARI DATABASE
                $user_id = $user['id'];

                $stmt_cart = $pdo->prepare("
                    SELECT c.product_id, p.name, p.price, c.quantity, p.image, p.stock 
                    FROM cart c 
                    JOIN products p ON c.product_id = p.id 
                    WHERE c.user_id = ?
                ");
                $stmt_cart->execute([$user_id]);
                $cart_items = $stmt_cart->fetchAll();

                // Masukkan ke session cart
                $_SESSION['cart'] = [];
                foreach ($cart_items as $item) {
                    $_SESSION['cart'][$item['product_id']] = [
                        'id' => $item['product_id'],
                        'name' => $item['name'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'image' => $item['image'],
                        'stock' => $item['stock']
                    ];
                }

                // Redirect ke halaman utama
                header("Location: home.php");
                exit();
            } else {
                $loginError = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $loginError = "Database error. Please try again later.";
        }
    }
}
// Proses registrasi
if (isset($_POST['register'])) {
    $username = trim($_POST['reg_username']);
    $password = $_POST['reg_password'];
    
    // Basic validation
    if (empty($username) || empty($password)) {
        $registerError = "Please fill in all fields.";
    } elseif (strlen($password) < 8) {
        $registerError = "Password must be at least 8 characters long.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashedPassword]);
            $registerSuccess = "Registration successful! <a href='auth.php'>Login here</a>";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry error code
                $registerError = "Username already exists.";
            } else {
                $registerError = "Registration failed. Please try again.";
            }
        }
    }
}

// Proses login admin (dari tabel users)
if (isset($_POST['login_admin'])) {
    $admin_user = trim($_POST['admin_user']);
    $admin_pass = $_POST['admin_pass'];

    // Validasi input
    if (empty($admin_user) || empty($admin_pass)) {
        $adminError = "Please fill in all fields.";
    } else {
        try {
            // Cari user dengan role 'admin'
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin'");
            $stmt->execute([$admin_user]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($admin_pass, $admin['password'])) {
                // Login sebagai admin
                $_SESSION['admin'] = [
                    'id' => $admin['id'],
                    'username' => $admin['username']
                ];
                header("Location: admin/dashboard.php");
                exit();
            } else {
                $adminError = "Invalid admin credentials.";
            }
        } catch (PDOException $e) {
            $adminError = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>UThrift - Auth</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f6f3;
            height: 100vh;
            display: flex;
        }

        .container {
            display: flex;
            width: 100%;
            height: 100vh;
            background-color: #f8f6f3;
        }

        .left-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background-color: #f8f6f3;
        }

       .image-container {
    background-image: url('asset/login.jpg'); 
    background-size: cover; 
    background-position: center; 
    border-radius: 30px;
    padding: 60px 40px;
    text-align: center;
    position: relative;
    overflow: hidden;
    max-width: 450px;
    height: 500px;
    width: 100%;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

        .image-container::before {
            content: '';
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 1000px;
            height: 40px;
            background: rgba(139, 115, 85, 0.3);
            border-radius: 20px;
        }

        .hangers {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 40px;
            position: relative;
        }

        .hanger {
            width: 60px;
            height: 80px;
            position: relative;
        }

        .hanger::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 3px;
            height: 15px;
            background: #8b7355;
            border-radius: 2px;
        }

        .hanger::after {
            content: '';
            position: absolute;
            top: 12px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: #8b7355;
            border-radius: 2px;
        }

        .clothes {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 50px;
        }

        .clothing-item {
            width: 70px;
            height: 120px;
            border-radius: 10px;
            position: relative;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .clothing-item:nth-child(1) {
            background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
        }

        .clothing-item:nth-child(2) {
            background: linear-gradient(135deg, #e8e8e8 0%, #d0d0d0 100%);
        }

        .clothing-item:nth-child(3) {
            background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
        }

        .clothing-item:nth-child(4) {
            background: linear-gradient(135deg, #ececec 0%, #d8d8d8 100%);
        }

        .left-content {
            margin-top: 200px;
            color: rgb(255, 255, 255);
            z-index: 1;
        }

        .left-content h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            line-height: 1.2;
        }

        .left-content p {
            font-size: 1.1rem;
            opacity: 0.95;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            font-weight: 400;
        }

        .right-section {
            flex: 1;
            background: white;
            padding: 40px 60px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            position: relative;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 60px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 1.4rem;
            color: #333;
            width:120px;
        }


        .nav-buttons {
            display: flex;
            gap: 5px;
        }

        .nav-btn {
            padding: 10px 25px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-btn.active {
            background-color: #b8a894;
            color: white;
        }

        .nav-btn:not(.active) {
            background-color: transparent;
            color: #999;
        }

        .nav-btn:hover:not(.active) {
            background-color: #f5f5f5;
            color: #666;
        }

        .form-content {
            max-width: 400px;
            width: 100%;
        }

        .welcome-text {
            margin-bottom: 15px;
            color: #333;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .login-description {
            margin-bottom: 40px;
            color: #666;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 15px 20px;
            border: 1px solid #e0e0e0;
            border-radius: 25px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #fafafa;
            color: #333;
        }

        .form-group input::placeholder {
            color: #aaa;
        }

        .form-group input:focus {
            outline: none;
            border-color: #b8a894;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(184, 168, 148, 0.1);
        }

        .password-field {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            font-size: 1.1rem;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            margin: 0;
            accent-color: #b8a894;
        }

        .remember-me label {
            font-size: 0.9rem;
            color: #666;
            margin: 0;
        }

        .forgot-password {
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .forgot-password:hover {
            text-decoration: underline;
            color: #b8a894;
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background-color: #b8a894;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(184, 168, 148, 0.3);
        }

        .login-btn:hover {
            background-color: #a89784;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(184, 168, 148, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .left-section {
                min-height: 250px;
                padding: 20px;
            }
            
            .right-section {
                padding: 30px;
            }
            
            .header {
                flex-direction: column;
                gap: 20px;
                margin-bottom: 40px;
            }
            
            .left-content h1 {
                font-size: 1.8rem;
            }

            .image-container {
                padding: 40px 20px;
            }
        }
                .form-tab {
            display: none;
        }
        .form-tab.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="image-container">
                <div class="left-content">
                    <h1>Look Good, Spend Less</h1>
                    <p>UThrift Got You Covered</p>
                </div>
            </div>
        </div>
        <div class="right-section">
            <div class="header">
                <img class="logo" src="asset/logo.jpg" alt="">
                <div class="logo">UThrift</div>
                <div class="nav-buttons">
                    <button class="nav-btn active" onclick="showTab('login')">Login</button>
                    <button class="nav-btn" onclick="showTab('register')">Register</button>
                    <button class="nav-btn" onclick="showTab('admin')">Admin</button>
                </div>
            </div>
            <div class="form-content">
                <!-- Login Form -->
                <div id="login" class="form-tab active">
                    <div class="welcome-text">Welcome Back!</div>
                    <div class="login-description">Log in to continue exploring UThrift.</div>
                    <?php if (!empty($loginError)) echo "<p style='color:red;'>$loginError</p>"; ?>
                    <form method="POST" action="auth.php">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="password-field">
                                <input type="password" name="password" id="login_password" required>
                                <span class="password-toggle" onclick="togglePassword('login_password')">👁️</span>
                            </div>
                        </div>
                        <button type="submit" name="login" class="login-btn">Login</button>
                    </form>
                </div>

                <!-- Register Form -->
                <div id="register" class="form-tab">
                    <div class="welcome-text">Create an Account</div>
                    <div class="login-description">Join UThrift to start exploring thrift collections.</div>
                    <?php if (!empty($registerError)) echo "<p style='color:red;'>$registerError</p>"; ?>
                    <?php if (!empty($registerSuccess)) echo "<p style='color:green;'>$registerSuccess</p>"; ?>
                    <form method="POST" action="auth.php">
                        <div class="form-group">
                            <label for="reg_username">Username</label>
                            <input type="text" name="reg_username" required>
                        </div>
                        <div class="form-group">
                            <label for="reg_password">Password</label>
                            <div class="password-field">
                                <input type="password" name="reg_password" id="reg_password" required>
                                <span class="password-toggle" onclick="togglePassword('reg_password')">👁️</span>
                            </div>
                        </div>
                        <button type="submit" name="register" class="login-btn">Register</button>
                    </form>
                </div>

                <!-- Admin Login Form -->
                <div id="admin" class="form-tab">
                    <div class="welcome-text">Admin Login</div>
                    <div class="login-description">Log in to access admin dashboard.</div>
                    <?php if (!empty($adminError)) echo "<p style='color:red;'>$adminError</p>"; ?>
                    <form method="POST" action="auth.php">
                        <div class="form-group">
                            <label for="admin_user">Username</label>
                            <input type="text" name="admin_user" required>
                        </div>
                        <div class="form-group">
                            <label for="admin_pass">Password</label>
                            <div class="password-field">
                                <input type="password" name="admin_pass" id="admin_pass" required>
                                <span class="password-toggle" onclick="togglePassword('admin_pass')">👁️</span>
                            </div>
                        </div>
                        <button type="submit" name="login_admin" class="login-btn">Login as Admin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

   <script>
    // Fungsi untuk menampilkan tab tertentu
    function showTab(tabId) {
        document.querySelectorAll('.form-tab').forEach(tab => tab.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');

        document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('active'));
        event.currentTarget.classList.add('active');
    }

    // Menampilkan tab aktif pertama kali halaman dimuat
    document.addEventListener('DOMContentLoaded', function () {
        const activeTab = document.querySelector('.nav-btn.active');
        if (activeTab) {
            const tabId = activeTab.getAttribute('onclick').match(/'([^']+)'/)[1];
            showTab(tabId);
        }
    });
</script>
</body>
</html>