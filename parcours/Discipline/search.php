<?php

######################################################################
# PHP-NUKE: Web Portal System
# ===========================
#
# Copyright (c) 2002 by Francisco Burzi (fbc@mandrakesoft.com)
# http://phpnuke.org
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
######################################################################

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);
include("header.php");

if (isset($query) AND (isset($eid)) AND ($query != "")) {
    $result = mysqli_query($dbi, "select tid, title from ".$prefix."_encyclopedia_text where eid='$eid' AND title LIKE '%$query%'");
    $result2 = mysqli_query($dbi, "select title from ".$prefix."_encyclopedia where eid='$eid'");
    list($ency_title) = mysqli_fetch_row($result2);
    title("$ency_title: "._SEARCHRESULTS."");
    OpenTable();
    echo "<center><b>"._SEARCHRESULTSFOR." <i>$query</i></b></center><br><br><br>"
	."<i><b>"._RESULTSINTERMTITLE."</b></i><br><br>";
    if ($result->num_rows == 0) {
        echo _NORESULTSTITLE;
    } else {
	while(list($tid, $title) = mysqli_fetch_row($result)) {
	    echo "<strong><big>&middot</big></strong>&nbsp;&nbsp;<a href=\"parcours.php?name=$module_name&op=content&tid=$tid\">$title</a><br>";
	}
    }
    $result = mysqli_query($dbi, "select tid, title from ".$prefix."_encyclopedia_text where eid='$eid' AND text LIKE '%$query%'");
    echo "<br><br><i><b>"._RESULTSINTERMTEXT."</b></i><br><br>";
    if ($result->num_rows == 0) {
        echo _NORESULTSTEXT;
    } else {
	while(list($tid, $title) = mysqli_fetch_row($result)) {
	    echo "<strong><big>&middot</big></strong>&nbsp;&nbsp;<a href=\"parcours.php?name=$module_name&op=content&tid=$tid&query=$query\">$title</a><br>";
	}
    }
    echo "<br><br>"
	."<center><form action=\"parcours.php?name=$module_name&file=search\" method=\"post\">"
	."<input type=\"text\" size=\"20\" name=\"query\">&nbsp;&nbsp;"
	."<input type=\"hidden\" name=\"eid\" value=\"$eid\">"
	."<input type=\"submit\" value=\""._SEARCH."\">"
	."</form><br><br>"
	."[ <a href=\"parcours.php?name=$module_name&op=list_content&eid=$eid\">"._RETURNTO." $ency_title</a> ]<br><br>"
	.""._GOBACK."</center>";
    CloseTable();
} else {
    OpenTable();
    echo "<center>"._SEARCHNOTCOMPLETE."<br><br><br>"
	."<center><form action=\"parcours.php?name=$module_name&file=search\" method=\"post\">"
	."<input type=\"text\" size=\"20\" name=\"query\">&nbsp;&nbsp;"
	."<input type=\"hidden\" name=\"eid\" value=\"$eid\">"
	."<input type=\"submit\" value=\""._SEARCH."\">"
	."</form><br><br>"
	.""._GOBACK."</center>";
    CloseTable();
}

include("footer.php");

?>