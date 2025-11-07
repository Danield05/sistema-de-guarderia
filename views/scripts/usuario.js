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
	$("#tipo_documento").val("");
	$("#num_documento").val("");
	$("#direccion").val("");
	$("#telefono").val("");
	$("#email").val("");
	$("#cargo").val("");
	$("#login").val("");
	$("#clave").val("");
	$("#imagen").val(""); 
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
    // Definir las columnas para la tabla de usuarios
    const columns = [
        { 
            title: 'Opciones',
            render: function(data, row, index) {
                return '<div class="action-buttons">' +
                    '<button class="btn-action btn-edit" onclick="mostrar(' + row[8] + ')">✏️</button>' +
                    '<button class="btn-action btn-edit" onclick="mostrar_clave(' + row[8] + ')">🔑</button>' +
                    '<button class="btn-action ' + (row[5] === 'Activo' ? 'btn-deactivate' : 'btn-activate') + 
                    '" onclick="' + (row[5] === 'Activo' ? 'desactivar' : 'activar') + '(' + row[8] + ')">' + 
                    (row[5] === 'Activo' ? 'Desactivar' : 'Activar') + '</button>' +
                    '</div>';
            }
        },
        { title: 'Nombre Completo' },
        { title: 'Rol' },
        { title: 'Teléfono' },
        { title: 'Email' },
        { title: 'Estado' }
    ];
    
    tabla = initCustomTable('tbllistado', {
        url: '../ajax/usuario.php?op=listar',
        columns: columns,
        itemsPerPage: 10,
        onEdit: mostrar,
        onActivate: activar,
        onDeactivate: desactivar
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
     		mostrarform(false);
     		if (tabla) tabla.refresh();
     	}
     });

     limpiar();
}

function mostrar(idusuario){
	$.post("../ajax/usuario.php?op=mostrar",{idusuario : idusuario},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#nombre").val(data.nombre_completo);
			$("#tipo_documento").val(data.tipo_documento);
			$("#num_documento").val(data.num_documento);
			$("#direccion").val(data.direccion);
			$("#telefono").val(data.telefono);
			$("#email").val(data.email);
			$("#cargo").val(data.cargo);
			$("#login").val(data.login);
			$("#idusuario").val(data.id_usuario);
		})
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
				if (tabla) tabla.refresh();
			});
		}
	})
}

function activar(idusuario){
	bootbox.confirm("¿Esta seguro de activar este usuario?" , function(result){
		if (result) {
			$.post("../ajax/usuario.php?op=activar" , {idusuario : idusuario}, function(e){
				bootbox.alert(e);
				if (tabla) tabla.refresh();
			});
		}
	})
}

init();