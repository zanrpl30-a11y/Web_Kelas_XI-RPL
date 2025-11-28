// backup-script.js - Simple backup functions
console.log('✅ backup-script.js loaded');

// Simple toggleLike function
function toggleLike(postId) {
    console.log('❤️ toggleLike called for post:', postId);
    
    const userName = prompt('Masukkan nama Anda untuk like:');
    
    if (!userName || userName.trim() === '') {
        alert('Nama tidak boleh kosong!');
        return;
    }
    
    console.log('Sending like request...');
    
    // Simple fetch
    fetch('action.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=like&post_id=' + postId + '&user_name=' + encodeURIComponent(userName)
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            // Update like count
            const likeCountElement = document.getElementById('like-count-' + postId);
            if (likeCountElement) {
                likeCountElement.textContent = data.like_count + ' suka';
            }
            
            // Update button
            const likeBtn = document.getElementById('like-btn-' + postId);
            if (likeBtn) {
                if (data.liked) {
                    likeBtn.innerHTML = '<span class="like-icon">❤️</span><span>Disukai</span>';
                    likeBtn.style.color = '#ff4444';
                } else {
                    likeBtn.innerHTML = '<span class="like-icon">🤍</span><span>Suka</span>';
                    likeBtn.style.color = '#88ffff';
                }
            }
            
            alert('✅ Like berhasil!');
        } else {
            alert('❌ Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan. Cek console untuk detail.');
    });
}

// Simple submitComment function
function submitComment(postId) {
    console.log('💬 submitComment called for post:', postId);
    
    const userName = document.getElementById('comment-name-' + postId).value.trim();
    const commentText = document.getElementById('comment-text-' + postId).value.trim();
    
    if (!userName) {
        alert('Silakan masukkan nama Anda!');
        document.getElementById('comment-name-' + postId).focus();
        return false;
    }
    
    if (!commentText) {
        alert('Silakan tulis komentar Anda!');
        document.getElementById('comment-text-' + postId).focus();
        return false;
    }
    
    console.log('Sending comment...');
    
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
            const commentCountElement = document.getElementById('comment-count-' + postId);
            if (commentCountElement) {
                commentCountElement.textContent = data.comment_count + ' komentar';
            }
            
            // Add comment to display
            const commentsContainer = document.getElementById('comments-' + postId);
            if (commentsContainer) {
                const commentElement = document.createElement('div');
                commentElement.className = 'comment';
                commentElement.innerHTML = `
                    <strong>${data.comment.user_name}:</strong> 
                    ${data.comment.comment}
                    <small>${data.comment.created_at}</small>
                `;
                commentsContainer.appendChild(commentElement);
            }
            
            alert('✅ Komentar berhasil dikirim!');
        } else {
            alert('❌ Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan. Cek console untuk detail.');
    });
    
    return false; // Prevent form submission
}

// Simple focusComment function
function focusComment(postId) {
    console.log('🎯 focusComment called for post:', postId);
    
    const nameInput = document.getElementById('comment-name-' + postId);
    const commentInput = document.getElementById('comment-text-' + postId);
    
    if (nameInput && !nameInput.value) {
        nameInput.focus();
    } else if (commentInput) {
        commentInput.focus();
    }
}

// Simple sharePost function
function sharePost(postId) {
    console.log('🔄 sharePost called for post:', postId);
    
    const shareUrl = window.location.href.split('#')[0] + '#post-' + postId;
    
    // Copy to clipboard
    navigator.clipboard.writeText(shareUrl).then(() => {
        alert('✅ Link berhasil disalin:\n' + shareUrl);
    }).catch(() => {
        // Fallback
        const temp = document.createElement('input');
        temp.value = shareUrl;
        document.body.appendChild(temp);
        temp.select();
        document.execCommand('copy');
        document.body.removeChild(temp);
        alert('✅ Link berhasil disalin:\n' + shareUrl);
    });
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Backup script initialized');
    
    // Test if functions are working
    console.log('✅ toggleLike function:', typeof toggleLike);
    console.log('✅ submitComment function:', typeof submitComment);
    console.log('✅ focusComment function:', typeof focusComment);
    
    // Add event listeners to prevent form submission
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('✅ Form submission prevented');
        });
    });
});