<?php

/************************************************************************/
/* PHP-NUKE: Advanced Content Management System                         */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi (fbc@mandrakesoft.com)         */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

require_once("mainfile.php");

if (isset($mot)) {
	$titre = 'Rechercher le mot "'. $mot.'" dans CoLiSciences';
	include("header.php");
	OpenTable();
	echo "Vous pouvez chercher $mot dans 
<a href=\"parcours.php?name=Recherche&action=go&blork=$mot\"><h3>Les Ouvrages du Corpus CoLiSciences</h3></a>
<a href=\"parcours.php?name=Glossaire&file=search&eid=6&query=$mot\"><h3>Le Glossaire</h3></a>
<a href=\"parcours.php?name=Auteurs_cités&file=search&eid=5&query=$mot\"><h3>Les Savants Cités</h3></a>
<a href=\"parcours.php?name=Parcours_Hypertexte&file=moteurCB&parcours=compte&op=search&query=$mot\"><h3>Les occurrences</h3></a>
<a href='http://www.google.fr/search?hl=fr&ie=UTF-8&oe=UTF-8&q=\"$mot\"'><h3>Google</h3></a>";
  CloseTable();
} else {
    die ("Sorry, you can't access this file directly...");
}

?>
