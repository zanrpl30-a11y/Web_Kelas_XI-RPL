<?php
// File: structure-item.php
// Partial template untuk menampilkan item struktur
?>

<div class="structure-photo">
    <img src="uploads/<?php echo $member['photo'] ?: 'default-avatar.png'; ?>" 
         alt="<?php echo htmlspecialchars($member['name']); ?>"
         onerror="this.src='assets/default-avatar.png'">
</div>

<div class="structure-info">
    <h3 class="position"><?php echo htmlspecialchars($member['position']); ?></h3>
    <p class="name"><?php echo htmlspecialchars($member['name']); ?></p>
</div>

<?php if ($is_admin): ?>
    <button class="edit-toggle-btn" onclick="toggleEditForm(<?php echo $member['id']; ?>)">
        ✏️ Edit
    </button>
    
    <form method="POST" enctype="multipart/form-data" class="structure-form" id="form-<?php echo $member['id']; ?>" style="display: none;">
        <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
        
        <div class="form-group">
            <select name="position" class="form-select" required>
                <option value="Wali Kelas" <?php echo $member['position'] == 'Wali Kelas' ? 'selected' : ''; ?>>Wali Kelas</option>
                <option value="Ketua Kelas" <?php echo $member['position'] == 'Ketua Kelas' ? 'selected' : ''; ?>>Ketua Kelas</option>
                <option value="Wakil Ketua Kelas" <?php echo $member['position'] == 'Wakil Ketua Kelas' ? 'selected' : ''; ?>>Wakil Ketua Kelas</option>
                <option value="Sekretaris" <?php echo $member['position'] == 'Sekretaris' ? 'selected' : ''; ?>>Sekretaris</option>
                <option value="Bendahara" <?php echo $member['position'] == 'Bendahara' ? 'selected' : ''; ?>>Bendahara</option>
                <option value="Sie Akademik" <?php echo $member['position'] == 'Sie Akademik' ? 'selected' : ''; ?>>Sie Akademik</option>
                <option value="Sie Kesenian" <?php echo $member['position'] == 'Sie Kesenian' ? 'selected' : ''; ?>>Sie Kesenian</option>
                <option value="Sie Olahraga" <?php echo $member['position'] == 'Sie Olahraga' ? 'selected' : ''; ?>>Sie Olahraga</option>
                <option value="Sie Kebersihan" <?php echo $member['position'] == 'Sie Kebersihan' ? 'selected' : ''; ?>>Sie Kebersihan</option>
                <option value="Anggota" <?php echo $member['position'] == 'Anggota' ? 'selected' : ''; ?>>Anggota</option>
            </select>
        </div>
        
        <div class="form-group">
            <input type="text" name="name" value="<?php echo htmlspecialchars($member['name']); ?>" 
                   class="form-input" placeholder="Nama" required>
        </div>
        
        <div class="form-group">
            <input type="file" name="photo" accept="image/*" class="form-input file-input">
            <small>Kosongkan jika tidak ingin mengubah foto</small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn primary-btn small-btn">Update</button>
            <button type="button" onclick="toggleEditForm(<?php echo $member['id']; ?>)" class="btn cancel-btn small-btn">Batal</button>
        </div>
    </form>
<?php endif; ?>