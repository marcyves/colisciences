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

	$main_module = "Parcours_Hypertexte";
    
	/* If the module doesn't exist, it will be removed from the database automaticaly */

	$result = mysqli_query($dbi, "select title from ".$prefix."_modules_parcours");
	while (list($title) = mysqli_fetch_row($result)) {
		$a = 0;
		$handle=opendir('parcours');
		while ($file = readdir($handle)) {
			if ($file == $title) {
				$a = 1;
			}
		}
		closedir($handle);
		if ($a == 0) {
			mysqli_query($dbi, "delete from ".$prefix."_modules_parcours where title='$title'");
		}
	}

	/* Now we make the Modules block with the correspondent links */

	$content = themeMenuOpen().themeMenuLien("<a href=\"index.php\">Accueil</a>");
	$result = mysqli_query($dbi, "select title, custom_title from ".$prefix."_modules_parcours where active='1' ORDER BY mid ASC");
	while(list($m_title, $custom_title) = mysqli_fetch_row($result)) {
	    $m_title2 = str_replace("_", " ", $m_title);
    	if ($custom_title != "") {
      		$m_title2 = $custom_title;
    	}
    	if ($m_title != $main_module) {
			$content .= themeMenuLien("<a href=\"parcours.php?name=$m_title\">$m_title2</a>");
		}
	}

	$content .= themeMenuClose();

    /* If you're Admin you and only you can see Inactive modules and test it */
    /* If you copied a new module is the /modules/ directory, it will be added to the database */
    
    if (is_admin($admin)) {
        $handle=opendir('parcours');
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
                $result = mysqli_query($dbi, "select mid from ".$prefix."_modules_parcours where title='$modlist[$i]'");
                list ($mid) = mysqli_fetch_row($result);
                if ($mid == "") {
                    mysqli_query($dbi, "insert into ".$prefix."_modules_parcours values (NULL, '$modlist[$i]', '', '0', '0')");
                }
            }
        }
	$content .= "<div align=\"center\">Modules inactifs";

	
	$tmp = themeboxgauche();

  $result = mysqli_query($dbi, "select title, custom_title from ".$prefix."_modules_parcours where active='0' ORDER BY title ASC");
	$count = 0;
	while(list($m_title, $custom_title) = mysqli_fetch_row($result)) {
    $m_title2 = ereg_replace("_", " ", $m_title);
    if ($custom_title != "") {
      $m_title2 = $custom_title;
    }
    if ($m_title != $main_module) {
			$tmp .= themeMenuLien("<a href=\"parcours.php?name=$m_title\">$m_title2</a>");
			$count++;
		}
	}
	$content .= themeMenuOpen();
	$content .= $tmp;
  $content .= themeboxdroite();
	$content .= themeMenuClose();
	
  $content .= "</div>";
    }

?>
