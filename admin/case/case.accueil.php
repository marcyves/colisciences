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
//wysiwyg
    case "wysiwygeditor":
    case "Pr�visualisation":
    case "Ok !":
//standard ones
    case "accueil":
    case "accueil_edit":
    case "accueil_delete":
    case "accueil_save":
    case "accueil_save_edit":
    case "accueil_change_status":
    case "add_category":
    case "edit_category":
    case "save_category":
    case "del_accueil_cat":
    include("admin/modules/accueil.php");
    break;

}

?>