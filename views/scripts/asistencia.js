var datosAsistencia = [];
var tablaActual = null;

//función que se ejecuta al inicio
function init(){
   // Cargar datos inmediatamente
   cargarDatosAsistencia();
   cargarEstadisticas();

   $("#formulario_asis").on("submit",function(e){
   	guardaryeditarasis(e);
   });

   // Filtrar secciones cuando se selecciona un aula
   $("#filtro_aula").on("change", function() {
       filtrarSecciones();
   });

   // Cargar estadísticas inmediatamente y periódicamente
   cargarEstadisticas();
   setInterval(function() {
       cargarEstadisticas();
   }, 30000);
}

//función para cargar estadísticas en tiempo real
function cargarEstadisticas(){
     $.ajax({
         url: "../ajax/asistencia.php?op=obtenerEstadisticas",
         type: "POST",
         data: {}, // Sin fecha para obtener estadísticas generales
         success: function(response){
             try {
                 console.log("Respuesta de estadísticas:", response); // Debug
                 const stats = JSON.parse(response);
                 console.log("Estadísticas parseadas:", stats); // Debug
                 actualizarEstadisticas(stats);
             } catch(e) {
                 console.error("Error al cargar estadísticas:", e);
                 console.error("Respuesta que falló:", response);
             }
         },
         error: function(xhr, status, error) {
             console.error("Error AJAX al obtener estadísticas:", status, error);
             console.error("Respuesta del servidor:", xhr.responseText);
         }
     });
 }

//función para actualizar las estadísticas en la interfaz
function actualizarEstadisticas(stats){
    // Verificar que los elementos existen
    const totalEl = $("#totalEstudiantes");
    const asistioEl = $("#estudiantesAsistieron");
    const faltoEl = $("#estudiantesFaltaron");
    const tardeEl = $("#estudiantesTarde");
    const permisoEl = $("#estudiantesPermiso");
    
    if (totalEl.length) totalEl.text(stats.total_estudiantes || 0);
    if (asistioEl.length) asistioEl.text(stats.asistieron || 0);
    if (faltoEl.length) faltoEl.text(stats.faltaron || 0);
    if (tardeEl.length) tardeEl.text(stats.tardanzas || 0);
    if (permisoEl.length) permisoEl.text(stats.permisos || 0);
}

//función para cargar datos de asistencia
function cargarDatosAsistencia(){
    $.ajax({
        url: '../ajax/asistencia.php?op=listarConFiltros',
        type: 'POST',
        data: {
            aula_id: $('#filtro_aula').val(),
            seccion_id: $('#filtro_seccion').val(),
            fecha_inicio: $('#filtro_fecha_inicio').val(),
            fecha_fin: $('#filtro_fecha_fin').val(),
            estado_id: $('#filtro_estado').val()
        },
        success: function(response){
            try {
                const data = JSON.parse(response);
                datosAsistencia = data.aaData;
                mostrarTabla();
            } catch(e) {
                console.error("Error al cargar datos:", e);
                alert("Error al cargar los datos de asistencia");
            }
        },
        error: function() {
            console.error("Error en la consulta AJAX");
            alert("Error de conexión al cargar datos");
        }
    });
}

//función para mostrar la tabla
function mostrarTabla(){
    const tbody = $('#tbody-asistencia');
    tbody.empty();
    
    if (datosAsistencia.length === 0) {
        tbody.html(`
            <tr>
                <td colspan="5" class="text-center" style="padding: 3rem; color: #999; font-style: italic; background: rgba(255, 255, 255, 0.95);">
                    <i class="fa fa-info-circle fa-3x mb-3 d-block" style="color: #ccc;"></i>
                    <h4 style="color: #666; margin-bottom: 1rem;">No hay registros de asistencia</h4>
                    <p style="color: #999; margin: 0;">Aplique filtros o registre asistencia para ver los datos</p>
                </td>
            </tr>
        `);
        return;
    }
    
    datosAsistencia.forEach(function(item, index) {
        const row = crearFila(item, index);
        tbody.append(row);
    });
}

