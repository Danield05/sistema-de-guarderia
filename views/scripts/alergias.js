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
	$("#id_alergia").val("");
	$("#id_nino").val("");
	$("#tipo_alergia").val("");
	$("#descripcion").val("");
}

//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
		$('#modalAlergiaLabel').html('<i class="fa fa-plus-circle"></i> Nueva Alergia');
		$('#modalAlergia').modal('show');
	}else{
		$('#modalAlergia').modal('hide');
	}
}

//cancelar form
function cancelarform(){
	limpiar();
	$('#modalAlergia').modal('hide');
}

//funcion listar
function listar(){
    $.ajax({
        url: "../ajax/alergias.php?op=listar",
        type: "POST",
        dataType: "json",
        success: function(data) {
            var tbody = $('#alergiasTableBody');
            tbody.empty();

            if (data.aaData && data.aaData.length > 0) {
                $.each(data.aaData, function(index, alergia) {
                    var acciones = '<button class="btn btn-outline-warning btn-sm" style="margin-right: 0.5rem; border-radius: 20px;" onclick="mostrar(' + alergia[4] + ')"><i class="fa fa-pencil"></i> Editar</button>' +
                                   '<button class="btn btn-outline-danger btn-sm" style="border-radius: 20px;" onclick="eliminar(' + alergia[4] + ')"><i class="fa fa-trash"></i> Eliminar</button>';

                    var row = '<tr style="border-bottom: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease;">' +
                        '<td style="padding: 1rem;">' + acciones + '</td>' +
                        '<td style="padding: 1rem; font-weight: 600; color: #3c8dbc;">' + alergia[1] + '</td>' +
                        '<td style="padding: 1rem;">' + alergia[2] + '</td>' +
                        '<td style="padding: 1rem;">' + (alergia[3] || '') + '</td>' +
                        '</tr>';

                    tbody.append(row);
                });
            } else {
                tbody.append('<tr><td colspan="4" style="text-align: center; padding: 2rem; color: #666;">No hay alergias registradas</td></tr>');
            }
        },
        error: function(xhr, status, error) {
            $('#alergiasTableBody').html('<tr><td colspan="4" style="text-align: center; padding: 2rem; color: #dc3545;">Error al cargar los datos</td></tr>');
        }
    });
}

//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();
     $("#btnGuardar").prop("disabled",true);
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
      	url: "../ajax/alergias.php?op=guardaryeditar",
      	type: "POST",
      	data: formData,
      	contentType: false,
      	processData: false,

      	success: function(datos){
      		bootbox.alert(datos);
      		$('#modalAlergia').modal('hide');
      		listar();
      	},
      	error: function(xhr, status, error) {
      		bootbox.alert("Error al guardar");
      	},
      	complete: function() {
      		$("#btnGuardar").prop("disabled", false);
      		$("#btnGuardar").html('<i class="fa fa-save"></i> Guardar Alergia');
      	}
     });

     limpiar();
}

function mostrar(id_alergia){
	$.post("../ajax/alergias.php?op=mostrar",{id_alergia : id_alergia},
		function(data,status)
		{
			data=JSON.parse(data);
			$('#modalAlergiaLabel').html('<i class="fa fa-edit"></i> Editar Alergia');
			$('#modalAlergia').modal('show');

			$("#id_alergia").val(data.id_alergia);
			$("#id_nino").val(data.id_nino);
			$("#tipo_alergia").val(data.tipo_alergia);
			$("#descripcion").val(data.descripcion);
		})
		.fail(function(xhr, status, error) {
			alert("Error al cargar los datos de la alergia");
		});
}

//funcion para eliminar
function eliminar(id_alergia){
	bootbox.confirm("¿Esta seguro de eliminar esta alergia?", function(result){
		if (result) {
			$.post("../ajax/alergias.php?op=eliminar", {id_alergia : id_alergia}, function(e){
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