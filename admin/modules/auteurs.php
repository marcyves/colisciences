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

$result = mysqli_query($dbi, "select radmincontent, radminsuper from ".$prefix."_authors where aid='$aid'");
list($radmincontent, $radminsuper) = mysqli_fetch_row($result);
if (($radmincontent==1) OR ($radminsuper==1)) {

######################################################################
# Spécifique CoLiSciences
######################################################################
//$colisroot = "/var/www/html/Colis/";

/*********************************************************/
/* Sections Manager Functions                            */
/*********************************************************/

function auteurs() {
    global $prefix, $dbi, $language, $multilingual, $bgcolor2;

    include("header.php");
    GraphicAdmin();
    title("Gestion des Auteurs des Ouvrages du Corpus");

    OpenTable();	
	//action=\"admin.php\"
    echo "\n<form  method=\"post\" enctype=\"multipart/form-data\">
<table border=\"0\" width=\"100%\">
<tr><td colspan=\"3\">	
	<button type=\"submit\" name=\"op\" value=\"fichiers_auteur\">Afficher tous les fichiers</button>
</td></tr>
<tr><td colspan=\"2\">
	Transférer un fichier:
	<td>
	<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"30000\" />
	<input type=\"file\" name=\"userfile\" size=\"55\" >\n",
	"<input type=\"submit\" value=\"Transférer\">
    <input type=\"hidden\" name=\"op\" value=\"auteur_upload\">
Commentaire (html)ou Image (jpg)",
"</td></tr>
<tr><td width=\"2%\"></td><td></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>Nom et prénom</b></td></tr>";

    $result0 = mysqli_query($dbi, "select aid, nom, prenom from cb_auteurs order by nom, prenom");
    while($mypages0 = mysqli_fetch_array($result0)) {
		echo "<tr>
			<td><input type=\"radio\" name=\"auteurId\" value=\"$mypages0[aid]\">
			<td bgcolor=\"#AAAAAA\" colspan='5'>$mypages0[prenom] $mypages0[nom]</td></tr>";
	}
    echo "</form>
	</table>";
    CloseTable();
    echo "<br>";

    OpenTable();
    echo "<center><b>Ajouter un nouvel auteur</b></center><br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<input type=\"hidden\" name=\"op\" value=\"add_auteur\">"
	." <b>Nom:</b> <input type=\"text\" name=\"nom\" size=\"30\">"
	." <b>Prénom:</b> <input type=\"text\" name=\"prenom\" size=\"30\"><br><br>"
	." <input type=\"submit\" value=\""._ADD."\">"
	."</form>";
    CloseTable();
    echo "<br>";

    include("footer.php");
}

// Formulaire d'édition des caractéristiques d'un auteur

function displayForm($caption, $title, $auteur, $active, $debut, $nombre_pages,$nombre_noeuds,$dossier,$signature,$texteActif,$notionActif, $facActif) {
    global $prefix, $dbi, $language, $multilingual, $bgcolor2;

    echo "<center><b>$caption</b></center><br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<table width=\"80%\" align=\"center\">"
	."<tr><td colspan=\"5\" align=\"center\"><b>"._TITLE.":</b> <input type=\"text\" name=\"titre\" value=\"$title\" size=\"50\">"
	."<tr><td colspan=\"3\"><b>"._AUTHOR."</b><br><select name=\"auteur\">";
    $result = mysqli_query($dbi, "select aid, nom, prenom from cb_auteurs order by nom, prenom");
	while (list($aid, $nom, $prenom) = mysqli_fetch_row($result)) {
		echo "<option value=\"$aid\"";
		if ($auteur==$aid) { echo "selected";}
		echo ">$prenom $nom</option>";
	}

	if ($active==1) {
		$sel1 = " checked";
	} else {
		$sel2 = " checked";
	}
	echo "<td colspan=\"2\" align=\"center\"><b>Rendre cet ouvrage actif?</b><br>"
	."<input type=\"radio\" name=\"active\" value=\"1\" $sel1>&nbsp;"._YES."&nbsp&nbsp;<input type=\"radio\" name=\"active\" value=\"0\" $sel2>&nbsp;"._NO."</td></tr>"
	."<tr><td><b>Premier paragraphe:</b><br><input type=\"text\" name=\"debut\" size=\"10\" value=\"$debut\"></td>"
	."    <td><b>Nombre de pages:</b><br><input type=\"text\" name=\"nombre_pages\" size=\"10\" value=\"$nombre_pages\"></td>"
	."    <td><b>Nombre de noeuds:</b><br><input type=\"text\" name=\"nombre_noeuds\" size=\"10\" value=\"$nombre_noeuds\"></td>"
	."    <td><b>Dossier de stockage:</b><br><input type=\"text\" name=\"dossier\" size=\"10\" value=\"$dossier\"></td><td>";
	echo "<b>Pages disponibles:</b><br><input type=\"checkbox\" name=\"texteActif\" value=\"1\"";
	if ($texteActif) echo "checked";
	echo ">Texte<br>"
	."<input type=\"checkbox\" name=\"notionActif\" value=\"1\"";
	if ($notionActif) echo "checked";
	echo ">Notions<br>"
	."<input type=\"checkbox\" name=\"facActif\" value=\"1\"";
	if ($facActif) echo "checked";
	echo ">Fac-similé<br>";
	if ($caption=="Ajouter un nouvel ouvrage"){
		echo "<input type=\"hidden\" name=\"op\" value=\"add_ouvrage\">"
		."<tr><td colspan=\"4\" align=\"center\"><input type=\"submit\" value=\""._ADD."\">";
	} else {
		echo "<input type=\"hidden\" name=\"op\" value=\"ouvrage_save_edit\">"
		."<tr><td colspan=\"4\"><b>"._SIGNATURE.":</b><br><input type=\"text\" name=\"signature\" size=\"30\" value=\"$signature\"><br><br>"
		."<tr><td colspan=\"4\" align=\"center\"><input type=\"submit\" value=\""._SAVECHANGES."\">";
	}
	echo "</table></form>";
}
function terms($eid) {
    global $module_name, $prefix, $sitename, $dbi, $admin;

	$result = mysqli_query($dbi, "select tid, title from ".$prefix."_encyclopedia_text WHERE eid='$eid'");
	while (list($tid, $title) = mysqli_fetch_row($result)) {
		echo "<option value=\"$tid\">$title</option>";
	}
}

function auteur_upload($pid){
    global $dbi,$webroot;

    $file = $_FILES['userfile']['tmp_name'];
    $erreur = $_FILES['userfile']['error'];
    $file_type  =  $_FILES['userfile']['type'];
    
    include("header.php");
   	GraphicAdmin();
	title(""._CONTENTMANAGER."");
   	OpenTable();

   	if ($erreur != "0") {
			echo "<center>Erreur $erreur pendant le transfert</center>";
	}else {
		if ($pid!=""){
    		$result = mysqli_query($dbi, "select Nom, Prenom from cb_auteurs where aid='$pid'");
			list($nom, $prenom) = mysqli_fetch_row($result);

			if ($file_type=="image/jpeg"){
				$ext = ".jpg";
			} else if ($file_type=="text/html"){
				$ext = ".html";
			} else {
				echo "<center><b>Transfert pour: $prenom $nom ($pid)</b><br>Type de fichier invalide</center>";
				$ext = "";
			}
			if ($ext!=""){
				$target = $webroot."auteurs/".$prenom."_".$nom.$ext;
				$target = str_replace(" ","_",$target);
				echo "<center><b>Transfert pour: $prenom $nom ($pid)</b><br>Fichier créé : ".$target."</center>";	

				/* COPY THE FILE TO THE DESIRED DESTINATION */	
				copy ($file, $target);
			}
		}else{
			echo "<p>Il faut sélectionner un auteur";
		}
	}
	CloseTable();
   	include("footer.php");
}

/**
 * add_auteur()
 * 
 * @param $nom
 * @param $prenom
 * @return 
 */
function add_auteur($nom, $prenom) {
    global $dbi;

    mysqli_query($dbi, "insert into cb_auteurs values (NULL, '$nom', '$prenom')");
    Header("Location: admin.php?op=auteurs");
}

/**
 * auteur_delete()
 * 
 * @param $aid
 * @return 
 */
function auteur_delete($aid) {
    global $dbi;
    mysqli_query($dbi, "delete from cb_auteurs where aid='$aid'");
    Header("Location: admin.php?op=auteurs");
}

function fichiers_auteur($auteur,$fichier,$action,$newfile){
	global $webroot, $op, $save,$code;
	
    include("header.php");
    GraphicAdmin();
    OpenTable();

    $baseDir = $webroot."auteurs/";

	echo "<h2>Les fichiers de commentaires sur les auteurs</h2>";
	require_once "fonctions.dir.inc";
    CloseTable();

    include("footer.php");

}

//print_r($HTTP_POST_VARS );

switch ($op) {
    case "auteurs":
	    auteurs();
    break;
    case "add_auteur":
    	add_auteur($nom, $prenom);
    break;
    case "auteur_edit":
    	auteur_edit($nom, $prenom);
    break;
    case "auteur_delete":
    	auteur_delete($auteurId);
    break;
    case "auteur_upload":
    	auteur_upload($auteurId);
    break;
    case "fichiers_auteur":
    	fichiers_auteur($pid,$fichier,$action,$newfile);
    break;
}

} else {
    echo "Access Denied";
}

?>