//función para crear una fila de la tabla
function crearFila(item, index){
    const idAsistencia = item[0];
    const nombre = item[1];
    const fecha = item[2];
    const estado = item[3];
    const observaciones = item[4];
    
    // Determinar color y icono del estado
    let colorEstado = '#6c757d';
    let iconoEstado = '❓';
    let colorFondo = '#f8f9fa';
    
    switch(estado) {
        case 'Asistió':
            colorEstado = '#28a745';
            iconoEstado = '✅';
            colorFondo = '#d4edda';
            break;
        case 'Tardanza':
            colorEstado = '#ffc107';
            iconoEstado = '🕐';
            colorFondo = '#fff3cd';
            break;
        case 'Inasistencia':
            colorEstado = '#dc3545';
            iconoEstado = '❌';
            colorFondo = '#f8d7da';
            break;
        case 'Permiso':
            colorEstado = '#17a2b8';
            iconoEstado = '📋';
            colorFondo = '#d1ecf1';
            break;
        case 'Salida temprana':
            colorEstado = '#6c757d';
            iconoEstado = '⏰';
            colorFondo = '#e2e3e5';
            break;
    }
    
    return `
        <tr style="border-bottom: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease; background: ${colorFondo}20;">
            <td style="padding: 1rem;">
                <div class="btn-group" role="group" style="display: flex; gap: 0.5rem;">
                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="mostrarAsistencia('${idAsistencia}')"
                            style="border-radius: 20px; border: none; background: #ffc107; color: white; padding: 0.4rem 0.8rem; font-size: 0.85rem; font-weight: 500; box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);"
                            title="Editar">
                        <i class="fa fa-pencil"></i> Editar
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarAsistencia('${idAsistencia}')"
                            style="border-radius: 20px; border: none; background: #dc3545; color: white; padding: 0.4rem 0.8rem; font-size: 0.85rem; font-weight: 500; box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);"
                            title="Eliminar">
                        <i class="fa fa-trash"></i> Eliminar
                    </button>
                </div>
            </td>
            <td style="padding: 1rem; font-weight: 600; color: #3c8dbc; font-size: 1rem;">
                <i class="fa fa-child" style="margin-right: 0.5rem; color: #17a2b8;"></i>
                ${nombre}
            </td>
            <td style="padding: 1rem;">
                <span style="background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%); color: white; padding: 0.4rem 0.8rem; border-radius: 15px; font-size: 0.85rem; font-weight: 500; display: inline-flex; align-items: center; box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);">
                    <i class="fa fa-calendar" style="margin-right: 0.5rem;"></i>
                    ${fecha}
                </span>
            </td>
            <td style="padding: 1rem;">
                <span style="background: ${colorFondo}; color: ${colorEstado}; padding: 0.4rem 0.8rem; border-radius: 15px; font-size: 0.85rem; font-weight: 500; display: inline-flex; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <i style="margin-right: 0.5rem; font-size: 1rem;">${iconoEstado}</i>
                    ${estado}
                </span>
            </td>
            <td style="padding: 1rem; color: #666; font-size: 0.9rem; max-width: 300px; word-wrap: break-word;">
                ${observaciones ?
                    `<div style="background: rgba(108, 117, 125, 0.1); padding: 0.6rem; border-radius: 10px; border-left: 4px solid #6c757d;">
                        <i class="fa fa-comment" style="margin-right: 0.5rem; color: #6c757d;"></i>
                        ${observaciones}
                     </div>` :
                    `<span style="color: #999; font-style: italic; display: flex; align-items: center;">
                        <i class="fa fa-minus" style="margin-right: 0.5rem;"></i>
                        Sin observaciones
                     </span>`
                }
            </td>
        </tr>
    `;
}

// Función para mostrar/ocultar formulario
function mostrarFormulario(id) {
    if (id === 0 || id === undefined) {
        // AGREGAR NUEVA ASISTENCIA
        $('#formulario_asis')[0].reset();
        $('#modalTitulo').html('<i class="fa fa-calendar-check"></i> Registrar Asistencia del Día');

        // Mover modal al body para evitar problemas de stacking context
        $('#modalAsistencia').appendTo('body');

        $('#modalAsistencia').modal({
            backdrop: 'static',
            keyboard: true
        });

        // Limpiar formulario para nuevo registro
            $("#idasistencia").val("");
            $("#id_nino").val("");
            $("#estado_id").val("");
            $("#observaciones").val("");
            $("#aula_info").val("");
            $("#seccion_info").val("");
            cargarNinosParaAsistencia();
    } else {
        // EDITAR ASISTENCIA EXISTENTE
        mostrarAsistencia(id);
    }
}

// Alias para mantener compatibilidad
function registrarAsistenciaHoy(){
    mostrarFormulario(0);
}

