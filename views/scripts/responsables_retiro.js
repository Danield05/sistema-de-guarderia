var tabla;

//funcion que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();

    $("#formulario").on("submit",function(e){
    	guardaryeditar(e);
    })

    // Cargar lista de niños al iniciar
    cargarNinos();

    // Funcionalidad de búsqueda en tiempo real
    $("#busqueda").on("keyup", function(){
        listar();
    });


}

//funcion limpiar
function limpiar(){
	$("#id_responsable").val("");
	$("#id_nino").val("");
	$("#nombre_completo").val("");
	$("#parentesco").val("");
	$("#telefono").val("");
	$("#periodo_inicio").val("");
	$("#periodo_fin").val("");

	// Limpiar canvas de firma
	var canvas = document.getElementById('signatureCanvas');
	if (canvas) {
		var ctx = canvas.getContext('2d');
		ctx.clearRect(0, 0, canvas.width, canvas.height);
	}

	// Ocultar secciones de preview y firma
	$("#previewSection").hide();
	$("#firmaSection").hide();
	$("#btnGenerarPDF").hide();
}

//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
		$('#modalResponsableLabel').html('<i class="fa fa-plus-circle"></i> Nuevo Responsable de Retiro');
		$('#modalResponsable').modal('show');
		// Habilitar campos para edición
		$("#id_nino").prop("disabled", false);
		$("#nombre_completo").prop("disabled", false);
		$("#parentesco").prop("disabled", false);
		$("#telefono").prop("disabled", false);
		$("#periodo_inicio").prop("disabled", false);
		$("#periodo_fin").prop("disabled", false);
		$("#btnGuardar").show();
		// Cambiar texto del botón cuando se crea nuevo
		$("#btnGuardar").html('<i class="fa fa-save"></i> Guardar Responsable');
	}else{
		$('#modalResponsable').modal('hide');
	}
}

//cancelar form
function cancelarform(){
	limpiar();
	$('#modalResponsable').modal('hide');
}

