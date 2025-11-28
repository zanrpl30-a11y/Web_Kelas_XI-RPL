<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'config.php';

// Handle upload post
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $type = $_POST['type'];
    $description = $_POST['description'];
    $file = $_FILES['file'];
    $fileName = time() . '_' . basename($file['name']);
    $filePath = 'uploads/' . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, type, file_path, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $_SESSION['user_id'], $type, $filePath, $description);
        if ($stmt->execute()) {
            $success = "✅ Post berhasil diupload!";
        } else {
            $error = "❌ Gagal menyimpan post!";
        }
    } else {
        $error = "❌ Gagal upload file!";
    }
}

// Handle update profile
if (isset($_POST['update_profile'])) {
    $profilePic = time() . '_' . $_FILES['profile_pic']['name'];
    $bio = $_POST['bio'] ?? '';
    
    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], 'uploads/' . $profilePic)) {
        $stmt = $conn->prepare("UPDATE users SET profile_pic = ?, bio = ? WHERE id = ?");
        $stmt->bind_param("ssi", $profilePic, $bio, $_SESSION['user_id']);
        if ($stmt->execute()) {
            $success_profile = "✅ Profile berhasil diupdate!";
        } else {
            $error_profile = "❌ Gagal update profile!";
        }
    } else {
        $error_profile = "❌ Gagal upload gambar!";
    }
}

