<?php
session_start();
include 'config.php';

// Ambil data profile kelas
$profile_result = $conn->query("
    SELECT username, profile_pic, bio 
    FROM users 
    WHERE role = 'admin' 
    LIMIT 1
");
$profile = $profile_result->fetch_assoc();

// Ambil posts dengan like dan comment count
$result = $conn->query("
    SELECT p.*, u.username, u.profile_pic,
           (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) as like_count,
           (SELECT COUNT(*) FROM post_comments WHERE post_id = p.id) as comment_count
    FROM posts p 
    LEFT JOIN users u ON p.user_id = u.id 
    ORDER BY p.created_at DESC
");

$total_posts = $conn->query("SELECT COUNT(*) as total FROM posts")->fetch_assoc()['total'];
$total_members = $conn->query("SELECT COUNT(*) as total FROM class_structure")->fetch_assoc()['total'];

// Ambil beberapa anggota untuk ditampilkan di hero section
$members_result = $conn->query("
    SELECT * FROM class_structure 
    WHERE position IN ('Wali Kelas', 'KM', 'Wakil KM') 
    ORDER BY 
        CASE position 
            WHEN 'Wali Kelas' THEN 1
            WHEN 'KM' THEN 2
            ELSE 4
        END
    LIMIT 2
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>XI RPL - SMKN 2 PURWAKARTA</title>
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
                <a href="#home" class="nav-link active"> Beranda</a>
                <a href="#about" class="nav-link"> Tentang</a>
                <a href="#activities" class="nav-link"> Aktivitas</a>
                <a href="structure.php" class="nav-link"> Struktur</a>
            </div>
            <div class="nav-actions">
                <a href="login.php" class="btn admin-btn nav-admin-btn">
                    <span class="btn-icon">🔐</span>
                    Admin
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <span class="badge-text">🎓 Kelas Rekayasa Perangkat Lunak</span>
                </div>
                <h1 class="hero-title">
                    <span class="title-gradient">XI RPL</span>
                    <span class="title-sub">SMKN 2 PURWAKARTA</span>
                </h1>
                <p class="hero-description">
                    <?php echo htmlspecialchars($profile['bio'] ?? 'Menjadi generasi digital yang kreatif, inovatif, dan siap menghadapi tantangan teknologi masa depan.'); ?>
                </p>
                
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="stat-number"><?php echo $total_members; ?>+</div>
                        <div class="stat-label">Anggota</div>
                    </div>
                    <div class="hero-stat">
                        <div class="stat-number"><?php echo $total_posts; ?>+</div>
                        <div class="stat-label">Aktivitas</div>
                    </div>
                    <div class="hero-stat">
                        <div class="stat-number">2027</div>
                        <div class="stat-label">Angkatan</div>
                    </div>
                </div>

                <div class="hero-actions">
                    <a href="#activities" class="btn primary-btn hero-btn">
                        <span class="btn-icon">📢</span>
                        Lihat Aktivitas
                    </a>
                    <a href="structure.php" class="btn secondary-btn hero-btn">
                        <span class="btn-icon">👥</span>
                        Struktur Kelas
                    </a>
                </div>
            </div>

            <div class="hero-visual">
                <div class="floating-cards">
                    <?php while ($member = $members_result->fetch_assoc()): ?>
                        <div class="floating-card">
                            <div class="member-avatar">
                                <img src="uploads/<?php echo $member['photo'] ?: 'default-avatar.png'; ?>" 
                                     alt="<?php echo htmlspecialchars($member['name']); ?>"
                                     onerror="this.src='assets/default-avatar.png'">
                            </div>
                            <div class="member-info">
                                <div class="member-name"><?php echo htmlspecialchars($member['name']); ?></div>
                                <div class="member-role"><?php echo htmlspecialchars($member['position']); ?></div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="hero-glow"></div>
            </div>
        </div>
        
        <div class="scroll-indicator">
            <div class="scroll-arrow"></div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Tentang Kelas Kami</h2>
                <p class="section-subtitle">Mengenal lebih dekat dengan keluarga besar XI RPL</p>
            </div>

            <div class="about-grid">
                <div class="about-card">
                    <div class="about-icon">🎯</div>
                    <h3>Visi Kami</h3>
                    <p>Menjadi kelas yang unggul dalam bidang teknologi informasi, berkarakter kuat, dan berkontribusi positif untuk sekolah dan masyarakat.</p>
                </div>
                
                <div class="about-card">
                    <div class="about-icon">🚀</div>
                    <h3>Misi Kami</h3>
                    <p>Mengembangkan kemampuan programming, teamwork, dan leadership melalui berbagai project dan kegiatan kolaboratif.</p>
                </div>
                
                <div class="about-card">
                    <div class="about-icon">💡</div>
                    <h3>Nilai Kami</h3>
                    <p>Kreativitas, Inovasi, Kolaborasi, Tanggung Jawab, dan Continuous Learning dalam setiap aspek pembelajaran.</p>
                </div>
            </div>

            <div class="skills-section">
                <h3 class="skills-title">Keahlian yang Kami Pelajari</h3>
                <div class="skills-grid">
                    <div class="skill-item">
                        <div class="skill-icon">🌐</div>
                        <span class="skill-name">Web Development</span>
                    </div>
                    <div class="skill-item">
                        <div class="skill-icon">📱</div>
                        <span class="skill-name">Mobile Apps</span>
                    </div>
                    <div class="skill-item">
                        <div class="skill-icon">🎨</div>
                        <span class="skill-name">UI/UX Design</span>
                    </div>
                    <div class="skill-item">
                        <div class="skill-icon">🗄️</div>
                        <span class="skill-name">Database</span>
                    </div>
                    <div class="skill-item">
                        <div class="skill-icon">🔧</div>
                        <span class="skill-name">Software Engineering</span>
                    </div>
                    <div class="skill-item">
                        <div class="skill-icon">☁️</div>
                        <span class="skill-name">Cloud Computing</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Activities Section -->
    <section id="activities" class="activities-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Aktivitas Terbaru</h2>
                <p class="section-subtitle">Ikuti perkembangan dan kegiatan terbaru kelas kami</p>
            </div>

            <?php if ($result->num_rows > 0): ?>
                <div class="posts-grid">
                    <?php while ($post = $result->fetch_assoc()): ?>
                        <article class="post-card" id="post-<?php echo $post['id']; ?>">
                            <div class="post-header">
                                <div class="post-author">
                                    <img src="uploads/<?php echo $post['profile_pic'] ?? 'default-avatar.png'; ?>" 
                                         alt="<?php echo htmlspecialchars($post['username']); ?>"
                                         class="author-avatar"
                                         onerror="this.src='assets/default-avatar.png'">
                                    <div class="author-info">
                                        <span class="author-name"><?php echo htmlspecialchars($post['username']); ?></span>
                                        <span class="post-date"><?php echo date('d M Y • H:i', strtotime($post['created_at'])); ?></span>
                                    </div>
                                </div>
                                <div class="post-type-badge">
                                    <?php echo $post['type'] == 'photo' ? '📷 Foto' : '🎥 Video'; ?>
                                </div>
                            </div>
                            
                            <?php if ($post['type'] == 'photo'): ?>
                                <div class="post-media">
                                    <img src="<?php echo $post['file_path']; ?>" 
                                         alt="Post Image" 
                                         class="post-image"
                                         onerror="this.style.display='none'">
                                </div>
                            <?php else: ?>
                                <div class="post-media">
                                    <video controls class="post-video">
                                        <source src="<?php echo $post['file_path']; ?>" type="video/mp4">
                                        Browser tidak mendukung video.
                                    </video>
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-content">
                                <p class="post-description"><?php echo htmlspecialchars($post['description']); ?></p>
                                
                                <div class="post-stats">
                                    <span class="like-count" id="like-count-<?php echo $post['id']; ?>">
                                        <?php echo $post['like_count']; ?> suka
                                    </span>
                                    <span class="comment-count" id="comment-count-<?php echo $post['id']; ?>">
                                        <?php echo $post['comment_count']; ?> komentar
                                    </span>
                                </div>
                                
                                <div class="post-actions">
                                    <button type="button" class="action-btn like-btn" onclick="toggleLike(<?php echo $post['id']; ?>)" id="like-btn-<?php echo $post['id']; ?>">
                                        <span class="like-icon">🤍</span>
                                        <span>Suka</span>
                                    </button>
                                    <button type="button" class="action-btn comment-btn" onclick="focusComment(<?php echo $post['id']; ?>)">
                                        <span class="comment-icon">💬</span>
                                        <span>Komentar</span>
                                    </button>
                                    <button type="button" class="action-btn share-btn" onclick="sharePost(<?php echo $post['id']; ?>)">
                                        <span class="share-icon">🔄</span>
                                        <span>Bagikan</span>
                                    </button>
                                </div>
                                
                                                                <div class="comments-section">
                                    <div class="comments-container" id="comments-<?php echo $post['id']; ?>">
                                        <!-- Comments akan dimuat via AJAX -->
                                    </div>
                                    
                                    <div class="comment-form" id="comment-form-<?php echo $post['id']; ?>">
                                        <div class="comment-input-group">
                                            <input type="text" 
                                                   id="comment-name-<?php echo $post['id']; ?>" 
                                                   class="comment-name-input" 
                                                   placeholder="Nama Anda" 
                                                   required>
                                            <div class="comment-text-wrapper">
                                                <textarea 
                                                    id="comment-text-<?php echo $post['id']; ?>" 
                                                    class="comment-text-input" 
                                                    placeholder="Tulis komentar..." 
                                                    rows="1"
                                                    required></textarea>
                                                <button type="button" class="comment-submit-btn" onclick="submitComment(<?php echo $post['id']; ?>)">
                                                    <span class="send-icon">➤</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="no-posts">
                    <div class="no-posts-illustration">
                        <div class="illustration-icon">📝</div>
                        <div class="illustration-glow"></div>
                    </div>
                    <h3>Belum ada aktivitas</h3>
                    <p>Admin belum mengupload konten apapun. Nantikan update terbaru dari kelas kami!</p>
                    <a href="#home" class="btn primary-btn">Kembali ke Beranda</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <span class="logo-icon">💻</span>
                        <span class="logo-text">XI RPL</span>
                    </div>
                    <p class="footer-description">
                        Rekayasa Perangkat Lunak<br>
                        SMKN 2 PURWAKARTA<br>
                        Angkatan 2027
                    </p>
                </div>
                
                <div class="footer-links">
                    <div class="footer-column">
                        <h4>Navigasi</h4>
                        <a href="#home" class="footer-link">Beranda</a>
                        <a href="#about" class="footer-link">Tentang</a>
                        <a href="#activities" class="footer-link">Aktivitas</a>
                        <a href="structure.php" class="footer-link">Struktur</a>
                    </div>
                    
                    <div class="footer-column">
                        <h4>Kontak</h4>
                        <a href="mailto:contact@example.com" class="footer-link">✉️ Email</a>
                        <a href="https://maps.google.com" class="footer-link">📍 Lokasi</a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 XI RPL SMKN 2 PURWAKARTA. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top">
        <span>↑</span>
    </button>

    <!-- INLINE JAVASCRIPT - SIMPLE & WORKING -->
    <script>
    console.log('✅ Loading inline JavaScript...');

    // Like function
    function toggleLike(postId) {
        console.log('❤️ toggleLike called for post:', postId);
        
        const userName = prompt('Masukkan nama Anda:');
        if (!userName || userName.trim() === '') {
            alert('Nama tidak boleh kosong!');
            return;
        }

        // Show loading
        const likeBtn = document.getElementById('like-btn-' + postId);
        likeBtn.innerHTML = '<span class="like-icon">⏳</span><span>Loading...</span>';
        likeBtn.disabled = true;

        fetch('action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=like&post_id=' + postId + '&user_name=' + encodeURIComponent(userName)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Like response:', data);
            
            if (data.success) {
                // Update like count
                document.getElementById('like-count-' + postId).textContent = data.like_count + ' suka';
                
                // Update button
                if (data.liked) {
                    likeBtn.innerHTML = '<span class="like-icon">❤️</span><span>Disukai</span>';
                    likeBtn.style.color = '#ff4444';
                } else {
                    likeBtn.innerHTML = '<span class="like-icon">🤍</span><span>Suka</span>';
                    likeBtn.style.color = '#88ffff';
                }
                
                alert('✅ Like berhasil!');
            } else {
                alert('❌ Error: ' + data.error);
                likeBtn.innerHTML = '<span class="like-icon">🤍</span><span>Suka</span>';
            }
            likeBtn.disabled = false;
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan');
            likeBtn.innerHTML = '<span class="like-icon">🤍</span><span>Suka</span>';
            likeBtn.disabled = false;
        });
    }

    // Comment function
    function submitComment(postId) {
        console.log('💬 submitComment called for post:', postId);
        
        const userName = document.getElementById('comment-name-' + postId).value.trim();
        const commentText = document.getElementById('comment-text-' + postId).value.trim();

        if (!userName) {
            alert('Silakan masukkan nama Anda!');
            return;
        }

        if (!commentText) {
            alert('Silakan tulis komentar Anda!');
            return;
        }

        // Show loading
        const submitBtn = document.querySelector('#comment-form-' + postId + ' .comment-submit-btn');
        submitBtn.innerHTML = '⏳';
        submitBtn.disabled = true;

        fetch('action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=comment&post_id=' + postId + '&user_name=' + encodeURIComponent(userName) + '&comment=' + encodeURIComponent(commentText)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Comment response:', data);
            
            if (data.success) {
                // Clear form
                document.getElementById('comment-text-' + postId).value = '';
                
                // Update comment count
                document.getElementById('comment-count-' + postId).textContent = data.comment_count + ' komentar';
                
                // Add comment to display
                const commentsContainer = document.getElementById('comments-' + postId);
                const commentElement = document.createElement('div');
                commentElement.className = 'comment';
                commentElement.innerHTML = `
                    <div class="comment-header">
                        <strong class="comment-author">${data.comment.user_name}</strong>
                        <small class="comment-time">${data.comment.created_at}</small>
                    </div>
                    <div class="comment-text">${data.comment.comment}</div>
                `;
                commentsContainer.appendChild(commentElement);
                
                alert('✅ Komentar berhasil dikirim!');
            } else {
                alert('❌ Error: ' + data.error);
            }
            submitBtn.innerHTML = '<span class="send-icon">➤</span>';
            submitBtn.disabled = false;
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan');
            submitBtn.innerHTML = '<span class="send-icon">➤</span>';
            submitBtn.disabled = false;
        });
    }

    // Focus comment function
    function focusComment(postId) {
        const nameInput = document.getElementById('comment-name-' + postId);
        if (nameInput && !nameInput.value) {
            nameInput.focus();
        } else {
            document.getElementById('comment-text-' + postId).focus();
        }
        
        // Load comments
        loadComments(postId);
    }

    // Share function
    function sharePost(postId) {
        const shareUrl = window.location.href.split('#')[0] + '#post-' + postId;
        navigator.clipboard.writeText(shareUrl).then(() => {
            alert('✅ Link berhasil disalin:\n' + shareUrl);
        });
    }

    // Load comments function
    function loadComments(postId) {
        fetch('action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=load_comments&post_id=' + postId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const commentsContainer = document.getElementById('comments-' + postId);
                commentsContainer.innerHTML = '';
                
                if (data.comments.length > 0) {
                    data.comments.forEach(comment => {
                        const commentElement = document.createElement('div');
                        commentElement.className = 'comment';
                        commentElement.innerHTML = `
                            <div class="comment-header">
                                <strong class="comment-author">${comment.user_name}</strong>
                                <small class="comment-time">${comment.created_at}</small>
                            </div>
                            <div class="comment-text">${comment.comment}</div>
                        `;
                        commentsContainer.appendChild(commentElement);
                    });
                } else {
                    commentsContainer.innerHTML = '<p class="no-comments">Belum ada komentar</p>';
                }
            }
        })
        .catch(error => console.error('Error loading comments:', error));
    }

    // Auto-resize textarea
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('comment-text-input')) {
            e.target.style.height = 'auto';
            e.target.style.height = (e.target.scrollHeight) + 'px';
        }
    });

    console.log('✅ All functions loaded successfully!');
    </script>
</body>
</html>