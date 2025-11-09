// Función para calcular edad basada en fecha de nacimiento
function calcularEdad(fechaNacimiento) {
  if (!fechaNacimiento) return '';

  const hoy = new Date();
  const nacimiento = new Date(fechaNacimiento);
  let edad = hoy.getFullYear() - nacimiento.getFullYear();
  const mes = hoy.getMonth() - nacimiento.getMonth();

  if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
    edad--;
  }

  return edad;
}

// Variable global para almacenar todos los niños
var todosLosNinos = [];

// Función para inicializar filtros
function inicializarFiltros() {
  cargarAulasFiltro();
  cargarSeccionesFiltro();

  // Event listeners para filtros en tiempo real
  $('#filtro-nombre').on('keyup', function() {
    aplicarFiltros();
  });

  $('#filtro-aula').on('change', function() {
    aplicarFiltros();
    // Recargar secciones cuando cambie el aula
    cargarSeccionesFiltro();
  });

  $('#filtro-seccion').on('change', function() {
    aplicarFiltros();
  });
}

// Función para cargar aulas en el filtro
function cargarAulasFiltro() {
  $('#filtro-aula').empty().append('<option value="">Todas las aulas</option>');

  $.ajax({
    url: "../ajax/aulas.php?op=listar",
    type: "POST",
    dataType: "json",
    success: function (data) {
      $.each(data.aaData, function (i, aula) {
        $('#filtro-aula').append('<option value="' + aula[1] + '">' + aula[1] + '</option>');
      });
    },
    error: function(xhr, status, error) {
      console.error("Error al cargar aulas para filtro:", error);
    }
  });
}

// Función para cargar secciones en el filtro
function cargarSeccionesFiltro() {
  $('#filtro-seccion').empty().append('<option value="">Todas las secciones</option>');

  $.ajax({
    url: "../ajax/secciones.php?op=listar",
    type: "POST",
    dataType: "json",
    success: function (data) {
      $.each(data.aaData, function (i, seccion) {
        $('#filtro-seccion').append('<option value="' + seccion[1] + '">' + seccion[1] + '</option>');
      });
    },
    error: function(xhr, status, error) {
      console.error("Error al cargar secciones para filtro:", error);
    }
  });
}

// Función para aplicar filtros
function aplicarFiltros() {
  const nombreFiltro = $('#filtro-nombre').val().toLowerCase();
  const aulaFiltro = $('#filtro-aula').val();
  const seccionFiltro = $('#filtro-seccion').val();

  // Obtener todas las filas de la tabla
  const filas = $('tbody tr');

  filas.each(function() {
    const fila = $(this);
    const nombre = fila.find('td:nth-child(2)').text().toLowerCase(); // Columna Nombre
    const aula = fila.find('td:nth-child(6)').text(); // Columna Aula
    const seccion = fila.find('td:nth-child(7)').text(); // Columna Sección

    // Aplicar filtros
    const cumpleNombre = nombreFiltro === '' || nombre.includes(nombreFiltro);
    const cumpleAula = aulaFiltro === '' || aula === aulaFiltro;
    const cumpleSeccion = seccionFiltro === '' || seccion === seccionFiltro;

    if (cumpleNombre && cumpleAula && cumpleSeccion) {
      fila.show();
    } else {
      fila.hide();
    }
  });
}

// Función para limpiar filtros
function limpiarFiltros() {
  $('#filtro-nombre').val('');
  $('#filtro-aula').val('');
  $('#filtro-seccion').val('');
  aplicarFiltros();
}

// Función para mostrar formulario de AGREGAR nuevo niño
function mostrarFormulario(id) {
  if (id === 0 || id === undefined) {
    // AGREGAR NUEVO NIÑO
    $('#formulario')[0].reset();
    $('#modalLabel').html('<i class="fa fa-child"></i> Agregar Nuevo Niño');
    $('#idField').hide(); // Ocultar campo ID para nuevos registros

    // Mover modal al body para evitar problemas de stacking context
    $('#modal').appendTo('body');

    $('#modal').modal({
      backdrop: 'static',
      keyboard: true
    });

    // Cargar datos en los selects
    cargarAulas();
    cargarSecciones();
    cargarMaestros();
    cargarTutores();

    // Event listener para calcular edad automáticamente
    $('#fecha_nacimiento').on('change', function() {
      const edad = calcularEdad($(this).val());
      $('#edad').val(edad);
    });
  } else {
    // EDITAR NIÑO EXISTENTE
    mostrar(id);
  }
}

