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

if (eregi("block-Sections_Articles.php", $PHP_SELF)) {
    Header("Location: index.php");
    die();
}

global $prefix, $dbi;

$result = mysqli_query($dbi, "SELECT artid, title FROM ".$prefix."_seccont order by artid DESC limit 0,10");
while(list($artid, $title) = mysqli_fetch_row($result)) {
    $content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?name=Sections&amp;sop=viewarticle&amp;artid=$artid\">$title</a><br>";
}

?>