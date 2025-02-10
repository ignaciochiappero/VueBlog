<?php
// api/posts.php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT p.*, u.name as author, COUNT(DISTINCT c.id) as comment_count, COUNT(DISTINCT l.id) as like_count 
            FROM posts p 
            LEFT JOIN users u ON p.user_id = u.id 
            LEFT JOIN comments c ON p.id = c.post_id 
            LEFT JOIN likes l ON p.id = l.post_id 
            GROUP BY p.id 
            ORDER BY p.created_at DESC";
    $result = $conn->query($sql);
    
    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    
    echo json_encode($posts);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    $user_id = $conn->real_escape_string($data->user_id);
    $title = $conn->real_escape_string($data->title);
    $content = $conn->real_escape_string($data->content);
    
    $sql = "INSERT INTO posts (user_id, title, content) VALUES ('$user_id', '$title', '$content')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Post creado exitosamente", "post_id" => $conn->insert_id]);
    } else {
        echo json_encode(["error" => "Error: " . $conn->error]);
    }
}

$conn->close();