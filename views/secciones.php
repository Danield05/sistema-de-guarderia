<?php
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.php");
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
      <div class="mb-4">
        <button type="button" class="btn btn-primary" style="border-radius: 25px; padding: 0.75rem 2rem; font-weight: 600; box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);" onclick="mostrarFormulario(0)">
          <i class="fa fa-plus-circle"></i> Agregar Nueva Sección
        </button>
      </div>

      <!-- Tabla de secciones -->
      <div class="activity-feed">
        <h3 class="activity-title">🏫 Lista de Secciones</h3>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white;">
              <tr>
                <th style="border: none; padding: 1rem;"><i class="fa fa-cogs"></i> Acciones</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-graduation-cap"></i> Sección</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-university"></i> Aula</th>
              </tr>
            </thead>
            <tbody id="tbllistado" style="background: rgba(255, 255, 255, 0.9);">
              <?php
              require_once "../models/Secciones.php";
              $secciones = new Secciones();
              $rspta = $secciones->listar();
              while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
                echo '<tr style="border-bottom: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease;">';
                echo '<td style="padding: 1rem;">';
                echo '<button class="btn btn-outline-warning btn-sm" style="margin-right: 0.5rem; border-radius: 20px;" onclick="mostrarFormulario(' . $reg->id_seccion . ')"><i class="fa fa-pencil"></i> Editar</button>';
                echo '<button class="btn btn-outline-danger btn-sm" style="border-radius: 20px;" onclick="eliminar_seccion(' . $reg->id_seccion . ')"><i class="fa fa-trash"></i> Eliminar</button>';
                echo '</td>';
                echo '<td style="padding: 1rem; font-weight: 600; color: #3c8dbc;">' . $reg->nombre_seccion . '</td>';
                echo '<td style="padding: 1rem; color: #666;">' . $reg->nombre_aula . '</td>';
                echo '</tr>';
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal para registro y edición -->
      <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; border-radius: 20px 20px 0 0; border-bottom: none; padding: 2rem;">
              <h4 class="modal-title" id="modalLabel" style="font-weight: 600; font-size: 1.5rem;">
                <i class="fa fa-graduation-cap"></i> Agregar Nueva Sección
              </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
                <span aria-hidden="true" style="font-size: 2rem;">&times;</span>
              </button>
            </div>
            <form id="formulario">
              <div class="modal-body" style="padding: 2.5rem;">
                <div class="form-group">
                  <label for="nombre_seccion" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                    <i class="fa fa-graduation-cap"></i> Nombre de la Sección *
                  </label>
                  <input type="text" class="form-control" id="nombre_seccion" name="nombre_seccion" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Ej: Prekínder A">
                  <input type="hidden" id="idseccion" name="idseccion">
                </div>
                <div class="form-group">
                  <label for="aula_id" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                    <i class="fa fa-university"></i> Aula *
                  </label>
                  <select class="form-control" id="aula_id" name="aula_id" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; min-height: 45px;">
                    <option value="">Cargando aulas...</option>
                  </select>
                </div>
              </div>
              <div class="modal-footer" style="border-top: none; padding: 2rem; background: #f8f9fa; border-radius: 0 0 20px 20px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: #6c757d;">
                  <i class="fa fa-times"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-primary" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); box-shadow: 0 4px 15px rgba(44, 62, 80, 0.4);">
                  <i class="fa fa-save"></i> Guardar Sección
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </main>


<?php
   } else {
     require 'noacceso.php';
   }
   require 'footer.php';
 }
 ?>

 <script>
    // Función para mostrar/ocultar formulario
    function mostrarFormulario(id) {
      $('#formulario')[0].reset();
      $('#modalLabel').html(id > 0 ? '<i class="fa fa-edit"></i> Editar Sección' : '<i class="fa fa-graduation-cap"></i> Agregar Nueva Sección');

      // Mover modal al body para evitar problemas de stacking context
      $('#modal').appendTo('body');

      $('#modal').modal({
        backdrop: 'static',
        keyboard: true
      });

      // Cargar aulas en el select
      cargarAulas();

      if (id > 0) {
        mostrar(id);
      }
    }

    // Función para mostrar datos de una sección
    function mostrar(id) {
      $.post("../ajax/secciones.php?op=mostrar", {idseccion: id}, function (data, status) {
        try {
          data = JSON.parse(data);
          $('#idseccion').val(data.id_seccion);
          $('#nombre_seccion').val(data.nombre_seccion);
          $('#aula_id').val(data.aula_id);
        } catch (e) {
          console.error("Error parsing JSON:", e, data);
          bootbox.alert("Error al procesar los datos de la sección.");
        }
      }).fail(function(xhr, status, error) {
        console.error("Error mostrando sección:", error, xhr.responseText);
        bootbox.alert("Error al cargar los datos de la sección.");
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
          bootbox.alert("Error al cargar las aulas. Por favor, recarga la página.");
        }
      });
    }


    // Función para eliminar sección
    function eliminar_seccion(id) {
      bootbox.confirm("¿Está seguro de eliminar la sección?", function (result) {
        if (result) {
          $.post("../ajax/secciones.php?op=desactivar", {idseccion: id}, function (e) {
            bootbox.alert(e);
            location.reload();
          }).fail(function(xhr, status, error) {
            bootbox.alert("Error al eliminar: " + xhr.responseText);
          });
        }
      });
    }

    // Función para activar sección
    function activar(id) {
      bootbox.confirm("¿Está seguro de activar la sección?", function (result) {
        if (result) {
          $.post("../ajax/secciones.php?op=activar", {idseccion: id}, function (e) {
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
          url: "../ajax/secciones.php?op=guardaryeditar",
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
            bootbox.alert("Error al guardar: " + xhr.responseText);
          }
        });
      });
    });
  </script>