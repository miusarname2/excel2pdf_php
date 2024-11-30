<?php

namespace src;

use Exception;

require_once '../vendor/autoload.php';
require './ExcelToPdfConversor.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] == UPLOAD_ERR_OK) {
        $excelFile = $_FILES['excelFile']['tmp_name']; // El archivo cargado
        $excelFileName = $_FILES['excelFile']['name']; // El nombre original del archivo
        $fileExtension = pathinfo($excelFileName, PATHINFO_EXTENSION);

        // Verificar que el archivo tenga la extensión .xlsx
        if ($fileExtension !== 'xlsx') {
            print_r(json_encode(['success' => false, 'message' => 'Por favor, sube un archivo Excel válido (.xlsx).']));
            exit;
        }

        $outputPdf = 'converted_' . time() . '.pdf'; // Nombre de salida del archivo PDF

        $converter = new ExcelToPdfConversor();
        
        try {
            // Llamar al método que convierte el archivo Excel a PDF
            $converter->excelToPdf($excelFile, $outputPdf);

            // Si la conversión fue exitosa, enviar la URL del archivo PDF
            print_r(json_encode(['success' => true, 'fileUrl' => $outputPdf]));
        } catch (Exception $e) {
            // Si hubo un error en la conversión, devolver el error
            print_r(json_encode(['success' => false, 'message' => 'Error en la conversión: ' . $e->getMessage()]));
        }
    } else {
        print_r(json_encode(['success' => false, 'message' => 'No se ha subido ningún archivo o ocurrió un error al subirlo.']));
    }
}
?>
