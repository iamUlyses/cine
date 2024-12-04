<?php
include '../bd/conexion.php';


$cine_id = isset($_GET['cine_id']) ? $_GET['cine_id'] : null;

if ($cine_id) {
    $sql = "SELECT * FROM horarios WHERE cine_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cine_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $horarios = [];
    while ($row = $result->fetch_assoc()) {
        $horarios[] = $row; 
    }

    echo json_encode($horarios);
} else {
    echo json_encode([]); 
}
?>
