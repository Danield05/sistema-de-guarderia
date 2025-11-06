<?php
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ((isset($_SESSION['ninos']) && $_SESSION['ninos'] == 1) || (isset($_SESSION['rol']) && $_SESSION['rol'] == 'Administrador')) {
?>
    <!-- 🔧 Quitamos padding lateral con clases personalizadas -->
    <main class="container-fluid py-5 px-3 main-dashboard" style="padding-top: 3rem; padding-bottom: 3rem;">
      
      <!-- Título de la sección -->
      <div class="welcome-card">
        <div class="welcome-content">
          <h1 class="welcome-title">👶 Gestión de Niños</h1>
          <p class="welcome-subtitle">Administra la información de los niños en la guardería</p>
        </div>
      </div>

      <!-- Botón para abrir modal de registro -->
      <div class="mb-3">
        <button type="button" class="btn btn-primary" onclick="mostrarFormulario(0)">
          <i class="fa fa-plus"></i> Nuevo Niño
        </button>
      </div>

      <!-- Tabla de niños -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Lista de Niños</h3>
        </div>
        <div class="box-body">
          <table id="tabla" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Acciones</th>
                <th>Nombre Completo</th>
                <th>Edad</th>
                <th>Fecha Nacimiento</th>
                <th>Peso (kg)</th>
                <th>Aula</th>
                <th>Sección</th>
                <th>Maestro</th>
                <th>Tutor</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal para registro y edición -->
      <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modalLabel">Niño</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="formulario">
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="idnino">ID</label>
                      <input type="text" class="form-control" id="idnino" name="idnino" readonly>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="nombre_completo">Nombre Completo *</label>
                      <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="fecha_nacimiento">Fecha de Nacimiento *</label>
                      <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="edad">Edad *</label>
                      <input type="number" class="form-control" id="edad" name="edad" required min="0" max="18">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="peso">Peso (kg)</label>
                      <input type="number" class="form-control" id="peso" name="peso" min="0" step="0.1">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="aula_id">Aula *</label>
                      <select class="form-control" id="aula_id" name="aula_id" required>
                        <option value="">Seleccionar aula...</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="seccion_id">Sección *</label>
                      <select class="form-control" id="seccion_id" name="seccion_id" required>
                        <option value="">Seleccionar sección...</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="maestro_id">Maestro Asignado</label>
                      <select class="form-control" id="maestro_id" name="maestro_id">
                        <option value="">Seleccionar maestro...</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="tutor_id">Tutor</label>
                      <select class="form-control" id="tutor_id" name="tutor_id">
                        <option value="">Seleccionar tutor...</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </main>

    <script>
      // Función para mostrar/ocultar formulario
      function mostrarFormulario(id) {
        $('#formulario')[0].reset();
        $('#modalLabel').html(id > 0 ? 'Editar Niño' : 'Nuevo Niño');
        $('#modal').modal('show');
        
        // Cargar datos en los selects
        cargarAulas();
        cargarUsuarios();
        
        if (id > 0) {
          mostrar(id);
        }
      }

      // Función para cargar aulas
      function cargarAulas() {
        $.post("../ajax/aulas.php?op=listar", function (data) {
          data = JSON.parse(data);
          $('#aula_id').empty().append('<option value="">Seleccionar aula...</option>');
          $.each(data.aaData, function (i, aula) {
            $('#aula_id').append('<option value="' + aula.id_aula + '">' + aula.nombre_aula + '</option>');
          });
        });
      }

      // Función para cargar secciones filtradas por aula
      function cargarSeccionesPorAula(aulaId) {
        $.post("../ajax/secciones.php?op=listarPorAula", {aula_id: aulaId}, function (data) {
          data = JSON.parse(data);
          $('#seccion_id').empty().append('<option value="">Seleccionar sección...</option>');
          $.each(data, function (i, seccion) {
            $('#seccion_id').append('<option value="' + seccion[0] + '">' + seccion[1] + '</option>');
          });
        });
      }

      // Función para cargar usuarios
      function cargarUsuarios() {
        $.post("../ajax/usuario.php?op=listar", function (data) {
          data = JSON.parse(data);
          $('#maestro_id').empty().append('<option value="">Seleccionar maestro...</option>');
          $('#tutor_id').empty().append('<option value="">Seleccionar tutor...</option>');
          $.each(data.aaData, function (i, usuario) {
            if (usuario.rol_id == 2) { // Maestro
              $('#maestro_id').append('<option value="' + usuario.id_usuario + '">' + usuario.nombre_completo + '</option>');
            }
            if (usuario.rol_id == 1) { // Padre/Tutor
              $('#tutor_id').append('<option value="' + usuario.id_usuario + '">' + usuario.nombre_completo + '</option>');
            }
          });
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

      // Función para desactivar niño
      function desactivar(id) {
        bootbox.confirm("¿Está seguro de desactivar el niño?", function (result) {
          if (result) {
            $.post("../ajax/ninos.php?op=desactivar", {idnino: id}, function (e) {
              tabla.ajax.reload();
            });
          }
        });
      }

      // Función para activar niño
      function activar(id) {
        bootbox.confirm("¿Está seguro de activar el niño?", function (result) {
          if (result) {
            $.post("../ajax/ninos.php?op=activar", {idnino: id}, function (e) {
              tabla.ajax.reload();
            });
          }
        });
      }

      // Inicializar tabla
      var tabla;
      $(document).ready(function () {
        tabla = $("#tabla").DataTable({
          "aProcessing": true,
          "aServerSide": true,
          ajax: {
            url: "../ajax/ninos.php?op=listar",
            type: "post",
          },
          columns: [
            { data: "acciones" },
            { data: "nombre_completo" },
            { data: "edad" },
            { data: "fecha_nacimiento" },
            { data: "peso" },
            { data: "nombre_aula" },
            { data: "nombre_seccion" },
            { data: "maestro" },
            { data: "tutor" }
          ],
          language: {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
              "sFirst": "Primero",
              "sLast": "Último",
              "sNext": "Siguiente",
              "sPrevious": "Anterior"
            },
            "oAria": {
              "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
          }
        });

        // Evento change para cargar secciones cuando se selecciona aula
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
              tabla.ajax.reload();
            }
          });
        });

      });
    </script>

<?php
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';
}
?>