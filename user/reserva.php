<?php 
include '../bd/conexion.php';


$cine_id = isset($_GET['cine_id']) ? $_GET['cine_id'] : null;
$cantidad_boletos = isset($_GET['cantidad_boletos']) ? $_GET['cantidad_boletos'] : null;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_cliente'], $_POST['cine_id'], $_POST['pelicula_id'], $_POST['horario_id'], $_POST['cantidad_boletos'], $_POST['metodo_pago'], $_POST['asientos'])) {
    $nombre_cliente = $_POST['nombre_cliente'];
    $cine_id = $_POST['cine_id'];
    $pelicula_id = $_POST['pelicula_id'];
    $horario_id = $_POST['horario_id'];
    $cantidad_boletos = $_POST['cantidad_boletos'];
    $metodo_pago = $_POST['metodo_pago'];

    $estado_pago = ($metodo_pago === 'tarjeta') ? 'pagado' : 'pendiente';

    $stmt = $conn->prepare("INSERT INTO reservas (nombre_cliente, cine_id, pelicula_id, horario_id, cantidad_boletos, metodo_pago, estado_pago) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siisiis", $nombre_cliente, $cine_id, $pelicula_id, $horario_id, $cantidad_boletos, $metodo_pago, $estado_pago); 
    $stmt->execute();
    $stmt->close();

    $asientos = json_decode($_POST['asientos'], true);
    if (is_array($asientos)) {
        foreach ($asientos as $asiento) {
            $fila = $asiento['fila'];
            $columna = $asiento['columna'];
            $estado = $asiento['estado'];

            $sql = "SELECT * FROM asientos WHERE cine_id = ? AND fila = ? AND columna = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isi", $cine_id, $fila, $columna);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $sql_update = "UPDATE asientos SET estado = ? WHERE cine_id = ? AND fila = ? AND columna = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("sisi", $estado, $cine_id, $fila, $columna);
                $stmt_update->execute();
                $stmt_update->close();
            } else {
                $sql_insert = "INSERT INTO asientos (cine_id, fila, columna, estado) VALUES (?, ?, ?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bind_param("isis", $cine_id, $fila, $columna, $estado);
                $stmt_insert->execute();
                $stmt_insert->close();
            }
        }
        $stmt->close();
        $conn->close();

        echo "<script>alert('Reserva y asientos guardados correctamente.'); window.location.href='indexuser.php';</script>";
        exit; 
    } else {
        echo "Error: los datos de los asientos no son válidos.";
        exit;
    }
}

$cinesResult = $conn->query("SELECT * FROM cines");
$peliculasResult = $conn->query("SELECT * FROM peliculas");
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Boletos</title>
    <link rel="stylesheet" href="../css/style_reserva.css">
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        form {
            margin-bottom: 50px;
        }

        button {
            display: block;
            margin-top: 20px;
            width: 100%;
        }

        .formulario {
            position: relative;
            z-index: 5;
        }

        footer {
            position: relative;
            z-index: 5;
        }

        table {
            border-collapse: collapse;
            margin: 20px;
        }
        td {
            width: 30px;
            height: 30px;
            text-align: center;
            border: 1px solid #ccc;
            cursor: pointer;
        }
        .disponible {
            background-color: #28a745;
        }
        .ocupado {
            background-color: #dc3545;
        }
        .seleccionado {
            background-color: #ffc107;
        }
    </style>
</head>
<body>
    <h1>Reserva de Boletos</h1>

    <form method="POST" action="reserva.php">
        <label for="nombre_cliente">Nombre:</label>
        <input type="text" name="nombre_cliente" id="nombre_cliente" required><br>

        <label for="cine_id">Cine:</label>
        <select name="cine_id" id="cine_id" required onchange="cargarHorarios()">
            <option value="">Seleccione un cine</option>
            <?php
            while ($cine = $cinesResult->fetch_assoc()) {
                echo "<option value='" . $cine['id'] . "'>" . $cine['nombre'] . " - " . $cine['ubicacion'] . "</option>";
            }
            ?>
        </select><br>

        <label for="pelicula_id">Película:</label>
        <select name="pelicula_id" id="pelicula_id" required>
            <option value="">Seleccione una película</option>
            <?php
            while ($pelicula = $peliculasResult->fetch_assoc()) {
                echo "<option value='" . $pelicula['id'] . "'>" . $pelicula['titulo'] . "</option>";
            }
            ?>
        </select><br>

        <label for="horario_id">Horario:</label>
        <select name="horario_id" id="horario_id" required>
            <option value="">Seleccione un horario</option>
        </select><br>

        <label for="cantidad_boletos">Cantidad de Boletos:</label>
        <input type="number" name="cantidad_boletos" id="cantidad_boletos" required min="1"><br>

        <label for="metodo_pago">Método de Pago:</label>
        <select name="metodo_pago" id="metodo_pago" required>
            <option value="efectivo">Efectivo</option>
            <option value="tarjeta">Tarjeta</option>
        </select><br><br>

        <h2>Seleccionar Asientos</h2>
        <table id="tabla-asientos"></table>

        <input type="hidden" name="asientos" id="asientos">

        <button type="submit">Guardar Reserva</button>
    </form>

    <script>
        const tabla = document.getElementById('tabla-asientos');
        const asientosSeleccionados = [];

        for (let fila = 1; fila <= 10; fila++) {
            const tr = document.createElement('tr');
            for (let columna = 1; columna <= 10; columna++) {
                const td = document.createElement('td');
                td.classList.add('disponible');
                td.dataset.fila = fila;
                td.dataset.columna = columna;
                td.dataset.estado = 'disponible';
                td.addEventListener('click', () => seleccionarAsiento(fila, columna, td));
                tr.appendChild(td);
            }
            tabla.appendChild(tr);
        }

        function seleccionarAsiento(fila, columna, td) {
            if (td.dataset.estado !== 'ocupado') {
                const estado = td.dataset.estado === 'disponible' ? 'seleccionado' : 'disponible';
                td.classList.toggle('seleccionado');
                td.classList.toggle('disponible');
                td.dataset.estado = estado;

                const index = asientosSeleccionados.findIndex(asiento => asiento.fila === fila && asiento.columna === columna);
                if (index !== -1) {
                    asientosSeleccionados.splice(index, 1);
                } else {
                    asientosSeleccionados.push({ fila, columna, estado });
                }
            }
        }

        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault();

            document.getElementById('asientos').value = JSON.stringify(asientosSeleccionados);
            this.submit(); 
        });

        function cargarHorarios() {
            const cine_id = document.getElementById('cine_id').value;
            const horarioSelect = document.getElementById('horario_id');
            horarioSelect.innerHTML = "<option value=''>Seleccione un horario</option>"; 

            if (cine_id) {
                fetch('cargar_horarios.php?cine_id=' + cine_id)
                    .then(response => response.json())
                    .then(horarios => {
                        horarios.forEach(horario => {
                            const option = document.createElement('option');
                            option.value = horario.id;
                            option.textContent = horario.hora;
                            horarioSelect.appendChild(option);
                        });
                    });
            }
        }
    </script>
</body>
</html>