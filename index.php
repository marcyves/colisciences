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
$sql_debug = 0;
require_once("mainfile.php");

$PHP_SELF = "modules.php";
$result = $dbi->query("select main_module from ".$prefix."_main");
$line = $result->fetch_object();
$name = $line->main_module;
$home = 1;
if ($httpref==1) {
    $referer = getenv("HTTP_REFERER");
    if ($referer=="" OR preg_match("/^unknown/i", $referer) OR substr("$referer",0,strlen($nukeurl))==$nukeurl OR preg_match("/^bookmark/i",$referer)) {
    } else {
        $dbi->query("insert into ".$prefix."_referer values (NULL, '$referer')");
    }
    $result = $dbi->query("select * from ".$prefix."_referer");
    $numrows = $result->num_rows;
    if($numrows>=$httprefmax) {
        $dbi->query("delete from ".$prefix."_referer");
    }
}
if (!isset($mop)) { $mop="modload"; }
if (!isset($mod_file)) { $mod_file="index"; }
$modpath="modules/$name/$mod_file.php";

if (file_exists($modpath)) {
        include($modpath);
} else {
    $index = 1;
    include("headerMain.php");
    OpenTable();
    if (is_admin($admin)) {
        echo "<center><font class=\"\"><b>"._HOMEPROBLEM."</b></font><br><br>[ <a href=\"admin.php?op=modules\">"._ADDAHOME."</a> ]</center>";
    } else {
        echo "<center>"._HOMEPROBLEMUSER."</center>";
    }
    CloseTable();
    include("footer.php");
}

?>
