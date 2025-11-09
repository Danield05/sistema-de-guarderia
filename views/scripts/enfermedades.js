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
	$("#id_enfermedad").val("");
	$("#id_nino").val("");
	$("#nombre_enfermedad").val("");
	$("#descripcion").val("");
	$("#fecha_diagnostico").val("");
}

//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
		$('#modalEnfermedadLabel').html('<i class="fa fa-plus-circle"></i> Nuevo Diagnóstico');
		$('#modalEnfermedad').modal('show');
		// Habilitar campos para edición
		$("#id_nino").prop("disabled", false);
		$("#nombre_enfermedad").prop("disabled", false);
		$("#descripcion").prop("disabled", false);
		$("#fecha_diagnostico").prop("disabled", false);
		$("#btnGuardar").show();
	}else{
		$('#modalEnfermedad').modal('hide');
	}
}

//cancelar form
function cancelarform(){
	limpiar();
	$('#modalEnfermedad').modal('hide');
}

//funcion listar
function listar(){
    // Verificar si es padre/tutor para filtrar solo sus enfermedades
    $.post("../ajax/usuario.php?op=mostrar_perfil", {}, function(userData){
        userData = JSON.parse(userData);
        var url = "../ajax/enfermedades.php?op=listar";

        // Para padres/tutores, usar endpoint filtrado
        if (userData.rol === 'Padre/Tutor') {
            url = "../ajax/enfermedades.php?op=listarPorTutor";
        }

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            success: function(data) {
                var tbody = $('#enfermedadesTableBody');
                tbody.empty();

                if (data.aaData && data.aaData.length > 0) {
                    $.each(data.aaData, function(index, enfermedad) {
                        var acciones = enfermedad[0]; // Usar las acciones generadas por el controlador

                        var row = '<tr style="border-bottom: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease;">' +
                            '<td style="padding: 1rem;">' + acciones + '</td>' +
                            '<td style="padding: 1rem; font-weight: 600; color: #3c8dbc;">' + enfermedad[1] + '</td>' +
                            '<td style="padding: 1rem;">' + enfermedad[2] + '</td>' +
                            '<td style="padding: 1rem;">' + (enfermedad[3] || '') + '</td>' +
                            '<td style="padding: 1rem;">' + enfermedad[4] + '</td>' +
                            '</tr>';

                        tbody.append(row);
                    });
                } else {
                    tbody.append('<tr><td colspan="5" style="text-align: center; padding: 2rem; color: #666;">No hay enfermedades registradas</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                $('#enfermedadesTableBody').html('<tr><td colspan="5" style="text-align: center; padding: 2rem; color: #dc3545;">Error al cargar los datos</td></tr>');
            }
        });
    }).fail(function(){
        // Fallback: mostrar todas las enfermedades
        $.ajax({
            url: "../ajax/enfermedades.php?op=listar",
            type: "POST",
            dataType: "json",
            success: function(data) {
                var tbody = $('#enfermedadesTableBody');
                tbody.empty();

                if (data.aaData && data.aaData.length > 0) {
                    $.each(data.aaData, function(index, enfermedad) {
                        var acciones = enfermedad[0]; // Usar las acciones generadas por el controlador

                        var row = '<tr style="border-bottom: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease;">' +
                            '<td style="padding: 1rem;">' + acciones + '</td>' +
                            '<td style="padding: 1rem; font-weight: 600; color: #3c8dbc;">' + enfermedad[1] + '</td>' +
                            '<td style="padding: 1rem;">' + enfermedad[2] + '</td>' +
                            '<td style="padding: 1rem;">' + (enfermedad[3] || '') + '</td>' +
                            '<td style="padding: 1rem;">' + enfermedad[4] + '</td>' +
                            '</tr>';

                        tbody.append(row);
                    });
                } else {
                    tbody.append('<tr><td colspan="5" style="text-align: center; padding: 2rem; color: #666;">No hay enfermedades registradas</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                $('#enfermedadesTableBody').html('<tr><td colspan="5" style="text-align: center; padding: 2rem; color: #dc3545;">Error al cargar los datos</td></tr>');
            }
        });
    });
}

//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();
     $("#btnGuardar").prop("disabled",true);
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
      	url: "../ajax/enfermedades.php?op=guardaryeditar",
      	type: "POST",
      	data: formData,
      	contentType: false,
      	processData: false,

      	success: function(datos){
      		bootbox.alert(datos);
      		$('#modalEnfermedad').modal('hide');
      		listar();
      		limpiar();
      	},
      	error: function(xhr, status, error) {
      		bootbox.alert("Error al guardar");
      	},
      	complete: function() {
      		$("#btnGuardar").prop("disabled", false);
      		$("#btnGuardar").html('<i class="fa fa-save"></i> Guardar Diagnóstico');
      	}
     });

     limpiar();
}

function mostrar(id_enfermedad){
$.post("../ajax/enfermedades.php?op=mostrar",{id_enfermedad : id_enfermedad},
	function(data,status)
	{
		data=JSON.parse(data);
		$('#modalEnfermedadLabel').html('<i class="fa fa-eye"></i> Ver Diagnóstico');
		$('#modalEnfermedad').modal('show');

		$("#id_enfermedad").val(data.id_enfermedad);
		$("#id_nino").val(data.id_nino);
		$("#nombre_enfermedad").val(data.nombre_enfermedad);
		$("#descripcion").val(data.descripcion);
		$("#fecha_diagnostico").val(data.fecha_diagnostico);

		// Deshabilitar campos para solo lectura
		$("#id_nino").prop("disabled", true);
		$("#nombre_enfermedad").prop("disabled", true);
		$("#descripcion").prop("disabled", true);
		$("#fecha_diagnostico").prop("disabled", true);

		// Ocultar botón de guardar
		$("#btnGuardar").hide();
	})
	.fail(function(xhr, status, error) {
		alert("Error al cargar los datos de la enfermedad");
	});
}

//funcion para eliminar
function eliminar(id_enfermedad){
	bootbox.confirm("¿Esta seguro de eliminar este diagnóstico?", function(result){
		if (result) {
			$.post("../ajax/enfermedades.php?op=eliminar", {id_enfermedad : id_enfermedad}, function(e){
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