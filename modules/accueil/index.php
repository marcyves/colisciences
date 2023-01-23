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
    global $prefix, $dbi, $sitename, $admin, $module_name, $desolePageInexitante;
    include("header.php");
    opentable();
    $result = mysqli_query($dbi, "SELECT * from ".$prefix."_pages where pid='$pid'");
    $mypage = mysqli_fetch_array($result);
    if (($mypage[active] == 0) AND (!is_admin($admin))) {
        echo $desolePageInexitante;
    } else {
        mysqli_query($dbi, "update ".$prefix."_pages set counter=counter+1 where pid='$pid'");
        $date = explode(" ", $mypage[date]);
        echo "<font class=\"title\">$mypage[title]</font><br>"
            ."<font class=\"content\">$mypage[subtitle]<br><br>";
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
            echo "<p>".nl2br($mypage[page_header])."</p><br>";
        }
        echo "<p>$contentpages[$arrayelement]</p>";
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
            echo "<br><p>".nl2br($mypage[page_footer])."</p><br><br>";
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

	if ($admin){
		$filename = "template74.html";
	} else{
		$filename = "template1.html";
	}
	$fd = fopen ($filename, "r");
	$c = fread ($fd, filesize ($filename));
	fclose ($fd);

	$c = str_replace("@titre@", $sitename, $c);

// Affichage de l'annonce
    $result = mysqli_query($dbi, "SELECT * from ".$prefix."_pages where title='Annonce'");
    $mypage = mysqli_fetch_array($result);

	if ($mypage[active] != 0){
        mysqli_query($dbi, "update ".$prefix."_pages set counter=counter+1 where pid='$pid'");
        $date = explode(" ", $mypage[date]);

        $annonce = $mypage[subtitle]."<br><p>".nl2br($mypage[page_header])."</p><p>$mypage[text]</p><br><p>".nl2br($mypage[page_footer])."</p>";
		$c = str_replace("@annonce@", $mypage[text], $c);
		$c = str_replace("@date_annonce@", $date[0], $c);
    }
/* fin modif page annonce */
	if ($admin){

		$compteur = 0;
    	$result = mysqli_query($dbi, "select * from ".$prefix."_pages_categories where description = 'accueil'");

	    if (sql_num_rows($result)> 0 AND sql_num_rows(mysqli_query($dbi, "select * from ".$prefix."_pages WHERE cid!='0'"))> 0) {

	        while(list($cid, $title, $description) = mysqli_fetch_row($result)) {
    	        if (sql_num_rows(mysqli_query($dbi, "select * from ".$prefix."_pages WHERE cid='$cid'"))> 0) {
			        $lien[$compteur++] = "modules.php?name=accueil&amp;pa=list_pages_categories&amp;cid=$cid";
				}
            }
        }
		$c = str_replace("@lien1@", $lien[0], $c);
		$c = str_replace("@lien2@", $lien[1], $c);
		$c = str_replace("@lien3@", $lien[2], $c);
		$c = str_replace("@lien4@", $lien[3], $c);
		$bibliotheque = "modules.php?name=Bibliotheque";
		$c = str_replace("@bibliotheque@", $bibliotheque , $c);
	} else{
    	$gauche = "";
	    $droite = "";
		$compteur = 0;
	    $result = mysqli_query($dbi, "select * from ".$prefix."_pages_categories where description = 'accueil'");

	    if (sql_num_rows($result)> 0 AND sql_num_rows(mysqli_query($dbi, "select * from ".$prefix."_pages WHERE cid!='0'"))> 0) {

	        while(list($cid, $title, $description) = mysqli_fetch_row($result)) {
    	        if (sql_num_rows(mysqli_query($dbi, "select * from ".$prefix."_pages WHERE cid='$cid'"))> 0) {
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
	}

	print ($c);
}

function list_pages_categories($cid) {
    global $prefix, $dbi, $sitename, $admin, $multilingual, $module_name,$titre;
	$titre = "$sitename: "._PAGESLIST;
    include("header.php");

    $result = mysqli_query($dbi, "select title from ".$prefix."_pages_categories where cid ='$cid'");
	list($title) = mysqli_fetch_row($result);
    title($title);
	//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
   	// opentable();
	//    echo "<center><font class=\"content\">"._LISTOFCONTENT." $sitename:</center><br><br>";
    $result = mysqli_query($dbi, "SELECT pid, title, subtitle, clanguage from ".$prefix."_pages WHERE active='1' AND cid='$cid' order by date");
    echo "<blockquote><font class=\"content\">";

    while(list($pid, $title, $subtitle, $clanguage) = mysqli_fetch_row($result)) {
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
            echo "<img src=\"themes/Clean/img/point_bleu.gif\" width=\"12\" height=\"10\" border=\"0\" align=\"absbottom\"> $the_lang <a href=\"modules.php?name=$module_name&amp;pa=showpage&amp;pid=$pid\">$title</a> $subtitle [ <a href=\"admin.php?op=content_edit&pid=$pid\">"._EDIT."</a> | <a href=\"admin.php?op=content_change_status&pid=$pid&active=1\">"._DEACTIVATE."</a> | <a href=\"admin.php?op=content_delete&pid=$pid\">"._DELETE."</a> ]<br>";
        } else {
            echo "<img src=\"themes/Clean/img/point_bleu.gif\" width=\"12\" height=\"10\" border=\"0\" align=\"absbottom\"> $the_lang <a href=\"modules.php?name=$module_name&amp;pa=showpage&amp;pid=$pid\">$title</a>$subtitle<br>";
        }
    }
    echo "</blockquote>";
    if (is_admin($admin)) {
        $result = mysqli_query($dbi, "SELECT pid, title, subtitle, clanguage from ".$prefix."_pages WHERE active='0' and cid='$cid' order by date");
        echo "<br><br><center><b>"._YOURADMINLIST."</b></center><br><br>";
        echo "<blockquote>";

        while(list($pid, $title, $subtitle, $clanguage) = mysqli_fetch_row($result)) {
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