// Handle delete post
if (isset($_GET['delete_post'])) {
    $post_id = $_GET['delete_post'];
    
    // Hapus likes dan comments terkait dulu (karena foreign key constraint)
    $conn->query("DELETE FROM post_likes WHERE post_id = $post_id");
    $conn->query("DELETE FROM post_comments WHERE post_id = $post_id");
    
    // Dapatkan file path untuk menghapus file fisik
    $post_result = $conn->query("SELECT file_path FROM posts WHERE id = $post_id");
    if ($post_result->num_rows > 0) {
        $post = $post_result->fetch_assoc();
        $file_path = $post['file_path'];
        
        // Hapus file fisik jika ada
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    // Hapus post dari database
    if ($conn->query("DELETE FROM posts WHERE id = $post_id")) {
        $success_delete = "✅ Post berhasil dihapus!";
    } else {
        $error_delete = "❌ Gagal menghapus post!";
    }
}

// Get user info
$user_result = $conn->query("SELECT username, profile_pic, bio FROM users WHERE id = " . $_SESSION['user_id']);
$user = $user_result->fetch_assoc();

// Get all posts untuk management
$posts_result = $conn->query("
    SELECT p.*, u.username,
           (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) as like_count,
           (SELECT COUNT(*) FROM post_comments WHERE post_id = p.id) as comment_count
    FROM posts p 
    LEFT JOIN users u ON p.user_id = u.id 
    ORDER BY p.created_at DESC
");

// Get stats
$total_posts = $conn->query("SELECT COUNT(*) as total FROM posts")->fetch_assoc()['total'];
$total_members = $conn->query("SELECT COUNT(*) as total FROM class_structure")->fetch_assoc()['total'];
$total_comments = $conn->query("SELECT COUNT(*) as total FROM post_comments")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - XI RPL</title>
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
                <a href="admin.php" class="nav-link active"> Admin</a>
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
            <!-- Admin Header -->
            <section class="admin-header">
                <div class="admin-user-info">
                    <img src="uploads/<?php echo $user['profile_pic'] ?? 'default-avatar.png'; ?>" 
                         alt="Profile" 
                         class="admin-avatar"
                         onerror="this.src='assets/default-avatar.png'">
                    <div class="admin-user-details">
                        <h1>Admin Panel</h1>
                        <p>Halo, <strong><?php echo htmlspecialchars($user['username']); ?></strong>! 👋</p>
                        <span class="admin-badge">Administrator</span>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="admin-stats-grid">
                    <div class="admin-stat-card">
                        <div class="stat-icon">📝</div>
                        <div class="stat-content">
                            <div class="stat-number"><?php echo $total_posts; ?></div>
                            <div class="stat-label">Total Posts</div>
                        </div>
                    </div>
                    <div class="admin-stat-card">
                        <div class="stat-icon">👥</div>
                        <div class="stat-content">
                            <div class="stat-number"><?php echo $total_members; ?></div>
                            <div class="stat-label">Anggota</div>
                        </div>
                    </div>
                    <div class="admin-stat-card">
                        <div class="stat-icon">💬</div>
                        <div class="stat-content">
                            <div class="stat-number"><?php echo $total_comments; ?></div>
                            <div class="stat-label">Komentar</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Quick Navigation -->
            <nav class="admin-quick-nav">
                <a href="index.php" class="quick-nav-btn">
                    <span class="nav-icon">👀</span>
                    <span class="nav-text">Lihat Website</span>
                </a>
                <a href="admin-structure.php" class="quick-nav-btn">
                    <span class="nav-icon">👥</span>
                    <span class="nav-text">Kelola Struktur</span>
                </a>
                <a href="#manage-posts" class="quick-nav-btn">
                    <span class="nav-icon">📋</span>
                    <span class="nav-text">Kelola Postingan</span>
                </a>
            </nav>

            <!-- Notifications -->
            <?php if (isset($success)): ?>
                <div class="alert success"><?php echo $success; ?></div>
            <?php elseif (isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (isset($success_delete)): ?>
                <div class="alert success"><?php echo $success_delete; ?></div>
            <?php elseif (isset($error_delete)): ?>
                <div class="alert error"><?php echo $error_delete; ?></div>
            <?php endif; ?>

            <!-- Admin Sections -->
            <div class="admin-sections-grid">
                <!-- Update Profile Section -->
                <section class="admin-section">
                    <div class="section-header">
                        <h2>🔄 Update Profile Kelas</h2>
                        <p>Ubah foto profil dan deskripsi kelas yang ditampilkan di halaman utama</p>
                    </div>
                    
                    <?php if (isset($success_profile)): ?>
                        <div class="alert success"><?php echo $success_profile; ?></div>
                    <?php elseif (isset($error_profile)): ?>
                        <div class="alert error"><?php echo $error_profile; ?></div>
                    <?php endif; ?>
                    
                    <div class="current-profile-preview">
                        <div class="preview-card">
                            <h4>Preview Profile Saat Ini:</h4>
                            <div class="preview-content">
                                <img src="uploads/<?php echo $user['profile_pic'] ?? 'default-class.png'; ?>" 
                                     alt="Current Profile" 
                                     class="preview-profile-pic"
                                     onerror="this.src='assets/default-class.png'">
                                <div class="preview-info">
                                    <h5>XI RPL</h5>
                                    <p class="preview-bio"><?php echo htmlspecialchars($user['bio'] ?? 'Belum ada deskripsi kelas'); ?></p>
                                    <small>Foto ini akan tampil di halaman utama</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" enctype="multipart/form-data" class="admin-form">
                        <div class="form-group">
                            <label>📷 Foto Profile Kelas:</label>
                            <input type="file" name="profile_pic" accept="image/*" class="form-input file-input" required>
                            <small class="form-help">Rekomendasi: format JPG/PNG, ukuran 1:1 (persegi)</small>
                        </div>
                        
                        <div class="form-group">
                            <label>📝 Deskripsi Kelas (Bio):</label>
                            <textarea name="bio" placeholder="Tulis deskripsi singkat tentang kelas XI RPL..." 
                                      class="form-textarea" 
                                      maxlength="200"
                                      rows="3"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                            <small class="form-help">Maksimal 200 karakter. Deskripsi akan tampil di halaman utama.</small>
                        </div>
                        
                        <button type="submit" name="update_profile" class="btn primary-btn large-btn">
                            💾 Update Profile
                        </button>
                    </form>
                </section>

                <!-- Upload Post Section -->
                <section class="admin-section">
                    <div class="section-header">
                        <h2>📤 Upload Post Baru</h2>
                        <p>Buat postingan baru untuk dibagikan ke halaman utama</p>
                    </div>
                    
                    <form method="POST" enctype="multipart/form-data" class="admin-form">
                        <div class="form-group">
                            <label>🎯 Tipe Konten:</label>
                            <select name="type" class="form-select" required>
                                <option value="photo">📷 Foto/Gambar</option>
                                <option value="video">🎥 Video</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>📁 Pilih File:</label>
                            <input type="file" name="file" required class="form-input file-input">
                            <small class="form-help">Format: JPG, PNG, MP4. Maksimal 10MB</small>
                        </div>
                        
                        <div class="form-group">
                            <label>💬 Deskripsi Post:</label>
                            <textarea name="description" placeholder="Tulis deskripsi atau caption untuk post ini..." 
                                      class="form-textarea" 
                                      rows="4"></textarea>
                        </div>
                        
                        <button type="submit" class="btn primary-btn large-btn">
                            🚀 Upload Post
                        </button>
                    </form>
                </section>

                <!-- Manage Posts Section -->
                <section id="manage-posts" class="admin-section full-width">
                    <div class="section-header">
                        <h2>📋 Kelola Postingan</h2>
                        <p>Kelola semua postingan yang sudah diupload</p>
                    </div>

                    <?php if ($posts_result->num_rows > 0): ?>
                        <div class="posts-management">
                            <div class="management-header">
                                <span>Total: <?php echo $posts_result->num_rows; ?> postingan</span>
                            </div>
                            
                            <div class="posts-list">
                                <?php while ($post = $posts_result->fetch_assoc()): ?>
                                    <div class="post-management-item">
                                        <div class="post-preview">
                                            <?php if ($post['type'] == 'photo'): ?>
                                                <img src="<?php echo $post['file_path']; ?>" 
                                                     alt="Post preview" 
                                                     class="post-thumbnail"
                                                     onerror="this.src='assets/default-image.png'">
                                            <?php else: ?>
                                                <div class="video-thumbnail">
                                                    <span>🎥 Video</span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="post-details">
                                                <h4><?php echo htmlspecialchars($post['description'] ?: 'Tidak ada deskripsi'); ?></h4>
                                                <div class="post-meta">
                                                    <span class="post-type"><?php echo $post['type'] == 'photo' ? '📷 Foto' : '🎥 Video'; ?></span>
                                                    <span class="post-stats">❤️ <?php echo $post['like_count']; ?> • 💬 <?php echo $post['comment_count']; ?></span>
                                                    <span class="post-date"><?php echo date('d M Y H:i', strtotime($post['created_at'])); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="post-actions">
                                            <a href="index.php#post-<?php echo $post['id']; ?>" 
                                               target="_blank" 
                                               class="btn view-btn small-btn">
                                               👀 Lihat
                                            </a>
                                            <button onclick="confirmDelete(<?php echo $post['id']; ?>)" 
                                                    class="btn delete-btn small-btn">
                                               🗑️ Hapus
                                            </button>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="no-posts-management">
                            <div class="no-posts-icon">📝</div>
                            <h3>Belum ada postingan</h3>
                            <p>Upload postingan pertama Anda di section "Upload Post Baru"</p>
                        </div>
                    <?php endif; ?>
                </section>
            </div>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3>🗑️ Konfirmasi Hapus</h3>
            <p>Apakah Anda yakin ingin menghapus postingan ini?</p>
            <p class="warning-text">Tindakan ini tidak dapat dibatalkan! Semua like dan komentar pada postingan ini juga akan terhapus.</p>
            <div class="modal-actions">
                <button onclick="closeDeleteModal()" class="btn cancel-btn">❌ Batal</button>
                <button onclick="deletePost()" class="btn delete-confirm-btn">🗑️ Ya, Hapus</button>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top">
        <span>↑</span>
    </button>

    <script>
    let postToDelete = null;

    function confirmDelete(postId) {
        postToDelete = postId;
        document.getElementById('deleteModal').style.display = 'block';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        postToDelete = null;
    }

    function deletePost() {
        if (postToDelete) {
            window.location.href = 'admin.php?delete_post=' + postToDelete;
        }
    }

    // Modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('deleteModal');
        const closeBtn = document.querySelector('.close-modal');
        
        if (closeBtn) {
            closeBtn.onclick = function() {
                modal.style.display = 'none';
            }
        }
        
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

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