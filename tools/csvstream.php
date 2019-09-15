<?php

use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

function createCSVWithHeader($headerType) {
	$HEADER_LCC = array(
		"Nama Tim",
		"Asal Sekolah",
		"Alamat Sekolah",
		"Nama Anggota 1",
		"Tempat dan Tanggal Lahir Anggota 1",
		"Alamat Email Anggota 1",
		"Nomor HP Anggota 1",
		"Nama Anggota 2",
		"Tempat dan Tanggal Lahir Anggota 2",
		"Alamat Email Anggota 2",
		"Nomor HP Anggota 2",
		"Nama Anggota 3",
		"Tempat dan Tanggal Lahir Anggota 3",
		"Alamat Email Anggota 3",
		"Nomor HP Anggota 3",
		"Lampiran Kartu Pelajar Anggota 1",
		"Lampiran Kartu Pelajar Anggota 2",
		"Lampiran Kartu Pelajar Anggota 3"
	);
	$HEADER_ESAI = array(
		"Nama",
		"Tempat dan Tanggal Lahir",
		"Alamat Email",
		"Nomor HP",
		"Asal Sekolah",
		"Alamat Sekolah",
		"Judul Esai",
		"Lampiran File Esai",
		"Lampiran Kartu Pelajar"
	);
	$HEADER_RANKING1 = array(
		"Nama",
		"Tempat dan Tanggal Lahir",
		"Alamat Email",
		"Nomor HP",
		"Asal Sekolah",
		"Alamat Sekolah",
		"Lampiran Kartu Pelajar"
	);
	$HEADER_TALKSHOW = array(
		"Nama",
		"Tempat dan Tanggal Lahir",
		"Alamat Email",
		"Nomor HP",
		"Asal Instansi",
		"Alamat Instansi"
	);

	//$file = fopen('../datanggocahKSK/'.$headerType.'/pendaftar.csv', 'w');
	$file = new Spreadsheet();
	$worksheetLCC = new Worksheet($file, "LCC");
	$worksheetEsai = new Worksheet($file, "Esai");
	$worksheetRanking1 = new Worksheet($file, "Ranking 1");
	$worksheetTalkshow = new Worksheet($file, "Talkshow");

	$worksheetLCC->fromArray($HEADER_LCC, NULL, 'A1');
	$worksheetEsai->fromArray($HEADER_ESAI, NULL, 'A1');
	$worksheetRanking1->fromArray($HEADER_RANKING1, NULL, 'A1');
	$worksheetTalkshow->fromArray($HEADER_TALKSHOW, NULL, 'A1');

	$file->addSheet($worksheetLCC, 0);
	$file->addSheet($worksheetEsai, 1);
	$file->addSheet($worksheetRanking1, 2);
	$file->addSheet($worksheetTalkshow, 3);
	
	$writer = new \PhpOffice\PhpSpreadsheet\Writer\Html($file);
	$writer->save('../datanggocahKSK/pendaftar.html');

	return $file;
}