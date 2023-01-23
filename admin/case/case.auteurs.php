<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi (fbc@mandrakesoft.com)         */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

switch($op) {

    case "auteurs":
    case "add_auteur":
    case "auteur_edit":
    case "auteur_delete":
    case "auteur_upload":
	case "fichiers_auteur":
	    include("admin/modules/auteurs.php");
    break;
}

?>