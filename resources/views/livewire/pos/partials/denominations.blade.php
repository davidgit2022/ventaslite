<div class="row mt-3">
    <div class="col-sm-12">
        <div class="connect-sorting">
            <h5 class="text-center mb-2">DENOMINACIONES</h5>
            <div class="container">
                <div class="row">
                    @foreach ($denominations as $denomination)
                        <div class="col-sm mt-2">
                            <button class="btn btn-dark btn-block den"
                                wire:click.prevent='ACash({{ $denomination->value }})'>
                                {{ $denomination->value > 0 ? '$' . number_format($denomination->value) : 'Exacto' }}
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="connect-sorting-content mt-4">
                <div class="card simple-title-task ui-sortable-handle">
                    <div class="card-body">
                        <div class="input-group input-group-md mb-3">
                            <div class="input-group-prepend" style="width: 95px">
                                <span class="input-group-text input-gp hideonsm"
                                    style="background: #3b3f5c; color:white">EFECTIVO F8</span>
                            </div>
                            <input type="number" id="cash" wire:model='efectivo' wire:keydown.enter='saveSale'
                                class="form-control text-center" value="{{ $efectivo }}">
                            <div class="input-group-append">
                                <span class="input-group text" wire:click="$set('efectivo',0)"
                                    style="background: #3b3f5c; color:white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="54" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"
                                        style="margin-top: 12px">
                                        <path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path>
                                        <line x1="18" y1="9" x2="12" y2="15"></line>
                                        <line x1="12" y1="9" x2="18" y2="15"></line>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <h4 class="text-muted">Cambio: ${{ number_format($change, 2) }}</h4>
                        <div class="row justify-content-between mt-5">
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                @if ($total > 0)
                                    <button onclick="Confirm('','clearCart','¿SEGURO DE ELIMINAR EL CARRITO?')"
                                        class="btn btn-dark mtmobile">
                                        <small>CANCELAR F4</small>
                                    </button>
                                @endif
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                @if ($efectivo >= $total && $total > 0)
                                    <button class="btn btn-dark btn-md btn-block" wire:click.prevent='saveSale'>
                                        GUARDAR F9
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
