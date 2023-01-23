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

if (!isset($op)) {
	$op="list_content";
	$eid = "titre";
	}
	
function encysearch($eid) {
    global $module_name;
    echo "<center><form action=\"parcours.php?name=$module_name&file=search\" method=\"post\">"
	."<input type=\"text\" size=\"20\" name=\"query\">&nbsp;&nbsp;"
	."<input type=\"hidden\" name=\"eid\" value=\"$eid\">"
	."<input type=\"submit\" value=\""._SEARCH."\">"
	."</form>"
	."</center>";
}

function alpha($quoi) {
    global $module_name, $prefix, $dbi;
    echo "<center>"._ENCYSELECTLETTER."</center><br><br>";
    $alphabet = array ("A","B","C","D","E","F","G","H","I","J","K","L","M",
                       "N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
    $num = count($alphabet) - 1;
    echo "<center>[ ";
    $counter = 0;
    while (list(, $ltr) = each($alphabet)) {
    $result = sql_query("select distinct $quoi from cb_biblio where UPPER($quoi) LIKE '$ltr%'", $dbi);
		$count = sql_num_rows($result);
	if ( $count > 0) {
	    echo "<a href=\"parcours.php?name=$module_name&op=terms&eid=$quoi&ltr=$ltr\">$ltr</a><small>($count)</small>";
	} else {
	    echo "$ltr";
	}
        if ( $counter == round($num/2) ) {
            echo " ]\n<br>\n[ ";
        } elseif ( $counter != $num ) {
            echo "&nbsp;|&nbsp;\n";
        }
        $counter++;
    }
    echo " ]<br><br>\n\n\n<p>Trier par:<ul>"
		."<li><a href=\"parcours.php?name=$module_name&op=list_content&eid=titre\">Titre</a>"
		."<li><a href=\"parcours.php?name=$module_name&op=list_content&eid=auteurs\">Auteur</a>"
		."<li><a href=\"parcours.php?name=$module_name&op=list_content&eid=editeurRevue\">Editeur</a>"
		."</ul></center>";
	
	encysearch($eid);
    echo "<center>"._GOBACK."</center>";
}

function list_content($quoi) {
    global $module_name, $prefix, $sitename, $dbi;
		
    include("header.php");
    title("Bibliographie");
    OpenTable();
		if ($quoi=="") {
			$quoi = "titre";
		}
    echo "<center><b>Bibliographie compl�te</b></center><br>"
	."<p align=\"justify\">Tri�e par $quoi</p>";
    CloseTable();
    echo "<br>";
    OpenTable();
    alpha($quoi);
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"tiny\">"._COPYRIGHT."</font></center>";
    CloseTable();
    include("footer.php");
}

function terms($quoi, $ltr) {
    global $module_name, $prefix, $sitename, $dbi, $admin;

		include("header.php");
		title("Bibliographie");
		OpenTable();
		echo "<center>Vous pouvez s�lectionner un terme dans la liste ci-dessous:</center><br><br>"
	    ."<table border=\"0\" align=\"center\">";
    $result = sql_query("select Numero, $quoi, Titre from cb_biblio where UPPER($quoi) LIKE '$ltr%' order by $quoi", $dbi);
    //list($numero, $biblio, $categorie, $dates, $type, $titre, $compil, $lieu, $editeurRevue, $reference, $commentaires, $auteurs) = sql_fetch_row($result, $dbi);
		if (sql_num_rows($result, $dbi) == 0) {
	    echo "<center><i>"._NOCONTENTFORLETTER." $ltr.</i></center>";
		}
		while(list($tid, $title, $detail) = sql_fetch_row($result, $dbi)) {
			echo "<tr><td><a href=\"parcours.php?name=$module_name&op=content&tid=$tid\">$title</a>";
			if ($title != $detail) {
				echo " ($detail)";
			}
			echo "</td></tr>";
		}
		echo "</table><br><br>";
		alpha($quoi);
    CloseTable();
    include("footer.php");
}

function content($tid, $ltr, $page=0, $query="") {
    global $prefix, $dbi, $sitename, $admin, $module_name;
    include("header.php");
    OpenTable();
		
    $result = sql_query("SELECT Dates, Type, Titre, Compil, Lieu, EditeurRevue, Reference, Commentaires, Auteurs from cb_biblio where Numero='$tid'", $dbi);
	afficheEntreesBiblio($result);
    CloseTable();
    include("footer.php");
}

switch($op) {

    case "content":
    content($tid, $ltr, $page, $query);
    break;

    case "list_content":
    list_content($eid);
    break;

    case "terms":
    terms($eid, $ltr);
    break;

    case "search":
    search($query, $eid);
    break;

    default:
    list_content($eid);
    break;

}

?>