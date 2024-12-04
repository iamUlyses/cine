document.getElementById('cine_id').addEventListener('change', function() {
    var cineId = this.value;
    if (cineId) {
        fetch('cargar_asientos.php?cine_id=' + cineId)
            .then(response => response.json())
            .then(data => {
                var asientosDiv = document.getElementById('asientos');
                asientosDiv.innerHTML = ''; 

                data.forEach(function(asiento) {
                    var div = document.createElement('div');
                    div.classList.add('asiento');
                    div.classList.add(asiento.estado);  
                    div.setAttribute('data-id', asiento.id);
                    div.textContent = asiento.fila + "-" + asiento.columna;

                    div.addEventListener('click', function() {
                        if (asiento.estado === 'disponible') {
                            this.classList.toggle('seleccionado');
                            updateSelectedSeats();
                        }
                    });

                    asientosDiv.appendChild(div);
                });
            })
            .catch(error => {
                console.error("Error al cargar los asientos:", error);
            });
    }
});

function updateSelectedSeats() {
    var selectedSeats = [];
    document.querySelectorAll('.asiento.seleccionado').forEach(function(seat) {
        selectedSeats.push(seat.getAttribute('data-id'));
    });
    document.getElementById('asientos_seleccionados').value = JSON.stringify(selectedSeats);
}