//función para aplicar filtros
function aplicarFiltros(){
    mostrarCargando();
    cargarDatosAsistencia();
    
    // Actualizar estadísticas después de aplicar filtros
    setTimeout(cargarEstadisticas, 1000);
}

//función para limpiar filtros
function limpiarFiltros(){
    $("#filtro_aula").val("");
    $("#filtro_seccion").val("");
    $("#filtro_estado").val("");
    $("#filtro_fecha_inicio").val(getFechaActual(-7));
    $("#filtro_fecha_fin").val(getFechaActual());
    
    // Recargar tabla sin filtros
    mostrarCargando();
    cargarDatosAsistencia();
    
    // Actualizar estadísticas
    setTimeout(cargarEstadisticas, 500);
}

//función para mostrar loading
function mostrarCargando(){
    const tbody = $('#tbody-asistencia');
    tbody.html(`
        <tr>
            <td colspan="5" class="text-center" style="padding: 3rem; background: rgba(255, 255, 255, 0.95);">
                <div style="color: #666;">
                    <i class="fa fa-spinner fa-spin fa-2x mb-3 d-block" style="color: #667eea;"></i>
                    <h4 style="color: #666; margin-bottom: 0.5rem;">Cargando datos de asistencia...</h4>
                    <p style="color: #999; margin: 0; font-style: italic;">Por favor espere un momento</p>
                </div>
            </td>
        </tr>
    `);
}

//función para filtrar secciones por aula
function filtrarSecciones(){
    const aula_id = $("#filtro_aula").val();
    const seccionSelect = $("#filtro_seccion");
    
    // Limpiar opciones actuales (excepto la primera)
    seccionSelect.find('option:not(:first)').remove();
    
    if (aula_id) {
        // Cargar secciones filtradas por aula
        $.ajax({
            url: "../ajax/secciones.php?op=listarPorAula",
            type: "POST",
            data: {aula_id: aula_id},
            success: function(response){
                try {
                    const secciones = JSON.parse(response);
                    secciones.forEach(function(seccion) {
                        seccionSelect.append(`<option value="${seccion.id_seccion}">${seccion.nombre_seccion}</option>`);
                    });
                } catch(e) {
                    console.error("Error al cargar secciones:", e);
                }
            },
            error: function() {
                console.error("Error al cargar secciones por aula");
            }
        });
    }
}

//función para mostrar modal de asistencia
function mostrarAsistencia(id_asistencia){
    $('#formulario_asis')[0].reset();
    $('#modalTitulo').html('<i class="fa fa-edit"></i> Editar Asistencia');

    // Mover modal al body para evitar problemas de stacking context
    $('#modalAsistencia').appendTo('body');

    $('#modalAsistencia').modal({
        backdrop: 'static',
        keyboard: true
    });

    // Cargar datos de la asistencia
    $.ajax({
        url: "../ajax/asistencia.php?op=mostrar",
        type: "POST",
        data: {idasistencia: id_asistencia},
        success: function(response){
            try {
                const data = JSON.parse(response);
                $("#idasistencia").val(data.id_asistencia);
                $("#id_nino").val(data.id_nino);
                $("#estado_id").val(data.estado_id);
                $("#observaciones").val(data.observaciones);

                // Cargar niños y seleccionar el actual
                cargarNinosParaAsistencia(data.id_nino);

                // Cargar información del niño
                $("#aula_info").val(data.nombre_aula || "");
                $("#seccion_info").val(data.nombre_seccion || "");
            } catch(e) {
                console.error("Error al cargar datos de asistencia:", e);
                alert("Error al cargar los datos de asistencia");
            }
        },
        error: function() {
            alert("Error de conexión al cargar datos");
        }
    });
}

//función para eliminar asistencia
function eliminarAsistencia(id_asistencia){
    bootbox.confirm({
        message: "¿Está seguro de eliminar este registro de asistencia?",
        buttons: {
            confirm: {
                label: 'Sí, eliminar',
                className: 'btn-danger'
            },
            cancel: {
                label: 'Cancelar',
                className: 'btn-secondary'
            }
        },
        callback: function (result) {
            if (result) {
                $.ajax({
                    url: "../ajax/asistencia.php?op=eliminar",
                    type: "POST",
                    data: {idasistencia: id_asistencia},
                    success: function(response){
                        bootbox.alert("Registro eliminado correctamente");
                        cargarDatosAsistencia();
                        cargarEstadisticas();
                    },
                    error: function() {
                        bootbox.alert("Error al eliminar el registro");
                    }
                });
            }
        }
    });
}

