<?php
require_once "../models/Consultas.php";
if (strlen(session_id()) < 1)
    session_start();

class ConsultasController {

    public function listaAsistencia() {
        $consulta = new Consultas();
        $user_id = $_SESSION["idusuario"];

        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $aula_id = $_REQUEST["idaula"];

        $range = 0;
        if ($fecha_inicio <= $fecha_fin) {
            $range = ((strtotime($fecha_fin) - strtotime($fecha_inicio)) + (24 * 60 * 60)) / (24 * 60 * 60);
            if ($range > 31) {
                echo "<p class='alert alert-warning'>El Rango Máximo es 31 Días.</p>";
                exit(0);
            }
        } else {
            echo "<p class='alert alert-danger'>Rango Inválido</p>";
            exit(0);
        }

        require_once "../models/Ninos.php";
        $ninos = new Ninos();
        $rsptav = $ninos->listarPorAula($aula_id);

        if (!empty($rsptav)) {
            ?>
            <table id="dataw" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Nombre del Niño</th>
                    <th>Sección</th>
                    <?php for ($i = 0; $i < $range; $i++) { ?>
                        <th>
                            <?php echo date("d-M", strtotime($fecha_inicio) + ($i * (24 * 60 * 60))); ?>
                        </th>
                    <?php } ?>
                </thead>
                <?php
                $rspta = $ninos->listarPorAula($aula_id);
                while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
                    ?>
                    <tr>
                        <td style="width:200px;"><?php echo $reg->nombre_completo; ?></td>
                        <td style="width:150px;"><?php echo $reg->nombre_seccion; ?></td>
                        <?php
                        for ($i = 0; $i < $range; $i++) {
                            $date_at = date("Y-m-d", strtotime($fecha_inicio) + ($i * (24 * 60 * 60)));
                            $asist = $consulta->listar_asistencia($reg->id_nino, $date_at, $date_at);
                            $regc = $asist->fetch(PDO::FETCH_OBJ);
                            ?>
                            <td style="text-align:center;">
                                <?php
                                if ($regc != null) {
                                    if ($regc->nombre_estado == 'Asistió') {
                                        echo "<strong style='color:green;'>A</strong>";
                                    } else if ($regc->nombre_estado == 'Inasistencia') {
                                        echo "<strong style='color:red;'>F</strong>";
                                    } else if ($regc->nombre_estado == 'Permiso') {
                                        echo "<strong style='color:orange;'>P</strong>";
                                    }
                                }
                                ?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                <?php } ?>
            </table>
            <?php
        } else {
            echo "<p class='alert alert-danger'>No hay niños en esta aula</p>";
        }
        ?>
        <script type="text/javascript">
            tabla = $('#dataw').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdf'
                ]
            });
        </script>
        <?php
    }

    public function listaComportamiento() {
        $consulta = new Consultas();
        $user_id = $_SESSION["idusuario"];

        $fecha_inicio = $_REQUEST["fecha_inicioc"];
        $fecha_fin = $_REQUEST["fecha_finc"];
        $aula_id = $_REQUEST["idaula"];

        $range = 0;
        if ($fecha_inicio <= $fecha_fin) {
            $range = ((strtotime($fecha_fin) - strtotime($fecha_inicio)) + (24 * 60 * 60)) / (24 * 60 * 60);
            if ($range > 31) {
                echo "<p class='alert alert-warning'>El Rango Máximo es 31 Días.</p>";
                exit(0);
            }
        } else {
            echo "<p class='alert alert-danger'>Rango Inválido</p>";
            exit(0);
        }

        require_once "../models/Ninos.php";
        $ninos = new Ninos();
        $rsptav = $ninos->listarPorAula($aula_id);

        if (!empty($rsptav)) {
            ?>
            <table id="dataco" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Nombre del Niño</th>
                    <th>Sección</th>
                    <?php for ($i = 0; $i < $range; $i++) { ?>
                        <th>
                            <?php echo date("d-M", strtotime($fecha_inicio) + ($i * (24 * 60 * 60))); ?>
                        </th>
                    <?php } ?>
                </thead>
                <?php
                $rspta = $ninos->listarPorAula($aula_id);
                while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
                    ?>
                    <tr>
                        <td style="width:200px;"><?php echo $reg->nombre_completo; ?></td>
                        <td style="width:150px;"><?php echo $reg->nombre_seccion; ?></td>
                        <?php
                        for ($i = 0; $i < $range; $i++) {
                            $date_at = date("Y-m-d", strtotime($fecha_inicio) + ($i * (24 * 60 * 60)));
                            $asist = $consulta->listar_comportamiento($reg->id_nino, $date_at, $date_at);
                            $regc = $asist->fetch(PDO::FETCH_OBJ);
                            ?>
                            <td style="text-align:center;">
                                <?php
                                if ($regc != null) {
                                    if ($regc->tipo == 'Conducta') {
                                        if ($regc->estado == 'Pendiente') {
                                            echo "<strong style='color:red;'>!</strong>";
                                        } else {
                                            echo "<strong style='color:green;'>✓</strong>";
                                        }
                                    }
                                }
                                ?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                <?php } ?>
            </table>
            <?php
        } else {
            echo "<p class='alert alert-danger'>No hay niños en esta aula</p>";
        }
        ?>
        <script type="text/javascript">
            tabla = $('#dataco').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdf'
                ]
            });
        </script>
        <?php
    }

    public function listarCalificacion() {
        $consulta = new Consultas();
        $user_id = $_SESSION["idusuario"];

        $fecha_inicio = $_REQUEST["fecha_inicio_eval"];
        $fecha_fin = $_REQUEST["fecha_fin_eval"];
        $aula_id = $_REQUEST["idaula"];

        require_once "../models/Ninos.php";
        $ninos = new Ninos();
        $rsptav = $ninos->listarPorAula($aula_id);

        if (!empty($rsptav)) {
            ?>
            <table id="dataca" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <tr>
                        <th>Nombre del Niño</th>
                        <th>Sección</th>
                        <th>Asistencias</th>
                        <th>Inasistencias</th>
                        <th>Permisos</th>
                        <th>Alertas</th>
                    </tr>
                </thead>
                <?php
                $rspta = $ninos->listarPorAula($aula_id);
                while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
                    ?>
                    <tr>
                        <td><?php echo $reg->nombre_completo; ?></td>
                        <td><?php echo $reg->nombre_seccion; ?></td>
                        <td style="text-align:center;">
                            <?php
                            $estadisticas = $consulta->estadisticas_asistencia($reg->id_nino, $fecha_inicio, $fecha_fin);
                            $asistencias = 0;
                            while ($est = $estadisticas->fetch(PDO::FETCH_OBJ)) {
                                if ($est->nombre_estado == 'Asistió') {
                                    $asistencias = $est->cantidad;
                                    echo $asistencias;
                                    break;
                                }
                            }
                            if ($asistencias == 0) {
                                echo "0";
                            }
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php
                            $estadisticas = $consulta->estadisticas_asistencia($reg->id_nino, $fecha_inicio, $fecha_fin);
                            $inasistencias = 0;
                            while ($est = $estadisticas->fetch(PDO::FETCH_OBJ)) {
                                if ($est->nombre_estado == 'Inasistencia') {
                                    $inasistencias = $est->cantidad;
                                    echo $inasistencias;
                                    break;
                                }
                            }
                            if ($inasistencias == 0) {
                                echo "0";
                            }
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php
                            $estadisticas = $consulta->estadisticas_asistencia($reg->id_nino, $fecha_inicio, $fecha_fin);
                            $permisos = 0;
                            while ($est = $estadisticas->fetch(PDO::FETCH_OBJ)) {
                                if ($est->nombre_estado == 'Permiso') {
                                    $permisos = $est->cantidad;
                                    echo $permisos;
                                    break;
                                }
                            }
                            if ($permisos == 0) {
                                echo "0";
                            }
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php
                            $alertas = $consulta->listar_comportamiento($reg->id_nino, $fecha_inicio, $fecha_fin);
                            $num_alertas = 0;
                            while ($alert = $alertas->fetch(PDO::FETCH_OBJ)) {
                                $num_alertas++;
                            }
                            if ($num_alertas > 0) {
                                echo "<strong style='color:red;'>" . $num_alertas . "</strong>";
                            } else {
                                echo "0";
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
        } else {
            echo "<p class='alert alert-danger'>No hay niños en esta aula</p>";
        }
        ?>
        <script type="text/javascript">
            tabla = $('#dataca').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdf'
                ]
            });
        </script>
        <?php
    }
}
?>