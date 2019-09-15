<?php

$isSuccess = true;
$reason = "";
// iki nggo Esai

$HOMEPAGE = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/';
$DATABASE_LOCATION = "../datanggocahKSK/pendaftar.xls";
$ALLOWED_IMAGE_EXTENSIONS = array("jpg", "jpeg", "png");

require '../vendor/autoload.php';
require '../tools/tools.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

// Stringify phone numbers first-----------------
$_POST["nomorHP"] = '"'.$_POST["nomorHP"].'"';

// Upload image to /datanggocahKSK/buktiPendaftaran/[date]/[nama][instance].jpg/png
$uploadExt = strtolower(pathinfo($_FILES["lampiranIdentitas"]["name"], PATHINFO_EXTENSION));
$uploadDir = "datanggocahKSK/buktiPendaftaran/esai/";
$uploadDestination = $uploadDir.date("YmdHis").sentenceToCamelCase($_POST["nama"]).".".$uploadExt;
$uploadDestinationWithDot = "../".$uploadDir.date("ymds").sentenceToCamelCase($_POST["nama"]).".".$uploadExt;

$checkFile = getimagesize($_FILES["lampiranIdentitas"]["tmp_name"]);
if (!$checkFile){
    $reason = "File have no size.";    // 0 bytes error.
    $isSuccess = false;
}

// Check if the file has the appropriate extension
$hasProperExt = false;
foreach ($ALLOWED_IMAGE_EXTENSIONS as $properExt){
    if (!strcasecmp($uploadExt, $properExt)){
        $hasProperExt = true;
        break;
    }
}
if (!$hasProperExt){
    $reason = "File has inappropriate extension.";  // not of image extension.
    $isSuccess = false;
}
if ($_FILES["lampiranIdentitas"]["size"] > 10000000) {
    $reason = "File is too large.";
    $isSuccess = false;
}

if ($isSuccess) {
    move_uploaded_file($_FILES["lampiranIdentitas"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"]."/".$uploadDestination); // Upload file to KSK place so that KSK kids biso delok identitas saben wong sing daftar.

    $_POST["lampiranIdentitas"] = $HOMEPAGE.$uploadDestination;
    //echo $_POST["lampiranIdentitas"]."<br>";

    //echo $uploadExt."<br>".$uploadDir."<br>".$uploadDestination."<br>".$uploadDestinationWithDot."<br>".$_SERVER["DOCUMENT_ROOT"];

    // record to xls -----------------------
    $DATABASE_FILE = new Spreadsheet();     // functions as spreadsheet
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xls");
    $reader -> setReadDataOnly(TRUE);

    if (!file_exists($DATABASE_LOCATION)) createCSVWithHeader('Esai');
    $DATABASE_FILE = $reader->load($DATABASE_LOCATION);
    $worksheet = $DATABASE_FILE -> getSheetByName('Esai');

    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();

    $worksheet->fromArray($_POST, NULL, 'A'.++$highestRow);

    $writer = new Xls($DATABASE_FILE);
    $writer->save($DATABASE_LOCATION);

    if ($isSuccess){
        echo("Data succesfully recorded. Now redirecting to success page!");
        header("Status: 301 Moved Permanently");
        header("Location:success.php?subevent=esai");
    }
}

else {
    echo "<script>javascript:alert('Image is invalid. <".$reason."> .Returning back to registration page...'); window.location = '/register'</script>"; 
}