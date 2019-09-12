<?php
// iki nggo LCC

$isSuccess = true;
$reason = "";

$HOMEPAGE = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/';
$DATABASE_LOCATION = "../datanggocahKSK/pendaftar.xlsx";
$ALLOWED_IMAGE_EXTENSIONS = array("jpg", "jpeg", "png");

require '../vendor/autoload.php';
require '../tools/tools.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Stringify phone numbers first-----------------
for ($i = 0; $i < 3; $i++) {
	$phoneNumber = '"'.$_POST["nomorHPAnggota".($i + 1)].'"';
	$_POST["nomorHPAnggota".($i + 1)] = $phoneNumber;
}

// Upload image to /datanggocahKSK/buktiPendaftaran/[date]/[nama][instance].jpg/png
$uploadExt1 = strtolower(pathinfo($_FILES["lampiranIdentitasAnggota1"]["name"], PATHINFO_EXTENSION));
$uploadExt2 = strtolower(pathinfo($_FILES["lampiranIdentitasAnggota2"]["name"], PATHINFO_EXTENSION));
$uploadExt3 = strtolower(pathinfo($_FILES["lampiranIdentitasAnggota3"]["name"], PATHINFO_EXTENSION));
$uploadDir = "datanggocahKSK/buktiPendaftaran/ranking1/";
$uploadDestination1 = $uploadDir.date("YmdHis").sentenceToCamelCase($_POST["namaAnggota1"]).".".$uploadExt1;
$uploadDestination2 = $uploadDir.date("YmdHis").sentenceToCamelCase($_POST["namaAnggota2"]).".".$uploadExt2;
$uploadDestination3 = $uploadDir.date("YmdHis").sentenceToCamelCase($_POST["namaAnggota3"]).".".$uploadExt3;

for ($i = 0; $i < 3; $i++){
	$checkFile = getimagesize($_FILES["lampiranIdentitasAnggota".($i + 1)]["tmp_name"]);
	if (!$checkFile){
		$reason = "File have no size.(".($i+1).")";    // 0 bytes error.
		$isSuccess = false;
	}
}

// Check if the file has the appropriate extension
$hasProperExt = array(false, false, false);

foreach ($ALLOWED_IMAGE_EXTENSIONS as $properExt){
	if (!strcasecmp($uploadExt1, $properExt)) $hasProperExt[0] = true;
	if (!strcasecmp($uploadExt2, $properExt)) $hasProperExt[1] = true;
	if (!strcasecmp($uploadExt3, $properExt)) $hasProperExt[2] = true;

}

if (!$hasProperExt[0] || !$hasProperExt[1] || !$hasProperExt[2]){
	$reason = "File has inappropriate extension.";  // not of image extension.
	$isSuccess = false;
}
if ($_FILES["lampiranIdentitasAnggota1"]["size"] > 10000000 ||
	$_FILES["lampiranIdentitasAnggota2"]["size"] > 10000000 ||
	$_FILES["lampiranIdentitasAnggota3"]["size"] > 10000000) {
	$reason = "File is too large.";
	$isSuccess = false;
}

if ($isSuccess) {
	move_uploaded_file($_FILES["lampiranIdentitasAnggota1"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"]."/".$uploadDestination1); // Upload file to KSK place so that KSK kids biso delok identitas saben wong sing daftar.
	move_uploaded_file($_FILES["lampiranIdentitasAnggota2"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"]."/".$uploadDestination2);
	move_uploaded_file($_FILES["lampiranIdentitasAnggota3"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"]."/".$uploadDestination3);

	$_POST["lampiranIdentitasAnggota1"] = $HOMEPAGE.$uploadDestination1;
	$_POST["lampiranIdentitasAnggota2"] = $HOMEPAGE.$uploadDestination2;
	$_POST["lampiranIdentitasAnggota3"] = $HOMEPAGE.$uploadDestination3;
	
	$DATABASE_FILE = new Spreadsheet();     // functions as spreadsheet
	$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
	$reader -> setReadDataOnly(TRUE);

	if (!file_exists($DATABASE_LOCATION)) createCSVWithHeader('LCC');
	$DATABASE_FILE = $reader->load($DATABASE_LOCATION);
	$worksheet = $DATABASE_FILE -> getSheetByName('LCC');

	$highestRow = $worksheet->getHighestRow();
	$highestColumn = $worksheet->getHighestColumn();

	$worksheet->fromArray($_POST, NULL, 'A'.++$highestRow);

	$writer = new Xlsx($DATABASE_FILE); // smth
	$writer->save($DATABASE_LOCATION);

	echo("Data succesfully recorded. Now redirecting to success page!");
	header("Status: 301 Moved Permanently");
	header("Location:success.php?subevent=lcc");
}

else {
	//echo "<script>javascript:alert('Image is invalid. <".$reason."> .Returning back to registration page...'); window.location = '/register'</script>"; 
	echo "hiya gagal.";
}

