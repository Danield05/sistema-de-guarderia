<?php
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.php");
} else {
  require 'header.php';

  if ((isset($_SESSION['ninos']) && $_SESSION['ninos'] == 1) || (isset($_SESSION['rol']) && $_SESSION['rol'] == 'Administrador') || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería') || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Maestro') || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Padre/Tutor')) {
?>
    <!-- 🔧 Quitamos padding lateral con clases personalizadas -->
    <main class="container-fluid py-5 px-3 main-dashboard" style="padding-top: 3rem; padding-bottom: 3rem;">
      
      <!-- Título de la sección -->
      <div class="welcome-card">
        <div class="welcome-content">
          <h1 class="welcome-title">
            <?php
            if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería') {
              echo '🏥 Información de Niños';
            } else {
              echo '👶 Gestión de Niños';
            }
            ?>
          </h1>
          <p class="welcome-subtitle">
            <?php
            if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería') {
              echo 'Consulta la información médica de los niños';
            } else {
              echo 'Administra la información de los niños en la guardería';
            }
            ?>
          </p>
        </div>
      </div>

      <!-- Botón para abrir modal de registro (solo para administradores) -->
      <?php if ((!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 'Médico/Enfermería') && (!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 'Padre/Tutor')): ?>
      <div class="mb-4">
        <button type="button" class="btn btn-primary" style="border-radius: 25px; padding: 0.75rem 2rem; font-weight: 600; box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);" onclick="mostrarFormulario(0)">
          <i class="fa fa-plus-circle"></i> Agregar Nuevo Niño
        </button>
      </div>
      <?php endif; ?>

      <!-- Filtros de búsqueda (solo para administradores y maestros) -->
      <?php if (!isset($_SESSION['cargo']) || ($_SESSION['cargo'] != 'Médico/Enfermería' && $_SESSION['cargo'] != 'Padre/Tutor')): ?>
      <div class="mb-4">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="filtro-nombre" style="font-weight: 600; color: #3c8dbc;">
                <i class="fa fa-search"></i> Buscar por Nombre
              </label>
              <input type="text" class="form-control" id="filtro-nombre" placeholder="Escriba el nombre del niño..." style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="filtro-aula" style="font-weight: 600; color: #3c8dbc;">
                <i class="fa fa-university"></i> Filtrar por Aula
              </label>
              <select class="form-control" id="filtro-aula" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; min-height: 45px;">
                <option value="">Todas las aulas</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="filtro-seccion" style="font-weight: 600; color: #3c8dbc;">
                <i class="fa fa-sitemap"></i> Filtrar por Sección
              </label>
              <select class="form-control" id="filtro-seccion" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; min-height: 45px;">
                <option value="">Todas las secciones</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <button type="button" class="btn btn-primary" onclick="aplicarFiltros()" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);">
              <i class="fa fa-filter"></i> Aplicar Filtros
            </button>
            <button type="button" class="btn btn-secondary ml-2" onclick="limpiarFiltros()" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600;">
              <i class="fa fa-times"></i> Limpiar Filtros
            </button>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <!-- Tabla de niños -->
      <div class="activity-feed">
        <h3 class="activity-title">👶 Lista de Niños</h3>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white;">
              <tr>
                <th style="border: none; padding: 1rem;"><i class="fa fa-cogs"></i> Acciones</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-user"></i> Nombre Completo</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-birthday-cake"></i> Edad</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-calendar"></i> Fecha Nacimiento</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-weight"></i> Peso (kg)</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-university"></i> Aula</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-sitemap"></i> Sección</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-graduation-cap"></i> Maestro</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-user-friends"></i> Tutor</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-info-circle"></i> Estado</th>
              </tr>
            </thead>
            <tbody style="background: rgba(255, 255, 255, 0.9);">
              <?php
              require_once "../models/Ninos.php";
              $ninos = new Ninos();

              // Si es maestro, mostrar solo sus niños asignados
              if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Maestro') {
                  $rspta = $ninos->listarParaMaestro($_SESSION['idusuario']);
              }
              // Si es padre/tutor, mostrar solo su niño
              elseif (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Padre/Tutor') {
                  $rspta = $ninos->listarParaPadre($_SESSION['idusuario']);
              }
              else {
                  $rspta = $ninos->listar();
              }

              while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
                echo '<tr style="border-bottom: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease;">';
                echo '<td style="padding: 1rem;">';
                if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería') {
                  // Para médicos: solo botón de ver detalles médicos
                  echo '<button class="btn btn-info btn-sm" style="border-radius: 20px;" onclick="verDetalles(' . $reg->id_nino . ')"><i class="fa fa-user-md"></i> Ver Detalles Médicos</button>';
                } elseif (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Maestro') {
                  // Para maestros: solo botón de ver información básica
                  echo '<button class="btn btn-info btn-sm" style="border-radius: 20px;" onclick="verDetalles(' . $reg->id_nino . ')"><i class="fa fa-eye"></i> Ver Información</button>';
                } elseif (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Padre/Tutor') {
                  // Para padres/tutores: solo botón de ver información de su niño
                  echo '<button class="btn btn-info btn-sm" style="border-radius: 20px;" onclick="verDetalles(' . $reg->id_nino . ')"><i class="fa fa-eye"></i> Ver Mi Niño</button>';
                } else {
                  // Para administradores: botones completos
                  echo '<button class="btn btn-warning btn-sm" style="margin-right: 0.5rem; border-radius: 20px;" onclick="mostrar(' . $reg->id_nino . ')"><i class="fa fa-pencil"></i> Editar</button>';
                  if ($reg->estado == 1) {
                    echo '<button class="btn btn-danger btn-sm" style="border-radius: 20px;" onclick="desactivar(' . $reg->id_nino . ')"><i class="fa fa-ban"></i> Desactivar</button>';
                  } else {
                    echo '<button class="btn btn-success btn-sm" style="border-radius: 20px;" onclick="activar(' . $reg->id_nino . ')"><i class="fa fa-check"></i> Activar</button>';
                  }
                }
                echo '</td>';
                echo '<td style="padding: 1rem; font-weight: 600; color: #3c8dbc;">' . $reg->nombre_completo . '</td>';
                echo '<td style="padding: 1rem;">' . $reg->edad . ' años</td>';
                echo '<td style="padding: 1rem;">' . date('d/m/Y', strtotime($reg->fecha_nacimiento)) . '</td>';
                echo '<td style="padding: 1rem;">' . ($reg->peso ? $reg->peso . ' kg' : 'N/A') . '</td>';
                echo '<td style="padding: 1rem; color: #666;">' . $reg->nombre_aula . '</td>';
                echo '<td style="padding: 1rem; color: #666;">' . $reg->nombre_seccion . '</td>';
                echo '<td style="padding: 1rem; color: #666;">' . $reg->maestro . '</td>';
                echo '<td style="padding: 1rem; color: #666;">' . $reg->tutor . '</td>';
                echo '<td style="padding: 1rem;">';
                if ($reg->estado == 1) {
                  echo '<span class="badge badge-success" style="background: linear-gradient(45deg, #27ae60 0%, #2ecc71 100%); border: none; padding: 0.5rem 1rem; border-radius: 20px;"><i class="fa fa-check-circle"></i> Activo</span>';
                } else {
                  echo '<span class="badge badge-danger" style="background: linear-gradient(45deg, #e74c3c 0%, #c0392b 100%); border: none; padding: 0.5rem 1rem; border-radius: 20px;"><i class="fa fa-times-circle"></i> Inactivo</span>';
                }
                echo '</td>';
                echo '</tr>';
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal para registro y edición (solo para administradores) -->
      <?php if ((!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 'Médico/Enfermería') && (!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 'Padre/Tutor')): ?>
      <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; border-radius: 20px 20px 0 0; border-bottom: none; padding: 2rem;">
              <h4 class="modal-title" id="modalLabel" style="font-weight: 600; font-size: 1.5rem;">
                <i class="fa fa-child"></i> Agregar Nuevo Niño
              </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
                <span aria-hidden="true" style="font-size: 2rem;">&times;</span>
              </button>
            </div>
            <form id="formulario">
              <div class="modal-body" style="padding: 2.5rem;">
                <div class="row">
                  <div class="col-md-6" id="idField" style="display: none;">
                    <div class="form-group">
                      <label for="idnino" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                        <i class="fa fa-id-card"></i> ID
                      </label>
                      <input type="text" class="form-control" id="idnino" name="idnino" readonly style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: #f8f9fa;">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="nombre_completo" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                        <i class="fa fa-user"></i> Nombre Completo *
                      </label>
                      <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Ej: Juan Pérez García">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="fecha_nacimiento" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                        <i class="fa fa-calendar"></i> Fecha de Nacimiento *
                      </label>
                      <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="edad" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                        <i class="fa fa-birthday-cake"></i> Edad (calculada automáticamente)
                      </label>
                      <input type="number" class="form-control" id="edad" name="edad" required min="0" max="18" readonly style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: #f8f9fa;" placeholder="Se calcula automáticamente">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="peso" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                        <i class="fa fa-weight"></i> Peso (kg)
                      </label>
                      <input type="number" class="form-control" id="peso" name="peso" min="0" step="0.1" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Ej: 18.5">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="aula_id" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                        <i class="fa fa-university"></i> Aula *
                      </label>
                      <select class="form-control" id="aula_id" name="aula_id" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; min-height: 45px;">
                        <option value="">Seleccionar aula...</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="seccion_id" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                        <i class="fa fa-sitemap"></i> Sección *
                      </label>
                      <select class="form-control" id="seccion_id" name="seccion_id" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; min-height: 45px;">
                        <option value="">Seleccionar sección...</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="maestro_id" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                        <i class="fa fa-graduation-cap"></i> Maestro Asignado
                      </label>
                      <select class="form-control" id="maestro_id" name="maestro_id" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; min-height: 45px;">
                        <option value="">Seleccionar maestro...</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="tutor_id" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                        <i class="fa fa-user-friends"></i> Tutor
                      </label>
                      <select class="form-control" id="tutor_id" name="tutor_id" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; min-height: 45px;">
                        <option value="">Seleccionar tutor...</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer" style="border-top: none; padding: 2rem; background: #f8f9fa; border-radius: 0 0 20px 20px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: #6c757d;">
                  <i class="fa fa-times"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-primary" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); box-shadow: 0 4px 15px rgba(44, 62, 80, 0.4);">
                  <i class="fa fa-save"></i> Guardar Niño
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </main>

<?php
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';
}
?>

<!-- jQuery debe cargarse antes de ninos.js -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../public/js/bootbox.min.js"></script>
<script src="scripts/ninos.js"></script>