//funcion listar
function listar(){
    var busqueda = $("#busqueda").val();

    $.ajax({
        url: "../ajax/responsables_retiro.php?op=listar",
        type: "POST",
        data: {busqueda: busqueda},
        dataType: "json",
        success: function(data) {
            // Verificar si hay error en la respuesta
            if (data.error) {
                $('#responsablesTableBody').html('<tr><td colspan="8" style="text-align: center; padding: 2rem; color: #dc3545;">' + data.message + '</td></tr>');
                return;
            }

            var tbody = $('#responsablesTableBody');
            tbody.empty();

            if (data.aaData && data.aaData.length > 0) {
                $.each(data.aaData, function(index, responsable) {
                    // Para maestros, solo mostrar botón de ver; para padres/tutores y admin, mostrar editar y eliminar
                    var acciones = '<button class="btn btn-outline-warning btn-sm" style="margin-right: 0.5rem; border-radius: 20px;" onclick="mostrar(' + responsable[8] + ')"><i class="fa fa-pencil"></i> Editar</button>' +
                                   '<button class="btn btn-outline-danger btn-sm" style="border-radius: 20px;" onclick="eliminar_responsable(' + responsable[8] + ')"><i class="fa fa-trash"></i> Eliminar</button>';

                    // Determinar el estado del documento
                    var documentoStatus = '';
                    if (responsable[7] && responsable[7] !== '' && responsable[7] !== null) {
                        // Verificar si contiene "_firmado" en el nombre para saber si está firmado
                        if (responsable[7].indexOf('_firmado') !== -1) {
                            documentoStatus = '<a href="' + responsable[7] + '" target="_blank" class="btn btn-sm btn-success" style="border-radius: 15px;"><i class="fa fa-file-pdf-o"></i> Ver Firmado</a>';
                        } else {
                            documentoStatus = '<a href="' + responsable[7] + '" target="_blank" class="btn btn-sm btn-info" style="border-radius: 15px;"><i class="fa fa-file-pdf-o"></i> Ver PDF</a> <button class="btn btn-sm btn-warning" onclick="firmarDocumento(' + responsable[8] + ')" style="border-radius: 15px;"><i class="fa fa-signature"></i> Firmar</button>';
                        }
                    } else {
                        documentoStatus = '<span class="badge badge-warning" style="border-radius: 15px; padding: 0.5rem 1rem;"><i class="fa fa-clock-o"></i> Pendiente</span>';
                    }

                    var row = '<tr style="border-bottom: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease;">' +
                        '<td style="padding: 1rem;">' + acciones + '</td>' +
                        '<td style="padding: 1rem; font-weight: 600; color: #3c8dbc;">' + responsable[1] + '</td>' +
                        '<td style="padding: 1rem;">' + responsable[2] + '</td>' +
                        '<td style="padding: 1rem;">' + responsable[3] + '</td>' +
                        '<td style="padding: 1rem;">' + (responsable[4] || '') + '</td>' +
                        '<td style="padding: 1rem;">' + responsable[5] + '</td>' +
                        '<td style="padding: 1rem;">' + responsable[6] + '</td>' +
                        '<td style="padding: 1rem;">' + documentoStatus + '</td>' +
                        '</tr>';

                    tbody.append(row);
                });

                // Actualizar contador de registros
                $("#totalRegistros").text(data.aaData.length);
            } else {
                 tbody.append('<tr><td colspan="8" style="text-align: center; padding: 2rem; color: #666;">No hay responsables registrados</td></tr>');
                 $("#totalRegistros").text("0");
             }
        },
        error: function(xhr, status, error) {
            console.log("Error:", xhr.responseText);
            console.log("Status:", status);
            console.log("Error:", error);
            $('#responsablesTableBody').html('<tr><td colspan="7" style="text-align: center; padding: 2rem; color: #dc3545;">Error al cargar los datos: ' + xhr.responseText + '</td></tr>');
        }
    });
}

//funcion para guardaryeditar
function guardaryeditar(e){
      e.preventDefault();


      $("#btnGuardar").prop("disabled",true);
      $("#btnGuardar").html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

      var formData=new FormData($("#formulario")[0]);

      $.ajax({
       	url: "../ajax/responsables_retiro.php?op=guardaryeditar",
       	type: "POST",
       	data: formData,
       	contentType: false,
       	processData: false,

       	success: function(datos){
       			bootbox.alert(datos);
       			$('#modalResponsable').modal('hide');
       			listar();
       			limpiar();
       		},
       	error: function(xhr, status, error) {
       		bootbox.alert("Error al guardar: " + xhr.responseText);
       	},
       	complete: function() {
       		$("#btnGuardar").prop("disabled", false);
       		var isEditing = $("#id_responsable").val() !== "";
       		$("#btnGuardar").html('<i class="fa fa-' + (isEditing ? 'edit' : 'save') + '"></i> ' + (isEditing ? 'Actualizar' : 'Guardar') + ' Responsable');
       	}
      });
}

function mostrar(id_responsable){
$.post("../ajax/responsables_retiro.php?op=mostrar",{id_responsable : id_responsable},
	function(data,status)
	{
		data=JSON.parse(data);
		$('#modalResponsableLabel').html('<i class="fa fa-file-pdf-o"></i> Generar Autorización de Retiro');
		$('#modalResponsable').modal('show');

		$("#id_responsable").val(data.id_responsable);
		$("#id_nino").val(data.id_nino);
		$("#nombre_completo").val(data.nombre_completo);
		$("#parentesco").val(data.parentesco);
		$("#telefono").val(data.telefono);
		$("#periodo_inicio").val(data.periodo_inicio);
		$("#periodo_fin").val(data.periodo_fin);

		// Deshabilitar campos para solo lectura
		$("#id_nino").prop("disabled", true);
		$("#nombre_completo").prop("disabled", true);
		$("#parentesco").prop("disabled", true);
		$("#telefono").prop("disabled", true);
		$("#periodo_inicio").prop("disabled", true);
		$("#periodo_fin").prop("disabled", true);

		// Ocultar botón de guardar y mostrar botón de generar PDF
		$("#btnGuardar").hide();

		// Agregar botón de generar PDF si no existe
		if (!$("#btnGenerarPDF").length) {
			$("#btnGuardar").after('<button type="button" class="btn btn-primary" id="btnGenerarPDF" onclick="generarDocumentoPDF()" style="border-radius: 15px; margin-left: 10px;"><i class="fa fa-file-pdf-o"></i> Generar PDF</button>');
		}
		$("#btnGenerarPDF").show();
	})
	.fail(function(xhr, status, error) {
		alert("Error al cargar los datos del responsable: " + xhr.responseText);
	});
}

