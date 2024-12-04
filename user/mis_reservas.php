<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

include '../bd/conexion.php';

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = $_POST['username'];

    $stmt = $conn->prepare("SELECT r.*, p.titulo AS pelicula_titulo, c.nombre AS cine_nombre 
                            FROM reservas r 
                            JOIN peliculas p ON r.pelicula_id = p.id 
                            JOIN cines c ON r.cine_id = c.id 
                            WHERE r.nombre_cliente = ?");
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conn->error);
    }

    $stmt->bind_param("s", $username);

    if (!$stmt->execute()) {
        die('Error en la ejecución de la consulta: ' . $stmt->error);
    }

    $reservas = $stmt->get_result();

    if ($reservas->num_rows > 0) {
        $mensaje = "Reservas encontradas:";
    } else {
        $mensaje = "No se encontraron reservas para el usuario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas</title>
    <link rel="stylesheet" href="../css/style_mis_reservas.css">
</head>
<body>
    <header>
        <h1>Mis Reservas</h1>
    </header>

    <main>
        <a href="../user/indexuser.php" class="btn">Volver al Inicio</a>
        <h2>Introduce el nombre de usuario para ver las reservas.</h2>
        <form method="POST" action="mis_reservas.php">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" name="username" id="username" required>
            <button type="submit" class="btn">Ver Reservas</button>
        </form>

        <?php
        if ($mensaje) {
            echo "<p>$mensaje</p>";  
        }

        if (isset($reservas) && $reservas->num_rows > 0) {
            echo "<section class='reservas'>";
            while ($reserva = $reservas->fetch_assoc()) {
                echo "<div class='reserva'>";
                echo "<p><strong>Película:</strong> " . htmlspecialchars($reserva['pelicula_titulo']) . "</p>";
                echo "<p><strong>Cine:</strong> " . htmlspecialchars($reserva['cine_nombre']) . "</p>";
                echo "<p><strong>Fecha y Hora:</strong> " . htmlspecialchars($reserva['horario_id']) . "</p>"; // Deberías mostrar un horario real si lo tienes
                echo "<p><strong>Estado de Pago:</strong> " . htmlspecialchars($reserva['estado_pago']) . "</p>";

                echo "<form method='POST' action='eliminar_reserva.php' style='display:inline;'>";
                echo "<input type='hidden' name='reserva_id' value='" . $reserva['id'] . "'>";
                echo "<button type='submit' class='btn eliminar'>Eliminar Reserva</button>";
                echo "</form>";

                echo "</div>";
            }
            echo "</section>";
        }
        ?>
    </main>
</body>
</html>

<?php
if (isset($stmt)) {
    $stmt->close();
}
?>
