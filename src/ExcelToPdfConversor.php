<?php

namespace src;

require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf as PdfMpdf;

class ExcelToPdfConversor
{
    private $writer;

    public function excelToPdf($excelRoute,$outputRoutePdf)
    {
        if (!file_exists($excelRoute)) {
            die("Could not find");
        }

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($excelRoute);
        $spreadsheet = $reader->load($excelRoute);

        $this->writer = new PdfMpdf($spreadsheet);

        $this->writer->save($outputRoutePdf);
    }

}
