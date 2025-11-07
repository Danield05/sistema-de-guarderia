// Función para mostrar/ocultar formulario
function mostrarFormulario(id) {
  $('#formulario')[0].reset();
  $('#modalLabel').html(id > 0 ? '<i class="fa fa-edit"></i> Editar Niño' : '<i class="fa fa-child"></i> Agregar Nuevo Niño');

  // Mostrar/ocultar campo ID según si es nuevo o edición
  if (id > 0) {
    $('#idField').show();
  } else {
    $('#idField').hide();
  }

  // Deshabilitar el dropdown de secciones al inicio
  $('#seccion_id').prop('disabled', true);

  // Mover modal al body para evitar problemas de stacking context
  $('#modal').appendTo('body');

  $('#modal').modal({
    backdrop: 'static',
    keyboard: true
  });

  // Cargar datos en los selects
  cargarAulas();
  cargarUsuarios();

  if (id > 0) {
    mostrar(id);
  }
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
        $('#aula_id').append('<option value="' + aula[0] + '">' + aula[2] + '</option>');
      });

      // Configurar el event handler después de cargar las aulas
      $('#aula_id').off('change').on('change', function() {
        var aulaId = $(this).val();
        if (aulaId) {
          $('#seccion_id').prop('disabled', false);
          cargarSeccionesPorAula(aulaId);
        } else {
          $('#seccion_id').prop('disabled', true);
          $('#seccion_id').empty().append('<option value="">Seleccionar sección...</option>');
        }
      });

      // Si hay un aula preseleccionada (en modo edición), cargar las secciones
      var selectedAula = $('#aula_id').val();
      if (selectedAula) {
        $('#seccion_id').prop('disabled', false);
        cargarSeccionesPorAula(selectedAula);
      }
    },
    error: function(xhr, status, error) {
      $('#aula_id').empty().append('<option value="">Error al cargar aulas</option>');
      bootbox.alert("Error al cargar las aulas. Por favor, recarga la página.");
    }
  });
}

// Función para cargar secciones filtradas por aula
function cargarSeccionesPorAula(aulaId) {
  $('#seccion_id').empty().append('<option value="">Cargando secciones...</option>');

  $.ajax({
    url: "../ajax/secciones.php?op=listarPorAula",
    type: "POST",
    data: {aula_id: aulaId},
    dataType: "json",
    success: function (data) {
      $('#seccion_id').empty().append('<option value="">Seleccionar sección...</option>');
      
      if (data && data.length > 0) {
        // PROCESAMIENTO: Array simple de secciones
        $.each(data, function (i, seccion) {
          $('#seccion_id').append('<option value="' + seccion[0] + '">' + seccion[1] + '</option>');
        });
      } else {
        $('#seccion_id').empty().append('<option value="">No hay secciones disponibles</option>');
      }
    },
    error: function(xhr, status, error) {
      $('#seccion_id').empty().append('<option value="">Error al cargar secciones</option>');
      bootbox.alert("Error al cargar las secciones: " + xhr.responseText);
    }
  });
}

// Función para cargar usuarios
function cargarUsuarios() {
  $('#maestro_id').empty().append('<option value="">Cargando maestros...</option>');
  $('#tutor_id').empty().append('<option value="">Cargando tutores...</option>');

  $.ajax({
    url: "../ajax/usuario.php?op=listar",
    type: "POST",
    dataType: "json",
    success: function (data) {
      $('#maestro_id').empty().append('<option value="">Seleccionar maestro...</option>');
      $('#tutor_id').empty().append('<option value="">Seleccionar tutor...</option>');
      $.each(data.aaData, function (i, usuario) {
        // Verificar si tiene rol_id definido
        if (usuario.rol_id == 2) { // Maestro
          $('#maestro_id').append('<option value="' + usuario.id_usuario + '">' + usuario.nombre_completo + '</option>');
        }
        if (usuario.rol_id == 1) { // Padre/Tutor
          $('#tutor_id').append('<option value="' + usuario.id_usuario + '">' + usuario.nombre_completo + '</option>');
        }
      });
    },
    error: function(xhr, status, error) {
      $('#maestro_id').empty().append('<option value="">Error al cargar maestros</option>');
      $('#tutor_id').empty().append('<option value="">Error al cargar tutores</option>');
      bootbox.alert("Error al cargar los usuarios: " + xhr.responseText);
    }
  });
}

// Función para mostrar datos de un niño
function mostrar(id) {
  $.post("../ajax/ninos.php?op=mostrar", {idnino: id}, function (data, status) {
    data = JSON.parse(data);
    $('#idnino').val(data.id_nino);
    $('#nombre_completo').val(data.nombre_completo);
    $('#fecha_nacimiento').val(data.fecha_nacimiento);
    $('#edad').val(data.edad);
    $('#peso').val(data.peso);
    $('#aula_id').val(data.aula_id);
    cargarSeccionesPorAula(data.aula_id);
    setTimeout(function() {
      $('#seccion_id').val(data.seccion_id);
    }, 500);
    $('#maestro_id').val(data.maestro_id);
    $('#tutor_id').val(data.tutor_id);
  });
}

// Evento change para cargar secciones cuando se selecciona aula
$(document).ready(function () {
  
  // Event handler del aula (para elementos que ya existen)
  $('#aula_id').change(function() {
    var aulaId = $(this).val();
    if (aulaId) {
      cargarSeccionesPorAula(aulaId);
    } else {
      $('#seccion_id').empty().append('<option value="">Seleccionar sección...</option>');
    }
  });

  // Envío de formulario
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
      }
    });
  });
});