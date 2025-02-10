<?php
// api/login.php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    $email = $conn->real_escape_string($data->email);
    $password = $data->password;
    
    $sql = "SELECT id, name, email, password FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Remove password from user data before sending
            unset($user['password']);
            echo json_encode([
                "success" => true,
                "message" => "Login exitoso",
                "user" => $user
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "error" => "Contraseña incorrecta"
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "error" => "Usuario no encontrado"
        ]);
    }
}

$conn->close();