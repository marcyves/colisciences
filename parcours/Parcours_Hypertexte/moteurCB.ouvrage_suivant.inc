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
/* Created: 27 October 2003                                                   */
/*                                                                            */
/* Copyright (c) 2002 LCP - MEDIATEC                                          */
/*                                                                            */
/* This file is part of phpCB (http://colisciences.net/)                      */
/*                                                                            */
/******************************************************************************/

$sql = sql_query("select pid, titre, nom, prenom,nombre_noeuds from cb_ouvrages, cb_auteurs where aid=auteur and pid > $ouvrage and active='1'",$dbi);

if (list($pid, $titre, $nom, $prenom,$nombre_noeuds ) = sql_fetch_row($sql, $dbi)) {
	$ouvrage = $pid;
	echo "<font class=\"title2\"><ul><li>$titre ($prenom $nom)</ul></font><br>";
	$nextStep = $parcours;
	$valeur = 1;
	$flagTitreNotion = TRUE;
} else {
	$nextStep = "";
}

?>