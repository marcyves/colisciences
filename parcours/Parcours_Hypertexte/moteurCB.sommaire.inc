<?php

/******************************************************************************/
/*                                                                            */
/* moteurCB.php - phpCB                                                       */
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

		$sommaire = "sommaire";
	    $file = $colisroot.$ouvrage."/$sommaire.html";
//debug		echo "<p>le fichier sommaire est: $file";
		if (!file_exists($file)) {
			$sommaireText = "<font class=\"title2\">Le Sommaire</font>
<table align=\"center\">
";
			do {
//debug				echo "<p>Construction du fichier $file: $valeur sur $nombre_noeuds";
			   	decodeDocument($valeur);
//debug				echo "<p>";
//debug				print_r($paragraphe);
				if ($page != $paragraphe['page']) {
					$page = $paragraphe['page'];
				}
				if ($partie != $paragraphe['partie']) {
					$partie = $paragraphe['partie'];
					$sommaireText .= "<tr><td colspan=\"3\" bgcolor=\"#BBBBBB\">$partie</td><td>".creeLien($valeur, "sommaire", $page, "Fac")."</td></tr>\n";
				}
				if ($chapitre != $paragraphe['chapitre']) {
					$chapitre = $paragraphe['chapitre'];
					$sommaireText .= "<tr><td>&nbsp;</td><td colspan=\"2\" bgcolor=\"#DDDDDD\">$chapitre</td><td>".creeLien($valeur, "sommaire", $page, "Fac")."</td></tr>\n";
				}
				if (($souschapitre != $paragraphe['sous-chapitre'])&&($paragraphe['sous-chapitre'] != "")) {
					$souschapitre = $paragraphe['sous-chapitre'];
					$sommaireText .= "<tr><td>&nbsp;</td><td>&nbsp;</td><td>$souschapitre</td><td>".creeLien($valeur, "sommaire", $page, "Fac")."</td></tr>\n";
				}
				$valeur++;
			} while ($valeur < $nombre_noeuds);
			$sommaireText .= "</table>";
			if (!fwrite(fopen ($file, "w") , $sommaireText)){
				echo "Erreur écriture fichier sommaire: ".$file;
			}
		}
		include ($file);
?>