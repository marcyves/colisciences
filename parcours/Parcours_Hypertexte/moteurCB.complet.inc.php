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

	echo "<h2>Parcours $parcours</h2>";
    $memoire->ajoute($valeur);
    $i = $valeur;
    do {
    	decodeDocument($i);
		affiche($notionsParagraphe->lien($valeur),"","","","","");
		$i = $i + 1;
	} while ($i < $nombre_noeuds);

?>