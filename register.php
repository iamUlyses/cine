<?php
include('bd/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $username = $_POST['username'];

    $role = 'user';

    if ($password != $confirm_password) {
        echo "<script>alert('Las contraseñas no coinciden.'); window.location.href = 'register.php';</script>";
    } else {
        $query = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "<script>alert('El correo electrónico ya está registrado.'); window.location.href = 'register.php';</script>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); 
            $insert_query = "INSERT INTO usuarios (email, password, username, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ssss", $email, $hashed_password, $username, $role); 

            if ($stmt->execute()) {
                echo "<script>alert('Cuenta creada con éxito.'); window.location.href = 'index.php';</script>";
            } else {
                echo "<script>alert('Hubo un error al crear la cuenta.'); window.location.href = 'register.php';</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Cine</title>
    <link rel="stylesheet" href="css/style_register.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Registrarse</h2>
            <h4>Por favor, llena los campos para crear una cuenta</h4>
            <form action="register.php" method="POST">
                <div class="input-group">
                    <input type="email" name="email" placeholder="Correo electrónico" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Contraseña" required>
                </div>
                <div class="input-group">
                    <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
                </div>
                <div class="input-group">
                    <input type="text" name="username" placeholder="Nombre de usuario" required>
                </div>
                <button type="submit" class="btn">Registrarse</button>
            </form>
            <div class="register-link">
                <p>¿Ya tienes una cuenta? <a href="index.php">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>
</body>
</html>

