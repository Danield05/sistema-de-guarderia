var tabla;
var alertasData = [];

//funcion que se ejecuta al inicio
function init(){
   mostrarform(false);
   listar();
   cargarNinos();
   cargarEstadisticas();

   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   })
}

//funcion limpiar
function limpiar(){
	$("#idalerta").val("");
	$("#id_nino").val("");
	$("#mensaje").val("");
	$("#tipo").val("");
	$("#estado").val("Pendiente");
}
 
//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
		$(".table-responsive").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}else{
		$(".table-responsive").show();
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
    // Cargar datos manualmente y renderizar
    $.post("../ajax/alertas.php?op=listar",{}, function(data){
        data = JSON.parse(data);
        const table = document.querySelector('#tbllistado');
        const thead = table.querySelector('thead');
        const tbody = document.querySelector('#tbody-alertas');
        
        if (!thead || !tbody) return;
        
        // Limpiar thead y tbody
        thead.innerHTML = '';
        while (tbody.firstChild) {
            tbody.removeChild(tbody.firstChild);
        }
        
        // Crear headers con estilo de aulas (sin ID)
        const headerTr = document.createElement('tr');
        const headers = [
            { text: 'Opciones', icon: 'fa-cogs' },
            { text: 'Niño', icon: 'fa-user' },
            { text: 'Mensaje', icon: 'fa-comment' },
            { text: 'Tipo', icon: 'fa-tag' },
            { text: 'Estado', icon: 'fa-flag' },
            { text: 'Fecha', icon: 'fa-calendar' }
        ];
        
        headers.forEach(function(header) {
            const th = document.createElement('th');
            th.style.cssText = 'border: none; padding: 1rem;';
            th.innerHTML = '<i class="fa ' + header.icon + '"></i> ' + header.text;
            headerTr.appendChild(th);
        });
        thead.appendChild(headerTr);
        
        // Crear filas de datos con estilo de aulas (sin ID)
        data.aaData.forEach(function(row) {
            const tr = document.createElement('tr');
            tr.style.cssText = 'border-bottom: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease;';
            
            // Columna Opciones (con botones)
            const tdOpciones = document.createElement('td');
            tdOpciones.style.cssText = 'padding: 1rem;';
            const buttons = [
                '<button class="btn btn-outline-warning btn-sm" style="margin-right: 0.5rem; border-radius: 20px;" onclick="mostrar(' + row[0] + ')"><i class="fa fa-pencil"></i> Editar</button>'
            ];
            
            if (row[4] === 'Pendiente') {
                buttons.push('<button class="btn btn-outline-success btn-sm" style="border-radius: 20px;" onclick="marcarRespondida(' + row[0] + ')"><i class="fa fa-check"></i> Responder</button>');
            } else {
                buttons.push('<button class="btn btn-outline-danger btn-sm" style="border-radius: 20px;" onclick="eliminar(' + row[0] + ')"><i class="fa fa-trash"></i> Eliminar</button>');
            }
            
            tdOpciones.innerHTML = buttons.join(' ');
            tr.appendChild(tdOpciones);
            
            // Niño (ahora en posición 1)
            const tdNino = document.createElement('td');
            tdNino.style.cssText = 'padding: 1rem; color: #666;';
            tdNino.textContent = row[1] || '';
            tr.appendChild(tdNino);
            
            // Mensaje (ahora en posición 2)
            const tdMensaje = document.createElement('td');
            tdMensaje.style.cssText = 'padding: 1rem; color: #666;';
            tdMensaje.textContent = row[2] || '';
            tr.appendChild(tdMensaje);
            
            // Tipo (ahora en posición 3)
            const tdTipo = document.createElement('td');
            tdTipo.style.cssText = 'padding: 1rem; color: #666;';
            tdTipo.textContent = row[3] || '';
            tr.appendChild(tdTipo);
            
            // Estado (ahora en posición 4)
            const tdEstado = document.createElement('td');
            tdEstado.style.cssText = 'padding: 1rem; color: #666;';
            const estado = row[4];
            const emoji = estado === 'Pendiente' ? '⏰' : '✅';
            tdEstado.textContent = emoji + ' ' + (estado || '');
            tr.appendChild(tdEstado);
            
            // Fecha (ahora en posición 5)
            const tdFecha = document.createElement('td');
            tdFecha.style.cssText = 'padding: 1rem; color: #666;';
            try {
                const date = new Date(row[5]);
                tdFecha.textContent = date.toLocaleDateString('es-ES');
            } catch (e) {
                tdFecha.textContent = row[5] || '';
            }
            tr.appendChild(tdFecha);
            
            tbody.appendChild(tr);
        });
        
        cargarEstadisticas();
    });
}

//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();//no se activara la accion predeterminada 
     $("#btnGuardar").prop("disabled",true);
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/alertas.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
     		bootbox.alert(datos);
     		mostrarform(false);
     		if (tabla) tabla.refresh();
     		cargarEstadisticas();
     	}
     });

     limpiar();
}

function mostrar(idalerta){
	$.post("../ajax/alertas.php?op=mostrar",{idalerta : idalerta},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#id_nino").val(data.id_nino);
			$("#mensaje").val(data.mensaje);
			$("#tipo").val(data.tipo);
			$("#estado").val(data.estado);
			$("#idalerta").val(data.id_alerta);
		})
}

