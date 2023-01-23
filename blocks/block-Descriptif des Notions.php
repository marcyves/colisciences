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

if (eregi("block-Descriptif des Notions.php", $PHP_SELF)) {
    Header("Location: index.php");
    die();
}

global $prefix, $dbi;

$result = mysqli_query($dbi, "SELECT pid, title FROM ".$prefix."_pages WHERE active='1'");
while(list($pid, $title) = mysqli_fetch_row($result)) {
    $content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?name=Content&amp;pa=showpage&amp;pid=$pid\">$title</a><br>";
}

?>