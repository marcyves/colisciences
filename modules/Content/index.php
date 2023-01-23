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

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);

function showpage($pid, $page=0) {
    global $theme,$prefix, $dbi, $sitename, $admin, $module_name;
    include("header.php");
    opentable();
    $result = mysqli_query($dbi, "SELECT * from ".$prefix."_pages where pid='$pid'");
    $mypage = mysqli_fetch_array($result);
    if (($mypage["active"] == 0) AND (!is_admin($admin))) {
        echo "Sorry... This page doesn't exist.";
    } else {
        mysqli_query($dbi, "update ".$prefix."_pages set counter=counter+1 where pid='$pid'");
        $date = explode(" ", $mypage["date"]);
        echo "<font class=\"title\">$mypage[title]</font><br>"
            ."<font class=\"content\">$mypage[subtitle]<br>
<br>";
        $contentpages = explode( "<!--pagebreak-->", $mypage["text"] );
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
            echo "<p align=\"justify\">".nl2br($mypage["page_header"])."</p><br>";
        }
        echo "<p align=\"justify\">$contentpages[$arrayelement]</p>";
        if($page>= $pageno) {
            $next_page = "";
        } else {
            $next_pagenumber = $page + 1;
            if ($page != 1) {
                $next_page .= "- ";
            }
            $next_page .= "<a href=\"modules.php?name=$module_name&pa=showpage&pid=$pid&page=$next_pagenumber\">"._NEXT." ($next_pagenumber/$pageno)</a> <a href=\"modules.php?name=$module_name&pa=showpage&pid=$pid&page=$next_pagenumber\"><img src=\"themes/$theme/img/right.gif\" border=\"0\" alt=\""._NEXT."\"></a>";
        }
        if ($page == $pageno) {
            echo "<br><p align=\"justify\">".nl2br($mypage["page_footer"])."</p><br><br>";
        }
        if($page <= 1) {
            $previous_page = "";
        } else {
            $previous_pagenumber = $page - 1;
            $previous_page = "<a href=\"modules.php?name=$module_name&pa=showpage&pid=$pid&page=$previous_pagenumber\"><img src=\"themes/$theme/img/left.gif\" border=\"0\" alt=\""._PREVIOUS."\"></a> <a href=\"modules.php?name=$module_name&pa=showpage&pid=$pid&page=$previous_pagenumber\">"._PREVIOUS." ($previous_pagenumber/$pageno)</a>";
        }
        echo "<br>
<center>$previous_page $next_page</center><br><br>";
        if ($page == $pageno) {
            echo "<p align=\"right\">".nl2br($mypage["signature"])."</p>"
                ."<p align=\"right\">"._COPYRIGHT."</p><br><br></font>"
                ."<p align=\"right\"><font class=\"tiny\">"._PUBLISHEDON.": $date[0] ($mypage[counter] "._READS.")</font></p>"
                ."<center>"._GOBACK."</center>";
        }
    }
    CloseTable();
    include("footer.php");
}

