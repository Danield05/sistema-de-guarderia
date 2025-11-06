<?php
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ((isset($_SESSION['secciones']) && $_SESSION['secciones'] == 1) || $_SESSION['cargo'] == 'Administrador') {
?>
    <!-- 🔧 Quitamos padding lateral con clases personalizadas -->
    <main class="container-fluid py-5 px-3 main-dashboard" style="padding-top: 3rem; padding-bottom: 3rem;">
      
      <!-- Título de la sección -->
      <div class="welcome-card">
        <div class="welcome-content">
          <h1 class="welcome-title">🏫 Gestión de Secciones</h1>
          <p class="welcome-subtitle">Administra las secciones de cada aula</p>
        </div>
      </div>

      <!-- Botón para abrir modal de registro -->
      <div class="mb-3">
        <button type="button" class="btn btn-primary" onclick="mostrarFormulario(0)">
          <i class="fa fa-plus"></i> Nueva Sección
        </button>
      </div>

      <!-- Tabla de secciones -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Lista de Secciones</h3>
        </div>
        <div class="box-body">
          <table id="tabla" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Acciones</th>
                <th>Sección</th>
                <th>Aula</th>
                <th>ID Aula</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal para registro y edición -->
      <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modalLabel">Sección</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="formulario">
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="idseccion">ID</label>
                      <input type="text" class="form-control" id="idseccion" name="idseccion" readonly>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="nombre_seccion">Nombre de la Sección *</label>
                      <input type="text" class="form-control" id="nombre_seccion" name="nombre_seccion" required>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="aula_id">Aula *</label>
                  <select class="form-control" id="aula_id" name="aula_id" required>
                    <option value="">Seleccionar aula...</option>
                  </select>
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
        $('#modalLabel').html(id > 0 ? 'Editar Sección' : 'Nueva Sección');
        $('#modal').modal('show');
        
        // Cargar aulas en el select
        cargarAulas();
        
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

      // Función para mostrar datos de una sección
      function mostrar(id) {
        $.post("../ajax/secciones.php?op=mostrar", {idseccion: id}, function (data, status) {
          data = JSON.parse(data);
          $('#idseccion').val(data.id_seccion);
          $('#nombre_seccion').val(data.nombre_seccion);
          $('#aula_id').val(data.aula_id);
        });
      }

      // Función para desactivar sección
      function desactivar(id) {
        bootbox.confirm("¿Está seguro de desactivar la sección?", function (result) {
          if (result) {
            $.post("../ajax/secciones.php?op=desactivar", {idseccion: id}, function (e) {
              tabla.ajax.reload();
            });
          }
        });
      }

      // Función para activar sección
      function activar(id) {
        bootbox.confirm("¿Está seguro de activar la sección?", function (result) {
          if (result) {
            $.post("../ajax/secciones.php?op=activar", {idseccion: id}, function (e) {
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
            url: "../ajax/secciones.php?op=listar",
            type: "post",
          },
          columns: [
            { data: "acciones" },
            { data: "nombre_seccion" },
            { data: "nombre_aula" },
            { data: "aula_id" }
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

        // Envío de formulario
        $("#formulario").on("submit", function (e) {
          e.preventDefault();
          var formData = new FormData(this);
          $.ajax({
            url: "../ajax/secciones.php?op=guardaryeditar",
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