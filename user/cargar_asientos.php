<?php
include '../bd/conexion.php';

if (isset($_GET['cine_id']) && is_numeric($_GET['cine_id'])) {
    $cine_id = $_GET['cine_id'];

    $query = "SELECT id, hora FROM horarios WHERE cine_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cine_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $horarios = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    echo json_encode($horarios);
} else {
    echo json_encode([]);
}
?>