function list_pages() {
    global $theme,$prefix, $dbi, $sitename, $admin, $multilingual, $module_name;

    include("headerMain.php");

    $gauche = "";
	$droite = "";

    $result = mysqli_query($dbi, "SELECT * from ".$prefix."_pages where title='Annonce'");
    $mypage = mysqli_fetch_array($result);
    if (($mypage["active"] == 0) AND (is_admin($admin))) {
	    opentable();
        echo "Pas de page d'annonce.";
	    CloseTable();
		echo "<br>";
    } else if ($mypage["active"] != 0){
	include ("includes/swap_img.js");	

	    opentable();
        mysqli_query($dbi, "update ".$prefix."_pages set counter=counter+1 where pid='$pid'");
        $date = explode(" ", $mypage["date"]);
 echo "
	<div id=\"acces\" style=\"position:absolute; width:200px; height:115px; z-index:1; top: 220px;\"><img src=\"themes/$theme/img/acces_2.gif\" name=\"acces\" width=\"418\" height=\"215\" border=\"0\" usemap=\"#accesMap\" id=\"acces\">
	<map name=\"accesMap\">
  <area shape=\"rect\" coords=\"91,107,333,130\" href=\"parcours.php?name=Bibliotheque\" onMouseOver=\"MM_swapImage('acces','','themes/$theme/img/acces_2i.gif',1)\" onMouseOut=\"MM_swapImgRestore()\">
</map>
	    <div id=\"acteur\" style=\"position:absolute; width:80px; height:35px; z-index:4; left: 10px; top: 164px;\"><a href=\"modules.php?name=accueil&amp;pa=list_pages_categories&amp;cid=6\"><img src=\"themes/$theme/img/acces_22.gif\" name=\"acteur\" width=\"76\" height=\"29\" border=\"0\" id=\"acteur\" onMouseOver=\"MM_swapImage('acteur','','themes/$theme/img/acces_22i.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></div>
	    <div id=\"emploi\" style=\"position:absolute; width:70px; height:40px; z-index:5; left: 308px; top: 172px;\"><a href=\"modules.php?name=accueil&amp;pa=list_pages_categories&amp;cid=10\"><img src=\"themes/$theme/img/acces_23.gif\" name=\"mode\" width=\"58\" height=\"31\" border=\"0\" id=\"mode\" onMouseOver=\"MM_swapImage('mode','','themes/$theme/img/acces_23i.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></div>
	    <div id=\"projet\" style=\"position:absolute; width:99px; height:32px; z-index:3; left: 321px; top: 3px;\"><a href=\"modules.php?name=accueil&amp;pa=list_pages_categories&amp;cid=1\"><img src=\"themes/$theme/img/acces_21.gif\" name=\"projet\" width=\"87\" height=\"30\" border=\"0\" id=\"projet\" onMouseOver=\"MM_swapImage('projet','','themes/$theme/img/acces_21i.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></div>
	    <div id=\"site\" style=\"position:absolute; width:77px; height:33px; z-index:2; left: 42px; top: 2px;\"><a href=\"modules.php?name=accueil&amp;pa=list_pages_categories&amp;cid=3\"><img src=\"themes/$theme/img/acces_20.gif\" name=\"pres_site\" width=\"88\" height=\"30\" border=\"0\" id=\"pres_site\" onMouseOver=\"MM_swapImage('pres_site','','themes/$theme/img/acces_20i.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></div>
	</div>
         <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
         <p><img src=\"themes/$theme/img/acces_0.gif\" name=\"largeur\" width=\"418\" height=\"8\" id=\"largeur\"></p>
	<p>&nbsp;</p><p>&nbsp;</p>
	  <p><a href=\"http://www.colisciences.net/modules.php?name=accueil&pa=showpage&pid=62\"><img src=\"themes/$theme/img/pdfgift.gif\" title=\"Liste des paratextes &agrave; t&eacute;l&eacute;charger en PDF\"></a> <a href=\"http://www.colisciences.net/parcours.php?name=Parcours_Hypertexte&file=moteurCB&ouvrage=18&parcours=signets&valeur=1\"><img src=\"themes/$theme/img/grossegorge.gif\" title=\"Acc&eacute;s &agrave; un choix d'illustrations\"></a></p>
				</td>";
              
        echo "<td><font class=\"content\"><b>$mypage[subtitle]</b><br>";
  /*      echo "<p align=\"justify\">".nl2br($mypage[page_header])."</p>";*/
        echo "<p align=\"justify\">$mypage[text]</p>";
        echo "<br><p align=\"justify\">".nl2br($mypage["page_footer"])."</p>";
        echo "<p align=\"right\"><font class=\"tiny\">"._PUBLISHEDON.": $date[0]</font></p>";
			
		
    	CloseTable();
		echo "<br>";
    }

/* fin modif page annonce */

	    
    CloseTable();
    include("footerMain.php");
}

function list_pages_categories($cid) {
    global $prefix, $dbi, $sitename, $admin, $multilingual, $module_name,$titre;
	$titre = "$sitename: "._PAGESLIST;
    include("header.php");

    $result = mysqli_query($dbi, "select title from ".$prefix."_pages_categories where cid ='$cid'");
	list($title) = mysqli_fetch_row($result);
    title($title);
	
    opentable();
//    echo "<center><font class=\"content\">"._LISTOFCONTENT." $sitename:</center><br><br>";
    $result = mysqli_query($dbi, "SELECT pid, title, subtitle, clanguage from ".$prefix."_pages WHERE active='1' AND cid='$cid' order by date");
    echo "<blockquote>";

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
            echo "<strong><big>&middot;</big></strong> $the_lang <a href=\"modules.php?name=$module_name&amp;pa=showpage&amp;pid=$pid\">$title</a> $subtitle [ <a href=\"admin.php?op=content_edit&pid=$pid\">"._EDIT."</a> | <a href=\"admin.php?op=content_change_status&pid=$pid&active=1\">"._DEACTIVATE."</a> | <a href=\"admin.php?op=content_delete&pid=$pid\">"._DELETE."</a> ]<br>";
        } else {
            echo "<strong><big>&middot;</big></strong> $the_lang <a href=\"modules.php?name=$module_name&amp;pa=showpage&amp;pid=$pid\">$title</a> $subtitle<br>";
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

list_pages();

/* switch($pa) {

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
 */
