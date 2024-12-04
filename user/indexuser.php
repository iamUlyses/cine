<?php
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

$username = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio Usuario</title>
    <link rel="stylesheet" href="../css/style_user.css">
</head>
<body>
    <header>
        <h1>Bienvenido, <?php echo htmlspecialchars($username); ?>.</h1> 
        <h2>Hola, <?php echo htmlspecialchars($username); ?>. ¿Qué película iremos a ver hoy?</h2>
    </header>
    
    <main>
        <a href="../includes/logout.php">Cerrar sesión</a>
        
        <a href="mis_reservas.php" class="btn-reservas">Mis Reservas</a>

        <section class="peliculas">
            <?php
            include '../bd/conexion.php';
            $sql = "SELECT * FROM peliculas";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='pelicula'>";
                    echo "<img src='../img/" . htmlspecialchars($row['imagen']) . "' alt='" . htmlspecialchars($row['titulo']) . "'>";
                    echo "<h2>" . htmlspecialchars($row['titulo']) . "</h2>";
                    echo "<p>Fecha de estreno: " . htmlspecialchars($row['fecha']) . "</p>";
                    echo "<a href='reserva.php?id=" . $row['id'] . "' class='btn'>Reservar</a>";
                    echo "<a href='info_pelicula.php?id=" . $row['id'] . "' class='btn-info'>Info</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>No hay películas disponibles.</p>";
            }
            ?>
        </section>
    </main>
    <footer>
        <?php include '../includes/footer.php'; ?>
    </footer>
</body>
</html>
