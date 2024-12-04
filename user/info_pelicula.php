<?php
session_start();
include '../bd/conexion.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

$pelicula_id = $_GET['id'];
$sql = "SELECT * FROM peliculas WHERE id = $pelicula_id";
$result = $conn->query($sql);
$pelicula = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de la Película</title>
    <link rel="stylesheet" href="../css/style_info.css">
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($pelicula['titulo']); ?></h1>
    </header>
    
    <main>
        <div class="info-pelicula">
            <div class="info-pelicula-img">
                <img src="../img/<?php echo htmlspecialchars($pelicula['imagen']); ?>" alt="<?php echo htmlspecialchars($pelicula['titulo']); ?>">
            </div>
            <div class="info-pelicula-detalles">
                <h2><?php echo htmlspecialchars($pelicula['titulo']); ?></h2>
                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($pelicula['descripcion']); ?></p>
                <p><strong>Fecha de estreno:</strong> <?php echo htmlspecialchars($pelicula['fecha']); ?></p>
                <p><strong>Duración:</strong> <?php echo htmlspecialchars($pelicula['duracion']); ?> min</p>
                <p><strong>Clasificación:</strong> <?php echo htmlspecialchars($pelicula['clasificacion']); ?></p>
                <div class="buttons">
                    <a href="javascript:history.back()" class="btn-back">Atrás</a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <?php include '../includes/footer.php'; ?>
    </footer>
</body>
</html>

