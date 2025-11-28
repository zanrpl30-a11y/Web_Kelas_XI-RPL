<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];
            header("Location: admin.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - XI RPL</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="bg-particle"></div>
        <div class="bg-particle"></div>
        <div class="bg-particle"></div>
    </div>
    
    <!-- Navigation -->
    <nav class="main-nav">
        <div class="nav-container">
            <div class="nav-brand">
                <div class="logo">
                    <span class="logo-icon">💻</span>
                    <span class="logo-text">XI RPL</span>
                </div>
            </div>
            <div class="nav-links">
                <a href="index.php" class="nav-link"> Beranda</a>
                <a href="structure.php" class="nav-link"> Struktur</a>
            </div>
        </div>
    </nav>

    <!-- Login Section -->
    <section class="login-section">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <div class="login-logo">
                        <span class="logo-icon">🔐</span>
                        <h1>Admin Login</h1>
                    </div>
                    <p class="login-subtitle">XI RPL - SMKN 2 PURWAKARTA</p>
                </div>
                
                <?php if (isset($error)): ?>
                    <div class="alert error">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="login-form">
                    <div class="form-group">
                        <label>👤 Username</label>
                        <input type="text" name="username" placeholder="Masukkan username Anda" required class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label>🔒 Password</label>
                        <input type="password" name="password" placeholder="Masukkan password Anda" required class="form-input">
                    </div>
                    
                    <button type="submit" class="btn primary-btn large-btn login-btn">
                        🚀 Login
                    </button>
                </form>
                
                <div class="login-footer">
                    <a href="index.php" class="back-link">
                        <span class="back-icon">←</span>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
            
            <div class="login-decoration">
                <div class="decoration-item">
                    <div class="decoration-icon">💻</div>
                    <h3>Admin Panel</h3>
                    <p>Kelola konten dan struktur kelas dengan mudah</p>
                </div>
                <div class="decoration-glow"></div>
            </div>
        </div>
    </section>

    <script src="assets/script.js"></script>
</body>
</html>