// Función para EDITAR niño existente (para administradores)
function mostrar(id) {
  $('#formulario')[0].reset();
  $('#modalLabel').html('<i class="fa fa-edit"></i> Editar Niño');
  $('#idField').show(); // Mostrar campo ID para edición

  // Mover modal al body para evitar problemas de stacking context
  $('#modal').appendTo('body');

  $('#modal').modal({
    backdrop: 'static',
    keyboard: true
  });

  // Cargar datos en los selects primero
  cargarAulas();
  cargarSecciones();
  cargarMaestros();
  cargarTutores();

  // Event listener para calcular edad automáticamente
  $('#fecha_nacimiento').on('change', function() {
    const edad = calcularEdad($(this).val());
    $('#edad').val(edad);
  });

  // Luego cargar los datos del niño
  $.post("../ajax/ninos.php?op=mostrar", {idnino: id}, function (data, status) {
    try {
      // Si data ya es un objeto, no necesitamos parsearlo
      if (typeof data === 'string') {
        data = JSON.parse(data);
      }
      $('#idnino').val(data.id_nino);
      $('#nombre_completo').val(data.nombre_completo);
      $('#fecha_nacimiento').val(data.fecha_nacimiento);
      $('#edad').val(data.edad);
      $('#peso').val(data.peso);

      // Usar setTimeout para asegurar que los selects estén cargados
      setTimeout(function() {
        $('#aula_id').val(data.aula_id);
        $('#seccion_id').val(data.seccion_id);
        $('#maestro_id').val(data.maestro_id);
        $('#tutor_id').val(data.tutor_id);
      }, 500);

    } catch (e) {
      console.error("Error parsing JSON:", e, data);
      bootbox.alert("Error al procesar los datos del niño.");
    }
  }).fail(function(xhr, status, error) {
    console.error("Error mostrando niño:", error, xhr.responseText);
    bootbox.alert("Error al cargar los datos del niño.");
  });
}

