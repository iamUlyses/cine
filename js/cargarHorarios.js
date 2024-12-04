// Función para cargar los asientos disponibles según el cine seleccionado
function cargarAsientos() {
    const cineId = document.getElementById('cine_id').value;

    // Verificar que se haya seleccionado un cine
    if (cineId) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `obtener_asientos.php?cine_id=${cineId}`, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                const asientos = JSON.parse(xhr.responseText);
                let asientosHtml = '';

                // Crear los asientos dinámicamente
                asientos.forEach(function(asiento) {
                    // Verificar si el asiento está ocupado o disponible
                    const clase = asiento.estado === 'ocupado' ? 'asiento ocupado' : 'asiento disponible';
                    asientosHtml += `<div class="asiento ${clase}" id="asiento_${asiento.id}" data-id="${asiento.id}"></div>`;
                });

                // Actualizar el contenedor de asientos
                document.getElementById('asientos').innerHTML = asientosHtml;
            } else {
                alert('Error al cargar los asientos.');
            }
        };
        xhr.send();
    }
}

// Llamar a la función de cargar asientos cuando se seleccione un cine
document.getElementById('cine_id').addEventListener('change', cargarAsientos);