//función para guardar/editar asistencia
function guardaryeditarasis(e){
      e.preventDefault();
      $("#btnGuardar_asis").prop("disabled",true);
      var formData=new FormData($("#formulario_asis")[0]);

      // Obtener el id del niño seleccionado
      var ninoSeleccionado = $("#nino_seleccionado").val();
      if (!ninoSeleccionado) {
          alert("Por favor seleccione un niño");
          $("#btnGuardar_asis").prop("disabled",false);
          return;
      }

      // Extraer el id del niño del valor seleccionado (formato: "id-nombre")
      var idNino = ninoSeleccionado.split('-')[0];

      // Verificar que el ID del niño sea válido
      if (!idNino || isNaN(idNino) || idNino <= 0) {
          alert("ID de niño inválido. Por favor seleccione un niño válido de la lista.");
          $("#btnGuardar_asis").prop("disabled",false);
          return;
      }

      // DEBUG: Mostrar qué ID se está enviando
      console.log("ID del niño a guardar:", idNino);
      console.log("Valor seleccionado completo:", ninoSeleccionado);

      // Asegurar que el campo id_nino tenga el valor correcto
      $("#id_nino").val(idNino);
      console.log("Campo id_nino establecido a:", $("#id_nino").val());

      // DEBUG: Mostrar todos los datos del formulario antes de enviar
      console.log("Datos del formulario:");
      for (let [key, value] of formData.entries()) {
          console.log(key + ': ' + value);
      }

      // Asegurar que id_nino tenga el valor correcto justo antes de enviar
      if (!formData.has('id_nino') || formData.get('id_nino') === '') {
          console.log("ADVERTENCIA: id_nino está vacío, agregándolo manualmente");
          formData.set('id_nino', idNino);
      }

      $.ajax({
      	url: "../ajax/asistencia.php?op=guardaryeditar",
      	type: "POST",
      	data: formData,
      	contentType: false,
      	processData: false,

      	success: function(datos){
      		console.log("Respuesta exitosa:", datos);

      		// Usar bootbox para un mensaje más elegante
      		if (datos.includes("correctamente")) {
      			bootbox.alert({
      				message: datos,
      				backdrop: true,
      				callback: function() {
      					$('#modalAsistencia').modal('hide');
      					cargarDatosAsistencia();
      					cargarEstadisticas();
      				}
      			});
      		} else {
      			bootbox.alert(datos);
      			$('#modalAsistencia').modal('hide');
      			cargarDatosAsistencia();
      			cargarEstadisticas();
      		}

      		$("#btnGuardar_asis").prop("disabled",false);
      	},
      	error: function(xhr, status, error) {
      		console.error("Error al guardar asistencia:", error);
      		console.error("Status:", status);
      		console.error("Respuesta completa:", xhr.responseText);

      		// Usar bootbox para errores también
      		let mensajeError = "Error al guardar la asistencia. Verifique los datos e intente nuevamente.";
      		if (xhr.responseText && xhr.responseText.includes("Integrity constraint violation")) {
      			mensajeError = "Error: El niño seleccionado no existe o ya tiene asistencia registrada para esta fecha.";
      		} else if (xhr.responseText && xhr.responseText.includes("Duplicate entry")) {
      			mensajeError = "Error: Este niño ya tiene asistencia registrada para la fecha seleccionada.";
      		}

      		bootbox.alert(mensajeError);
      		$("#btnGuardar_asis").prop("disabled",false);
      	}
      });
}

function exportarReporte(formato){
    console.log("Exportando reporte en formato:", formato);
    
    const aula_id = $("#filtro_aula").val();
    const seccion_id = $("#filtro_seccion").val();
    const fecha_inicio = $("#filtro_fecha_inicio").val();
    const fecha_fin = $("#filtro_fecha_fin").val();
    const estado_id = $("#filtro_estado").val();
    
    // Solo CSV y Excel son soportados
    if (formato === 'csv' || formato === 'xls') {
        let url = `../ajax/asistencia.php?op=generarReporte&formato=${formato}&_t=${new Date().getTime()}`;
        if (aula_id) url += `&aula_id=${aula_id}`;
        if (seccion_id) url += `&seccion_id=${seccion_id}`;
        if (fecha_inicio) url += `&fecha_inicio=${fecha_inicio}`;
        if (fecha_fin) url += `&fecha_fin=${fecha_fin}`;
        if (estado_id) url += `&estado_id=${estado_id}`;
        
        window.open(url, '_blank');
        
        // Mostrar mensaje de éxito
        setTimeout(function() {
            alert(`✅ Reporte ${formato.toUpperCase()} descargado exitosamente`);
        }, 1000);
    } else {
        alert("❌ Formato no soportado. Solo se permiten archivos CSV y Excel.");
    }
}

