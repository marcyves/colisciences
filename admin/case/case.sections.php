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

    case "sections":
    case "sectionedit":
    case "sectionmake":
    case "sectiondelete":
    case "sectionchange":
    case "secarticleadd":
    case "secartedit":
    case "secartchange":
    case "secartdelete":
    include("admin/modules/sections.php");
    break;

}

?>