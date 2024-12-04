<?php
session_start();
include 'bd/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT id, email, password, username, role FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];              
            $_SESSION['user'] = $user['username'];     
            $_SESSION['role'] = $user['role'];         

            if ($user['role'] === 'admin') {
                header('Location: admin/indexadmin.php');
            } else {
                header('Location: user/indexuser.php');
            }
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta.'); window.location.href='.../index.php';</script>";
        }
    } else {
        echo "<script>alert('El correo no está registrado.'); window.location.href='.../index.php';</script>";
    }
}
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="css/style_index.css">
</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <h2>Bienvenido</h2>
            <h4>Inicia sesión para continuar</h4>
            <form action="" method="POST">
                <div class="input-group">
                    <input type="email" placeholder="Correo electrónico" name="email" required>
                </div>
                <div class="input-group">
                    <input type="password" placeholder="Contraseña" name="password" required>
                </div>
                <button type="submit" class="btn">Ingresar</button>
                <div class="forgot-password">
                    <a href="#">¿Olvidaste tu contraseña?</a>
                </div>
            </form>
            <div class="register-link">
                <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a></p>
            </div>
        </div>
    </div>

</body>
</html>
