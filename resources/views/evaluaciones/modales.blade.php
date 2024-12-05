<div class="modal fade" id="modalConfirm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-mg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 ">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="text-dark">Confirmar operación</h3>
                        <p id="dialogoT" class="mb-0">Está a punto de deshabilitar el registro. ¿Desea continuar?</p>
                    </div>
                    <div class="card-body">
                        <form role="form text-left" id="confirmarForm">
                            @csrf
                            <input type="hidden" id="methodC">
                            <div class="text-end">
                                <button type="reset" data-bs-dismiss="modal" style="border-color:transparent"
                                    class="btn btn-outline-dark btn-sm mt-4 mb-0">
                                    <i class="fas fa-times text-xs"></i>&nbsp;&nbsp;No</button>
                                <button type="submit" class="btn btn-icon bg-gradient-danger btn-sm mt-4 mb-0">
                                    <i class="fas fa-check text-xs"></i>&nbsp;&nbsp;Sí</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCarga" tabindex="-1" role="dialog" aria-labelledby="modalCargaLabel" aria-hidden="true"  data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" >
        <div class="modal-content text-center">
            <div class="modal-body">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
                <h5 class="mt-3">Enviando credenciales a la empresa...</h5>
            </div>
        </div>
    </div>
</div>
