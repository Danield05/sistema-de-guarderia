<?php
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ((isset($_SESSION['aulas']) && $_SESSION['aulas'] == 1) || $_SESSION['cargo'] == 'Administrador') {
?>
    <!-- 🔧 Quitamos padding lateral con clases personalizadas -->
    <main class="container-fluid py-5 px-3 main-dashboard" style="padding-top: 3rem; padding-bottom: 3rem;">
      
      <!-- Título de la sección -->
      <div class="welcome-card">
        <div class="welcome-content">
          <h1 class="welcome-title">🏫 Gestión de Aulas</h1>
          <p class="welcome-subtitle">Administra las aulas de la guardería</p>
        </div>
      </div>

      <!-- Botón para abrir modal de registro -->
      <div class="mb-3">
        <button type="button" class="btn btn-primary" onclick="mostrarFormulario(0)">
          <i class="fa fa-plus"></i> Nuevo Aula
        </button>
      </div>

      <!-- Tabla de aulas -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Lista de Aulas</h3>
        </div>
        <div class="box-body">
          <table id="tabla" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Acciones</th>
                <th>Aula</th>
                <th>Descripción</th>
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
              <h4 class="modal-title" id="modalLabel">Aula</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="formulario">
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="idaula">ID</label>
                      <input type="text" class="form-control" id="idaula" name="idaula" readonly>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="nombre_aula">Nombre del Aula *</label>
                      <input type="text" class="form-control" id="nombre_aula" name="nombre_aula" required>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="descripcion">Descripción</label>
                  <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
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
        $('#modalLabel').html(id > 0 ? 'Editar Aula' : 'Nuevo Aula');
        $('#modal').modal('show');
        
        if (id > 0) {
          mostrar(id);
        }
      }

      // Función para mostrar datos de un aula
      function mostrar(id) {
        $.post("../ajax/aulas.php?op=mostrar", {idaula: id}, function (data, status) {
          data = JSON.parse(data);
          $('#idaula').val(data.id_aula);
          $('#nombre_aula').val(data.nombre_aula);
          $('#descripcion').val(data.descripcion);
        });
      }

      // Función para desactivar aula
      function desactivar(id) {
        bootbox.confirm("¿Está seguro de desactivar el aula?", function (result) {
          if (result) {
            $.post("../ajax/aulas.php?op=desactivar", {idaula: id}, function (e) {
              tabla.ajax.reload();
            });
          }
        });
      }

      // Función para activar aula
      function activar(id) {
        bootbox.confirm("¿Está seguro de activar el aula?", function (result) {
          if (result) {
            $.post("../ajax/aulas.php?op=activar", {idaula: id}, function (e) {
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
            url: "../ajax/aulas.php?op=listar",
            type: "post",
          },
          columns: [
            { data: "acciones" },
            { data: "nombre_aula" },
            { data: "descripcion" }
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
            url: "../ajax/aulas.php?op=guardaryeditar",
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