//función para obtener fecha actual
function getFechaActual(dias = 0) {
    const today = new Date();
    if (dias !== 0) {
        today.setDate(today.getDate() + dias);
    }
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

//función para mostrar ayuda
function mostrarAyuda() {
    alert(`📋 Control de Asistencia - Guía de Uso

🔍 Filtros disponibles:
• Aula: Filtra por aula específica
• Sección: Filtra por sección dentro del aula
• Fechas: Define el rango de fechas a consultar
• Estado: Filtra por tipo de asistencia

📊 Estados de asistencia:
• ✅ Asistió: El niño asistió normalmente
• 🕐 Tardanza: El niño llegó tarde
• ❌ Inasistencia: El niño no asistió
• 📋 Permiso: Ausencia con permiso autorizado
• ⏰ Salida temprana: El niño se retiró antes de tiempo

📁 Reportes:
• CSV: Archivo de texto separado por comas
• Excel: Formato nativo de Microsoft Excel`);
}

//función para cargar niños en el select del modal
function cargarNinosParaAsistencia(ninoSeleccionado = null){
    const aula_id = $("#filtro_aula").val();
    const seccion_id = $("#filtro_seccion").val();

    $.ajax({
        url: "../ajax/asistencia.php?op=obtenerNinos",
        type: "POST",
        data: {
            aula_id: aula_id,
            seccion_id: seccion_id
        },
        success: function(response){
            try {
                const ninos = JSON.parse(response);
                const select = $("#nino_seleccionado");
                select.empty();
                select.append('<option value="">Seleccione un niño</option>');

                ninos.forEach(function(nino) {
                    const selected = (ninoSeleccionado && ninoSeleccionado == nino.id_nino) ? 'selected' : '';
                    select.append(`<option value="${nino.id_nino}-${nino.nombre_completo}" ${selected}>${nino.nombre_completo}</option>`);
                });

                // Agregar evento change para cargar información del niño seleccionado
                select.off('change').on('change', function() {
                    const valorSeleccionado = $(this).val();
                    if (valorSeleccionado) {
                        const idNino = valorSeleccionado.split('-')[0];
                        cargarInformacionNino(idNino);
                    } else {
                        $("#aula_info").val("");
                        $("#seccion_info").val("");
                    }
                });

                // Si hay un niño preseleccionado, cargar su información
                if (ninoSeleccionado) {
                    cargarInformacionNino(ninoSeleccionado);
                }
            } catch(e) {
                console.error("Error al cargar niños:", e);
            }
        },
        error: function() {
            console.error("Error al cargar niños para asistencia");
        }
    });
}

//función para cargar información del niño seleccionado
function cargarInformacionNino(idNino) {
    $.ajax({
        url: "../ajax/ninos.php?op=mostrar",
        type: "POST",
        data: {idnino: idNino},
        success: function(response) {
            try {
                const data = JSON.parse(response);
                $("#aula_info").val(data.nombre_aula || "");
                $("#seccion_info").val(data.nombre_seccion || "");
            } catch(e) {
                console.error("Error al cargar información del niño:", e);
                $("#aula_info").val("");
                $("#seccion_info").val("");
            }
        },
        error: function() {
            console.error("Error al cargar información del niño");
            $("#aula_info").val("");
            $("#seccion_info").val("");
        }
    });
}

// Asegurar que las funciones estén disponibles globalmente
window.aplicarFiltros = aplicarFiltros;
window.limpiarFiltros = limpiarFiltros;
window.mostrarAsistencia = mostrarAsistencia;
window.eliminarAsistencia = eliminarAsistencia;
window.exportarReporte = exportarReporte;
window.registrarAsistenciaHoy = registrarAsistenciaHoy;
window.mostrarFormulario = mostrarFormulario;
window.mostrarAyuda = mostrarAyuda;

$(document).ready(function() {
    init();
});