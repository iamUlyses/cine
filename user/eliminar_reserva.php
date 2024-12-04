<?php
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

include '../bd/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserva_id'])) {
    $reserva_id = $_POST['reserva_id'];

    if (is_numeric($reserva_id)) {
        $stmt = $conn->prepare("DELETE FROM reservas WHERE id = ?");
        
        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $conn->error);
        }

        $stmt->bind_param("i", $reserva_id);

        if ($stmt->execute()) {
            header("Location: mis_reservas.php?mensaje=Reserva eliminada con éxito");
            exit();
        } else {
            die('Error al eliminar la reserva: ' . $stmt->error);
        }

        $stmt->close();
    } else {
        die('ID de reserva no válido.');
    }
} else {
    die('No se ha enviado el ID de la reserva.');
}
?>
