<?php
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.php");
} else {
  require 'header.php';

  if ((isset($_SESSION['aulas']) && $_SESSION['aulas'] == 1) || $_SESSION['cargo'] == 'Administrador' || $_SESSION['cargo'] == 'Maestro') {
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


      <!-- Tabla de aulas -->
      <div class="activity-feed">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="activity-title">🏫 Lista de Aulas</h3>
          <!-- Botón para abrir modal de registro (solo para administradores) -->
          <?php if ($_SESSION['cargo'] == 'Administrador'): ?>
          <button type="button" class="btn btn-primary" style="border-radius: 25px; padding: 0.75rem 2rem; font-weight: 600; box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);" onclick="mostrarFormulario(0)">
            <i class="fa fa-plus-circle"></i> Agregar Nueva Aula
          </button>
          <?php endif; ?>
        </div>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white;">
              <tr>
                <th style="border: none; padding: 1rem;"><i class="fa fa-cogs"></i> Acciones</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-university"></i> Aula</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-info-circle"></i> Descripción</th>
                <?php if ($_SESSION['cargo'] == 'Maestro'): ?>
                <th style="border: none; padding: 1rem;"><i class="fa fa-sitemap"></i> Secciones</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-child"></i> Niños</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody id="tbllistado" style="background: rgba(255, 255, 255, 0.9);">
                <?php
                require_once "../models/Aulas.php";
                require_once "../models/Consultas.php";
                $aulas = new Aulas();
                $consultas = new Consultas();

                // Para maestros, mostrar solo aulas donde tienen niños asignados
                if ($_SESSION['cargo'] == 'Maestro') {
                  $rspta = $aulas->listarParaMaestro($_SESSION['idusuario']);
                } else {
                  $rspta = $aulas->listar();
                }

                while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
                  echo '<tr style="border-bottom: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease;">';
                  echo '<td style="padding: 1rem;">';

                  // Para maestros, solo mostrar botón de ver
                  if ($_SESSION['cargo'] == 'Maestro') {
                    echo '<button class="btn btn-outline-info btn-sm" style="border-radius: 20px;" onclick="verAula(' . $reg->id_aula . ')"><i class="fa fa-eye"></i> Ver</button>';
                  } else {
                    echo '<button class="btn btn-outline-warning btn-sm" style="margin-right: 0.5rem; border-radius: 20px;" onclick="mostrarFormulario(' . $reg->id_aula . ')"><i class="fa fa-pencil"></i> Editar</button>';
                    echo '<button class="btn btn-outline-danger btn-sm" style="border-radius: 20px;" onclick="eliminar_aula(' . $reg->id_aula . ')"><i class="fa fa-trash"></i> Eliminar</button>';
                  }

                  echo '</td>';
                  echo '<td style="padding: 1rem; font-weight: 600; color: #3c8dbc;">' . $reg->nombre_aula . '</td>';
                  echo '<td style="padding: 1rem; color: #666;">' . $reg->descripcion . '</td>';

                  // Para maestros, mostrar información adicional
                  if ($_SESSION['cargo'] == 'Maestro') {
                    // Obtener secciones del aula para este maestro
                    $secciones_rspta = $consultas->obtenerSeccionesPorAulaYMaestro($reg->id_aula, $_SESSION['idusuario']);
                    $secciones = array();
                    while ($sec = $secciones_rspta->fetch(PDO::FETCH_OBJ)) {
                      $secciones[] = $sec->nombre_seccion;
                    }

                    // Contar niños en el aula para este maestro
                    $ninos_rspta = $consultas->contarNinosPorAulaYMaestro($reg->id_aula, $_SESSION['idusuario']);
                    $total_ninos = isset($ninos_rspta['total_ninos']) ? $ninos_rspta['total_ninos'] : 0;

                    echo '<td style="padding: 1rem; color: #666;">' . implode(', ', $secciones) . '</td>';
                    echo '<td style="padding: 1rem; font-weight: 600; color: #e74c3c;">' . $total_ninos . ' niños</td>';
                  }

                  echo '</tr>';
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Modal para registro y edición -->
      <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; border-radius: 20px 20px 0 0; border-bottom: none; padding: 2rem;">
              <h4 class="modal-title" id="modalLabel" style="font-weight: 600; font-size: 1.5rem;">
                <i class="fa fa-university"></i> Agregar Nueva Aula
              </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
                <span aria-hidden="true" style="font-size: 2rem;">&times;</span>
              </button>
            </div>
            <form id="formulario">
              <div class="modal-body" style="padding: 2.5rem;">
                <div class="form-group">
                  <label for="nombre_aula" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                    <i class="fa fa-graduation-cap"></i> Nombre del Aula *
                  </label>
                  <input type="text" class="form-control" id="nombre_aula" name="nombre_aula" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Ej: Prekínder A">
                  <input type="hidden" id="idaula" name="idaula">
                </div>
                <div class="form-group">
                  <label for="descripcion" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                    <i class="fa fa-info-circle"></i> Descripción
                  </label>
                  <textarea class="form-control" id="descripcion" name="descripcion" rows="4" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; resize: vertical;" placeholder="Describe brevemente el aula..."></textarea>
                </div>
              </div>
              <div class="modal-footer" style="border-top: none; padding: 2rem; background: #f8f9fa; border-radius: 0 0 20px 20px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: #6c757d;">
                  <i class="fa fa-times"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-primary" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); box-shadow: 0 4px 15px rgba(44, 62, 80, 0.4);">
                  <i class="fa fa-save"></i> Guardar Aula
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
      $('#modalLabel').html(id > 0 ? '<i class="fa fa-edit"></i> Editar Aula' : '<i class="fa fa-university"></i> Agregar Nueva Aula');
      $('#modal').modal('show');

      // Habilitar campos para edición
      $('#nombre_aula').prop('disabled', false);
      $('#descripcion').prop('disabled', false);
      $('button[type="submit"]').show();

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
      }).fail(function(xhr, status, error) {
        bootbox.alert("Error al cargar los datos del aula: " + xhr.responseText);
      });
    }

    // Función para ver aula (solo lectura para maestros)
    function verAula(id) {
      $.post("../ajax/aulas.php?op=mostrar", {idaula: id}, function (data, status) {
        data = JSON.parse(data);
        $('#modalLabel').html('<i class="fa fa-eye"></i> Ver Aula');
        $('#modal').modal('show');

        $('#idaula').val(data.id_aula);
        $('#nombre_aula').val(data.nombre_aula);
        $('#descripcion').val(data.descripcion);

        // Deshabilitar campos para solo lectura
        $('#nombre_aula').prop('disabled', true);
        $('#descripcion').prop('disabled', true);

        // Ocultar botón de guardar
        $('button[type="submit"]').hide();

        // Para maestros, mostrar información adicional de secciones
        <?php if ($_SESSION['cargo'] == 'Maestro'): ?>
        // Agregar información de secciones después del formulario
        setTimeout(function() {
          if (!$('#secciones-info').length) {
            $('#formulario').after('<div id="secciones-info" class="mt-4"></div>');
          }

          // Cargar información detallada de secciones
          $.post("../ajax/aulas.php?op=detalle_secciones_maestro", {idaula: id, maestro_id: '<?php echo $_SESSION['idusuario']; ?>'}, function (detalle, status) {
            $('#secciones-info').html(detalle);
          }).fail(function(xhr, status, error) {
            console.log("Error al cargar detalle de secciones: " + xhr.responseText);
          });
        }, 100);
        <?php endif; ?>

      }).fail(function(xhr, status, error) {
        bootbox.alert("Error al cargar los datos del aula: " + xhr.responseText);
      });
    }

    // Función para eliminar aula
    function eliminar_aula(id) {
      bootbox.confirm("¿Está seguro de eliminar el aula?", function (result) {
        if (result) {
          $.post("../ajax/aulas.php?op=desactivar", {idaula: id}, function (e) {
            bootbox.alert(e);
            location.reload();
          }).fail(function(xhr, status, error) {
            bootbox.alert("Error al eliminar: " + xhr.responseText);
          });
        }
      });
    }

    // Función para activar aula
    function activar(id) {
      bootbox.confirm("¿Está seguro de activar el aula?", function (result) {
        if (result) {
          $.post("../ajax/aulas.php?op=activar", {idaula: id}, function (e) {
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
          url: "../ajax/aulas.php?op=guardaryeditar",
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