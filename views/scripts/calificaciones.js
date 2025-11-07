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
	$("#idcalificacion").val("");
	$("#idnino").val("");
	$("#idcurso").val("");
	$("#calificacion").val("");
	$("#periodo").val("");
	$("#año").val("");
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
    // Definir las columnas para la tabla de calificaciones
    const columns = [
        { 
            title: 'Opciones',
            render: function(data, row, index) {
                return '<div class="action-buttons">' +
                    '<button class="btn-action btn-edit" onclick="mostrar(' + row[0] + ')">✏️</button>' +
                    '<button class="btn-action ' + (row[6] === 'Activo' ? 'btn-deactivate' : 'btn-activate') + 
                    '" onclick="' + (row[6] === 'Activo' ? 'desactivar' : 'activar') + '(' + row[0] + ')">' + 
                    (row[6] === 'Activo' ? 'Desactivar' : 'Activar') + '</button>' +
                    '</div>';
            }
        },
        { title: 'ID' },
        { title: 'Niño' },
        { title: 'Curso' },
        { title: 'Calificación' },
        { title: 'Período' },
        { title: 'Estado' }
    ];
    
    tabla = initCustomTable('tbllistado', {
        url: '../ajax/calificaciones.php?op=listar',
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
     	url: "../ajax/calificaciones.php?op=guardaryeditar",
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

function mostrar(idcalificacion){
	$.post("../ajax/calificaciones.php?op=mostrar",{idcalificacion : idcalificacion},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#idnino").val(data.idnino);
			$("#idcurso").val(data.idcurso);
			$("#calificacion").val(data.calificacion);
			$("#periodo").val(data.periodo);
			$("#año").val(data.año);
			$("#idcalificacion").val(data.idcalificacion);
		})
}

//funcion para desactivar
function desactivar(idcalificacion){
	bootbox.confirm("¿Esta seguro de desactivar esta calificación?", function(result){
		if (result) {
			$.post("../ajax/calificaciones.php?op=desactivar", {idcalificacion : idcalificacion}, function(e){
				bootbox.alert(e);
				if (tabla) tabla.refresh();
			});
		}
	})
}

function activar(idcalificacion){
	bootbox.confirm("¿Esta seguro de activar esta calificación?" , function(result){
		if (result) {
			$.post("../ajax/calificaciones.php?op=activar" , {idcalificacion : idcalificacion}, function(e){
				bootbox.alert(e);
				if (tabla) tabla.refresh();
			});
		}
	})
}

init();