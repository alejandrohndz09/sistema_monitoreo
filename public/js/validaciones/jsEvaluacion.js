$(document).ready(function () {
    $('#evaluacionForm').submit(function (e) {
        const errorSpans = document.querySelectorAll('span.text-danger');
        errorSpans.forEach(function (span) {
            span.innerHTML = '';
        });
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let formData = new FormData(this);
        // Verificar qué preguntas faltan y añadirlas al FormData
        $('[name^="evaluacion"]').each(function () {
            if (!formData.has(this.name)) {
                formData.append(this.name, ''); // Agregar un valor vacío si no está presente
            }
        });
        // for (const [key, value] of formData.entries()) {
        //     console.log(`${key}:`, value);
        // }
        $.ajax({
            url: url,
            method: 'post',
            data: formData,
            contentType: false, // Evitar que jQuery procese el tipo de contenido
            processData: false, // Evitar que jQuery convierta los datos en una cadena de consulta
            cache: false,
            success: function (response) {
                // Procesar la respuesta exitosa
                Toast.fire({
                    icon: response.type,
                    title: response.message
                });
                
                window.location.href = response.redirect;
            },
            error: function (xhr) {
                // Validaciones de datos fallida
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    // console.log(errors);
                    $.each(errors, function (key, error) {
                        // Reemplazar "evaluacion." por "error-evaluacion-" y escapar caracteres especiales
                        let escapedKey = key.replace('evaluacion.', 'evaluacion-').replace(/\./g, '\\.');
                        let spanId = '#error-' + escapedKey;
                        // Mostrar el mensaje de error en el span correspondiente
                        $(spanId).text(error[0]);
                    });
                    Toast.fire({
                        icon: 'info',
                        title: 'Aun no ha respondido por completo la evaluación.'
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

    $(document).on('click', '.btnEliminar', function () {
        eliminar($(this).data('id'));
    });

    $(document).on('click', '.btnDeshabilitar', function () {
        baja($(this).data('id'));
    });

    $(document).on('click', '.btnHabilitar', function () {
        alta($(this).data('id'));
    });

    //Abrir nueva vista
    $('#tableBody').on('click', '.tr-link', function (e) {
        if (!$(e.target).closest('a').length) {
            let id = $(this).data('id');
            window.location.href = `/evaluaciones/${id}`;
        }
    });

    $(".btn-check").change(function (e) {
        // Evitar comportamiento por defecto
        // e.preventDefault();
        // Obtener el name del radio (correspondiente a la pregunta)
        let name = $(this).attr('name');
        // Crear el id del span correspondiente usando el name del radio
        // Reemplazar evaluacion[] por evaluacion- y escapar puntos
        let spanId = '#error-' + name.replace('evaluacion[', 'evaluacion-').replace(']', '').replace(/\./g, '\\.');
        // Vaciar el contenido del span de error
        $(spanId).text('');
    });
});

function eliminar(idEvaluacion) {
    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', '/evaluaciones/' + idEvaluacion);
    $('#methodC').val('Delete')
    $('#dialogoT').text('Está a punto de eliminar permanentemente el registro. ¿Desea continuar?')
}

function baja(idEvaluacion) {
    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', '/evaluaciones/baja/' + idEvaluacion);
    $('#methodC').val('get')
    $('#dialogoT').text('Está a punto de deshabilitar el registro. ¿Desea continuar?')
}

function alta(idEvaluacion) {
    $.ajax({
        url: '/evaluaciones/alta/' + idEvaluacion,
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

function mostrarDatos() {
    $.ajax({
        url: '/evaluaciones/obtener-evaluaciones',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#tableBody').empty(); // Limpiar el tbody antes de llenarlo

            originalData = data.map(c => {
                // Lógica para los botones dependiendo del estado
                let acciones;
                if (c.estado == 1) {
                    acciones = `
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" 
                           data-id="${c.idEvaluacion}" data-bs-tt="tooltip" 
                           data-bs-original-title="Deshabilitar" class="btnDeshabilitar me-3">
                            <i class="fas fa-minus-circle text-secondary"></i>
                        </a>
                    `;
                } else {
                    acciones = `
                        <a role="button" data-id="${c.idEvaluacion}" data-bs-tt="tooltip" 
                           data-bs-original-title="Habilitar" class="btnHabilitar me-3">
                            <i class="fas fa-arrow-up text-secondary"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" 
                           data-id="${c.idEvaluacion}" data-bs-tt="tooltip" 
                           data-bs-original-title="Eliminar" class="btnEliminar me-3">
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

                let horaA12 = c.fecha_actualizado!=null?new Date(c.fecha_actualizado).toLocaleTimeString('es-ES', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true // Asegura el formato de 12 horas
                }):'';
                // Crear la fila de la tabla
                const tr = document.createElement('tr');
                tr.classList.add('tr-link');
                tr.setAttribute('data-id', c.idEvaluacion);

                tr.innerHTML = `
                        <td>
                             <div
                                class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                                <i class="fas fa-file opacity-10 text-sm"></i>
                            </div>
                        </td>
                        <td class="px-1">
                            <p class="text-xs font-weight-bold mb-0">${c.idEvaluacion}</p>
                        </td>
                        <td class="px-1">
                            <p class="text-xs font-weight-bold mb-0">
                                <i class="fas fa-user opacity-10 text-xs"></i>
                                ${c.usuario.usuario}
                            </p>
                        </td>
                        <td class="px-1">
                            <p class="text-xs font-weight-bold mb-0">${new Date(c.fecha_creado).toLocaleDateString('es-ES')}</p>
                            <p class="text-xxs mb-0">(${horaC12.replace(/(AM|PM)/, '$1'.toUpperCase())})</p>
                        </td>
                        <td class="px-1">
                            <p class="text-xs font-weight-bold mb-0">${c.fecha_actualizado!=null?new Date(c.fecha_actualizado).toLocaleDateString('es-ES'):''}</p>
                            <p class="text-xxs mb-0">${c.fecha_actualizado!=null?'('+horaA12.replace(/(AM|PM)/, '$1'.toUpperCase())+')':''}</p>
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
            console.error('Error al cargar los productos:', error);
            Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error al cargar los productos.'
            });
        }
    });
}


