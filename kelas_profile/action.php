<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $post_id = intval($_POST['post_id'] ?? 0);

    if ($action === 'like') {
        $user_name = trim($_POST['user_name'] ?? '');
        
        if (empty($user_name)) {
            echo json_encode(['success' => false, 'error' => 'Nama tidak boleh kosong']);
            exit;
        }
        
        // Check if already liked
        $check_like = $conn->query("SELECT id FROM post_likes WHERE post_id = $post_id AND user_name = '" . $conn->real_escape_string($user_name) . "'");
        
        if ($check_like->num_rows > 0) {
            // Unlike
            $conn->query("DELETE FROM post_likes WHERE post_id = $post_id AND user_name = '" . $conn->real_escape_string($user_name) . "'");
            $liked = false;
        } else {
            // Like
            $conn->query("INSERT INTO post_likes (post_id, user_name) VALUES ($post_id, '" . $conn->real_escape_string($user_name) . "')");
            $liked = true;
        }
        
        // Get updated like count
        $like_count_result = $conn->query("SELECT COUNT(*) as count FROM post_likes WHERE post_id = $post_id");
        $like_count = $like_count_result->fetch_assoc()['count'];
        
        echo json_encode([
            'success' => true,
            'liked' => $liked,
            'like_count' => $like_count
        ]);
        
    } elseif ($action === 'comment') {
        $user_name = trim($_POST['user_name'] ?? '');
        $comment = trim($_POST['comment'] ?? '');
        
        if (empty($user_name)) {
            echo json_encode(['success' => false, 'error' => 'Nama tidak boleh kosong']);
            exit;
        }
        
        if (empty($comment)) {
            echo json_encode(['success' => false, 'error' => 'Komentar tidak boleh kosong']);
            exit;
        }
        
        $stmt = $conn->prepare("INSERT INTO post_comments (post_id, user_name, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $post_id, $user_name, $comment);
        
        if ($stmt->execute()) {
            $comment_count_result = $conn->query("SELECT COUNT(*) as count FROM post_comments WHERE post_id = $post_id");
            $comment_count = $comment_count_result->fetch_assoc()['count'];
            
            $new_comment = $conn->query("SELECT * FROM post_comments WHERE id = " . $stmt->insert_id)->fetch_assoc();
            
            echo json_encode([
                'success' => true,
                'comment_count' => $comment_count,
                'comment' => [
                    'id' => $new_comment['id'],
                    'user_name' => $new_comment['user_name'],
                    'comment' => $new_comment['comment'],
                    'created_at' => date('d M Y • H:i', strtotime($new_comment['created_at']))
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Gagal menyimpan komentar']);
        }
        
    } elseif ($action === 'load_comments') {
        $post_id = intval($_POST['post_id'] ?? 0);
        
        $comments_result = $conn->query("
            SELECT * FROM post_comments 
            WHERE post_id = $post_id 
            ORDER BY created_at DESC
        ");
        
        $comments = [];
        while ($comment = $comments_result->fetch_assoc()) {
            $comments[] = [
                'id' => $comment['id'],
                'user_name' => $comment['user_name'],
                'comment' => $comment['comment'],
                'created_at' => date('d M Y • H:i', strtotime($comment['created_at']))
            ];
        }
        
        echo json_encode([
            'success' => true,
            'comments' => $comments
        ]);
        
    } else {
        echo json_encode(['success' => false, 'error' => 'Unknown action']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>