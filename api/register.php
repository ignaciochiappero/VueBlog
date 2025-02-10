<?php
// api/register.php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    $name = $conn->real_escape_string($data->name);
    $email = $conn->real_escape_string($data->email);
    $password = password_hash($data->password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Usuario registrado exitosamente"]);
    } else {
        echo json_encode(["error" => "Error: " . $conn->error]);
    }
}

$conn->close();