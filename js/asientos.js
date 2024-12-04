    
    function actualizarAsientosSeleccionados() {
        const asientosSeleccionados = [];
        
        const asientos = document.querySelectorAll('.asiento.seleccionado');
        
        asientos.forEach(asiento => {
    
            asientosSeleccionados.push(asiento.id.replace('asiento_', '')); // Eliminar 'asiento_' del id
        });
        
    
        document.getElementById('asientos_seleccionados').value = JSON.stringify(asientosSeleccionados);
    }

    document.querySelectorAll('.asiento').forEach(asiento => {
        asiento.addEventListener('click', function() {
            this.classList.toggle('seleccionado');
            actualizarAsientosSeleccionados();
        });
    });
