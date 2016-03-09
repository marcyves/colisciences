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

if (!eregi("modules.php", $PHP_SELF)) {
    die ("You can't access this file directly...");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);

function showpage($pid, $page=0) {
    global $prefix, $dbi, $sitename, $admin, $module_name;
    include("header.php");
    opentable();
    $result = sql_query("SELECT * from ".$prefix."_pages where pid='$pid'", $dbi);
    $mypage = sql_fetch_array($result, $dbi);
    if (($mypage[active] == 0) AND (!is_admin($admin))) {
        echo "Sorry... This page doesn't exist.";
    } else {
        sql_query("update ".$prefix."_pages set counter=counter+1 where pid='$pid'", $dbi);
        $date = explode(" ", $mypage[date]);
        echo "<font class=\"title\">$mypage[title]</font><br>"
            ."<font class=\"content\">$mypage[subtitle]<br>
<br>";
        $contentpages = explode( "<!--pagebreak-->", $mypage[text] );
        $pageno = count($contentpages);
        if ( $page=="" || $page < 1 )
            $page = 1;
        if ( $page> $pageno )
            $page = $pageno;
        $arrayelement = (int)$page;
        $arrayelement --;
        if ($pageno> 1) {
            echo ""._PAGE.": $page/$pageno<br>";
        }
        if ($page == 1) {
            echo "<p align=\"justify\">".nl2br($mypage[page_header])."</p><br>";
        }
        echo "<p align=\"justify\">$contentpages[$arrayelement]</p>";
        if($page>= $pageno) {
            $next_page = "";
        } else {
            $next_pagenumber = $page + 1;
            if ($page != 1) {
                $next_page .= "- ";
            }
            $next_page .= "<a href=\"modules.php?name=$module_name&pa=showpage&pid=$pid&page=$next_pagenumber\">"._NEXT." ($next_pagenumber/$pageno)</a> <a href=\"modules.php?name=$module_name&pa=showpage&pid=$pid&page=$next_pagenumber\"><img src=\"themes/Clean/img/right.gif\" border=\"0\" alt=\""._NEXT."\"></a>";
        }
        if ($page == $pageno) {
            echo "<br><p align=\"justify\">".nl2br($mypage[page_footer])."</p><br><br>";
        }
        if($page <= 1) {
            $previous_page = "";
        } else {
            $previous_pagenumber = $page - 1;
            $previous_page = "<a href=\"modules.php?name=$module_name&pa=showpage&pid=$pid&page=$previous_pagenumber\"><img src=\"themes/Clean/img/left.gif\" border=\"0\" alt=\""._PREVIOUS."\"></a> <a href=\"modules.php?name=$module_name&pa=showpage&pid=$pid&page=$previous_pagenumber\">"._PREVIOUS." ($previous_pagenumber/$pageno)</a>";
        }
        echo "<br>
<center>$previous_page $next_page</center><br><br>";
        if ($page == $pageno) {
            echo "<p align=\"right\">".nl2br($mypage[signature])."</p>"
                ."<p align=\"right\">"._COPYRIGHT."</p><br><br></font>"
                ."<p align=\"right\"><font class=\"tiny\">"._PUBLISHEDON.": $date[0] ($mypage[counter] "._READS.")</font></p>"
                ."<center>"._GOBACK."</center>";
        }
    }
    CloseTable();
    include("footer.php");
}

function list_pages() {
    global $prefix, $dbi, $sitename, $admin, $multilingual, $module_name;

	$filename = "template74.html";
	$fd = fopen ($filename, "r");
	$c = fread ($fd, filesize ($filename));
	fclose ($fd);

	$c = str_replace("@titre@", $sitename, $c);

// Affichage de l'annonce
    $result = sql_query("SELECT * from ".$prefix."_pages where title='Annonce'", $dbi);
    $mypage = sql_fetch_array($result, $dbi);

	if ($mypage[active] != 0){
        sql_query("update ".$prefix."_pages set counter=counter+1 where pid='$pid'", $dbi);
        $date = explode(" ", $mypage[date]);

        $annonce = $mypage[subtitle]."<br>
<p align=\"justify\">".nl2br($mypage[page_header])."</p>
<p align=\"justify\">$mypage[text]</p>
<br><p align=\"justify\">".nl2br($mypage[page_footer])."</p>";
		$c = str_replace("@annonce@", $mypage[text], $c);
		$c = str_replace("@date_annonce@", $date[0], $c);
    }
/* fin modif page annonce */
    $gauche = "";
    $droite = "";
	$compteur = 0;
    $result = sql_query("select * from ".$prefix."_pages_categories where description = 'accueil'", $dbi);

    if (sql_num_rows($result, $dbi)> 0 AND sql_num_rows(sql_query("select * from ".$prefix."_pages WHERE cid!='0'", $dbi),$dbi)> 0) {

        while(list($cid, $title, $description) = sql_fetch_row($result, $dbi)) {
            if (sql_num_rows(sql_query("select * from ".$prefix."_pages WHERE cid='$cid'", $dbi),$dbi)> 0) {
				if ($compteur++<2){
		            $gauche .= "<p><a class=\"navigation\" href=\"modules.php?name=accueil&amp;pa=list_pages_categories&amp;cid=$cid\">$title</a>";
				} else {
		            $droite .= "<p><a class=\"navigation\" href=\"modules.php?name=accueil&amp;pa=list_pages_categories&amp;cid=$cid\">$title</a>";
				}
            }
        }
    }
	$c = str_replace("@lien1@", $gauche , $c);
	$c = str_replace("@lien2@", $droite , $c);
	$bibliotheque = "<a class=\"navigation\" href=\"modules.php?name=Bibliotheque\">La Bibliothèque</a>";
	$c = str_replace("@bibliotheque@", $bibliotheque , $c);
	print ($c);
}

