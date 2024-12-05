$(document).ready(function () {
    itemsPerPage = 8;
    updatePagination();
    $('#empresaForm, #usuarioForm').submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        const currentPath = window.location.pathname;

        let method = currentPath === '/empresas' ? $('#method').val() : $('#methodU').val();
        $.ajax({
            url: url,
            method: method,
            data: form.serialize(),
            success: function (response) {
                // Procesar la respuesta exitosa
                Toast.fire({
                    icon: response.type,
                    title: response.message
                });

                if (currentPath === '/empresas') {
                    $('#modalForm').modal('hide');
                } else {
                    $('#modalFormUsuario').modal('hide');
                }
                mostrarDatos();
            },
            error: function (xhr) {
                // Validaciones de datos fallida
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, error) {
                        // Insertar el mensaje de error en el span correspondiente
                        $('#error-' + key).text(error[0]);
                    });
                } else {
                    console.log(xhr.responseJSON);
                    // Manejo de errores generales
                    Toast.fire({
                        icon: 'error',
                        title: 'Ocurrió un error. Por favor, inténtelo de nuevo.'
                    });

                }
            }
        });
    });

    $('#confirmarForm').submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let method = $('#methodC').val();

        $.ajax({
            url: url,
            method: method,
            data: form.serialize(),
            success: function (response) {
                // Procesar la respuesta exitosa
                Toast.fire({
                    icon: response.type,
                    title: response.message
                });
                $('#modalConfirm').modal('hide');
                if (response.type == 'success') {
                    mostrarDatos();
                }
            },
            error: function (xhr) {
                console.log(xhr.responseJSON);
                // Manejo de errores generales
                Toast.fire({
                    icon: 'error',
                    title: 'Ocurrió un error. Por favor, inténtelo de nuevo.'
                });

            }
        });
    });

    $('#btnAgregar').click(function (e) {
        e.preventDefault();
        agregar();
    });

    $(document).on('click', '.btnEditar', function () {
        editar($(this).data('id'));
    });

    $(document).on('click', '.btnEliminar', function () {
        eliminar($(this).data('id'));
    });

    $(document).on('click', '.btnDeshabilitar', function () {
        baja($(this).data('id'));
    });

    $(document).on('click', '.btnHabilitar', function () {
        alta($(this).data('id'));
    });

    $(document).on('click', '.btnCredencial', function () {
        enviarCredenciales($(this).data('id'));
    });

    //Evento para ir a detalle de un registro
    $('#tableBody').on('click', '.tr-link', function (e) {
        if (!$(e.target).closest('a').length) {
            let id = $(this).data('id');
            window.location.href = `/empresas/${id}`;
        }
    });
});


function agregar() {
    //Limpieza de spams
    const errorSpans = document.querySelectorAll('span.text-danger');
    errorSpans.forEach(function (span) {
        span.innerHTML = '';
    });
    const currentPath = window.location.pathname;

    // Verificar si estamos en '/empresas' o '/empresas/{id}'
    if (currentPath === '/empresas') {
        //Preparación de formulario
        $('#titulo').text("Nuevo Registro");
        $('#nombre').val('');
        $('#direccion').val('');
        $('#correo').val('');

        //otros
        $('#method').val('POST'); // Cambiar a POST
        $('#empresaForm').attr('action', '');
        $('#modalForm').modal('show');
    } else {

        //Preparación de formulario
        $('#tituloU').text('Nuevo Registro');
        $('#clave').val('');
        $('#clave1').val('');

        //otros
        $('#methodU').val('POST'); // Cambiar a POST
        $('#usuarioForm').attr('action', window.location.pathname + '/usuarios');
        $('#modalFormUsuario').modal('show');
    }

}

function editar(id) {
    const currentPath = window.location.pathname;

    // Verificar si estamos en '/empresas' o '/empresas/{id}'
    if (currentPath === '/empresas') {
        var idEmpresa = id;
        $.get('/empresas/' + idEmpresa + '/edit', function (obj) {
            //Limpieza de spams
            const errorSpans = document.querySelectorAll('span.text-danger');
            errorSpans.forEach(function (span) {
                span.innerHTML = '';
            });
            //Preparación de formulario
            $('#titulo').text("Editar Registro");
            $('#nombre').val(obj.nombre);
            $('#direccion').val(obj.direccion);
            $('#correo').val(obj.correo);

            //otros
            $('#method').val('PUT'); // Cambiar a PUT
            $('#empresaForm').attr('action', '/empresas/' + idEmpresa);
            $('#modalForm').modal('show');
        });
    } else {
        var idUsuario = id;
        console.log(currentPath + '/usuarios/'
            + idUsuario + '/edit');

        $.get(currentPath + '/usuarios/' + idUsuario + '/edit', function (obj) {
            //Limpieza de spams
            const errorSpans = document.querySelectorAll('span.text-danger');
            errorSpans.forEach(function (span) {
                span.innerHTML = '';
            });
            //Preparación de formulario
            $('#tituloU').text("Cambiar contraseña para: " + obj.idUsuario);
            $('#clave').val('');
            $('#clave1').val('');
            //otros
            $('#methodU').val('PUT'); // Cambiar a PUT
            $('#usuarioForm').attr('action', currentPath + '/usuarios/' + idUsuario);
            $('#modalFormUsuario').modal('show');
        });
    }
}

