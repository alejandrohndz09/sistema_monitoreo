document.addEventListener('DOMContentLoaded', function() {
    var checkbox = document.getElementById('mostrarClave');
    var claveInput = document.getElementById('contraseña');

    checkbox.addEventListener('change', function() {
        if (checkbox.checked) {
            // Si el checkbox está marcado, cambia el tipo del input a 'text'
            claveInput.type = 'text';
        } else {
            // Si el checkbox está desmarcado, vuelve a establecer el tipo del input a 'password'
            claveInput.type = 'password';
        }
    });
});

$('#recuperar').on('shown.bs.modal', function () {
    // Limpiar el campo y posibles errores previos
    $('#usuarioR').val('');
    $('#error-usuarioR').text('');

    // Enfocar automáticamente el campo de usuario
    $('#usuarioR').focus();
});

$(document).ready(function () {

   
    $(document).on('submit', '#formRecuperar', function(e) {
        e.preventDefault(); // Prevenir el comportamiento por defecto del formulario
        $('#error-usuarioR').text('');
        const form = $(this); // El formulario actual
        const url = form.attr('action'); // La URL especificada en el atributo action
        const data = form.serialize(); // Serializar los datos del formulario
    
        // Mostrar un indicador de carga, si es necesario
        // $('#loadingSpinner').show();
    
        $.ajax({
            url: url,
            method: 'post', // Método de envío
            data: data, // Datos del formulario
            success: function(response) {
                // Ocultar el indicador de carga
                // $('#loadingSpinner').hide();
                if (response.redirect) {
                    // Redirigir si el backend envía una URL de redirección
                    window.location.href = response.redirect;
                } else {
                    // Mostrar alertas si hay mensajes
                    Toast.fire({
                        icon: response.type,
                        title: response.message
                    });
                }
            },
            error: function (xhr) {
                // Validaciones de datos fallida
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, error) {
                        // Insertar el mensaje de error en el span correspondiente
                        $('#error-' + key).text(error[0]);
                    });
                }  else {
                    console.log(xhr.responseJSON);
                    // Manejo de errores generales
                    Toast.fire({
                        icon: 'error',
                        title: 'Ocurrió un error. Por favor, inténtelo de nuevo.'
                    });

                }
            },
        });
    });
});