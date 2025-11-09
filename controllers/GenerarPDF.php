<?php
require_once '../vendor/tcpdf/tcpdf.php';

class GenerarPDF {

  // ==========================
  // FUNCIÓN PRINCIPAL
  // ==========================
  public function generarAutorizacionResponsable($datos) {
    $pdf = new TCPDF();
    $pdf->AddPage();

    // Configurar metadatos
    $pdf->SetCreator('Sistema de Guardería PequeControl');
    $pdf->SetAuthor('Sistema de Guardería');
    $pdf->SetTitle('Autorización de Retiro');
    $pdf->SetSubject('Autorización Responsable');

    // Encabezado
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'AUTORIZACIÓN DE RETIRO DE MENOR', 0, 1, 'C');
    $pdf->Ln(8);

    // Contenido general
    $pdf->SetFont('helvetica', '', 12);
    $contenido = "GUARDERÍA PEQUECONTROL\n\n";
    $contenido .= "AUTORIZACIÓN DE RESPONSABLE DE RETIRO\n\n";
    $contenido .= "DATOS DEL NIÑO Y RESPONSABLE:\n\n";
    $contenido .= "Niño: " . ($datos['nino_nombre'] ?? 'N/A') . "\n";
    $contenido .= "Nombre Completo: " . ($datos['responsable_nombre'] ?? 'N/A') . "\n";
    $contenido .= "Parentesco: " . ($datos['parentesco'] ?? 'N/A') . "\n";
    $contenido .= "Teléfono: " . ($datos['telefono'] ?? 'N/A') . "\n";
    $contenido .= "Inicio: " . ($datos['periodo_inicio'] ?? 'N/A') . "\n";
    $contenido .= "Fin: " . ($datos['periodo_fin'] ?? 'N/A') . "\n\n";
    $contenido .= "FIRMA DIGITAL DEL RESPONSABLE:\n\n";
    $pdf->MultiCell(0, 8, $contenido, 0, 'L');

    // ==========================
    // SECCIÓN DE FIRMA ELECTRÓNICA
    // ==========================
    if (!empty($datos['firma_data'])) {
      $firma_base64 = $datos['firma_data'];

      // Asegurarse que tiene el prefijo correcto
      if (strpos($firma_base64, 'data:image/png;base64,') === 0) {
        $firma_base64 = str_replace('data:image/png;base64,', '', $firma_base64);
      } elseif (strpos($firma_base64, 'data:image/jpeg;base64,') === 0) {
        $firma_base64 = str_replace('data:image/jpeg;base64,', '', $firma_base64);
      }

      $firma_bin = base64_decode($firma_base64);

      if ($firma_bin === false) {
        error_log("ERROR: No se pudo decodificar la firma base64.");
      } else {
        // Crear archivo temporal con extensión .png
        $temp_firma = tempnam(sys_get_temp_dir(), 'firma_') . '.png';
        file_put_contents($temp_firma, $firma_bin);

        // Insertar imagen en el PDF
        if (file_exists($temp_firma)) {
          $pdf->Image($temp_firma, 20, $pdf->GetY(), 60, 30, 'PNG');
          $pdf->Ln(20);
          unlink($temp_firma); // eliminar archivo temporal
        } else {
          error_log("ERROR: No se encontró la imagen temporal de firma.");
        }
      }
    } else {
      $pdf->Ln(10);
      $pdf->Cell(0, 10, 'Sin firma registrada.', 0, 1, 'L');
    }

    // Pie de página
    $pdf->SetFont('helvetica', 'I', 10);
    $pdf->Ln(10);
    $pdf->MultiCell(0, 8, 'Documento generado por el Sistema de Guardería PequeControl. Fecha de emisión: ' . date('d/m/Y'), 0, 'C');

    // ==========================
    // GUARDAR EL ARCHIVO
    // ==========================
    $directorio = '../files/firmas/';
    if (!file_exists($directorio)) {
      mkdir($directorio, 0777, true);
    }

    $nombreArchivo = 'autorizacion_' . date('Ymd_His') . '.pdf';
    $rutaArchivo = $directorio . $nombreArchivo;

    // Guardar PDF en el servidor
    $pdf->Output($rutaArchivo, 'F');

    // Retornar la ruta o el nombre del archivo
    return $nombreArchivo;
  }
}