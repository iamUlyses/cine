<?php
include '../bd/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM peliculas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: indexadmin.php'); 
        exit();
    } else {
        echo "Error al eliminar la película.";
    }
} else {
    echo "ID no proporcionado.";
}
?>

