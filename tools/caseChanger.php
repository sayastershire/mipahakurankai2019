<?php
function sentenceToCamelCase($sentence){
	$sentenceChar = str_split($sentence);
	$sentenceCamel = "";
	$capitalise = false;
	foreach($sentenceChar as $key){
		if ($key != ' '){
			if ($capitalise) $sentenceCamel .= strtoupper($key);
			else $sentenceCamel .= $key;
			$capitalise = false;
		}
		else $capitalise = true;
	}
	return $sentenceCamel;
}