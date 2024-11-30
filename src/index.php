<?php

namespace src;

require_once '../vendor/autoload.php';
require './ExcelToPdfConversor.php';

$converter = new ExcelToPdfConversor();
$excelFile = 'example.xlsx';
$pdfFileObjetive = 'converted.pdf';
$converter->excelToPdf($excelFile, $pdfFileObjetive);