function eliminar(id) {
    const currentPath = window.location.pathname;
    // Verificar si estamos en '/empresas' o '/empresas/{id}'
    if (currentPath === '/empresas') {
        var idEmpresa = id;
        $('#confirmarForm').attr('action', currentPath + '/' + idEmpresa);
    } else {
        var idUsuario = id;
        $('#confirmarForm').attr('action', currentPath + '/usuarios/' + idUsuario);
    }
    $('#methodC').val('Delete')
    $('#dialogoT').text('Está a punto de eliminar permanentemente el registro. ¿Desea continuar?')
}

function baja(id) {
    //Preparacion visual y direccion de la accion en el formulario
    const currentPath = window.location.pathname;
    // Verificar si estamos en '/empresas' o '/empresas/{id}'
    if (currentPath === '/empresas') {
        var idEmpresa = id;
        $('#confirmarForm').attr('action', currentPath + '/baja/' + idEmpresa);
    } else {
        var idEmpresa = id;
        $('#confirmarForm').attr('action', currentPath + '/usuarios/baja/' + idEmpresa);
    }
    $('#methodC').val('get')
    $('#dialogoT').text('Está a punto de deshabilitar el registro. ¿Desea continuar?')
}

function alta(id) {
    const currentPath = window.location.pathname;
    var url = currentPath + (currentPath == '/empresas' ? '/alta/' + id : '/usuarios/alta/' + id);
    $.ajax({
        url: url,
        method: 'get',
        success: function (response) {
            // Procesar la respuesta exitosa
            Toast.fire({
                icon: response.type,
                title: response.message
            });

            if (response.type == 'success') {
                mostrarDatos();
            }
        },
        error: function (xhr) {
            console.log(xhr.responseJSON);
            // Manejo de errores generales
            Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error. Por favor, inténtelo de nuevo.'
            });

        }
    });
}

function enviarCredenciales(idEmpresa) {
    // Mostrar el modal de carga
    $('#modalCarga').modal('show');
    $.ajax({
        url: '/enviar-credenciales/' + idEmpresa,
        method: 'get',
        success: function (response) {
            Toast.fire({
                icon: response.type,
                title: response.message
            });

            if (response.type == 'success') {
                mostrarDatos();
            }

        },
        error: function (xhr) {
            $('#modalCarga').modal('hide');
            console.log(xhr.responseJSON);
            // Manejo de errores generales
            Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error. Por favor, inténtelo de nuevo.'
            });

        }, complete: function () {
            // Esto se ejecutará siempre, independientemente de si la solicitud fue exitosa o no
            $('#modalCarga').modal('hide');
        }
    });
}

function mostrarDatos() {
    const currentPath = window.location.pathname;
    var url = currentPath === '/empresas' ? '/obtener-empresas' : currentPath + '/obtener-usuarios';

    if (currentPath === '/empresas') {
        showEmpresas(url);
    } else {
        showUsuarios(url);
    }
}

