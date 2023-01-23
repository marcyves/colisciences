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

global $prefix, $dbi, $admin;

    $result = sql_query("select main_module from ".$prefix."_main", $dbi);
    list($main_module) = sql_fetch_row($result, $dbi);
    
    /* If the module doesn't exist, it will be removed from the database automaticaly */

    $result = sql_query("select title from ".$prefix."_modules", $dbi);
    while (list($title) = sql_fetch_row($result, $dbi)) {
	$a = 0;
	$handle=opendir('modules');
	while ($file = readdir($handle)) {
    	    if ($file == $title) {
		$a = 1;
	    }
	}
	closedir($handle);
	if ($a == 0) {
    	    sql_query("delete from ".$prefix."_modules where title='$title'", $dbi);
	}
    }

    /* Now we make the Modules block with the correspondent links */

    $content .= themeMenuOpen();
    $result = sql_query("select title, custom_title from ".$prefix."_modules where active='1' ORDER BY title ASC", $dbi);
    while(list($m_title, $custom_title) = sql_fetch_row($result, $dbi)) {
	$m_title2 = ereg_replace("_", " ", $m_title);
	if ($custom_title != "") {
	    $m_title2 = $custom_title;
	}
	if ($m_title != $main_module) {
	    $content .= themeMenuLien("<a href=\"modules.php?name=$m_title\">$m_title2</a>\n");
	}
    }
  $content .= themeMenuClose();

    /* If you're Admin you and only you can see Inactive modules and test it */
    /* If you copied a new module is the /modules/ directory, it will be added to the database */
    
    if (is_admin($admin)) {
	$handle=opendir('modules');
	while ($file = readdir($handle)) {
	    if ( (!ereg("[.]",$file)) ) {
		$modlist .= "$file ";
	    }
	}
	closedir($handle);
	$modlist = explode(" ", $modlist);
	sort($modlist);
	for ($i=0; $i < sizeof($modlist); $i++) {
	    if($modlist[$i] != "") {
		$result = sql_query("select mid from ".$prefix."_modules where title='$modlist[$i]'", $dbi);
		list ($mid) = sql_fetch_row($result, $dbi);
		if ($mid == "") {
		    sql_query("insert into ".$prefix."_modules values (NULL, '$modlist[$i]', '', '0', '0')", $dbi);
		}
	    }
	}
    }
?>