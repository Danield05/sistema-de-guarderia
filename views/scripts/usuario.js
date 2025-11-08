var tabla;

//funcion que se ejecuta al inicio
function init(){
   mostrarform(false);
   listar();

   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   })
}

//funcion limpiar
function limpiar(){
	$("#idusuario").val("");
	$("#nombre").val("");
	$("#dui").val("");
	$("#direccion").val("");
	$("#telefono").val("");
	$("#email").val("");
	$("#rol").val("");
	$("#clave").val("");
	$("#imagen").val("");
	$("#imagenactual").val("");
	$("#imagenmuestra").html('<i class="fa fa-user" style="font-size: 2.5rem; color: #ccc;"></i>');
	$("#estado").val("");
	// Limpiar lista de permisos
	$("#permisos").empty();
}
 
//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
		$('#modalUsuarioLabel').html('<i class="fa fa-user-plus"></i> Nuevo Usuario');
		$('#modalUsuario').modal({
			backdrop: 'static',
			keyboard: true
		});
		// Mostrar campo de contraseña para nuevos usuarios
		$("#claves").show();
		$("#clave").prop("required", true);
		$("#claveHelp").hide();
	}else{
		$('#modalUsuario').modal('hide');
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
        url: "../ajax/usuario.php?op=listar",
        type: "POST",
        dataType: "json",
        success: function(data) {
            var tbody = $('#usuariosTableBody');
            tbody.empty();

            if (data.aaData && data.aaData.length > 0) {
                $.each(data.aaData, function(index, usuario) {
                    var acciones = '<button class="btn btn-outline-warning btn-sm" style="margin-right: 0.5rem; border-radius: 20px;" onclick="mostrar(' + usuario[8] + ')"><i class="fa fa-pencil"></i> Editar</button>';

                    if (usuario[7] === 'Activo') {
                        acciones += '<button class="btn btn-outline-danger btn-sm" style="border-radius: 20px;" onclick="desactivar(' + usuario[8] + ')"><i class="fa fa-ban"></i> Desactivar</button>';
                    } else {
                        acciones += '<button class="btn btn-outline-success btn-sm" style="border-radius: 20px;" onclick="activar(' + usuario[8] + ')"><i class="fa fa-check"></i> Activar</button>';
                    }

                    // Agregar ID del usuario como atributo data para debugging
                    acciones = acciones.replace('onclick="mostrar(', 'data-id="' + usuario[8] + '" onclick="mostrar(');
                    acciones = acciones.replace('onclick="desactivar(', 'data-id="' + usuario[8] + '" onclick="desactivar(');
                    acciones = acciones.replace('onclick="activar(', 'data-id="' + usuario[8] + '" onclick="activar(');

                    var estadoBadge = usuario[7] === 'Activo' ?
                        '<span class="badge badge-success" style="font-size: 0.8rem; padding: 0.5rem 1rem; border-radius: 20px;"><i class="fa fa-check-circle"></i> Activo</span>' :
                        '<span class="badge badge-danger" style="font-size: 0.8rem; padding: 0.5rem 1rem; border-radius: 20px;"><i class="fa fa-times-circle"></i> Inactivo</span>';

                    var fotoDisplay = usuario[6] ? '<img src="../files/usuarios/' + usuario[6] + '" alt="Foto" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">' : '<i class="fa fa-user-circle" style="font-size: 2rem; color: #ccc;"></i>';

                    var row = '<tr style="border-bottom: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease;">' +
                        '<td style="padding: 1rem;">' + acciones + '</td>' +
                        '<td style="padding: 1rem; font-weight: 600; color: #3c8dbc;">' + usuario[1] + '</td>' +
                        '<td style="padding: 1rem;">' + (usuario[2] || '') + '</td>' + // DUI
                         '<td style="padding: 1rem;">' + (usuario[3] || '') + '</td>' + // Teléfono
                         '<td style="padding: 1rem;">' + (usuario[4] || '') + '</td>' + // Email
                         '<td style="padding: 1rem;">' + (usuario[5] || '') + '</td>' + // Rol
                         '<td style="padding: 1rem; text-align: center;">' + fotoDisplay + '</td>' + // Foto
                         '<td style="padding: 1rem;">' + estadoBadge + '</td>' + // Estado
                        '</tr>';

                    tbody.append(row);
                });
            } else {
                tbody.append('<tr><td colspan="8" style="text-align: center; padding: 2rem; color: #666;">No hay usuarios registrados</td></tr>');
            }
        },
        error: function(xhr, status, error) {
            $('#usuariosTableBody').html('<tr><td colspan="8" style="text-align: center; padding: 2rem; color: #dc3545;">Error al cargar los datos</td></tr>');
        }
    });
}

