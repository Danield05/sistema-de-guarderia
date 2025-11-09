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
	// Sin firma por ahora

	// Sin firma por ahora
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
                $('#responsablesTableBody').html('<tr><td colspan="7" style="text-align: center; padding: 2rem; color: #dc3545;">' + data.message + '</td></tr>');
                return;
            }

            var tbody = $('#responsablesTableBody');
            tbody.empty();

            if (data.aaData && data.aaData.length > 0) {
                $.each(data.aaData, function(index, responsable) {
                    // Para maestros, solo mostrar botón de ver
                    var acciones = '<button class="btn btn-outline-info btn-sm" style="border-radius: 20px;" onclick="mostrar(' + responsable[8] + ')"><i class="fa fa-eye"></i> Ver</button>';

                    var row = '<tr style="border-bottom: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease;">' +
                        '<td style="padding: 1rem;">' + acciones + '</td>' +
                        '<td style="padding: 1rem; font-weight: 600; color: #3c8dbc;">' + responsable[1] + '</td>' +
                        '<td style="padding: 1rem;">' + responsable[2] + '</td>' +
                        '<td style="padding: 1rem;">' + responsable[3] + '</td>' +
                        '<td style="padding: 1rem;">' + (responsable[4] || '') + '</td>' +
                        '<td style="padding: 1rem;">' + responsable[5] + '</td>' +
                        '<td style="padding: 1rem;">' + responsable[6] + '</td>' +
                        '</tr>';

                    tbody.append(row);
                });

                // Actualizar contador de registros
                $("#totalRegistros").text(data.aaData.length);
            } else {
                tbody.append('<tr><td colspan="7" style="text-align: center; padding: 2rem; color: #666;">No hay responsables registrados</td></tr>');
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
		$('#modalResponsableLabel').html('<i class="fa fa-eye"></i> Ver Responsable de Retiro');
		$('#modalResponsable').modal('show');

		$("#id_responsable").val(data.id_responsable);
		$("#id_nino").val(data.id_nino);
		$("#nombre_completo").val(data.nombre_completo);
		$("#parentesco").val(data.parentesco);
		$("#telefono").val(data.telefono);
		$("#periodo_inicio").val(data.periodo_inicio);
		$("#periodo_fin").val(data.periodo_fin);
		$("#firma_actual").val(data.autorizacion_firma);

		// Deshabilitar campos para solo lectura
		$("#id_nino").prop("disabled", true);
		$("#nombre_completo").prop("disabled", true);
		$("#parentesco").prop("disabled", true);
		$("#telefono").prop("disabled", true);
		$("#periodo_inicio").prop("disabled", true);
		$("#periodo_fin").prop("disabled", true);

		// Ocultar botón de guardar
		$("#btnGuardar").hide();
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
    $.post("../ajax/ninos.php?op=select", function(r){
        $("#id_nino").html('<option value="">Seleccionar niño</option>');
        var data = JSON.parse(r);
        $.each(data, function(index, item){
            $("#id_nino").append('<option value="' + item[0] + '">' + item[1] + '</option>');
        });
    });
}

// Funciones de firma eliminadas - simplificar

init();