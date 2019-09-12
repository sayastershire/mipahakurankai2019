<?php
$debugmode = false;

if ($debugmode){ // if 
	foreach ($_POST as $key => $value) echo $key.": ".$value."<br>";
}