// assets/script.js - Pastikan file ini ada di folder assets
console.log('🎯 MAIN script.js loaded successfully!');

// Debug: Test if functions are available
console.log('✅ Testing function availability:');
console.log('toggleLike:', typeof toggleLike);
console.log('submitComment:', typeof submitComment);
console.log('focusComment:', typeof focusComment);

// Simple toggleLike function
function toggleLike(postId) {
    console.log('❤️ toggleLike called for post:', postId);
    
    const userName = prompt('Masukkan nama Anda untuk like:');
    
    if (!userName || userName.trim() === '') {
        alert('Nama tidak boleh kosong!');
        return;
    }
    
    console.log('Sending like request for:', userName);
    
    fetch('action.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=like&post_id=' + postId + '&user_name=' + encodeURIComponent(userName)
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('Network error: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Like response:', data);
        
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
        console.error('Fetch error:', error);
        alert('❌ Terjadi kesalahan: ' + error.message);
    });
}

    // Comment function - FIXED VERSION
    function submitComment(postId) {
        console.log('💬 submitComment called for post:', postId);
        
        const userName = document.getElementById('comment-name-' + postId).value.trim();
        const commentText = document.getElementById('comment-text-' + postId).value.trim();

        console.log('User:', userName, 'Comment:', commentText);

        if (!userName) {
            alert('Silakan masukkan nama Anda!');
            document.getElementById('comment-name-' + postId).focus();
            return;
        }

        if (!commentText) {
            alert('Silakan tulis komentar Anda!');
            document.getElementById('comment-text-' + postId).focus();
            return;
        }

        // Show loading - FIXED SELECTOR
        const submitBtn = document.querySelector('#comment-form-' + postId + ' .comment-submit-btn') || 
                         document.querySelector('.comment-submit-btn');
        
        if (submitBtn) {
            submitBtn.innerHTML = '⏳';
            submitBtn.disabled = true;
        }

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
                    // Remove "no comments" message if exists
                    const noComments = commentsContainer.querySelector('.no-comments');
                    if (noComments) {
                        noComments.remove();
                    }
                    
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
                }
                
                alert('✅ Komentar berhasil dikirim!');
            } else {
                alert('❌ Error: ' + data.error);
            }
            
            // Reset button - FIXED
            if (submitBtn) {
                submitBtn.innerHTML = '<span class="send-icon">➤</span>';
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan');
            
            // Reset button - FIXED
            if (submitBtn) {
                submitBtn.innerHTML = '<span class="send-icon">➤</span>';
                submitBtn.disabled = false;
            }
        });
    }
// Function to add new comment to display
function addNewComment(postId, comment) {
    const commentsContainer = document.getElementById('comments-' + postId);
    
    if (!commentsContainer) {
        console.error('Comments container not found for post:', postId);
        return;
    }
    
    // Remove "no comments" message if exists
    const noComments = commentsContainer.querySelector('.no-comments');
    if (noComments) {
        noComments.remove();
    }
    
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
    console.log('✅ DOM fully loaded and parsed');
    console.log('✅ All functions are ready to use');
    
    // Test button functionality
    const likeButtons = document.querySelectorAll('.like-btn');
    const commentButtons = document.querySelectorAll('.comment-btn');
    
    console.log('✅ Found', likeButtons.length, 'like buttons');
    console.log('✅ Found', commentButtons.length, 'comment buttons');
    
    // Add click event listeners for testing
    likeButtons.forEach((btn, index) => {
        btn.addEventListener('click', function() {
            console.log('✅ Like button', index, 'clicked successfully');
        });
    });
    
    // Prevent form submission
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('✅ Form submission prevented');
        });
    });
    
    console.log('✅ Script initialization complete');
});

// Make functions globally available
window.toggleLike = toggleLike;
window.submitComment = submitComment;
window.focusComment = focusComment;
window.sharePost = sharePost;

console.log('✅ All functions exported to window object');