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
	$("#autorizacion_firma").val("");
	$("#firma_actual").val("");
}

//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}else{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//cancelar form
function cancelarform(){
	limpiar();
	mostrarform(false);
}

//funcion listar
function listar(){
    $.ajax({
        url: "../ajax/responsables_retiro.php?op=listar",
        type: "POST",
        dataType: "json",
        success: function(data) {
            var tbody = $('#responsablesTableBody');
            tbody.empty();

            if (data.aaData && data.aaData.length > 0) {
                $.each(data.aaData, function(index, responsable) {
                    var acciones = '<button class="btn btn-outline-warning btn-sm" style="margin-right: 0.5rem; border-radius: 20px;" onclick="mostrar(' + responsable[8] + ')"><i class="fa fa-pencil"></i> Editar</button>' +
                                   '<button class="btn btn-outline-danger btn-sm" style="border-radius: 20px;" onclick="eliminar(' + responsable[8] + ')"><i class="fa fa-trash"></i> Eliminar</button>';

                    var firmaLink = responsable[7] ? '<a href="../files/firmas/' + responsable[7] + '" target="_blank" class="btn btn-outline-info btn-sm" style="border-radius: 20px;"><i class="fa fa-file-signature"></i> Ver Firma</a>' : 'Sin firma';

                    var row = '<tr style="border-bottom: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease;">' +
                        '<td style="padding: 1rem;">' + acciones + '</td>' +
                        '<td style="padding: 1rem; font-weight: 600; color: #3c8dbc;">' + responsable[1] + '</td>' +
                        '<td style="padding: 1rem;">' + responsable[2] + '</td>' +
                        '<td style="padding: 1rem;">' + responsable[3] + '</td>' +
                        '<td style="padding: 1rem;">' + (responsable[4] || '') + '</td>' +
                        '<td style="padding: 1rem;">' + responsable[5] + '</td>' +
                        '<td style="padding: 1rem;">' + responsable[6] + '</td>' +
                        '<td style="padding: 1rem; text-align: center;">' + firmaLink + '</td>' +
                        '</tr>';

                    tbody.append(row);
                });
            } else {
                tbody.append('<tr><td colspan="8" style="text-align: center; padding: 2rem; color: #666;">No hay responsables registrados</td></tr>');
            }
        },
        error: function(xhr, status, error) {
            $('#responsablesTableBody').html('<tr><td colspan="8" style="text-align: center; padding: 2rem; color: #dc3545;">Error al cargar los datos</td></tr>');
        }
    });
}

//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();
     $("#btnGuardar").prop("disabled",true);
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
      	},
      	error: function(xhr, status, error) {
      		bootbox.alert("Error al guardar");
      	},
      	complete: function() {
      		$("#btnGuardar").prop("disabled", false);
      		$("#btnGuardar").html('<i class="fa fa-save"></i> Guardar Responsable');
      	}
     });

     limpiar();
}

function mostrar(id_responsable){
	$.post("../ajax/responsables_retiro.php?op=mostrar",{id_responsable : id_responsable},
		function(data,status)
		{
			data=JSON.parse(data);
			$('#modalResponsableLabel').html('<i class="fa fa-edit"></i> Editar Responsable de Retiro');
			$('#modalResponsable').modal('show');

			$("#id_responsable").val(data.id_responsable);
			$("#id_nino").val(data.id_nino);
			$("#nombre_completo").val(data.nombre_completo);
			$("#parentesco").val(data.parentesco);
			$("#telefono").val(data.telefono);
			$("#periodo_inicio").val(data.periodo_inicio);
			$("#periodo_fin").val(data.periodo_fin);
			$("#firma_actual").val(data.autorizacion_firma);
		})
		.fail(function(xhr, status, error) {
			alert("Error al cargar los datos del responsable");
		});
}

//funcion para eliminar
function eliminar(id_responsable){
	bootbox.confirm("¿Esta seguro de eliminar este responsable?", function(result){
		if (result) {
			$.post("../ajax/responsables_retiro.php?op=eliminar", {id_responsable : id_responsable}, function(e){
				bootbox.alert(e);
				listar();
			});
		}
	})
}

// Función para cargar la lista de niños
function cargarNinos(){
    $.post("../ajax/ninos.php?op=select", function(r){
        $("#id_nino").html(r);
    });
}

init();