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
	$("#idpermiso").val("");
	$("#nombre").val("");
	$("#slug").val("");
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
    // Definir las columnas para la tabla de permisos
    const columns = [
        { 
            title: 'Opciones',
            render: function(data, row, index) {
                return '<div class="action-buttons">' +
                    '<button class="btn-action btn-edit" onclick="mostrar(' + row[0] + ')">✏️</button>' +
                    '<button class="btn-action ' + (row[3] === 'Activo' ? 'btn-deactivate' : 'btn-activate') + 
                    '" onclick="' + (row[3] === 'Activo' ? 'desactivar' : 'activar') + '(' + row[0] + ')">' + 
                    (row[3] === 'Activo' ? 'Desactivar' : 'Activar') + '</button>' +
                    '</div>';
            }
        },
        { title: 'Nombre' },
        { title: 'Slug' },
        { title: 'Estado' }
    ];
    
    tabla = initCustomTable('tbllistado', {
        url: '../ajax/permiso.php?op=listar',
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
     	url: "../ajax/permiso.php?op=guardaryeditar",
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

function mostrar(idpermiso){
	$.post("../ajax/permiso.php?op=mostrar",{idpermiso : idpermiso},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#nombre").val(data.nombre);
			$("#slug").val(data.slug);
			$("#idpermiso").val(data.idpermiso);
		})
}

//funcion para desactivar
function desactivar(idpermiso){
	bootbox.confirm("¿Esta seguro de desactivar este permiso?", function(result){
		if (result) {
			$.post("../ajax/permiso.php?op=desactivar", {idpermiso : idpermiso}, function(e){
				bootbox.alert(e);
				if (tabla) tabla.refresh();
			});
		}
	})
}

function activar(idpermiso){
	bootbox.confirm("¿Esta seguro de activar este permiso?" , function(result){
		if (result) {
			$.post("../ajax/permiso.php?op=activar" , {idpermiso : idpermiso}, function(e){
				bootbox.alert(e);
				if (tabla) tabla.refresh();
			});
		}
	})
}

init();