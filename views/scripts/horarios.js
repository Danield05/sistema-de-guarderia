// Función para mostrar formulario de AGREGAR nuevo horario
function mostrarFormulario(id) {
  if (id === 0 || id === undefined) {
    // AGREGAR NUEVO HORARIO
    $('#formulario')[0].reset();
    $('#modalLabel').html('<i class="fa fa-clock-o"></i> Agregar Nuevo Horario');
    $('#idField').hide(); // Ocultar campo ID para nuevos registros

    // Mover modal al body para evitar problemas de stacking context
    $('#modal').appendTo('body');

    $('#modal').modal({
      backdrop: 'static',
      keyboard: true
    });

    // Cargar datos en los selects
    cargarNinos();

  } else {
    // EDITAR HORARIO EXISTENTE
    mostrar(id);
  }
}

// Función para EDITAR horario existente
function mostrar(id) {
  $('#formulario')[0].reset();
  $('#modalLabel').html('<i class="fa fa-edit"></i> Editar Horario');
  $('#idField').show(); // Mostrar campo ID para edición

  // Mover modal al body para evitar problemas de stacking context
  $('#modal').appendTo('body');

  $('#modal').modal({
    backdrop: 'static',
    keyboard: true
  });

  // Cargar datos en los selects primero
  cargarNinos();

  // Usar setTimeout para asegurar que los datos del select estén cargados
  setTimeout(function() {
    // Luego cargar los datos del horario
    $.post("../ajax/horarios.php?op=mostrar", {idhorario: id}, function (data, status) {
      try {
        // Si data ya es un objeto, no necesitamos parsearlo
        if (typeof data === 'string') {
          data = JSON.parse(data);
        }
        $('#idhorario').val(data.id_horario);
        $('#id_nino').val(data.id_nino);
        $('#dia_semana').val(data.dia_semana);
        $('#hora_entrada').val(data.hora_entrada);
        $('#hora_salida').val(data.hora_salida);
        $('#descripcion').val(data.descripcion);

        // Actualizar información del aula y sección
        var selectedOption = $('#id_nino').find('option[value="' + data.id_nino + '"]');
        var aula = selectedOption.data('aula') || '';
        var seccion = selectedOption.data('seccion') || '';
        $('#aula_info').val(aula);
        $('#seccion_info').val(seccion);

      } catch (e) {
        console.error("Error parsing JSON:", e, data);
        bootbox.alert("Error al procesar los datos del horario.");
      }
    }).fail(function(xhr, status, error) {
      console.error("Error mostrando horario:", error, xhr.responseText);
      bootbox.alert("Error al cargar los datos del horario.");
    });
  }, 500);
}

// Función para cargar niños
function cargarNinos() {
  $('#id_nino').empty().append('<option value="">Cargando niños...</option>');

  $.ajax({
    url: "../ajax/horarios.php?op=selectNinos",
    type: "POST",
    dataType: "html",
    success: function (data) {
      $('#id_nino').html(data);

      // Event listener para actualizar aula y sección cuando se selecciona un niño
      $('#id_nino').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var aula = selectedOption.data('aula') || '';
        var seccion = selectedOption.data('seccion') || '';

        $('#aula_info').val(aula);
        $('#seccion_info').val(seccion);
      });
    },
    error: function(xhr, status, error) {
      $('#id_nino').empty().append('<option value="">Error al cargar niños</option>');
      console.error("Error al cargar niños:", error, xhr.responseText);
      bootbox.alert("Error al cargar los niños. Por favor, recarga la página.");
    }
  });
}

// Función para desactivar horario
function desactivar(id) {
  bootbox.confirm("¿Está seguro de eliminar este horario del niño?", function (result) {
    if (result) {
      $.post("../ajax/horarios.php?op=desactivar", {idhorario: id}, function (e) {
        bootbox.alert(e);
        location.reload();
      });
    }
  });
}

// Función para activar horario
function activar(id) {
  bootbox.confirm("¿Está seguro de activar este horario?", function (result) {
    if (result) {
      $.post("../ajax/horarios.php?op=activar", {idhorario: id}, function (e) {
        location.reload();
      });
    }
  });
}

// Función para cargar filtros
function cargarFiltros() {
  // Cargar aulas
  $.ajax({
    url: "../ajax/horarios.php?op=selectAulas",
    type: "POST",
    dataType: "html",
    success: function (data) {
      $('#filtro_aula').html(data);
    },
    error: function(xhr, status, error) {
      console.error("Error al cargar aulas:", error, xhr.responseText);
    }
  });

  // Cargar secciones
  $.ajax({
    url: "../ajax/horarios.php?op=selectSecciones",
    type: "POST",
    dataType: "html",
    success: function (data) {
      $('#filtro_seccion').html(data);
    },
    error: function(xhr, status, error) {
      console.error("Error al cargar secciones:", error, xhr.responseText);
    }
  });
}

// Función para filtrar horarios
function filtrarHorarios() {
  var nombre_nino = $('#filtro_nombre').val();
  var aula = $('#filtro_aula').val();
  var seccion = $('#filtro_seccion').val();

  // Construir URL con parámetros de filtro
  var url = window.location.pathname;
  var params = [];

  if (nombre_nino) params.push('nombre_nino=' + encodeURIComponent(nombre_nino));
  if (aula && aula !== '') params.push('aula=' + encodeURIComponent(aula));
  if (seccion && seccion !== '') params.push('seccion=' + encodeURIComponent(seccion));

  if (params.length > 0) {
    url += '?' + params.join('&');
  }

  // Recargar la página con filtros
  window.location.href = url;
}

// Función para limpiar filtros
function limpiarFiltros() {
  $('#filtro_nombre').val('');
  $('#filtro_aula').val('');
  $('#filtro_seccion').val('');
  window.location.href = window.location.pathname;
}

// Envío de formulario
$(document).ready(function () {
  // Cargar filtros si no es tutor
  if ($('#filtro_aula').length > 0) {
    cargarFiltros();

    // Cargar valores de filtros desde URL si existen
    const urlParams = new URLSearchParams(window.location.search);
    $('#filtro_nombre').val(urlParams.get('nombre_nino') || '');
    $('#filtro_aula').val(urlParams.get('aula') || '');
    $('#filtro_seccion').val(urlParams.get('seccion') || '');
  }

  $("#formulario").on("submit", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
      url: "../ajax/horarios.php?op=guardaryeditar",
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
        console.error("Error guardando horario:", error, xhr.responseText);
        bootbox.alert("Error al guardar el horario. Por favor, intenta nuevamente.");
      }
    });
  });
});