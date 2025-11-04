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
        $team_id = $_REQUEST["idgrupo"];

        $range = 0;
        if ($fecha_inicio <= $fecha_fin) {
            $range = ((strtotime($fecha_fin) - strtotime($fecha_inicio)) + (24 * 60 * 60)) / (24 * 60 * 60);
            if ($range > 31) {
                echo "<p class='alert alert-warning'>El Rango Maximo es 31 Dias.</p>";
                exit(0);
            }
        } else {
            echo "<p class='alert alert-danger'>Rango Invalido</p>";
            exit(0);
        }

        require_once "../models/Alumnos.php";
        $alumnos = new Alumnos();
        $rsptav = $alumnos->verficar_alumno($user_id, $team_id);

        if (!empty($rsptav)) {
            ?>
            <table id="dataw" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Nombre</th>
                    <?php for ($i = 0; $i < $range; $i++) { ?>
                        <th>
                            <?php echo date("d-M", strtotime($fecha_inicio) + ($i * (24 * 60 * 60))); ?>
                        </th>
                    <?php } ?>
                </thead>
                <?php
                $rspta = $alumnos->listar_calif($user_id, $team_id);
                while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
                    ?>
                    <tr>
                        <td style="width:250px;"><?php echo $reg->name . " " . $reg->lastname; ?></td>
                        <?php
                        for ($i = 0; $i < $range; $i++) {
                            $date_at = date("Y-m-d", strtotime($fecha_inicio) + ($i * (24 * 60 * 60)));
                            $asist = $consulta->listar_asistencia($reg->idalumn, $team_id, $date_at);
                            $regc = $asist->fetch(PDO::FETCH_OBJ)
                            ?>
                            <td >
                                <?php
                                if ($regc != null) {
                                    if ($regc->kind_id == 1) {
                                        echo "<strong>A</stron>";
                                    } else if ($regc->kind_id == 2) {
                                        echo "<strong>T</stron>";
                                    } else if ($regc->kind_id == 3) {
                                        echo "<strong>F</stron>";
                                    } else if ($regc->kind_id == 4) {
                                        echo "<strong>P</stron>";
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
            echo "<p class='alert alert-danger'>No hay Alumnos</p>";
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
        $team_id = $_REQUEST["idgrupo"];

        $range = 0;
        if ($fecha_inicio <= $fecha_fin) {
            $range = ((strtotime($fecha_fin) - strtotime($fecha_inicio)) + (24 * 60 * 60)) / (24 * 60 * 60);
            if ($range > 31) {
                echo "<p class='alert alert-warning'>El Rango Maximo es 31 Dias.</p>";
                exit(0);
            }
        } else {
            echo "<p class='alert alert-danger'>Rango Invalido</p>";
            exit(0);
        }

        require_once "../models/Alumnos.php";
        $alumnos = new Alumnos();
        $rsptav = $alumnos->verficar_alumno($user_id, $team_id);

        if (!empty($rsptav)) {
            ?>
            <table id="dataco" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Nombre</th>
                    <?php for ($i = 0; $i < $range; $i++) { ?>
                        <th>
                            <?php echo date("d-M", strtotime($fecha_inicio) + ($i * (24 * 60 * 60))); ?>
                        </th>
                    <?php } ?>
                </thead>
                <?php
                $rspta = $alumnos->listar_calif($user_id, $team_id);
                while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
                    ?>
                    <tr>
                        <td style="width:250px;"><?php echo $reg->name . " " . $reg->lastname; ?></td>
                        <?php
                        for ($i = 0; $i < $range; $i++) {
                            $date_at = date("Y-m-d", strtotime($fecha_inicio) + ($i * (24 * 60 * 60)));
                            $asist = $consulta->listar_comportamiento($reg->idalumn, $team_id, $date_at);
                            $regc = $asist->fetch(PDO::FETCH_OBJ)
                            ?>
                            <td >
                                <?php
                                if ($regc != null) {
                                    if ($regc->kind_id == 1) {
                                        echo "<strong>N</stron>";
                                    } else if ($regc->kind_id == 2) {
                                        echo "<strong>B</stron>";
                                    } else if ($regc->kind_id == 3) {
                                        echo "<strong>E</stron>";
                                    } else if ($regc->kind_id == 4) {
                                        echo "<strong>M</stron>";
                                    } else if ($regc->kind_id == 5) {
                                        echo "<strong>MM</stron>";
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
            echo "<p class='alert alert-danger'>No hay Alumnos</p>";
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

        require_once "../models/Alumnos.php";
        $alumnos = new Alumnos();
        $team_id = $_REQUEST["idgrupo"];
        $rsptav = $alumnos->verficar_alumno($user_id, $team_id);
        require_once "../models/Cursos.php";
        $cursos = new Cursos();
        $rsptac = $cursos->listar($team_id);

        if (!empty($rsptav)) {
            ?>
            <table id="dataca" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Nombre</th>
                    <?php
                    while ($reg = $rsptac->fetch(PDO::FETCH_OBJ)) {
                        echo '<th>' . $reg->name . '</th>';
                    }
                    ?>
                </thead>
                <?php
                $rspta = $alumnos->listar_calif($user_id, $team_id);
                while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
                    ?>
                    <tr>
                        <td><?php echo $reg->name . " " . $reg->lastname; ?></td>
                        <?php
                        require_once "../models/Cursos.php";
                        $cursos = new Cursos();
                        $rsptacurso = $cursos->listar($team_id);
                        while ($regc = $rsptacurso->fetch(PDO::FETCH_OBJ)) {
                            $idcurso = $regc->id;
                            $idalumno = $reg->idalumn;

                            require_once "../models/Calificaciones.php";
                            $calificaciones = new Calificaciones();
                            $rsptacalif = $calificaciones->listar_calificacion($idalumno, $idcurso);
                            $regn = $rsptacalif->fetch(PDO::FETCH_OBJ);
                            ?>
                            <td><?php if ($regn != null) {
                                echo $regn->val;
                            } ?></td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
        } else {
            echo "<p class='alert alert-danger'>No hay Alumnos</p>";
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