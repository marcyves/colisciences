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
/* Created: 29 March 2002                                                     */
/*                                                                            */
/* Copyright (c) 2002 LCP - MEDIATEC                                          */
/*                                                                            */
/* This file is part of CoLiSciences (https://github.com/marcyves/colisciences)                      */
/*                                                                            */
/******************************************************************************/

if ($notion == "") {
	decodeDocument($valeur);
	affiche( "Notions et relations de ce paragraphe", $notionsParagraphe->lien($valeur), "Mots et notions", $motclef->liste("motclef"),"Parcours", $memoire->paragraphe("icone"));
} else {
	if ($valeur >= $nombre_noeuds) {
		affiche( "Recherche terminée",  "La fin de l'ouvrage est atteinte.", "", "","msg","");
		$nextStep = "";
	} else {
		$nextStep = "notion_relation";
		$valeur = $valeur + 1;
//debug echo "<p>Paragraphe en cours $valeur";
		if (!$flagTitreNotion){
			echo "<h2>Etude de la relation $notion</h2>";
			$flagTitreNotion = TRUE;
		}

		decodeDocument($valeur);
		$flagParcours = $notionsParagraphe->chercheR($notion);

        if ($flagParcours) {
			switch ($type) {
            case "icone":
                echo affichageIcone($valeur, "actif");
			break;
			case "montre":
				affiche( "Notions de ce paragraphe", $notionsParagraphe->lien($valeur), "", "","short", "");
                $cnt = $cnt + 1;
                $ancienTitre = $titre;
			break;
			case "cache":
				affiche( "Notions de ce paragraphe", $notionsParagraphe->lien($valeur), "", "","short", "");
                  $cnt = $cnt + 1;
                  $ancienTitre = $titre;
			break;
            case "simple":
				$notion = "";
            break;
            }
		} else {
            switch ($type) {
            case "montre":
				echo "<div class=\"petit\">";
				affiche( "Notions de ce paragraphe", $notionsParagraphe->lien($valeur), "", "","short", "");
		        echo "</div>";
            break;
            case "icone";
                echo affichageIcone($valeur, "inactif");
            break;
            }
            $ancienTitre = $titre;
		}
	}
}

?>