// Función para VER DETALLES del niño (para médicos)
function verDetalles(id) {
  // Cargar datos básicos del niño
  $.post("../ajax/ninos.php?op=mostrar", {idnino: id}, function (data, status) {
    try {
      if (typeof data === 'string') {
        data = JSON.parse(data);
      }

      // Crear modal de detalles médicos
      const modalContent = `
        <div class="modal fade" id="detallesModal" tabindex="-1" role="dialog" aria-labelledby="detallesLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
              <div class="modal-header" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; border-radius: 20px 20px 0 0; border-bottom: none; padding: 2rem;">
                <h4 class="modal-title" id="detallesLabel" style="font-weight: 600; font-size: 1.5rem;">
                  <i class="fa fa-user-md"></i> Detalles Médicos - ${data.nombre_completo}
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
                  <span aria-hidden="true" style="font-size: 2rem;">&times;</span>
                </button>
              </div>
              <div class="modal-body" style="padding: 2.5rem;">
                <div class="row">
                  <div class="col-md-6">
                    <h5 style="color: #3c8dbc; margin-bottom: 1rem;"><i class="fa fa-info-circle"></i> Información Básica</h5>
                    <div class="card" style="border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                      <div class="card-body">
                        <p><strong>Nombre:</strong> ${data.nombre_completo}</p>
                        <p><strong>Edad:</strong> ${data.edad} años</p>
                        <p><strong>Fecha de Nacimiento:</strong> ${new Date(data.fecha_nacimiento).toLocaleDateString('es-ES')}</p>
                        <p><strong>Peso:</strong> ${data.peso ? data.peso + ' kg' : 'No registrado'}</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <h5 style="color: #3c8dbc; margin-bottom: 1rem;"><i class="fa fa-graduation-cap"></i> Información Académica</h5>
                    <div class="card" style="border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                      <div class="card-body">
                        <p><strong>Aula:</strong> ${data.nombre_aula || 'No asignada'}</p>
                        <p><strong>Sección:</strong> ${data.nombre_seccion || 'No asignada'}</p>
                        <p><strong>Maestro:</strong> ${data.maestro || 'No asignado'}</p>
                        <p><strong>Tutor:</strong> ${data.tutor || 'No asignado'}</p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-4">
                  <div class="col-md-6">
                    <h5 style="color: #e74c3c; margin-bottom: 1rem;"><i class="fa fa-stethoscope"></i> Enfermedades</h5>
                    <div id="enfermedades-content" class="card" style="border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                      <div class="card-body">
                        <div class="text-center">
                          <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Cargando...</span>
                          </div>
                          <p>Cargando enfermedades...</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <h5 style="color: #f39c12; margin-bottom: 1rem;"><i class="fa fa-allergies"></i> Alergias</h5>
                    <div id="alergias-content" class="card" style="border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                      <div class="card-body">
                        <div class="text-center">
                          <div class="spinner-border text-warning" role="status">
                            <span class="sr-only">Cargando...</span>
                          </div>
                          <p>Cargando alergias...</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-4">
                  <div class="col-md-12">
                    <h5 style="color: #27ae60; margin-bottom: 1rem;"><i class="fa fa-bell"></i> Alertas de Salud Recientes</h5>
                    <div id="alertas-content" class="card" style="border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                      <div class="card-body">
                        <div class="text-center">
                          <div class="spinner-border text-success" role="status">
                            <span class="sr-only">Cargando...</span>
                          </div>
                          <p>Cargando alertas de salud...</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer" style="border-top: none; padding: 2rem; background: #f8f9fa; border-radius: 0 0 20px 20px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: #6c757d;">
                  <i class="fa fa-times"></i> Cerrar
                </button>
              </div>
            </div>
          </div>
        </div>
      `;

      // Agregar modal al body
      $('body').append(modalContent);

      // Mostrar modal
      $('#detallesModal').modal({
        backdrop: 'static',
        keyboard: true
      });

      // Cargar información médica
      cargarEnfermedadesNino(id);
      cargarAlergiasNino(id);
      cargarAlertasSaludNino(id);

      // Limpiar modal cuando se cierre
      $('#detallesModal').on('hidden.bs.modal', function () {
        $(this).remove();
      });

    } catch (e) {
      console.error("Error parsing JSON:", e, data);
      bootbox.alert("Error al procesar los datos del niño.");
    }
  }).fail(function(xhr, status, error) {
    console.error("Error mostrando detalles del niño:", error, xhr.responseText);
    bootbox.alert("Error al cargar los detalles del niño.");
  });
}

// Función para cargar aulas
function cargarAulas() {
  $('#aula_id').empty().append('<option value="">Cargando aulas...</option>');

  $.ajax({
    url: "../ajax/aulas.php?op=listar",
    type: "POST",
    dataType: "json",
    success: function (data) {
      $('#aula_id').empty().append('<option value="">Seleccionar aula...</option>');
      $.each(data.aaData, function (i, aula) {
        $('#aula_id').append('<option value="' + aula[0] + '">' + aula[1] + '</option>');
      });
    },
    error: function(xhr, status, error) {
      $('#aula_id').empty().append('<option value="">Error al cargar aulas</option>');
      console.error("Error al cargar aulas:", error, xhr.responseText);
      bootbox.alert("Error al cargar las aulas. Por favor, recarga la página.");
    }
  });
}

