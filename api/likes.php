<?php
// api/likes.php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    $post_id = $conn->real_escape_string($data->post_id);
    $user_id = $conn->real_escape_string($data->user_id);
    
    $sql = "INSERT INTO likes (post_id, user_id) VALUES ('$post_id', '$user_id') ON DUPLICATE KEY UPDATE id=id";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Like agregado exitosamente"]);
    } else {
        echo json_encode(["error" => "Error: " . $conn->error]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));
    
    $post_id = $conn->real_escape_string($data->post_id);
    $user_id = $conn->real_escape_string($data->user_id);
    
    $sql = "DELETE FROM likes WHERE post_id = '$post_id' AND user_id = '$user_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Like eliminado exitosamente"]);
    } else {
        echo json_encode(["error" => "Error: " . $conn->error]);
    }
}

$conn->close();