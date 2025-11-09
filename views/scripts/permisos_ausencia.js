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
	$("#id_permiso").val("");
	$("#id_nino").val("");
	$("#tipo_permiso").val("Médico");
	$("#descripcion").val("");
	$("#fecha_inicio").val("");
	$("#fecha_fin").val("");
	$("#hora_inicio").val("");
	$("#hora_fin").val("");
	$("#archivo_permiso").val("");
	$("#archivo_actual").val("");
}

//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
		$("#modalPermiso").modal('show');
		var titulo = '<i class="fa fa-plus-circle"></i> ';
		titulo += (isMedicoGlobal === 'true') ? 'Nuevo Permiso Médico' : 'Nuevo Permiso de Ausencia';
		$("#modalPermisoLabel").html(titulo);
	}else{
		$("#modalPermiso").modal('hide');
	}
}

//cancelar form
function cancelarform(){
	limpiar();
	$("#modalPermiso").modal('hide');
}

//funcion listar
function listar(){
    // Verificar si es padre/tutor para filtrar solo sus permisos
    $.post("../ajax/usuario.php?op=mostrar_perfil", {}, function(userData){
        userData = JSON.parse(userData);
        var url = "../ajax/permisos_ausencia.php?op=listar";

        // Para padres/tutores, usar endpoint filtrado
        if (userData.rol === 'Padre/Tutor') {
            url = "../ajax/permisos_ausencia.php?op=listarPorTutor";
        }

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            success: function(data) {
                var tbody = $('#permisosTableBody');
                tbody.empty();

                // Usar variable global definida en PHP

                if (data.aaData && data.aaData.length > 0) {
                    $.each(data.aaData, function(index, permiso) {
                        // Filtrar solo permisos médicos para usuarios médicos
                        if (isMedicoGlobal === 'true' && permiso[2] !== 'Médico') {
                            return true; // continue to next iteration
                        }

                    var acciones = '';
                    if (isMedicoGlobal === 'true') {
                        acciones = '<button class="btn btn-outline-warning btn-sm" style="margin-right: 0.5rem; border-radius: 20px;" onclick="mostrar(' + permiso[9] + ')"><i class="fa fa-pencil"></i> Editar</button>';
                    } else {
                        acciones = '<button class="btn btn-outline-warning btn-sm" style="margin-right: 0.5rem; border-radius: 20px;" onclick="mostrar(' + permiso[9] + ')"><i class="fa fa-pencil"></i> Editar</button>' +
                                   '<button class="btn btn-outline-danger btn-sm" style="border-radius: 20px;" onclick="eliminar(' + permiso[9] + ')"><i class="fa fa-trash"></i> Eliminar</button>';
                    }

                    var archivoLink = permiso[8] ? '<a href="../files/permisos/' + permiso[8] + '" target="_blank" class="btn btn-outline-info btn-sm" style="border-radius: 20px;"><i class="fa fa-file"></i> Ver</a>' : 'Sin archivo';

                    var row = '<tr style="border-bottom: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease;">' +
                        '<td style="padding: 1rem;">' + acciones + '</td>' +
                        '<td style="padding: 1rem; font-weight: 600; color: #3c8dbc;">' + permiso[1] + '</td>' +
                        '<td style="padding: 1rem;">' + permiso[2] + '</td>' +
                        '<td style="padding: 1rem;">' + (permiso[3] || '') + '</td>' +
                        '<td style="padding: 1rem;">' + permiso[4] + '</td>' +
                        '<td style="padding: 1rem;">' + permiso[5] + '</td>' +
                        '<td style="padding: 1rem;">' + (permiso[6] || '') + '</td>' +
                        '<td style="padding: 1rem;">' + (permiso[7] || '') + '</td>' +
                        '<td style="padding: 1rem; text-align: center;">' + archivoLink + '</td>' +
                        '</tr>';

                    tbody.append(row);
                });
            } else {
                tbody.append('<tr><td colspan="9" style="text-align: center; padding: 2rem; color: #666;">No hay permisos registrados</td></tr>');
            }
        },
        error: function(xhr, status, error) {
            $('#permisosTableBody').html('<tr><td colspan="9" style="text-align: center; padding: 2rem; color: #dc3545;">Error al cargar los datos</td></tr>');
        }
        });
    }).fail(function(){
        // Fallback: mostrar todos los permisos
        $.ajax({
            url: "../ajax/permisos_ausencia.php?op=listar",
            type: "POST",
            dataType: "json",
            success: function(data) {
                var tbody = $('#permisosTableBody');
                tbody.empty();

                if (data.aaData && data.aaData.length > 0) {
                    $.each(data.aaData, function(index, permiso) {
                        // Filtrar solo permisos médicos para usuarios médicos
                        if (isMedicoGlobal === 'true' && permiso[2] !== 'Médico') {
                            return true; // continue to next iteration
                        }

                        var acciones = '';
                        if (isMedicoGlobal === 'true') {
                            acciones = '<button class="btn btn-outline-warning btn-sm" style="margin-right: 0.5rem; border-radius: 20px;" onclick="mostrar(' + permiso[9] + ')"><i class="fa fa-pencil"></i> Editar</button>';
                        } else {
                            acciones = '<button class="btn btn-outline-warning btn-sm" style="margin-right: 0.5rem; border-radius: 20px;" onclick="mostrar(' + permiso[9] + ')"><i class="fa fa-pencil"></i> Editar</button>' +
                                       '<button class="btn btn-outline-danger btn-sm" style="border-radius: 20px;" onclick="eliminar(' + permiso[9] + ')"><i class="fa fa-trash"></i> Eliminar</button>';
                        }

                        var archivoLink = permiso[8] ? '<a href="../files/permisos/' + permiso[8] + '" target="_blank" class="btn btn-outline-info btn-sm" style="border-radius: 20px;"><i class="fa fa-file"></i> Ver</a>' : 'Sin archivo';

                        var row = '<tr style="border-bottom: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease;">' +
                            '<td style="padding: 1rem;">' + acciones + '</td>' +
                            '<td style="padding: 1rem; font-weight: 600; color: #3c8dbc;">' + permiso[1] + '</td>' +
                            '<td style="padding: 1rem;">' + permiso[2] + '</td>' +
                            '<td style="padding: 1rem;">' + (permiso[3] || '') + '</td>' +
                            '<td style="padding: 1rem;">' + permiso[4] + '</td>' +
                            '<td style="padding: 1rem;">' + permiso[5] + '</td>' +
                            '<td style="padding: 1rem;">' + (permiso[6] || '') + '</td>' +
                            '<td style="padding: 1rem;">' + (permiso[7] || '') + '</td>' +
                            '<td style="padding: 1rem; text-align: center;">' + archivoLink + '</td>' +
                            '</tr>';

                        tbody.append(row);
                    });
                } else {
                    tbody.append('<tr><td colspan="9" style="text-align: center; padding: 2rem; color: #666;">No hay permisos registrados</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                $('#permisosTableBody').html('<tr><td colspan="9" style="text-align: center; padding: 2rem; color: #dc3545;">Error al cargar los datos</td></tr>');
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
      	url: "../ajax/permisos_ausencia.php?op=guardaryeditar",
      	type: "POST",
      	data: formData,
      	contentType: false,
      	processData: false,

      	success: function(datos){
      		bootbox.alert(datos);
      		$("#modalPermiso").modal('hide');
      		listar();
      	}
     });

     limpiar();
}

function mostrar(id_permiso){
$.post("../ajax/permisos_ausencia.php?op=mostrar",{id_permiso : id_permiso},
	function(data,status)
	{
		data=JSON.parse(data);
		$("#modalPermiso").modal('show');
		$("#modalPermisoLabel").html('<i class="fa fa-edit"></i> Editar Permiso de Ausencia');

		$("#id_permiso").val(data.id_permiso);
		$("#id_nino").val(data.id_nino);
		$("#tipo_permiso").val(data.tipo_permiso);
		$("#descripcion").val(data.descripcion);
		$("#fecha_inicio").val(data.fecha_inicio);
		$("#fecha_fin").val(data.fecha_fin);
		$("#hora_inicio").val(data.hora_inicio);
		$("#hora_fin").val(data.hora_fin);
		$("#archivo_actual").val(data.archivo_permiso);
	})
}

//funcion para eliminar
function eliminar(id_permiso){
	bootbox.confirm("¿Esta seguro de eliminar este permiso?", function(result){
		if (result) {
			$.post("../ajax/permisos_ausencia.php?op=eliminar", {id_permiso : id_permiso}, function(e){
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