// Función para cargar secciones
function cargarSecciones() {
  $('#seccion_id').empty().append('<option value="">Cargando secciones...</option>');

  $.ajax({
    url: "../ajax/secciones.php?op=listar",
    type: "POST",
    dataType: "json",
    success: function (data) {
      $('#seccion_id').empty().append('<option value="">Seleccionar sección...</option>');
      $.each(data.aaData, function (i, seccion) {
        $('#seccion_id').append('<option value="' + seccion[0] + '">' + seccion[1] + '</option>');
      });
    },
    error: function(xhr, status, error) {
      $('#seccion_id').empty().append('<option value="">Error al cargar secciones</option>');
      console.error("Error al cargar secciones:", error, xhr.responseText);
      bootbox.alert("Error al cargar las secciones. Por favor, recarga la página.");
    }
  });
}

// Función para cargar maestros
function cargarMaestros() {
  $('#maestro_id').empty().append('<option value="">Cargando maestros...</option>');

  $.ajax({
    url: "../ajax/usuario.php?op=listar_maestros",
    type: "POST",
    dataType: "json",
    success: function (data) {
      $('#maestro_id').empty().append('<option value="">Seleccionar maestro...</option>');
      $.each(data.aaData, function (i, maestro) {
        $('#maestro_id').append('<option value="' + maestro[0] + '">' + maestro[1] + '</option>');
      });
    },
    error: function(xhr, status, error) {
      $('#maestro_id').empty().append('<option value="">Error al cargar maestros</option>');
      console.error("Error al cargar maestros:", error, xhr.responseText);
    }
  });
}

// Función para cargar tutores
function cargarTutores() {
  $('#tutor_id').empty().append('<option value="">Cargando tutores...</option>');

  $.ajax({
    url: "../ajax/usuario.php?op=listar_tutores",
    type: "POST",
    dataType: "json",
    success: function (data) {
      $('#tutor_id').empty().append('<option value="">Seleccionar tutor...</option>');
      $.each(data.aaData, function (i, tutor) {
        $('#tutor_id').append('<option value="' + tutor[0] + '">' + tutor[1] + '</option>');
      });
    },
    error: function(xhr, status, error) {
      $('#tutor_id').empty().append('<option value="">Error al cargar tutores</option>');
      console.error("Error al cargar tutores:", error, xhr.responseText);
    }
  });
}

// Función para desactivar niño
function desactivar(id) {
  bootbox.confirm("¿Está seguro de desactivar este niño?", function (result) {
    if (result) {
      $.post("../ajax/ninos.php?op=desactivar", {idnino: id}, function (e) {
        location.reload();
      });
    }
  });
}

// Función para activar niño
function activar(id) {
  bootbox.confirm("¿Está seguro de activar este niño?", function (result) {
    if (result) {
      $.post("../ajax/ninos.php?op=activar", {idnino: id}, function (e) {
        location.reload();
      });
    }
  });
}

// Función para cargar enfermedades del niño
function cargarEnfermedadesNino(idNino) {
  $.post("../ajax/enfermedades.php?op=listarPorNino", {id_nino: idNino}, function (data, status) {
    try {
      if (typeof data === 'string') {
        data = JSON.parse(data);
      }

      let html = '';
      if (data && data.length > 0) {
        data.forEach(function(enfermedad) {
          // Los datos vienen como array: [id, nombre, descripcion, fecha]
          const nombre = enfermedad[1] || 'Enfermedad sin nombre';
          const descripcion = enfermedad[2] || 'Sin descripción';
          const fecha = enfermedad[3] || null;

          const fechaFormateada = fecha ?
            new Date(fecha).toLocaleDateString('es-ES') :
            'Fecha no disponible';

          html += `
            <div class="alert alert-danger" style="margin-bottom: 0.5rem;">
              <strong>${nombre}</strong><br>
              <small>Fecha: ${fechaFormateada}</small><br>
              <small>Descripción: ${descripcion}</small>
            </div>
          `;
        });
      } else {
        html = '<p class="text-muted mb-0"><i class="fa fa-check-circle text-success"></i> No tiene enfermedades registradas</p>';
      }

      $('#enfermedades-content .card-body').html(html);
    } catch (e) {
      console.error("Error cargando enfermedades:", e);
      $('#enfermedades-content .card-body').html('<p class="text-danger">Error al cargar enfermedades</p>');
    }
  }).fail(function(xhr, status, error) {
    console.error("Error cargando enfermedades:", error);
    $('#enfermedades-content .card-body').html('<p class="text-danger">Error al cargar enfermedades</p>');
  });
}

