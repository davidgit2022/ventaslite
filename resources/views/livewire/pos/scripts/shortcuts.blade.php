<script>
    let listener = new window.keypress.Listener();

    listener.simple_combo("f9", function() {
        window.livewire.emit('saveSale')
    })

    listener.simple_combo("f8", function() {
        document.getElementById('cash').value = ''
        document.getElementById('cash').focus()
    })

    listener.simple_combo("f4", function() {
        let total = parseFloat(document.getElementById('hiddenTotal').value)
        if (total > 0) {
            Confirm(0, 'clearCart', '¿SEGURO DE ELIMINAR EL CARRITO?')
        } else {
            noty('AGREGAR PRODUCTOS A LA VENTA')
        }
    })
</script>
