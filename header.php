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

if (eregi("header.php",$PHP_SELF)) {
    Header("Location: index.php");
    die();
}

require_once("mainfile.php");

##################################################
# Include some common header for HTML generation #
##################################################

$header = 1;

function head() {
    global $theme,$slogan, $sitename, $banners, $Default_Theme, $nukeurl, $Version_Num, $artpage, $topic, $hlpfile,
	 $user, $hr, $theme, $cookie, $bgcolor1, $bgcolor2, $bgcolor3, $bgcolor4, $textcolor1, $textcolor2, $forumpage,
	 $adminpage, $userpage, $parcourspage, $titre,$auteur,$colispage, $texteAide, $titre , $wysiwyg, $userColis;
    if(is_user($user)) {
	$user2 = base64_decode($user);
	$cookie = explode(":", $user2);
	if($cookie[9]=="") $cookie[9]=$Default_Theme;
	if(isset($theme)) $cookie[9]=$theme;
	if(!$file=@opendir("themes/$cookie[9]")) {
	    $ThemeSel = $Default_Theme;
	} else {
	    $ThemeSel = $cookie[9];
	}
    } else {
	$ThemeSel = $Default_Theme;
    }
//    include("themes/$ThemeSel/theme.php");
    include("themes/$theme/theme.php");
	
    echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">
	<html>\n";
    echo "<head>\n";
    echo "<title>$sitename - $titre</title>\n";

    include("includes/meta.php");         //Inclusion des meta tags
    include("includes/javascript.php");   //Inclusion Javascript
    
    echo "<LINK REL=\"StyleSheet\" HREF=\"themes/$theme/css/style.css\" TYPE=\"text/css\">\n";
//    echo "<LINK REL=\"StyleSheet\" HREF=\"colisciences.css\" TYPE=\"text/css\">\n\n";
    include("includes/my_header.php");
    echo "</head>
	<BODY>";
	
	if (isset($titre)){
		$tmpTitre = "<table width=\"100%\" height=\"20\" cellPadding=0 cellSpacing=0 class=\"boxcontent\" >
		<tr>
			<td><a href=\"modules.php?name=Your_Account\"><img src=\"themes/$theme/img/connect.gif\" width=\"59\" height=\"17\">id:$userColis</a></td>
			<td align=\"center\">$titre&nbsp;&nbsp;<strong>$auteur</strong></td>
			<td><img src=\"themes/$theme/img/spacer.gif\" width=\"59\" height=\"17\"></td>
		</tr>
		</table>";
		if ($texteAide==""){
			echo $tmpTitre;
		}else{
			afficheShowHide("AIDE",$tmpTitre, $texteAide);
		}
	}
    themeheader();
}

head();
include("includes/counter.php");
online();

?>