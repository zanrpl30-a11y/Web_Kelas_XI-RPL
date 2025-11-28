<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'config.php';

// Handle update struktur
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    
    if (isset($_FILES['photo']) && $_FILES['photo']['size'] > 0) {
        $photo = time() . '_' . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/' . $photo);
        $stmt = $conn->prepare("UPDATE class_structure SET name = ?, position = ?, photo = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $position, $photo, $id);
    } else {
        $stmt = $conn->prepare("UPDATE class_structure SET name = ?, position = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $position, $id);
    }
    
    if ($stmt->execute()) {
        $success = "✅ Data berhasil diupdate!";
    } else {
        $error = "❌ Gagal mengupdate data!";
    }
}

// Query data struktur
$result = $conn->query("SELECT * FROM class_structure ORDER BY 
    CASE position 
        WHEN 'Wali Kelas' THEN 1
        WHEN 'KM' THEN 2
        WHEN 'Wakil KM' THEN 3
        WHEN 'Sekretaris 1' THEN 4
        WHEN 'Sekretaris 2' THEN 5
        WHEN 'Bendahara 1' THEN 6
        WHEN 'Bendahara 2' THEN 7
        WHEN 'Humas' THEN 8
        WHEN 'Seksi Keamanan' THEN 9
        WHEN 'Seksi Kerohanian' THEN 10
        WHEN 'Seksi Upacara' THEN 11
        WHEN 'Seksi Kesehatan' THEN 12
        WHEN 'Seksi Olahraga' THEN 13
        WHEN 'Seksi Kewirausahaan' THEN 14
        WHEN 'Seksi Kesenian' THEN 15
        WHEN 'Seksi Absensi' THEN 16
        WHEN 'Anggota Kelas' THEN 17
        ELSE 18
    END, name");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Struktur Kelas - Admin</title>
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
                <a href="admin.php" class="nav-link">Admin</a>
                <a href="admin-structure.php" class="nav-link active"> Edit Struktur</a>
            </div>
            <div class="nav-actions">
                <a href="logout.php" class="btn logout-btn">
                    <span class="btn-icon">🚪</span>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="admin-container">
            <!-- Header -->
            <section class="admin-header">
                <div class="admin-user-info">
                    <h1>Edit Struktur Kelas</h1>
                    <p>Kelola data anggota dan pengurus kelas XI RPL</p>
                </div>
                
                <div class="admin-actions">
                    <a href="admin.php" class="btn secondary-btn">
                        <span class="btn-icon">←</span>
                        Kembali ke Admin
                    </a>
                    <a href="structure.php" class="btn primary-btn">
                        <span class="btn-icon">👁️</span>
                        Lihat Publik
                    </a>
                </div>
            </section>

            <!-- Notifications -->
            <?php if (isset($success)): ?>
                <div class="alert success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Structure Edit Grid -->
            <section class="admin-section full-width">
                <div class="section-header">
                    <h2>👥 Data Struktur Kelas</h2>
                    <p>Klik tombol "Edit" pada setiap anggota untuk mengubah data</p>
                </div>

                <div class="structure-edit-grid">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="structure-edit-item">
                            <div class="current-data">
                                <div class="structure-photo">
                                    <img src="uploads/<?php echo $row['photo'] ?: 'default-avatar.png'; ?>" 
                                         alt="<?php echo htmlspecialchars($row['name']); ?>"
                                         onerror="this.src='assets/default-avatar.png'">
                                </div>
                                <div class="structure-info">
                                    <h3 class="position"><?php echo htmlspecialchars($row['position']); ?></h3>
                                    <p class="name"><?php echo htmlspecialchars($row['name']); ?></p>
                                </div>
                                <button class="edit-toggle-btn" onclick="toggleEditForm(<?php echo $row['id']; ?>)">
                                    ✏️ Edit
                                </button>
                            </div>
                            
                            <form method="POST" enctype="multipart/form-data" class="structure-edit-form" id="form-<?php echo $row['id']; ?>" style="display: none;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                
                                <div class="form-group">
                                    <label>Jabatan:</label>
                                    <select name="position" class="form-select" required>
                                        <option value="Wali Kelas" <?php echo $row['position'] == 'Wali Kelas' ? 'selected' : ''; ?>>Wali Kelas</option>
                                        <option value="KM" <?php echo $row['position'] == 'KM' ? 'selected' : ''; ?>>Ketua Kelas (KM)</option>
                                        <option value="Wakil KM" <?php echo $row['position'] == 'Wakil KM' ? 'selected' : ''; ?>>Wakil Ketua Kelas</option>
                                        <option value="Sekretaris 1" <?php echo $row['position'] == 'Sekretaris 1' ? 'selected' : ''; ?>>Sekretaris 1</option>
                                        <option value="Sekretaris 2" <?php echo $row['position'] == 'Sekretaris 2' ? 'selected' : ''; ?>>Sekretaris 2</option>
                                        <option value="Bendahara 1" <?php echo $row['position'] == 'Bendahara 1' ? 'selected' : ''; ?>>Bendahara 1</option>
                                        <option value="Bendahara 2" <?php echo $row['position'] == 'Bendahara 2' ? 'selected' : ''; ?>>Bendahara 2</option>
                                        <option value="Humas" <?php echo $row['position'] == 'Humas' ? 'selected' : ''; ?>>Humas</option>
                                        <option value="Seksi Keamanan" <?php echo $row['position'] == 'Seksi Keamanan' ? 'selected' : ''; ?>>Seksi Keamanan</option>
                                        <option value="Seksi Kerohanian" <?php echo $row['position'] == 'Seksi Kerohanian' ? 'selected' : ''; ?>>Seksi Kerohanian</option>
                                        <option value="Seksi Upacara" <?php echo $row['position'] == 'Seksi Upacara' ? 'selected' : ''; ?>>Seksi Upacara</option>
                                        <option value="Seksi Kesehatan" <?php echo $row['position'] == 'Seksi Kesehatan' ? 'selected' : ''; ?>>Seksi Kesehatan</option>
                                        <option value="Seksi Olahraga" <?php echo $row['position'] == 'Seksi Olahraga' ? 'selected' : ''; ?>>Seksi Olahraga</option>
                                        <option value="Seksi Kewirausahaan" <?php echo $row['position'] == 'Seksi Kewirausahaan' ? 'selected' : ''; ?>>Seksi Kewirausahaan</option>
                                        <option value="Seksi Kesenian" <?php echo $row['position'] == 'Seksi Kesenian' ? 'selected' : ''; ?>>Seksi Kesenian</option>
                                        <option value="Seksi Absensi" <?php echo $row['position'] == 'Seksi Absensi' ? 'selected' : ''; ?>>Seksi Absensi</option>
                                        <option value="Anggota Kelas" <?php echo $row['position'] == 'Anggota Kelas' ? 'selected' : ''; ?>>Anggota Kelas</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label>Nama:</label>
                                    <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" 
                                           class="form-input" placeholder="Nama Lengkap" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Foto:</label>
                                    <input type="file" name="photo" accept="image/*" class="form-input file-input">
                                    <small class="form-help">Kosongkan jika tidak ingin mengubah foto</small>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="btn primary-btn small-btn">💾 Update</button>
                                    <button type="button" onclick="toggleEditForm(<?php echo $row['id']; ?>)" class="btn cancel-btn small-btn">❌ Batal</button>
                                </div>
                            </form>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
        </div>
    </main>

    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top">
        <span>↑</span>
    </button>

    <script>
    function toggleEditForm(id) {
        const form = document.getElementById('form-' + id);
        const currentData = form.previousElementSibling;
        
        if (form.style.display === 'none') {
            form.style.display = 'block';
            currentData.style.display = 'none';
        } else {
            form.style.display = 'none';
            currentData.style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Back to top button
        const backToTop = document.getElementById('backToTop');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });

        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
    </script>

    <script src="assets/script.js"></script>
</body>
</html>