//funcion para eliminar
function eliminar_responsable(id_responsable){
	bootbox.confirm("¿Esta seguro de eliminar este responsable?", function(result){
		if (result) {
			$.post("../ajax/responsables_retiro.php?op=eliminar", {id_responsable : id_responsable}, function(e){
				bootbox.alert(e);
				listar();
			});
		}
	})
}

//funcion para activar
function activar(id_responsable){
	bootbox.confirm("¿Esta seguro de activar este responsable?", function(result){
		if (result) {
			$.post("../ajax/responsables_retiro.php?op=activar", {id_responsable : id_responsable}, function(e){
				bootbox.alert(e);
				listar();
			});
		}
	})
}

// Función para cargar la lista de niños
function cargarNinos(){
    // Para padres/tutores, solo mostrar su propio niño
    var url = "../ajax/ninos.php?op=select";

    $.post(url, function(r){
        $("#id_nino").html('<option value="">Seleccionar niño</option>');
        // Verificar si la respuesta es HTML (opción) o JSON
        if (r.trim().startsWith('<option')) {
            // Es HTML directo
            $("#id_nino").append(r.replace('<option value="">Seleccionar niño</option>', ''));
        } else {
            // Es JSON
            try {
                var data = JSON.parse(r);
                $.each(data, function(index, item){
                    $("#id_nino").append('<option value="' + item[0] + '">' + item[1] + '</option>');
                });
            } catch(e) {
                console.error("Error parsing JSON:", e);
                console.log("Response:", r);
            }
        }
    }).fail(function(xhr, status, error) {
        console.error("Error loading children:", xhr.responseText);
    });
}

// Funcionalidad de firma digital
var canvas, ctx, drawing = false;

function initSignature() {
    canvas = document.getElementById('signatureCanvas');
    if (canvas) {
        ctx = canvas.getContext('2d');

        // Eventos del mouse
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        // Eventos táctiles para móviles
        canvas.addEventListener('touchstart', startDrawingTouch);
        canvas.addEventListener('touchmove', drawTouch);
        canvas.addEventListener('touchend', stopDrawing);

        // Botones
        document.getElementById('clearSignature').addEventListener('click', clearSignature);
        document.getElementById('saveSignature').addEventListener('click', saveSignature);
    }
}

function startDrawing(e) {
    drawing = true;
    ctx.beginPath();
    ctx.moveTo(e.offsetX, e.offsetY);
}

function draw(e) {
    if (!drawing) return;
    ctx.lineTo(e.offsetX, e.offsetY);
    ctx.stroke();
}

function stopDrawing() {
    drawing = false;
}

function startDrawingTouch(e) {
    e.preventDefault();
    var touch = e.touches[0];
    var rect = canvas.getBoundingClientRect();
    var x = touch.clientX - rect.left;
    var y = touch.clientY - rect.top;

    drawing = true;
    ctx.beginPath();
    ctx.moveTo(x, y);
}

