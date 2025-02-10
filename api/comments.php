<?php
// api/comments.php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $post_id = $conn->real_escape_string($_GET['post_id']);
    
    $sql = "SELECT c.*, u.name as author FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = '$post_id' ORDER BY c.created_at DESC";
    $result = $conn->query($sql);
    
    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    
    echo json_encode($comments);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    $post_id = $conn->real_escape_string($data->post_id);
    $user_id = $conn->real_escape_string($data->user_id);
    $content = $conn->real_escape_string($data->content);
    
    $sql = "INSERT INTO comments (post_id, user_id, content) VALUES ('$post_id', '$user_id', '$content')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Comentario agregado exitosamente"]);
    } else {
        echo json_encode(["error" => "Error: " . $conn->error]);
    }
}

$conn->close();