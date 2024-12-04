<?php
session_start();
include '../bd/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM peliculas WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pelicula = $result->fetch_assoc();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $fecha = $_POST['fecha'];
        $clasificacion = $_POST['clasificacion'];
        $duracion = $_POST['duracion'];
        $genero = $_POST['genero'];

        $query = "UPDATE peliculas SET titulo = ?, descripcion = ?, fecha = ?, clasificacion = ?, duracion = ?, genero = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $titulo, $descripcion, $fecha, $clasificacion, $duracion, $genero, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Película actualizada correctamente.'); window.location.href='indexadmin.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar la película.'); window.location.href='editar_pelicula.php?id=$id';</script>";
        }
    }
} else {
    echo "<script>alert('ID de película no proporcionado.'); window.location.href='indexadmin.php';</script>";
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Película</title>
    <link rel="stylesheet" href="../css/style_editarPelicula.css">
</head>
<body>
<header>
    <h1>Editar Película</h1>
</header>
<a href="indexadmin.php" class="back-button">Atrás</a>
<main>
    <form action="" method="POST">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" value="<?= isset($pelicula) ? htmlspecialchars($pelicula['titulo']) : '' ?>" required>
        
        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" required><?= isset($pelicula) ? htmlspecialchars($pelicula['descripcion']) : '' ?></textarea>
        
        <label for="fecha">Fecha de Estreno:</label>
        <input type="date" name="fecha" value="<?= isset($pelicula) ? htmlspecialchars($pelicula['fecha']) : '' ?>" required>
        
        <label for="clasificacion">Clasificación:</label>
        <input type="text" name="clasificacion" value="<?= isset($pelicula) ? htmlspecialchars($pelicula['clasificacion']) : '' ?>" required>

        <label for="duracion">Duración:</label>
        <input type="text" name="duracion" value="<?= isset($pelicula) ? htmlspecialchars($pelicula['duracion']) : '' ?>" required>

        <label for="genero">Género:</label>
        <input type="text" name="genero" value="<?= isset($pelicula) ? htmlspecialchars($pelicula['genero']) : '' ?>" required>

        <button type="submit">Guardar Cambios</button>
    </form>
</main>

    <footer>
        <?php include '../includes/footer.php'; ?>
    </footer>
</body>
</html>