// Función para cargar alergias del niño
function cargarAlergiasNino(idNino) {
  $.post("../ajax/alergias.php?op=listarPorNino", {id_nino: idNino}, function (data, status) {
    try {
      if (typeof data === 'string') {
        data = JSON.parse(data);
      }

      let html = '';
      if (data && data.length > 0) {
        data.forEach(function(alergia) {
          // Los datos vienen como array: [id, nombre, descripcion] (sin fecha para alergias)
          const nombre = alergia[1] || 'Alergia sin nombre';
          const descripcion = alergia[2] || 'Sin descripción';

          html += `
            <div class="alert alert-warning" style="margin-bottom: 0.5rem;">
              <strong>${nombre}</strong><br>
              <small>Descripción: ${descripcion}</small>
            </div>
          `;
        });
      } else {
        html = '<p class="text-muted mb-0"><i class="fa fa-check-circle text-success"></i> No tiene alergias registradas</p>';
      }

      $('#alergias-content .card-body').html(html);
    } catch (e) {
      console.error("Error cargando alergias:", e);
      $('#alergias-content .card-body').html('<p class="text-danger">Error al cargar alergias</p>');
    }
  }).fail(function(xhr, status, error) {
    console.error("Error cargando alergias:", error);
    $('#alergias-content .card-body').html('<p class="text-danger">Error al cargar alergias</p>');
  });
}

// Función para cargar alertas de salud del niño
function cargarAlertasSaludNino(idNino) {
  $.post("../ajax/alertas.php?op=listarPorNino", {id_nino: idNino}, function (data, status) {
    try {
      if (typeof data === 'string') {
        data = JSON.parse(data);
      }

      let html = '';
      if (data && data.length > 0) {
        // Filtrar solo alertas de salud
        const alertasSalud = data.filter(function(alerta) {
          return alerta[3] === 'Salud'; // índice 3 es el tipo de alerta
        });

        if (alertasSalud.length > 0) {
          alertasSalud.forEach(function(alerta) {
            const estadoClass = alerta[4] === 'Pendiente' ? 'warning' : 'success';
            const estadoIcon = alerta[4] === 'Pendiente' ? 'clock' : 'check';
            html += `
              <div class="alert alert-${estadoClass}" style="margin-bottom: 0.5rem;">
                <strong><i class="fa fa-${estadoIcon}"></i> ${alerta[2]}</strong><br>
                <small>Fecha: ${new Date(alerta[1]).toLocaleDateString('es-ES')}</small><br>
                <small>Estado: ${alerta[4]}</small>
              </div>
            `;
          });
        } else {
          html = '<p class="text-muted mb-0"><i class="fa fa-info-circle text-info"></i> No tiene alertas de salud registradas</p>';
        }
      } else {
        html = '<p class="text-muted mb-0"><i class="fa fa-info-circle text-info"></i> No tiene alertas de salud registradas</p>';
      }

      $('#alertas-content .card-body').html(html);
    } catch (e) {
      console.error("Error cargando alertas:", e);
      $('#alertas-content .card-body').html('<p class="text-danger">Error al cargar alertas de salud</p>');
    }
  }).fail(function(xhr, status, error) {
    console.error("Error cargando alertas:", error);
    $('#alertas-content .card-body').html('<p class="text-danger">Error al cargar alertas de salud</p>');
  });
}

// Envío de formulario
$(document).ready(function () {
  // Inicializar filtros cuando se carga la página
  inicializarFiltros();

  $("#formulario").on("submit", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
      url: "../ajax/ninos.php?op=guardaryeditar",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (datos) {
        bootbox.alert(datos);
        $('#modal').modal('hide');
        location.reload();
      },
      error: function(xhr, status, error) {
        console.error("Error guardando niño:", error, xhr.responseText);
        bootbox.alert("Error al guardar el niño. Por favor, intenta nuevamente.");
      }
    });
  });
});