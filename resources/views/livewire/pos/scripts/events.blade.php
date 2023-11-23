<script>
    document.addEventListener('DOMContentLoaded', function(){
        windown.livewire.on('scan-ok', Msg => {
            noty(Msg)
        })
        windown.livewire.on('scan-notfound', Msg =>{
            alert('fdgfdg')
            noty(Msg, 2)
        })
        windown.livewire.on('no-stock', Msg => {
            noty(Msg,2)
        })
        windown.livewire.on('sale-error', Msg => {
            noty(Msg)
        })
        windown.livewire.on('print-ticket', saleId => {
            windown.open("print://" + saleId, '_blank')
        })
    })
</script>
