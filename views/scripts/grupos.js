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
	$("#idgrupo").val("");
	$("#nombre").val("");
	$("#favorito").val(""); 
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
    // Definir las columnas para la tabla de grupos
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
        { title: 'Usuario' },
        { title: 'Estado' }
    ];
    
    tabla = initCustomTable('tbllistado', {
        url: '../ajax/grupos.php?op=listar',
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
     	url: "../ajax/grupos.php?op=guardaryeditar",
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

function mostrar(idgrupo){
	$.post("../ajax/grupos.php?op=mostrar",{idgrupo : idgrupo},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#nombre").val(data.nombre);
			if (data.favorito==1) {
				$("#favorito").prop('checked', true);
			}else{
				$("#favorito").prop('checked', false);
			}
			$("#idgrupo").val(data.idgrupo);
		})
}


//funcion para desactivar
function desactivar(idgrupo){
	bootbox.confirm("¿Esta seguro de desactivar este dato?", function(result){
		if (result) {
			$.post("../ajax/grupos.php?op=desactivar", {idgrupo : idgrupo}, function(e){
				bootbox.alert(e);
				if (tabla) tabla.refresh();
			});
		}
	})
}

function activar(idgrupo){
	bootbox.confirm("¿Esta seguro de activar este dato?" , function(result){
		if (result) {
			$.post("../ajax/grupos.php?op=activar" , {idgrupo : idgrupo}, function(e){
				bootbox.alert(e);
				if (tabla) tabla.refresh();
			});
		}
	})
}

init();