function list_pages_categories($cid) {
    global $prefix, $dbi, $sitename, $admin, $multilingual, $module_name,$titre;
	$titre = "$sitename: "._PAGESLIST;
    include("header.php");

    $result = sql_query("select title from ".$prefix."_pages_categories where cid ='$cid'", $dbi);
	list($title) = sql_fetch_row($result, $dbi);
    title($title);
	
    opentable();
//    echo "<center><font class=\"content\">"._LISTOFCONTENT." $sitename:</center><br><br>";
    $result = sql_query("SELECT pid, title, subtitle, clanguage from ".$prefix."_pages WHERE active='1' AND cid='$cid' order by date", $dbi);
    echo "<blockquote>";

    while(list($pid, $title, $subtitle, $clanguage) = sql_fetch_row($result, $dbi)) {
        if ($multilingual == 1) {
            $the_lang = "<img src=\"images/language/flag-$clanguage.png\" hspace=\"3\" border=\"0\" height=\"10\" width=\"20\">";
        } else {
            $the_lang = "";
        }
        if ($subtitle != "") {
            $subtitle = " ($subtitle)";
        } else {
            $subtitle = "";
        }
        if (is_admin($admin)) {
            echo "<strong><big>&middot;</big></strong> $the_lang <a href=\"modules.php?name=$module_name&amp;pa=showpage&amp;pid=$pid\">$title</a> $subtitle [ <a href=\"admin.php?op=content_edit&pid=$pid\">"._EDIT."</a> | <a href=\"admin.php?op=content_change_status&pid=$pid&active=1\">"._DEACTIVATE."</a> | <a href=\"admin.php?op=content_delete&pid=$pid\">"._DELETE."</a> ]<br>";
        } else {
            echo "<strong><big>&middot;</big></strong> $the_lang <a href=\"modules.php?name=$module_name&amp;pa=showpage&amp;pid=$pid\">$title</a> $subtitle<br>";
        }
    }
    echo "</blockquote>";
    if (is_admin($admin)) {
        $result = sql_query("SELECT pid, title, subtitle, clanguage from ".$prefix."_pages WHERE active='0' and cid='$cid' order by date", $dbi);
        echo "<br><br><center><b>"._YOURADMINLIST."</b></center><br><br>";
        echo "<blockquote>";

        while(list($pid, $title, $subtitle, $clanguage) = sql_fetch_row($result, $dbi)) {
            if ($multilingual == 1) {
                $the_lang = "<img src=\"images/language/flag-$clanguage.png\" hspace=\"3\" border=\"0\" height=\"10\" width=\"20\">";
            } else {
                $the_lang = "";
            }
            if ($subtitle != "") {
                $subtitle = " ($subtitle) ";
            } else {
                $subtitle = " ";
            }
            echo "<strong><big>&middot;</big></strong> $the_lang <a href=\"modules.php?name=$module_name&amp;pa=showpage&amp;pid=$pid\">$title</a> $subtitle [ <a href=\"admin.php?op=content_edit&pid=$pid\">"._EDIT."</a> | <a href=\"admin.php?op=content_change_status&pid=$pid&active=0\">"._ACTIVATE."</a> | <a href=\"admin.php?op=content_delete&pid=$pid\">"._DELETE."</a> ]<br>";
        }
        echo "</blockquote>";
    }
    echo "<center>"._GOBACK."</center>";
    CloseTable();
    include("footerMain.php");
}

switch($pa) {

    case "showpage":
    showpage($pid, $page);
    break;
    
    case "list_pages_categories":
    list_pages_categories($cid);
    break;
    
    default:
    list_pages();
    break;

}

?>
