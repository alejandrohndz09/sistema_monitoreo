<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-mg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 ">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="text-dark" id="titulo"></h3>
                        <p class="mb-0">(*) Campos Obligatorios</p>
                    </div>
                    <div class="card-body">
                        <form role="form text-left" id="empresaForm">
                            @csrf
                            <input type="hidden" name="_method" id="method" value="POST"> <!-- Para edición -->
                            <div class="row mb-4">

                                <label>Nombre: *</label>
                                <div class="input-group mb-1">
                                    <input type="text" name="nombre" id="nombre" class="form-control"
                                        placeholder="Nombre" autocomplete="off">
                                </div>
                                <span id="error-nombre" class="text-danger text-xs mb-3"></span>

                                <label>Direccion: *</label>
                                <div class="input-group mb-1">
                                    <input type="text" name="direccion" id="direccion" class="form-control"
                                        placeholder="Dirección" autocomplete="off">
                                </div>
                                <span id="error-direccion" class="text-danger text-xs mb-3"></span>

                                <label>Correo: *</label>
                                <div class="input-group mb-1">
                                    <input type="text" name="correo" id="correo" class="form-control"
                                        placeholder="Ej. empresa@dominio.com" autocomplete="off">
                                </div>
                                <span id="error-correo" class="text-danger text-xs mb-3"></span>
                            </div>

                            <div class="text-end">
                                <button type="reset" data-bs-dismiss="modal" style="border-color:transparent"
                                    class="btn btn-outline-dark btn-sm mt-4 mb-0">
                                    <i class="fas fa-undo text-xs"></i>&nbsp;&nbsp;Cancelar</button>
                                <button type="submit" class="btn btn-icon bg-gradient-success btn-sm mt-4 mb-0">
                                    <i class="fas fa-check text-xs"></i>&nbsp;&nbsp;Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFormUsuario" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-mg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 ">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="text-dark" id="tituloU"></h3>
                        <p class="mb-0">(*) Campos Obligatorios</p>
                    </div>
                    <div class="card-body">
                        <form role="form text-left" id="usuarioForm">
                            @csrf
                            <input type="hidden" name="_method" id="methodU" value="POST"> <!-- Para edición -->
                            <div class="row mb-4">

                                <label>Contraseña: *</label>
                                <div class="input-group mb-1">
                                    <input type="text" name="clave" id="clave" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                                <span id="error-clave" class="text-danger text-xs mb-3"></span>

                                <label>Repetir contraseña: *</label>
                                <div class="input-group mb-1">
                                    <input type="text" name="clave1" id="clave1" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                                <span id="error-clave1" class="text-danger text-xs mb-3"></span>
                            </div>

                            <div class="text-end">
                                <button type="reset" data-bs-dismiss="modal" style="border-color:transparent"
                                    class="btn btn-outline-dark btn-sm mt-4 mb-0">
                                    <i class="fas fa-undo text-xs"></i>&nbsp;&nbsp;Cancelar</button>
                                <button type="submit" class="btn btn-icon bg-gradient-success btn-sm mt-4 mb-0">
                                    <i class="fas fa-check text-xs"></i>&nbsp;&nbsp;Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
