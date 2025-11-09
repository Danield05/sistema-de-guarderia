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

// Función para EDITAR niño existente
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

// Envío de formulario
$(document).ready(function () {
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