<?php
include '../bd/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : '';
    $descripcion = isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : '';
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
    $clasificacion = isset($_POST['clasificacion']) ? htmlspecialchars($_POST['clasificacion']) : '';
    $duracion = isset($_POST['duracion']) ? $_POST['duracion'] : '';
    $genero = isset($_POST['genero']) ? htmlspecialchars($_POST['genero']) : '';
    $imagen = isset($_FILES['imagen']['name']) ? $_FILES['imagen']['name'] : '';
    $rutaImagen = '../img/' . basename($imagen);

    if (empty($titulo) || empty($descripcion) || empty($fecha) || empty($clasificacion) || empty($duracion) || empty($genero)) {
        echo "<script>alert('Todos los campos son obligatorios.');</script>";
        exit();
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $imageType = $_FILES['imagen']['type'];
    $maxSize = 2 * 1024 * 1024; 

    if (in_array($imageType, $allowedTypes) && $_FILES['imagen']['size'] <= $maxSize) {
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
            try {
                $sql = "INSERT INTO peliculas (titulo, descripcion, imagen, fecha, clasificacion, duracion, genero) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssis", $titulo, $descripcion, $imagen, $fecha, $clasificacion, $duracion, $genero);

                if ($stmt->execute()) {
                    header('Location: indexadmin.php');
                    exit();
                } else {
                    echo "<script>alert('Error al insertar la película en la base de datos.');</script>";
                }
            } catch (Exception $e) {
                echo "Error al insertar la película: " . $e->getMessage();
            }
        } else {
            echo "<script>alert('Error al cargar la imagen.');</script>";
        }
    } else {
        echo "<script>alert('La imagen no es válida o el tamaño es demasiado grande.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Película</title>
    <link rel="stylesheet" href="../css/style_agregarPelicula.css">
</head>
<body>
    <div class="container">
    <a href="indexadmin.php" class="back-button">Atrás</a>
        <h1>Agregar Película</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="titulo">Título de la película</label>
            <input type="text" id="titulo" name="titulo" placeholder="Título de la película" required>

            <label for="descripcion">Descripción de la película</label>
            <textarea id="descripcion" name="descripcion" placeholder="Descripción de la película" required></textarea>

            <label for="fecha">Fecha de estreno</label>
            <input type="date" id="fecha" name="fecha" required>

            <label for="clasificacion">Clasificación:</label>
            <input type="text" name="clasificacion" required>

            <label for="duracion">Duración (en minutos):</label>
            <input type="number" name="duracion" required>

            <label for="genero">Género:</label>
            <input type="text" name="genero" required>

            <label for="imagen">Imagen</label>
            <div class="file-input-container">
                <input type="file" id="imagen" name="imagen" required>
                <span id="file-name" style="visibility: hidden;">No se eligió ningún archivo</span>
            </div>

            <button type="submit" class="submit-btn">Agregar Película</button>
        </form>
    </div>
    <footer>
        <?php include '../includes/footer.php'; ?>
    </footer>
    <script src="../js/noImagen/noImagen.js"></script>
</body>
</html>
