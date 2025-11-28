<?php
session_start();
include 'config.php';

// Query dengan urutan hierarki yang benar - Anggota di paling bawah
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
        WHEN 'Anggota Kelas' THEN 99
        ELSE 100
    END, name");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struktur Kelas - XI RPL</title>
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
                <a href="index.php" class="nav-link">Beranda</a>
                <a href="structure.php" class="nav-link active">Struktur</a>
                <a href="login.php" class="nav-link">Admin</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="structure-main">
        <div class="container">
            <!-- Header -->
            <section class="structure-header">
                <div class="section-header">
                    <h1 class="section-title">Struktur Kelas XI RPL</h1>
                    <p class="section-subtitle">Organisasi dan anggota kelas Rekayasa Perangkat Lunak</p>
                </div>
                
                <div class="structure-stats">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $result->num_rows; ?></div>
                        <div class="stat-label">Total Anggota</div>
                    </div>
                </div>
            </section>

            <!-- Structure Organization Chart -->
            <section class="org-chart">
                <?php
                // Group by position untuk hierarki - Anggota dipisahkan
                $positions = [
                    'Wali Kelas' => [],
                    'KM' => [],
                    'Wakil KM' => [],
                    'Sekretaris 1' => [],
                    'Sekretaris 2' => [],
                    'Bendahara 1' => [],
                    'Bendahara 2' => [],
                    'Humas' => [],
                    'Seksi Keamanan' => [],
                    'Seksi Kerohanian' => [],
                    'Seksi Upacara' => [],
                    'Seksi Kesehatan' => [],
                    'Seksi Olahraga' => [],
                    'Seksi Kewirausahaan' => [],
                    'Seksi Kesenian' => [],
                    'Seksi Absensi' => []
                ];
                
                $anggota_kelas = []; // Pisahkan anggota kelas
                
                // Reset pointer result
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    if ($row['position'] == 'Anggota Kelas') {
                        $anggota_kelas[] = $row;
                    } elseif (isset($positions[$row['position']])) {
                        $positions[$row['position']][] = $row;
                    }
                }
                ?>

                <!-- Level 1: Wali Kelas (Paling Atas) -->
                <?php if (!empty($positions['Wali Kelas'])): ?>
                    <div class="org-level level-1">
                        <div class="level-header">
                            <h2 class="level-title">Wali Kelas</h2>
                            <div class="level-connector"></div>
                        </div>
                        <div class="level-members">
                            <?php foreach ($positions['Wali Kelas'] as $member): ?>
                                <div class="org-member wali-kelas">
                                    <div class="member-avatar">
                                        <img src="uploads/<?php echo $member['photo'] ?: 'default-avatar.png'; ?>" 
                                             alt="<?php echo htmlspecialchars($member['name']); ?>"
                                             onerror="this.src='assets/default-avatar.png'">
                                        <div class="avatar-glow"></div>
                                    </div>
                                    <div class="member-info">
                                        <h3 class="member-name"><?php echo htmlspecialchars($member['name']); ?></h3>
                                        <p class="member-position"><?php echo htmlspecialchars($member['position']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Level 2: Pimpinan Kelas (KM & Wakil KM) -->
                <?php if (!empty($positions['KM']) || !empty($positions['Wakil KM'])): ?>
                    <div class="org-level level-2">
                        <div class="level-header">
                            <h2 class="level-title">Pimpinan Kelas</h2>
                            <div class="level-connector"></div>
                        </div>
                        <div class="level-members leadership-team">
                            <!-- KM -->
                            <?php if (!empty($positions['KM'])): ?>
                                <?php foreach ($positions['KM'] as $member): ?>
                                    <div class="org-member km">
                                        <div class="member-connector top"></div>
                                        <div class="member-avatar">
                                            <img src="uploads/<?php echo $member['photo'] ?: 'default-avatar.png'; ?>" 
                                                 alt="<?php echo htmlspecialchars($member['name']); ?>"
                                                 onerror="this.src='assets/default-avatar.png'">
                                            <div class="avatar-glow"></div>
                                        </div>
                                        <div class="member-info">
                                            <h3 class="member-name"><?php echo htmlspecialchars($member['name']); ?></h3>
                                            <p class="member-position">Ketua Kelas</p>
                                        </div>
                                        <div class="member-connector bottom"></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <!-- Wakil KM -->
                            <?php if (!empty($positions['Wakil KM'])): ?>
                                <?php foreach ($positions['Wakil KM'] as $member): ?>
                                    <div class="org-member wakil-km">
                                        <div class="member-connector top"></div>
                                        <div class="member-avatar">
                                            <img src="uploads/<?php echo $member['photo'] ?: 'default-avatar.png'; ?>" 
                                                 alt="<?php echo htmlspecialchars($member['name']); ?>"
                                                 onerror="this.src='assets/default-avatar.png'">
                                            <div class="avatar-glow"></div>
                                        </div>
                                        <div class="member-info">
                                            <h3 class="member-name"><?php echo htmlspecialchars($member['name']); ?></h3>
                                            <p class="member-position">Wakil Ketua</p>
                                        </div>
                                        <div class="member-connector bottom"></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Level 3: Sekretaris & Bendahara -->
                <?php if (!empty($positions['Sekretaris 1']) || !empty($positions['Sekretaris 2']) || !empty($positions['Bendahara 1']) || !empty($positions['Bendahara 2'])): ?>
                    <div class="org-level level-3">
                        <div class="level-header">
                            <h2 class="level-title">Badan Pengurus</h2>
                            <div class="level-connector"></div>
                        </div>
                        <div class="level-members management-team">
                            <!-- Sekretaris -->
                            <div class="department">
                                <h3 class="department-title">Sekretaris</h3>
                                <div class="department-members">
                                    <?php foreach (['Sekretaris 1', 'Sekretaris 2'] as $position): ?>
                                        <?php if (!empty($positions[$position])): ?>
                                            <?php foreach ($positions[$position] as $member): ?>
                                                <div class="org-member sekretaris">
                                                    <div class="member-connector top"></div>
                                                    <div class="member-avatar">
                                                        <img src="uploads/<?php echo $member['photo'] ?: 'default-avatar.png'; ?>" 
                                                             alt="<?php echo htmlspecialchars($member['name']); ?>"
                                                             onerror="this.src='assets/default-avatar.png'">
                                                        <div class="avatar-glow"></div>
                                                    </div>
                                                    <div class="member-info">
                                                        <h3 class="member-name"><?php echo htmlspecialchars($member['name']); ?></h3>
                                                        <p class="member-position"><?php echo htmlspecialchars($member['position']); ?></p>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Bendahara -->
                            <div class="department">
                                <h3 class="department-title">Bendahara</h3>
                                <div class="department-members">
                                    <?php foreach (['Bendahara 1', 'Bendahara 2'] as $position): ?>
                                        <?php if (!empty($positions[$position])): ?>
                                            <?php foreach ($positions[$position] as $member): ?>
                                                <div class="org-member bendahara">
                                                    <div class="member-connector top"></div>
                                                    <div class="member-avatar">
                                                        <img src="uploads/<?php echo $member['photo'] ?: 'default-avatar.png'; ?>" 
                                                             alt="<?php echo htmlspecialchars($member['name']); ?>"
                                                             onerror="this.src='assets/default-avatar.png'">
                                                        <div class="avatar-glow"></div>
                                                    </div>
                                                    <div class="member-info">
                                                        <h3 class="member-name"><?php echo htmlspecialchars($member['name']); ?></h3>
                                                        <p class="member-position"><?php echo htmlspecialchars($member['position']); ?></p>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Level 4: Seksi-Seksi -->
                <?php 
                $seksi_positions = [
                    'Humas',
                    'Seksi Keamanan', 
                    'Seksi Kerohanian',
                    'Seksi Upacara',
                    'Seksi Kesehatan',
                    'Seksi Olahraga',
                    'Seksi Kewirausahaan',
                    'Seksi Kesenian',
                    'Seksi Absensi'
                ];
                $has_seksi = false;
                foreach ($seksi_positions as $seksi) {
                    if (!empty($positions[$seksi])) {
                        $has_seksi = true;
                        break;
                    }
                }
                ?>
                
                <?php if ($has_seksi): ?>
                    <div class="org-level level-4">
                        <div class="level-header">
                            <h2 class="level-title">Seksi-Seksi Kelas</h2>
                            <div class="level-connector"></div>
                        </div>
                        <div class="level-members sections-team">
                            <?php foreach ($seksi_positions as $seksi): ?>
                                <?php if (!empty($positions[$seksi])): ?>
                                    <div class="department">
                                        <h3 class="department-title"><?php echo htmlspecialchars(str_replace('Seksi ', '', $seksi)); ?></h3>
                                        <div class="department-members">
                                            <?php foreach ($positions[$seksi] as $member): ?>
                                                <div class="org-member seksi">
                                                    <div class="member-connector top"></div>
                                                    <div class="member-avatar">
                                                        <img src="uploads/<?php echo $member['photo'] ?: 'default-avatar.png'; ?>" 
                                                             alt="<?php echo htmlspecialchars($member['name']); ?>"
                                                             onerror="this.src='assets/default-avatar.png'">
                                                        <div class="avatar-glow"></div>
                                                    </div>
                                                    <div class="member-info">
                                                        <h3 class="member-name"><?php echo htmlspecialchars($member['name']); ?></h3>
                                                        <p class="member-position"><?php echo htmlspecialchars($member['position']); ?></p>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Level 5: Anggota Kelas (PALING BAWAH) -->
                <?php if (!empty($anggota_kelas)): ?>
                    <div class="org-level level-5">
                        <div class="level-header">
                            <h2 class="level-title">Anggota Kelas</h2>
                            <div class="level-connector"></div>
                        </div>
                        <div class="level-description">
                            <p>Seluruh siswa kelas XI RPL yang aktif berpartisipasi dalam kegiatan pembelajaran</p>
                        </div>
                        <div class="level-members members-grid">
                            <?php foreach ($anggota_kelas as $member): ?>
                                <div class="org-member anggota">
                                    <div class="member-avatar">
                                        <img src="uploads/<?php echo $member['photo'] ?: 'default-avatar.png'; ?>" 
                                             alt="<?php echo htmlspecialchars($member['name']); ?>"
                                             onerror="this.src='assets/default-avatar.png'">
                                        <div class="avatar-glow"></div>
                                    </div>
                                    <div class="member-info">
                                        <h3 class="member-name"><?php echo htmlspecialchars($member['name']); ?></h3>
                                        <p class="member-position">Anggota</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </section>

            <?php if ($result->num_rows == 0): ?>
                <div class="no-structure">
                    <div class="no-structure-icon">👥</div>
                    <h3>Struktur Kelas Belum Dibuat</h3>
                    <p>Admin belum mengatur struktur kelas.</p>
                    <a href="index.php" class="btn primary-btn">Kembali ke Beranda</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

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
                        <a href="index.php" class="footer-link">Beranda</a>
                        <a href="structure.php" class="footer-link">Struktur Kelas</a>
                        <a href="login.php" class="footer-link">Admin</a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 XI RPL SMKN 1 Cibinong. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top">
        <span>↑</span>
    </button>

    <script src="assets/script.js"></script>
</body>
</html>