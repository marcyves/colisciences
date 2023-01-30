<?php
/******************************************************************************/
/*                                                                            */
/* moteurCB.Fac.php - phpCB                                                   */
/*                                                                            */
/******************************************************************************/
/*                                                                            */
/* Requirements: PHP, MySQL and web-browser                                   */
/*                                                                            */
/* Author: Marc Augier                                                        */
/*         <marc.augier@cote-azur.cci.fr>                                     */
/*                                                                            */
/* Created: 26 November 2002                                                  */
/*                                                                            */
/* Copyright (c) 2002 LCP - MEDIATEC                                          */
/*                                                                            */
/* This file is part of CoLiSciences (https://github.com/marcyves/colisciences)                      */
/*                                                                            */
/******************************************************************************/

// ----- Affiche arborescence ----- Auteur : fgauharou@yahoo.fr ------------- //
require("fonctions.php");

	if ($ouvrage!=""){
	    $chemintotal = $webroot."vignettes/$ouvrage/";
		echo $baseDir;
// ------------------------------- Récupération des fichiers et répertoires dans tableau-- //


$handle  = @opendir($chemintotal);
$file    = @readdir($handle);      // repertoire .
$file    = @readdir($handle);      // repertoire ..
$repind  = 0;
$fileind = 0;

while ($file = @readdir($handle))
{
	if(!is_dir("$chemintotal/$file"))
	{
		$filetab[$fileind]["nom"]         = $file;
		$fileind++;
	}
}

sort($filetab);

@closedir($handle);

// --------------------------------------- Affichage des fichiers ----------------------------------------- //

$IndiceImage++;


for($i=0;$i<$fileind;$i++)
	{
	$nomfic      = $filetab[$i]["nom"];
	$ext         = GetExtension($nomfic);
	$ext         = strtolower($ext);
	$numPage 	 = GetFileName($nomfic);
	
	echo "<a href=\"parcours.php?name=Parcours_Hypertexte&file=moteurCB&ouvrage=$ouvrage&parcours=Fac&numPage=$numPage\">
	<IMG SRC =\"vignettes/$ouvrage/$nomfic\" alt=\"$nomfic\">
</a> ";
	}

// ------ fin du tableau ---- //

?><BR><?
	}else{
		echo "<p>Il faut sélectionner un ouvrage";
	}


?>