function drawTouch(e) {
    e.preventDefault();
    if (!drawing) return;

    var touch = e.touches[0];
    var rect = canvas.getBoundingClientRect();
    var x = touch.clientX - rect.left;
    var y = touch.clientY - rect.top;

    ctx.lineTo(x, y);
    ctx.stroke();
}

function clearSignature() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

function saveSignature() {
    var firmaData = canvas.toDataURL('image/png');

    // Enviar firma al servidor
    var id_responsable = $("#id_responsable").val();
    if (!id_responsable) {
        bootbox.alert("Primero debe guardar el responsable antes de firmar");
        return;
    }

    $.ajax({
        url: "../ajax/responsables_retiro.php?op=guardarFirmaDocumento",
        type: "POST",
        data: {
            id_responsable: id_responsable,
            firma_data: firmaData
        },
        success: function(data) {
            if (data.success) {
                bootbox.alert("Firma guardada correctamente. Documento generado: " + data.document_url);
                $('#modalResponsable').modal('hide');
                listar();
            } else {
                bootbox.alert("Error: " + data.message);
            }
        },
        error: function(xhr, status, error) {
            bootbox.alert("Error al guardar la firma: " + xhr.responseText);
        }
    });
}

// Función para generar y guardar el PDF
function generarDocumentoPDF() {
    var id_responsable = $("#id_responsable").val();
    if (!id_responsable) {
        bootbox.alert("Primero debe guardar el responsable antes de generar el PDF");
        return;
    }

    // Mostrar loading
    $("#btnGenerarPDF").prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Generando...');

    $.ajax({
        url: "../ajax/responsables_retiro.php?op=guardarDocumentoPDF",
        type: "POST",
        data: { id_responsable: id_responsable },
        success: function(data) {
            $("#btnGenerarPDF").prop("disabled", false).html('<i class="fa fa-file-pdf-o"></i> Generar PDF');

            if (data.success) {
                bootbox.alert("Documento PDF generado correctamente. Ahora puede firmarlo.");
                $('#modalResponsable').modal('hide');
                listar(); // Recargar la tabla para mostrar el estado actualizado
            } else {
                bootbox.alert("Error: " + data.message);
            }
        },
        error: function(xhr, status, error) {
            $("#btnGenerarPDF").prop("disabled", false).html('<i class="fa fa-file-pdf-o"></i> Generar PDF');
            bootbox.alert("Error al generar PDF: " + xhr.responseText);
        }
    });
}

// Función para generar documento final desde el preview (sin firma)
function generarDocumentoFinal() {
    var id_responsable = $("#id_responsable").val();
    if (!id_responsable) {
        bootbox.alert("ID de responsable no encontrado");
        return;
    }

    // Mostrar loading
    $("#btnGenerarDocumentoFinal").prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Generando...');

    $.ajax({
        url: "../ajax/responsables_retiro.php?op=guardarDocumentoPDF",
        type: "POST",
        data: { id_responsable: id_responsable },
        success: function(data) {
            $("#btnGenerarDocumentoFinal").prop("disabled", false).html('<i class="fa fa-save"></i> Generar y Guardar PDF');

            if (data.success) {
                bootbox.alert("Documento PDF generado y guardado correctamente.");
                $('#modalResponsable').modal('hide');
                listar(); // Recargar la tabla para mostrar el estado actualizado
            } else {
                bootbox.alert("Error: " + data.message);
            }
        },
        error: function(xhr, status, error) {
            $("#btnGenerarDocumentoFinal").prop("disabled", false).html('<i class="fa fa-save"></i> Generar y Guardar PDF');
            bootbox.alert("Error al generar PDF: " + xhr.responseText);
        }
    });
}

// Función para generar preview del PDF (opcional, para mostrar antes de guardar)
function generarPreviewPDF() {
    var id_responsable = $("#id_responsable").val();
    if (!id_responsable) {
        bootbox.alert("Primero debe guardar el responsable antes de generar el PDF");
        return;
    }

    $.ajax({
        url: "../ajax/responsables_retiro.php?op=obtenerPreviewPDF",
        type: "POST",
        data: { id_responsable: id_responsable },
        success: function(data) {
            if (data.success) {
                mostrarPreviewPDF(data.preview_data);
            } else {
                bootbox.alert("Error: " + data.message);
            }
        },
        error: function(xhr, status, error) {
            bootbox.alert("Error al obtener preview: " + xhr.responseText);
        }
    });
}

