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
	$("#id_medicamento").val("");
	$("#id_nino").val("");
	$("#nombre_medicamento").val("");
	$("#dosis").val("");
	$("#frecuencia").val("");
	$("#observaciones").val("");
}

//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
		$('#modalMedicamentoLabel').html('<i class="fa fa-plus-circle"></i> Nuevo Medicamento');
		$('#modalMedicamento').modal('show');
		// Habilitar campos para edición
		$("#id_nino").prop("disabled", false);
		$("#nombre_medicamento").prop("disabled", false);
		$("#dosis").prop("disabled", false);
		$("#frecuencia").prop("disabled", false);
		$("#observaciones").prop("disabled", false);
		$("#btnGuardar").show();
	}else{
		$('#modalMedicamento').modal('hide');
	}
}

//cancelar form
function cancelarform(){
	limpiar();
	$('#modalMedicamento').modal('hide');
}

//funcion listar
function listar(){
    $.ajax({
        url: "../ajax/medicamentos.php?op=listar",
        type: "POST",
        dataType: "json",
        success: function(data) {
            var tbody = $('#medicamentosTableBody');
            tbody.empty();

            if (data.aaData && data.aaData.length > 0) {
                $.each(data.aaData, function(index, medicamento) {
                    var acciones = '<button class="btn btn-outline-info btn-sm" style="margin-right: 0.5rem; border-radius: 20px;" onclick="mostrar(' + medicamento[6] + ')"><i class="fa fa-eye"></i> Ver</button>';

                    var row = '<tr style="border-bottom: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease;">' +
                        '<td style="padding: 1rem;">' + acciones + '</td>' +
                        '<td style="padding: 1rem; font-weight: 600; color: #3c8dbc;">' + medicamento[1] + '</td>' +
                        '<td style="padding: 1rem;">' + medicamento[2] + '</td>' +
                        '<td style="padding: 1rem;">' + (medicamento[3] || '') + '</td>' +
                        '<td style="padding: 1rem;">' + (medicamento[4] || '') + '</td>' +
                        '<td style="padding: 1rem;">' + (medicamento[5] || '') + '</td>' +
                        '</tr>';

                    tbody.append(row);
                });
            } else {
                tbody.append('<tr><td colspan="6" style="text-align: center; padding: 2rem; color: #666;">No hay medicamentos registrados</td></tr>');
            }
        },
        error: function(xhr, status, error) {
            $('#medicamentosTableBody').html('<tr><td colspan="6" style="text-align: center; padding: 2rem; color: #dc3545;">Error al cargar los datos</td></tr>');
        }
    });
}

//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();
     $("#btnGuardar").prop("disabled",true);
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
      	url: "../ajax/medicamentos.php?op=guardaryeditar",
      	type: "POST",
      	data: formData,
      	contentType: false,
      	processData: false,

      	success: function(datos){
      		bootbox.alert(datos);
      		$('#modalMedicamento').modal('hide');
      		listar();
      		limpiar();
      	},
      	error: function(xhr, status, error) {
      		bootbox.alert("Error al guardar");
      	},
      	complete: function() {
      		$("#btnGuardar").prop("disabled", false);
      		$("#btnGuardar").html('<i class="fa fa-save"></i> Guardar Medicamento');
      	}
     });

     limpiar();
}

function mostrar(id_medicamento){
$.post("../ajax/medicamentos.php?op=mostrar",{id_medicamento : id_medicamento},
	function(data,status)
	{
		data=JSON.parse(data);
		$('#modalMedicamentoLabel').html('<i class="fa fa-eye"></i> Ver Medicamento');
		$('#modalMedicamento').modal('show');

		$("#id_medicamento").val(data.id_medicamento);
		$("#id_nino").val(data.id_nino);
		$("#nombre_medicamento").val(data.nombre_medicamento);
		$("#dosis").val(data.dosis);
		$("#frecuencia").val(data.frecuencia);
		$("#observaciones").val(data.observaciones);

		// Deshabilitar campos para solo lectura
		$("#id_nino").prop("disabled", true);
		$("#nombre_medicamento").prop("disabled", true);
		$("#dosis").prop("disabled", true);
		$("#frecuencia").prop("disabled", true);
		$("#observaciones").prop("disabled", true);

		// Ocultar botón de guardar
		$("#btnGuardar").hide();
	})
	.fail(function(xhr, status, error) {
		alert("Error al cargar los datos del medicamento");
	});
}

//funcion para eliminar
function eliminar(id_medicamento){
	bootbox.confirm("¿Esta seguro de eliminar este medicamento?", function(result){
		if (result) {
			$.post("../ajax/medicamentos.php?op=eliminar", {id_medicamento : id_medicamento}, function(e){
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