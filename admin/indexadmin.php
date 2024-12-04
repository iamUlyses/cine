<?php
session_start();
include '../bd/conexion.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['id'];
$username = $_SESSION['user']; 
$role = $_SESSION['role'];

$sql = "SELECT * FROM peliculas";
$result = $conn->query($sql);

if ($conn->error) {
    echo "Error en la consulta: " . $conn->error;
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../css/style_admin.css">
    <style>
        .btn-container {
            display: flex;
            flex-direction: column; 
            justify-content: space-between;
            height: 100%; 
        }
        .btn-container a {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenido Admin</h1>
        <p>Hola, <?php echo htmlspecialchars($username); ?>. Este es tu panel de administrador.</p>
    </header>
    
    <nav>
        <ul>
            <li><a href="agregar_pelicula.php">Agregar Película</a></li>
        </ul>
    </nav>

    <main>
        <h2>Películas en la Base de Datos</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Fecha de Estreno</th>
                    <th>Clasificación</th>
                    <th>Duración</th>
                    <th>Género</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        
                        $imagen = $row['imagen'];
                        $imagen_path = '../img/' . $imagen;
                        echo "<td style='text-align: center;'>"; 
                        if ($imagen && file_exists($imagen_path)) {
                            echo "<img src='$imagen_path' alt='Imagen de la película' class='movie-image'>";
                        } else {
                            echo "<img src='../img/default.jpg' alt='Imagen no disponible' class='movie-image'>";
                        }
                        echo "</td>";
                        
                        echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
                        
                        echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                        
                        echo "<td>" . htmlspecialchars($row['fecha']) . "</td>";
                        
                        echo "<td>" . htmlspecialchars($row['clasificacion']) . "</td>";
                        
                        echo "<td>" . htmlspecialchars($row['duracion']) . " minutos</td>";
                        
                        echo "<td>" . htmlspecialchars($row['genero']) . "</td>";
                        
                        echo "<td>";
                        echo "<div class='btn-container'>";
                        echo "<a href='editar_pelicula.php?id=" . $row['id'] . "' class='btn-edit'>Editar</a>";
                        echo "<a href='eliminar_pelicula.php?id=" . $row['id'] . "' class='btn-delete'>Eliminar</a>";
                        echo "</div>";
                        echo "</td>";
                        
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No hay películas en la base de datos.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <a href="../includes/logout.php">Cerrar sesión</a>
    
    <footer>
        <?php include '../includes/footer.php'; ?>
    </footer>
</body>
</html>