function showUsuarios(url) {
    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#tableBody').empty(); // Limpiar el tbody antes de llenarlo
            originalData = data.usuarios.map(c => {
                // Lógica para los botones dependiendo del estado
                let acciones = "";
                if (c.estado != 0) {
                    if (c.rol != 0) {
                        acciones = `
                       <a role="button" data-bs-toggle="modal" data-bs-target="#modalFormUser" data-id="${c.idUsuario}" data-bs-tt="tooltip"
                        data-bs-original-title="Cambiar contraseña"class="btnEditar me-2">
                            <i class="fas fa-pen text-secondary"></i>
                        </a>
                        `
                    }
                    if (c.idUsuario != data.idUsuario) {
                        acciones += `
                        
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${c.idUsuario}" data-bs-tt="tooltip" data-bs-original-title="Deshabilitar" class="btnDeshabilitar me-3">
                            <i class="fas fa-minus-circle text-secondary"></i>
                        </a>
                    `;
                    }
                } else {
                    acciones = `
                        <a role="button" data-id="${c.idUsuario}" data-bs-tt="tooltip" data-bs-original-title="Habilitar" class="btnHabilitar me-3">
                            <i class="fas fa-arrow-up text-secondary"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${c.idUsuario}" data-bs-tt="tooltip" data-bs-original-title="Eliminar" class="btnEliminar me-3">
                            <i class="fas fa-trash text-secondary"></i>
                        </a>
                    `;
                }
                let horaC12 = new Date(c.fecha_creado).toLocaleTimeString('es-ES', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true // Asegura el formato de 12 horas
                });

                let horaA12 = c.fecha_actualizado != null ? new Date(c.fecha_actualizado).toLocaleTimeString('es-ES', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true // Asegura el formato de 12 horas
                }) : '';

                const tr = document.createElement('tr');
                // tr.classList.add('tr-link');
                tr.setAttribute('data-id', c.idEmpresa);

                tr.innerHTML = `
                    <td>
                        <div class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                            <i class="fas fa-user opacity-10 text-sm"></i>
                        </div>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.idUsuario}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.usuario}</p>
                        ${c.idUsuario == data.idUsuario ? '<p class="text-xxs mb-0">(Tu)</p>' : ''}
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">
                           ${c.rol == 1 ? 'Administrador' : 'Colaborador'}
                        </p>
                    </td>
                     <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${new Date(c.fecha_creado).toLocaleDateString('es-ES')}</p>
                        <p class="text-xxs mb-0">(${horaC12.replace(/(AM|PM)/, '$1'.toUpperCase())})</p>
                    </td>
                     <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.fecha_actualizado != null ? new Date(c.fecha_actualizado).toLocaleDateString('es-ES') : ''}</p>
                            <p class="text-xxs mb-0">${c.fecha_actualizado!=null?'('+horaA12.replace(/(AM|PM)/, '$1'.toUpperCase())+')':''}</p>
                    </td>

                    <td class="px-1 text-sm">
                        <span class="badge badge-xs opacity-7 bg-${c.estado == 1 ? 'success' : (c.estado == 2 ? 'info' : 'secondary')}">
                            ${c.estado == 1 ? 'activo' : (c.estado == 2 ? 'Pendiante de activación' : 'Inactivo')}
                        </span>
                    </td>
                    <td>
                        ${acciones}
                    </td>
                `;
                return tr;
            });


            // Inicializar los datos actuales
            currentData = [...originalData];
            // Actualizar la paginación
            updatePagination();
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar:', xhr.responseJSON);
            Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error al cargar los registros.'
            });
        }
    });
}

function showEmpresas(url) {
    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#tableBody').empty(); // Limpiar el tbody antes de llenarlo
            originalData = data.map(c => {
                // Lógica para los botones dependiendo del estado
                let acciones = "";
                if (c.estado == 1) {
                    if (c.usuarios.length == 0) {
                        acciones = `
                        <a role="button" data-id="${c.idEmpresa}" data-bs-tt="tooltip"
                        data-bs-original-title="Enviar Credenciales" class="btnCredencial me-3">
                            <i class="fas fa-user-shield text-secondary"></i>
                        </a>
                        `
                    }

                    acciones += `
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalForm" data-id="${c.idEmpresa}" data-bs-tt="tooltip" data-bs-original-title="Editar" class="btnEditar me-3">
                            <i class="fas fa-pen text-secondary"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${c.idEmpresa}" data-bs-tt="tooltip" data-bs-original-title="Deshabilitar" class="btnDeshabilitar me-3">
                            <i class="fas fa-minus-circle text-secondary"></i>
                        </a>
                    `;
                } else {
                    acciones = `
                        <a role="button" data-id="${c.idEmpresa}" data-bs-tt="tooltip" data-bs-original-title="Habilitar" class="btnHabilitar me-3">
                            <i class="fas fa-arrow-up text-secondary"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${c.idEmpresa}" data-bs-tt="tooltip" data-bs-original-title="Eliminar" class="btnEliminar me-3">
                            <i class="fas fa-trash text-secondary"></i>
                        </a>
                    `;
                }
                const tr = document.createElement('tr');
                tr.classList.add('tr-link');
                tr.setAttribute('data-id', c.idEmpresa);

                tr.innerHTML = `
                    <td style="width: 9%">
                        <div class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                            <i class="fas fa-building opacity-10 text-sm"></i>
                        </div>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.idEmpresa}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.nombre}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.direccion}</p>
                    </td>
                     <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.correo}</p>
                    </td>
                    <td class="px-1 text-sm">
                        <span class="badge badge-xs opacity-7 bg-${c.estado == 1 ? 'success' : 'secondary'}">
                            ${c.estado == 1 ? 'activo' : 'inactivo'}
                        </span>
                    </td>
                    <td>
                        ${acciones}
                    </td>
                `;
                return tr;
            });


            // Inicializar los datos actuales
            currentData = [...originalData];
            // Actualizar la paginación
            updatePagination();
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar:', error);
            Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error al cargar los registros.'
            });
        }
    });
}