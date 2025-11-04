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
}

//funcion mostrar formulario
function mostrarform(flag){
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
   // Redireccionar al escritorio
   window.location.href = "escritorio.php";
}

//funcion listar
function listar(){
	tabla=$('#tbllistado').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdf'
		],
		"ajax":
		{
			url:'../ajax/permiso.php?op=listar',
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":5,//paginacion
		"order":[[0,"desc"]]//ordenar (columna, orden)
	}).DataTable();
}

//funcion para guardar o editar
function guardaryeditar(e){
   e.preventDefault(); //No se activará la acción predeterminada del evento
   $("#btnGuardar").prop("disabled",true);
   var formData = new FormData($("#formulario")[0]);

   $.ajax({
      url: "../ajax/permiso.php?op=guardaryeditar",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,

      success: function(datos){
         bootbox.alert(datos);
         mostrarform(false);
         tabla.ajax.reload();
      }
   });
   limpiar();
}

//funcion mostrar
function mostrar(idpermiso){
   $.post("../ajax/permiso.php?op=mostrar",{idpermiso : idpermiso}, function(data, status)
   {
      data = JSON.parse(data);
      mostrarform(true);

      $("#idpermiso").val(data.idpermiso);
      $("#nombre").val(data.nombre);
   })
}

//funcion para desactivar
function desactivar(idpermiso){
   bootbox.confirm("¿Está seguro de desactivar el permiso?", function(result){
      if(result){
         $.post("../ajax/permiso.php?op=desactivar", {idpermiso : idpermiso}, function(e){
            bootbox.alert(e);
            tabla.ajax.reload();
         });
      }
   })
}

//funcion para activar
function activar(idpermiso){
   bootbox.confirm("¿Está seguro de activar el permiso?", function(result){
      if(result){
         $.post("../ajax/permiso.php?op=activar", {idpermiso : idpermiso}, function(e){
            bootbox.alert(e);
            tabla.ajax.reload();
         });
      }
   })
}

init();