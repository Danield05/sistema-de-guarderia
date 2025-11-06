$("#frmAcceso").on('submit',function(e)
{
    e.preventDefault();
    logina=$("#logina").val();
    clavea=$("#clavea").val();

    // Validar campos vacíos
    if(logina.trim() === "" || clavea.trim() === "") {
        // Crear HTML personalizado para la alerta
        var html = '<div class="text-center p-4">' +
                   '<div class="mb-3"><i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i></div>' +
                   '<h4 class="text-warning mb-2">⚠️ Campos Incompletos</h4>' +
                   '<p class="mb-0">Por favor, complete todos los campos antes de continuar.</p>' +
                   '</div>';
        
        bootbox.dialog({
            title: '<i class="fas fa-warning"></i> Validación',
            message: html,
            size: 'small',
            buttons: {
                ok: {
                    label: "Entendido",
                    className: 'btn-warning'
                }
            }
        });
        return false;
    }

    // Mostrar indicador de carga
    var $btn = $('button[type="submit"]');
    $btn.html('<i class="fas fa-spinner fa-spin"></i> Verificando...').prop('disabled', true);

    $.post("../ajax/usuario.php?op=verificar",
       {"logina":logina,"clavea":clavea},
       function(data)
   {
       $btn.html('Ingresar').prop('disabled', false);
       
       if (data!="null")
       {
           // Alerta de bienvenida
           var html = '<div class="text-center p-4">' +
                      '<div class="mb-3"><i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i></div>' +
                      '<h4 class="text-success mb-2">¡Bienvenido al Sistema!</h4>' +
                      '<p class="text-muted mb-0">Credenciales correctas. Redirigiendo al escritorio...</p>' +
                      '<div class="progress mt-3">' +
                      '<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" style="width: 100%"></div>' +
                      '</div>' +
                      '</div>';
           
           bootbox.dialog({
               title: '<i class="fas fa-unlock text-success"></i> Acceso Autorizado',
               message: html,
               size: 'small',
               closeButton: false
           });

           // Redirección después de mostrar la alerta
           setTimeout(function() {
               $(location).attr("href","escritorio.php");
           }, 2000);
       }
        else
        {
            // Alerta de error
            var html = '<div class="text-center p-4">' +
                      '<div class="mb-3"><i class="fas fa-exclamation-triangle text-danger" style="font-size: 2.5rem;"></i></div>' +
                      '<h5 class="text-danger mb-2">❌ Credenciales Incorrectas</h5>' +
                      '<p class="mb-1">Usuario y/o Password inválidos</p>' +
                      '<p class="text-muted mb-0">Verifique sus credenciales e inténtelo nuevamente.</p>' +
                      '<small class="text-muted">💡 Sugerencia: Recuerde usar su email completo</small>' +
                      '</div>';
            
            bootbox.dialog({
                title: '<i class="fas fa-times-circle text-danger"></i> Error de Autenticación',
                message: html,
                size: 'small',
                buttons: {
                    ok: {
                        label: "Intentar de Nuevo",
                        className: 'btn-danger'
                    }
                }
            });
        }
    }).fail(function() {
        // Manejo de errores de conexión
        $btn.html('Ingresar').prop('disabled', false);
        var html = '<div class="text-center p-4">' +
                  '<div class="mb-3"><i class="fas fa-wifi text-warning" style="font-size: 3rem;"></i></div>' +
                  '<h5 class="text-warning mb-2">🌐 Error de Conexión</h5>' +
                  '<p class="mb-0">No se pudo conectar con el servidor. Verifique su conexión a internet e inténtelo nuevamente.</p>' +
                  '</div>';
        
        bootbox.dialog({
            title: '<i class="fas fa-exclamation-triangle text-warning"></i> Sin Conexión',
            message: html,
            size: 'small',
            buttons: {
                ok: {
                    label: "Reintentar",
                    className: 'btn-warning'
                }
            }
        });
    });
})