function verDetalles(idalerta){
    // Cargar detalles de la alerta
    $.post("../ajax/alertas.php?op=mostrar",{idalerta : idalerta},
        function(data,status)
        {
            data=JSON.parse(data);
            
            const detalles = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>ID:</strong> ${data.id_alerta}<br>
                        <strong>Niño:</strong> ${data.nino}<br>
                        <strong>Tipo:</strong> ${data.tipo}<br>
                        <strong>Estado:</strong> <span style="color: ${data.estado === 'Pendiente' ? '#dc3545' : '#28a745'}">${data.estado}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Fecha:</strong> ${new Date(data.fecha_alerta).toLocaleString('es-ES')}<br>
                        <strong>ID Niño:</strong> ${data.id_nino}
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <strong>Mensaje:</strong><br>
                        <div class="alert alert-info">${data.mensaje}</div>
                    </div>
                </div>
            `;
            
            $('#alerta-detalles').html(detalles);
            $('#verAlertaModal').modal('show');
            
            // Configurar botón de responder
            if (data.estado === 'Pendiente') {
                $('#btn-responder-alerta').show().off('click').on('click', function() {
                    marcarRespondida(idalerta);
                    $('#verAlertaModal').modal('hide');
                });
            } else {
                $('#btn-responder-alerta').hide();
            }
        })
}

function marcarRespondida(idalerta){
	bootbox.confirm("¿Esta seguro de marcar esta alerta como respondida?", function(result){
		if (result) {
			$.post("../ajax/alertas.php?op=marcarRespondida", {idalerta : idalerta}, function(e){
				bootbox.alert(e);
				if (tabla) tabla.refresh();
				cargarEstadisticas();
			});
		}
	})
}

function eliminar(idalerta){
	bootbox.confirm("¿Esta seguro de eliminar esta alerta?", function(result){
		if (result) {
			$.post("../ajax/alertas.php?op=eliminar", {idalerta : idalerta}, function(e){
				bootbox.alert(e);
				if (tabla) tabla.refresh();
				cargarEstadisticas();
			});
		}
	})
}

//función para cargar niños en select
function cargarNinos(){
    $.post("../ajax/ninos.php?op=listar",{}, function(data){
        data = JSON.parse(data);
        const selectNino = $('#id_nino');
        
        // Limpiar opciones existentes
        selectNino.find('option:not(:first)').remove();
        
        // Verificar que existan datos
        if (data && data.aaData && data.aaData.length > 0) {
            // Agregar opciones
            data.aaData.forEach(function(nino) {
                // Extraer ID del campo acciones (onclick="mostrar(ID)")
                let id = null;
                if (nino.acciones) {
                    const match = nino.acciones.match(/onclick="mostrar\((\d+)\)/);
                    if (match) {
                        id = match[1];
                    }
                }
                
                // Usar nombre_completo que sí existe
                let nombre = nino.nombre_completo;
                
                if (id && nombre) {
                    selectNino.append(`<option value="${id}">${nombre}</option>`);
                }
            });
        }
    }).fail(function(xhr, status, error) {
        console.log('Error al cargar niños:', error, xhr.responseText);
    });
}

//función para cargar estadísticas
function cargarEstadisticas(){
    // Usar el nuevo endpoint de estadísticas completas
    $.post("../ajax/alertas.php?op=estadisticasCompletas",{}, function(data){
        data = JSON.parse(data);
        $('#total-alertas').text(data.total || 0);
        $('#alertas-pendientes').text(data.pendientes || 0);
        $('#alertas-respondidas').text(data.respondidas || 0);
    }).fail(function() {
        // Fallback si falla la consulta de estadísticas, obtener datos de la tabla actual
        const tbody = document.querySelector('#tbody-alertas');
        if (tbody) {
            const rows = tbody.querySelectorAll('tr');
            const total = rows.length;
            let pendientes = 0;
            let respondidas = 0;
            
            rows.forEach(function(row) {
                const estadoText = row.cells[4].textContent;
                if (estadoText.includes('⏰')) {
                    pendientes++;
                } else if (estadoText.includes('✅')) {
                    respondidas++;
                }
            });
            
            $('#total-alertas').text(total);
            $('#alertas-pendientes').text(pendientes);
            $('#alertas-respondidas').text(respondidas);
        }
    });
}

//función para filtrar alertas
function filtrarAlertas(){
    const estado = $('#filtro-estado').val();
    const tipo = $('#filtro-tipo').val();
    const nino = $('#filtro-nino').val();
    const fecha = $('#filtro-fecha').val();
    
    if (tabla && tabla.data) {
        tabla.filteredData = tabla.data.filter(alerta => {
            return (estado === '' || alerta[5] === estado) &&
                   (tipo === '' || alerta[4] === tipo) &&
                   (nino === '' || alerta[2] === nino) &&
                   (fecha === '' || (alerta[6] && alerta[6].startsWith(fecha)));
        });
        
        tabla.currentPage = 1;
        tabla.renderTable();
        tabla.updateTableInfo();
        tabla.renderPagination();
    }
}

// Auto-refresh cada 30 segundos
setInterval(function() {
    if (tabla) {
        tabla.refresh();
        cargarEstadisticas();
    }
}, 30000);

init();