<?php

// iki nggo Talkshow

require '../vendor/autoload.php';
require '../tools/csvstream.php';
include '../tools/debug.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Variables
$DATABASE_LOCATION = $_SERVER["DOCUMENT_ROOT"]."/datanggocahKSK/pendaftar.xls";
$_POST["nomorHP"] = '"'.$_POST["nomorHP"].'"'; // Stringify phone numbers

//$DATABASE_FILE = new XLSXWriter();
$DATABASE_FILE = new Spreadsheet();     // functions as spreadsheet
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xls");
$reader -> setReadDataOnly(TRUE);

if (!file_exists($DATABASE_LOCATION)) createCSVWithHeader('Talkshow');
$DATABASE_FILE = $reader->load($DATABASE_LOCATION);
$worksheet = $DATABASE_FILE -> getSheetByName('Talkshow');

$highestRow = $worksheet->getHighestRow();
$highestColumn = $worksheet->getHighestColumn();

$worksheet->fromArray($_POST, NULL, 'A'.++$highestRow);

$writer = new Xls($DATABASE_FILE);
$writer->save($DATABASE_LOCATION);

echo("Data succesfully recorded. Now redirecting to success page!");
header("Status: 301 Moved Permanently");
header("Location:success.php?subevent=talkshow");