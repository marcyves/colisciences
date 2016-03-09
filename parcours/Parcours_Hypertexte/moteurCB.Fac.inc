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
/* This file is part of phpCB (http://colisciences.net/)                      */
/*                                                                            */
/******************************************************************************/

	decodeDocument($valeur);
	$flagAffiche = true;

	if (!isset($numPage)) {
		$numPage = $paragraphe['page'];
		if ($numPage <= 0) {
			echo "<p><br>Désolé, je ne peux pas déterminer quelle page contient le paragraphe $valeur.<br><br><br>Le numéro de page est: ($numPage).<br><br>";
			$flagAffiche = false;
		} else {
			echo "<p>Le paragraphe $valeur se trouve sur la page: $numPage";
		}
	}else{
//		echo "<p>Recherche de la page $numPage sur le paragraphe $valeur de la page ".$paragraphe['page'];
		if ($numPage == $paragraphe['page']){
//			echo "<p>Trouvé le paragraphe $valeur se trouve sur la page: ";
		}else if($numPage > $paragraphe['page']){
			if ($flagDirection=='down'){
				echo "<p>La page $numPage est blanche";
			}else{
				//préparation pour le paragraphe suivant
				$valeur = $valeur + 1;	
				$nextStep = $parcours;
				$flagAffiche = false;
				$flagDirection = 'up';
			}
		}else if($numPage < $paragraphe['page']){
			if ($flagDirection=='up'){
				echo "<p>La page $numPage est blanche";
			}else{
			//préparation pour le paragraphe suivant
				$valeur = $valeur - 1;	
				$nextStep = $parcours;
				$flagAffiche = false;
				$flagDirection = 'down';
			}
		} 
	}
	
	if ($flagAffiche){
/*		$suivant = $numPage + 1;
		$precedent = $numPage - 1;
  		if ($suivant > $nombre_pages ) { $suivant = 1; }
		if ($precedent < 1 ) { $precedent = $nombre_pages; }

		echo creeLienFac("1", "menunav", "<<", $parcours, $valeur)." ".
	     creeLienFac($precedent, "menunav", "< ", $parcours, $valeur)."$numPage". 
		 creeLienFac($suivant, "menunav", " >", $parcours, $valeur)." ".
		 creeLienFac($nombre_pages, "menunav", ">>", $parcours, $valeur);
*/
		echo "<p>".creeLien("", "bouton", "Vue d'ensemble", "vignettes");
 		affichageFacSimile($numPage);
		$flagParcours = true;
	}
?>