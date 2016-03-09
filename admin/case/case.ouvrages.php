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

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }

switch($op) {

    case "ouvrages":
    case "fichiers_ouvrage":
    case "upload_ouvrage":
    case "upload_ouvrage_XML":
    case "upload_notions_ouvrage":
    case "ouvrage_edit":
    case "ouvrage_discipline":
    case "add_discipline":
    case "del_discipline":
    case "add_domaine":
    case "del_domaine":
    case "ouvrage_delete":
    case "ouvrage_save":
    case "ouvrage_save_edit":
    case "ouvrage_change_status":
    case "add_ouvrage":
	case "ouvrage_upload_comment":
    include("admin/modules/ouvrages.php");
    break;
    case "upload":
    include("admin/modules/upload.php");
    break;

}

?>