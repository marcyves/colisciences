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

global $cat, $language, $prefix, $multilingual, $currentlang, $dbi;

    if ($multilingual == 1) {
	    $querylang = "AND (alanguage='$currentlang' OR alanguage='')"; /* the OR is needed to display stories who are posted to ALL languages */
    } else {
	    $querylang = "";
    }
    $result = mysqli_query($dbi, "select catid, title from ".$prefix."_stories_cat order by title");
    $numrows = sql_num_rows($result);
    if ($numrows == 0) {
	return;
    } else {
	$boxstuff = "<font class=\"content\">";
	while(list($catid, $title) = mysqli_fetch_row($result)) {
	    $result2 = mysqli_query($dbi, "select * from ".$prefix."_stories where catid='$catid' $querylang");
	    $numrows = sql_num_rows($result2);
	    if ($numrows > 0) {
		$res = mysqli_query($dbi, "select time from ".$prefix."_stories where catid='$catid' $querylang order by sid DESC limit 0,1");
		list($time) = mysqli_fetch_row($res);
		ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $dat);
		if ($cat == 0 AND !$a) {
		    $boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<b>"._ALLCATEGORIES."</b><br>";
		    $a = 1;
		} elseif ($cat != 0 AND !$a) {
		    $boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?name=News\">"._ALLCATEGORIES."</a><br>";
		    $a = 1;
		}
		
		if ($cat == $catid) {
		    $boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<b>$title</b><br>";
		} else {
		    $boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?name=News&amp;file=categories&amp;op=newindex&amp;catid=$catid\">$title</a> <font class=tiny>($dat[2]/$dat[3])</font><br>";
		}
	    }
	}
	$boxstuff .= "</font>";
	$content = $boxstuff;
    }

?>