//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();//no se activara la accion predeterminada 
     $("#btnGuardar").prop("disabled",true);
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/usuario.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
     			bootbox.alert(datos);
     			$('#modalUsuario').modal('hide');
     			listar(); // Recargar la tabla
     		},
     		error: function(xhr, status, error) {
     			bootbox.alert("Error al guardar");
     		},
     	complete: function() {
     		// Rehabilitar el botón después de completar (éxito o error)
     		$("#btnGuardar").prop("disabled", false);
     		$("#btnGuardar").html('<i class="fa fa-save"></i> Guardar Usuario');
     	}
     });

     limpiar();
}

function mostrar(idusuario){
	$.post("../ajax/usuario.php?op=mostrar",{idusuario : idusuario},
		function(data,status)
		{
			try {
				data=JSON.parse(data);

				$('#modalUsuarioLabel').html('<i class="fa fa-edit"></i> Editar Usuario');
				$('#modalUsuario').modal('show');

				$("#nombre").val(data.nombre_completo || '');
				$("#dui").val(data.dui || '');
				$("#direccion").val(data.direccion || '');
				$("#telefono").val(data.telefono || '');
				$("#email").val(data.email || '');
				$("#rol").val(data.rol_id || ''); // Usar rol_id en lugar de rol
				$("#idusuario").val(data.id_usuario || '');
				$("#imagenactual").val(data.fotografia || '');
				if(data.fotografia) {
					$("#imagenmuestra").html('<img src="../files/usuarios/' + data.fotografia + '" alt="Foto" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">');
				} else {
					$("#imagenmuestra").html('<i class="fa fa-user" style="font-size: 2.5rem; color: #ccc;"></i>');
				}
				$("#estado").val(data.estado_usuario || '');

				// Mostrar campo de contraseña en edición (opcional)
				$("#claves").show();
				$("#clave").prop("required", false);
				$("#claveHelp").show();
				$("#clave").val(""); // Dejar vacío para que sea opcional
			} catch(e) {
				alert("Error al cargar los datos del usuario: " + e.message);
			}
		})
		.fail(function(xhr, status, error) {
			alert("Error al cargar los datos del usuario.");
		});
}

function mostrar_clave(idusuario){
	$.post("../ajax/usuario.php?op=mostrar_clave",{idusuario : idusuario},
		function(data,status)
		{
			data=JSON.parse(data);
			bootbox.alert("Password actual: "+data.password);
		})
}

//funcion para desactivar
function desactivar(idusuario){
	bootbox.confirm("¿Esta seguro de desactivar este usuario?", function(result){
		if (result) {
			$.post("../ajax/usuario.php?op=desactivar", {idusuario : idusuario}, function(e){
				bootbox.alert(e);
				listar(); // Recargar la tabla
			});
		}
	})
}

function activar(idusuario){
	bootbox.confirm("¿Esta seguro de activar este usuario?" , function(result){
		if (result) {
			$.post("../ajax/usuario.php?op=activar" , {idusuario : idusuario}, function(e){
				bootbox.alert(e);
				listar(); // Recargar la tabla
			});
		}
	})
}

$(document).ready(function() {
    // Vista previa de imagen al seleccionar archivo
    $("#imagen").change(function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $("#imagenmuestra").html('<img src="' + e.target.result + '" alt="Vista previa" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">');
            };
            reader.readAsDataURL(file);
        }
    });
});

init();