// Función para mostrar el preview del PDF
function mostrarPreviewPDF(previewData) {
    var html = '<div style="text-align: center; font-weight: bold; font-size: 16px; margin-bottom: 20px;">' + previewData.titulo + '</div>';
    html += '<div style="margin-bottom: 15px;">' + previewData.descripcion + '</div>';

    html += '<table style="width: 100%; border-collapse: collapse;">';
    $.each(previewData.datos, function(key, item) {
        html += '<tr>';
        html += '<td style="padding: 5px 0; font-weight: bold; width: 40%;">' + item.label + '</td>';
        html += '<td style="padding: 5px 0;">' + item.valor + '</td>';
        html += '</tr>';
    });
    html += '</table>';

    html += '<div style="margin-top: 30px; text-align: left;">';
    html += '<div style="font-weight: bold;">Firma del Responsable:</div>';
    html += '<div style="margin-top: 10px; border-bottom: 1px solid #000; width: 300px;"></div>';
    html += '</div>';

    html += '<div style="margin-top: 20px; text-align: right;">';
    html += 'Fecha: ____________________';
    html += '</div>';

    $("#pdfPreview").html(html);
    $("#previewSection").show();

    // Scroll al preview
    $("#previewSection")[0].scrollIntoView({ behavior: 'smooth' });
}

// Función para iniciar el proceso de firma
function iniciarFirmaDocumento() {
    $("#previewSection").hide();
    mostrarFirmaSection();
}

// Mostrar sección de firma cuando se edita un responsable existente
function mostrarFirmaSection() {
    $("#firmaSection").show();
    initSignature();

    // Scroll a la sección de firma
    $("#firmaSection")[0].scrollIntoView({ behavior: 'smooth' });
}

// Función para firmar documento desde la tabla
function firmarDocumento(id_responsable) {
    // Cargar datos del responsable y mostrar modal de firma
    $.post("../ajax/responsables_retiro.php?op=mostrar",{id_responsable : id_responsable},
        function(data,status) {
            data=JSON.parse(data);
            $('#modalResponsableLabel').html('<i class="fa fa-signature"></i> Firmar Autorización de Retiro');
            $('#modalResponsable').modal('show');

            $("#id_responsable").val(data.id_responsable);
            $("#id_nino").val(data.id_nino);
            $("#nombre_completo").val(data.nombre_completo);
            $("#parentesco").val(data.parentesco);
            $("#telefono").val(data.telefono);
            $("#periodo_inicio").val(data.periodo_inicio);
            $("#periodo_fin").val(data.periodo_fin);

            // Deshabilitar campos para solo lectura
            $("#id_nino").prop("disabled", true);
            $("#nombre_completo").prop("disabled", true);
            $("#parentesco").prop("disabled", true);
            $("#telefono").prop("disabled", true);
            $("#periodo_inicio").prop("disabled", true);
            $("#periodo_fin").prop("disabled", true);

            // Ocultar botones de guardar y generar PDF
            $("#btnGuardar").hide();
            $("#btnGenerarPDF").hide();

            // Mostrar directamente la sección de firma
            mostrarFirmaSection();
        }
    ).fail(function(xhr, status, error) {
        alert("Error al cargar los datos del responsable: " + xhr.responseText);
    });
}

// Evento para generar documento final sin firma
$(document).on('click', '#btnGenerarDocumentoFinal', function() {
    generarDocumentoFinal();
});

// Evento para el botón de firmar documento
$(document).on('click', '#btnFirmarDocumento', function() {
    iniciarFirmaDocumento();
});

init();