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

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }
$result = sql_query("select radmincontent, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
list($radmincontent, $radminsuper) = sql_fetch_row($result, $dbi);
if (($radmincontent==1) OR ($radminsuper==1)) {

######################################################################
# Spécifique CoLiSciences
######################################################################
//$colisroot = "/var/www/html/Colis/";

/*********************************************************/
/* Sections Manager Functions                            */
/*********************************************************/

function ouvrages() {
    global $prefix, $dbi, $language, $multilingual, $bgcolor2;

    include("header.php");
    GraphicAdmin();
    title("Gestion des Ouvrages en ligne");
    OpenTable();	
	//action=\"admin.php\"
    echo "\n<form  method=\"post\" enctype=\"multipart/form-data\">
<table border=\"0\" width=\"100%\">
<tr><td colspan=\"4\">	
	<button type=\"submit\" name=\"op\" value=\"ouvrage_edit\">Modifier</button>
	<button type=\"submit\" name=\"op\" value=\"fichiers_ouvrage\">Afficher les fichiers</button>
	<button type=\"submit\" name=\"op\" value=\"ouvrage_change_status\">Activer/désactiver</button>
	<button type=\"submit\" name=\"op\" value=\"ouvrage_delete\">Effacer</button>
</td></tr>
<tr><td colspan=\"4\">
	Transférer le fichier de commentaires(html): <input type=\"file\" name=\"file\" size=\"35\" accept=\"text/html\">\n",
	"<button type=\"submit\" name=\"op\" value=\"ouvrage_upload_comment\">Transférer</button>",
"</td></tr>
<tr><td bgcolor=\"$bgcolor2\">
	<b>"._TITLE."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._CURRENTSTATUS."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>Nombre de pages</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>Nombre de noeuds</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._FUNCTIONS."</b></td></tr>";

    $result0 = sql_query("select aid, nom, prenom from cb_auteurs order by nom, prenom", $dbi);
    while($mypages0 = sql_fetch_array($result0, $dbi)) {
		echo "
		<tr><td bgcolor=\"#AAAAAA\" colspan='5'>$mypages0[prenom] $mypages0[nom]</td></tr>";
	    $result = sql_query("select * from cb_ouvrages where auteur='$mypages0[aid]' order by active,auteur,titre", $dbi);
	    while($mypages = sql_fetch_array($result, $dbi)) {
			if ($mypages[active] == 1) {
	    		$status = _ACTIVE;
			    $status_chng = _DEACTIVATE;
	    		$active = 1;
			} else {
	    		$status = "<i>"._INACTIVE."</i>";
			    $status_chng = _ACTIVATE;
	    		$active = 0;
			}
			echo "<tr>
			<td><input type=\"radio\" name=\"pid\" value=\"$mypages[pid]\">
			<td>$mypages[titre]</td><td align=\"center\">$status</td>
			<td align=\"center\">$mypages[nombre_pages]</td>
			<td align=\"center\">$mypages[nombre_noeuds]</td>
</tr>";
/*
			<td align=\"center\">[ <a href=\"admin.php?op=ouvrage_edit&pid=$mypages[pid]\">Modifier</a>
								 | <a href=\"admin.php?op=fichiers_ouvrage&pid=$mypages[pid]\">Voir les fichiers</a>
								 | <a href=\"admin.php?op=ouvrage_change_status&pid=$mypages[pid]&active=$active\">$status_chng</a>
								 | <a href=\"admin.php?op=ouvrage_delete&pid=$mypages[pid]\">"._DELETE."</a> ]</td>
*/ 
    	}
	}
    echo "</form>
	</table>";
    CloseTable();
    echo "<br>";

    OpenTable();
	displayForm("Ajouter un nouvel ouvrage", "","","","","","","","","","","","");
    CloseTable();
    echo "<br>";

    OpenTable();
	echo '
<p align=center><b>Ajouter/Modifier disciplines et thématiques d\'un ouvrage</b></p>
<form action="admin.php" method="post" enctype="multipart/form-data">';

    $rescat = sql_query("select pid, titre from cb_ouvrages order by titre", $dbi);
    if (sql_num_rows($rescat, $dbi) > 0) {
		echo "<select name=\"pid\">";
		while (list($pid, $titre) = sql_fetch_row($rescat, $dbi)) {
	    	echo "<option value=\"$pid\">$titre</option>";
		}
		echo "</select>";
	}
	echo '
<input type="hidden" name="op" value="ouvrage_discipline"><br>
<input type="submit" value="Modifier">
</form>';
    CloseTable();
    echo "<br>";

    OpenTable();
	echo '
<p align=center><b>Télécharger le fichier XML d\'un ouvrage</b></p>
<form action="admin.php" method="post" enctype="multipart/form-data">';

    $rescat = sql_query("select pid, titre from cb_ouvrages order by titre", $dbi);
    if (sql_num_rows($rescat, $dbi) > 0) {
		echo "<select name=\"pid\">";
		while (list($pid, $titre) = sql_fetch_row($rescat, $dbi)) {
	    	echo "<option value=\"$pid\">$titre</option>";
		}
		echo "</select>";
	}
	echo '
<INPUT NAME="userfile" TYPE="file">
<input type="checkbox" name="debug" value="1">Verbose
<input type="hidden" name="op" value="upload_ouvrage_XML"><br>
<input type="submit" value="transférer">
</form>';
    CloseTable();
    echo "<br>";

    OpenTable();
	echo '
<p align=center><b>Télécharger la liste des notions d\'un ouvrage</b></p>
<form action="admin.php" method="post" enctype="multipart/form-data">';

    $rescat = sql_query("select pid, titre from cb_ouvrages order by titre", $dbi);
    if (sql_num_rows($rescat, $dbi) > 0) {
		echo "<select name=\"pid\">";
		while (list($pid, $titre) = sql_fetch_row($rescat, $dbi)) {
	    	echo "<option value=\"$pid\">$titre</option>";
		}
		echo "</select>";
	}
	echo '
<INPUT NAME="userfile" TYPE="file">
<input type="hidden" name="op" value="upload_notions_ouvrage"><br>
<input type="submit" value="transférer">
</form>';
    CloseTable();
    echo "<br>";

    OpenTable();
	echo '
<p align=center><b>Télécharger les fichiers image ou xml d\'un ouvrage dans un ZIP</b></p>
<form action="admin.php" method="post" enctype="multipart/form-data">';

    $rescat = sql_query("select pid, titre from cb_ouvrages order by titre", $dbi);
    if (sql_num_rows($rescat, $dbi) > 0) {
		echo "<select name=\"pid\">";
		while (list($pid, $titre) = sql_fetch_row($rescat, $dbi)) {
	    	echo "<option value=\"$pid\">$titre</option>";
		}
		echo "</select>";
	}
	echo '<INPUT NAME="userfile" TYPE="file"><input type="hidden" name="op" value="upload_ouvrage"><br><input type="submit" value="transférer"></form>';
    CloseTable();

    include("footer.php");
}

// Formulaire d'édition des caractéristiques d'un ouvrage

function displayForm($caption, $title, $auteur, $active, $debut, $nombre_pages,$nombre_noeuds,$dossier,$signature,$texteActif,$notionActif, $facActif) {
    global $prefix, $dbi, $language, $multilingual, $bgcolor2;

    echo "<center><b>$caption</b></center><br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<table width=\"80%\" align=\"center\">"
	."<tr><td colspan=\"5\" align=\"center\"><b>"._TITLE.":</b> <input type=\"text\" name=\"titre\" value=\"$title\" size=\"50\">"
	."<tr><td colspan=\"3\"><b>"._AUTHOR."</b><br><select name=\"auteur\">";
    $result = sql_query("select aid, nom, prenom from cb_auteurs order by nom, prenom", $dbi);
	while (list($aid, $nom, $prenom) = sql_fetch_row($result, $dbi)) {
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

	$result = sql_query("select tid, title from ".$prefix."_encyclopedia_text WHERE eid='$eid'", $dbi);
	while (list($tid, $title) = sql_fetch_row($result, $dbi)) {
		echo "<option value=\"$tid\">$title</option>";
	}
}

function fichiers_ouvrage($pid,$fichier,$action,$newfile){
	global $colisroot, $op, $save,$code;
	
    include("header.php");
    GraphicAdmin();
    OpenTable();
	if ($pid!=""){
	    $baseDir = $colisroot."$pid/";
    	$sql = sql_query("select titre, auteur from cb_ouvrages where pid=$pid",$dbi);
	    list($titre, $auteur) = sql_fetch_row($sql, $dbi);
    	$sql = sql_query("select Nom, Prenom from cb_auteurs where aid=$auteur",$dbi);
	    list($nom, $prenom) = sql_fetch_row($sql, $dbi);
		echo "<h2>$nom, $prenom<br>$titre</h2>";
		require_once "fonctions.dir.inc";
	}else{
		echo "<p>Il faut sélectionner un ouvrage";
	}
    CloseTable();

    include("footer.php");

}


function upload_notions_ouvrage($userfile, $nombre, $pid){
	global $colisroot, $baseDir;
	
	require_once "fonctions.xmlupload.inc";
	require_once "class.inc";
	
    include("header.php");
    GraphicAdmin();
    title("Transfert du fichier XML en cours...");

    $baseDir = "$colisroot$pid/";
	$userfile_name = "notions.liste";
    $file = $baseDir.$userfile_name;

    OpenTable();
	echo "<p>Transfert de $userfile vers $file";
    copy($userfile,$file);
    echo "<p>Le fichier $file a bien été transféré.";

    CloseTable();
    include("footer.php");

}

function upload_ouvrage_XML($userfile, $nombre, $pid){
	global $colisroot, $baseDir;
	
	require_once "fonctions.xmlupload.inc";
	require_once "class.inc";
	
    include("header.php");
    GraphicAdmin();
    title("Transfert du fichier XML en cours...");

    $baseDir = "$colisroot$pid/";
	$userfile_name = "fichier_base.xml";
    $file = $baseDir.$userfile_name;

    OpenTable();
	echo "<p>Transfert de $userfile vers $file";
    copy($userfile,$file);
    echo "<p>Le fichier $file a bien été transféré.<h2>Le traitement commence...</h2>";
	$titre  = "";
	$numero = "";
	$front  = "";
	$texteParagraphe = "";
	decodeDocument($file);
	CloseTable();
    include("footer.php");
}

function upload_ouvrage($dossier, $nombre, $pid){
	global $colisroot;

    include("header.php");
	include("pclzip/pclzip.lib.php");
	
    OpenTable();
    GraphicAdmin();
    title("Transfert du fichier ZIP en cours...");

    $baseDir = "$colisroot$pid/";

    OpenTable();
	echo "<p>Transfert de $userfile en cours.";
	
	$zipFile = new pclZip($userfile);
	/*** Check the zip content (real size and file extension) ***/
	$zipContentArray = $zipFile->listContent();
	foreach($zipContentArray as $thisContent)
	{
		if ( preg_match("/.php$/", $thisContent['filename']) )
		{
			echo "<p>Fichiers php interdits";
			break;
		}
		$realFileSize += $thisContent['size'];
	}
	/*** Uncompressing phase ***/
	if (PHP_OS == "Linux" && ! get_cfg_var("safe_mode")) 
	{	
	/*** Shell Method - if this is possible, this gains some speed ***/
		exec("unzip -d".$baseDir."/".$fileName." ".$HTTP_POST_FILES['userfile']['tmp_name']);
	}
	else
	{	
	/*** PHP method - slower... ***/
		chdir($baseWorkDir.$uploadPath);
		$unzippingSate = $zipFile->extract();
	}

    CloseTable();
    include("footer.php");

}

function add_ouvrage($dossier, $active, $auteur, $titre, $debut, $nombre_pages, $nombre_noeuds, $texteActif, $notionActif, $facActif) {
    global $prefix, $dbi, $aid;

    sql_query("insert into cb_ouvrages values ('$dossier', '$auteur', '$titre', '$debut','$nombre_noeuds', '$active','$aid', now(),'$texteActif','$notionActif','$facActif', '$nombre_pages')", $dbi);
	@mkdir ("/var/www/html/Colis/$dossier", 0700);
    Header("Location: admin.php?op=ouvrages");
}

function add_discipline($pid, $did) {
    global $prefix, $dbi, $aid;

    sql_query("insert into cb_disciplines values ('$pid', '$did')", $dbi);
    Header("Location: admin.php?op=ouvrage_discipline&pid=$pid");
}

function del_discipline($pid, $did) {
    global $prefix, $dbi;

    sql_query("delete from cb_disciplines where pid='$pid' and discipline='$did'", $dbi);
    Header("Location: admin.php?op=ouvrage_discipline&pid=$pid");
}

function add_domaine($pid, $did) {
    global $prefix, $dbi, $aid;

    sql_query("insert into cb_domaines values ('$pid', '$did')", $dbi);
    Header("Location: admin.php?op=ouvrage_discipline&pid=$pid");
}

function del_domaine($pid, $did) {
    global $prefix, $dbi;

//	echo "delete from cb_domaines where pid='$pid' and domaine='$did'";
	sql_query("delete from cb_domaines where pid='$pid' and domaine='$did'", $dbi);
    Header("Location: admin.php?op=ouvrage_discipline&pid=$pid");
}


function ouvrage_edit($pid) {
    global $prefix, $dbi, $language, $multilingual, $bgcolor2;

    include("header.php");
    GraphicAdmin();
    title(""._CONTENTMANAGER."");
    $result = sql_query("select * from cb_ouvrages WHERE pid='$pid'", $dbi);
    $mypages = sql_fetch_array($result, $dbi);

    OpenTable();
	displayForm(_EDITPAGECONTENT, $mypages[titre], $mypages[auteur] , $mypages[active],$mypages[debut],$mypages[nombre_pages],$mypages[nombre_noeuds],$mypages[pid], $mypages[signature], $mypages[texteActif], $mypages[notionActif], $mypages[facActif]);
    CloseTable();
    include("footer.php");
}

function ouvrage_discipline($pid) {
    global $prefix, $dbi, $language, $multilingual, $bgcolor2;

    include("header.php");
    GraphicAdmin();
    title("Modifier les disciplines et les thématiques d'un ouvrage");

    OpenTable();
    $result = sql_query("select titre from cb_ouvrages WHERE pid='$pid'", $dbi);
    list($ceTitre) = sql_fetch_row($result, $dbi);
	echo "<h2>$ceTitre</h2>";
// Les discliplines déjà enregistrées
    $result = sql_query("select title, discipline from cb_disciplines, nuke_encyclopedia_text WHERE pid='$pid' and discipline=tid", $dbi);
	echo "<h3>Disciplines</h3>
	<table align=\"center\" width=\"60%\" bgcolor=\"#CCDDDD\">
	<tr><td><h4>Supprimer</h4>
	<form action=\"admin.php\" method=\"post\">
	<input type=\"hidden\" name=\"op\" value=\"del_discipline\">
	<input type=\"hidden\" name=\"pid\" value=\"$pid\">
	";
    while (list($cetteDiscipline, $did) = sql_fetch_row($result, $dbi)){
		echo"<br><input type=\"radio\" name=\"did\" value=\"$did\"> ($did) - $cetteDiscipline";
	};
	echo "<br><input type=\"submit\" value=\"Supprimer\">
	</form></td>
	<td><h4>Ajouter</h4>
	<form action=\"admin.php\" method=\"post\">
	<input type=\"hidden\" name=\"op\" value=\"add_discipline\">
	<input type=\"hidden\" name=\"pid\" value=\"$pid\">
	<select name=\"did\">";
	terms(_DISCIPLINE);
//	echo "</select></td><td><b>Thématique:</b><br><select name=\"thematique\">";
//	echo "</select></td>";
	echo "</select>
	<input type=\"submit\" value=\"ajouter\"></form></td></tr></table>";
//Les domaines déjà enregistrés
    $result = sql_query("select title, domaine from cb_domaines, nuke_encyclopedia_text WHERE pid='$pid' and domaine=tid", $dbi);
	echo "<h3>Domaines</h3><table align=\"center\" width=\"60%\" bgcolor=\"#CCDDDD\"><tr><td><h4>Supprimer</h4>
	<form action=\"admin.php\" method=\"post\">
	<input type=\"hidden\" name=\"op\" value=\"del_domaine\">
	<input type=\"hidden\" name=\"pid\" value=\"$pid\">
	";
    while (list($ceDomaine, $did) = sql_fetch_row($result, $dbi)){
		echo"<br><input type=\"radio\" name=\"did\" value=\"$did\"> ($did) - $ceDomaine";
	};
	echo "<br><input type=\"submit\" value=\"Supprimer\">
	</form></td><td><h4>Ajouter</h4>
	<form action=\"admin.php\" method=\"post\">
	<input type=\"hidden\" name=\"op\" value=\"add_domaine\">
	<input type=\"hidden\" name=\"pid\" value=\"$pid\">
	<select name=\"did\">";
	terms(_DOMAINE);
	echo "</select>
	<input type=\"submit\" value=\"ajouter\"></form></td></tr></table>";

    CloseTable();
    include("footer.php");
}

function ouvrage_save_edit($pid, $titre, $auteur, $debut, $nombre_noeuds, $nombre_pages, $signature, $active, $texteActif, $notionActif, $facActif) {
    global $prefix, $dbi;
	$titre = addslashes($titre);
//debug echo "<h2>update cb_ouvrages set  titre='$titre', auteur='$auteur', active='$active', debut='$debut', nombre_noeuds='$nombre_noeuds', nombre_pages='$nombre_pages', signature='$signature' , texteActif='$texteActif', notionActif='$notionActif', facActif='$facActif' where pid='$pid'</h2>";
    sql_query("update cb_ouvrages set  titre='$titre', auteur='$auteur', active='$active', debut='$debut', nombre_noeuds='$nombre_noeuds', nombre_pages='$nombre_pages', signature='$signature' , texteActif='$texteActif', notionActif='$notionActif', facActif='$facActif' where pid='$pid'", $dbi);
    Header("Location: admin.php?op=ouvrages");
}

function ouvrage_change_status($pid, $active) {
    global $prefix, $dbi;
	
	if ($pid!=""){
	    if ($active == 1) {
			$new_active = 0;
	    } elseif ($active == 0) {
			$new_active = 1;
    	}
	    sql_query("update cb_ouvrages set active='$new_active' WHERE pid='$pid'", $dbi);
    	Header("Location: admin.php?op=ouvrages");
	}else{
	    include("header.php");
	    GraphicAdmin();
    	OpenTable();
		echo "<p>Il faut sélectionner un ouvrage";
		CloseTable();
	    include("footer.php");
	}
}

function ouvrage_delete($pid, $ok=0) {
    global $prefix, $dbi;

	if ($pid!=""){
	    if ($ok==1) {
    	    sql_query("delete from cb_ouvrages where pid='$pid'", $dbi);
        	Header("Location: admin.php?op=ouvrages");
	    } else {
    	    include("header.php");
        	GraphicAdmin();
			title(""._CONTENTMANAGER."");
			$result = sql_query("select titre from cb_ouvrages where pid='$pid'", $dbi);
			list($title) = sql_fetch_row($result, $dbi);
			OpenTable();
			echo "<center><b>"._DELCONTENT.": $title</b><br><br>"
	    	.""._DELCONTWARNING." $title?<br><br>"
		    ."[ <a href=\"admin.php?op=ouvrages\">"._NO."</a> | <a href=\"admin.php?op=ouvrage_delete&amp;pid=$pid&amp;ok=1\">"._YES."</a> ]</center>";
			CloseTable();
        	include("footer.php");
    	}
	}else{
	    include("header.php");
	    GraphicAdmin();
    	OpenTable();
		echo "<p>Il faut sélectionner un ouvrage";
		CloseTable();
	    include("footer.php");
	}
}

function ouvrage_upload_comment($pid, $file){
    global $prefix, $dbi,$colisroot;

	if ($pid!=""){
   	    include("header.php");
       	GraphicAdmin();
		title(""._CONTENTMANAGER."");
		$result = sql_query("select titre from cb_ouvrages where pid='$pid'", $dbi);
		list($title) = sql_fetch_row($result, $dbi);
		OpenTable();
		echo "<center><b>Transfert des commentaires pour: $title</b><br>$pid<br>$file to ".$colisroot."$pid/presentation.html"."</center>";

		/* COPY THE FILE TO THE DESIRED DESTINATION */
		copy ($file, $colisroot."$pid/presentation.html");
		CloseTable();
       	include("footer.php");
	}else{
	    include("header.php");
	    GraphicAdmin();
    	OpenTable();
		echo "<p>Il faut sélectionner un ouvrage";
		CloseTable();
	    include("footer.php");
	}

}

//print_r($HTTP_POST_VARS );

switch ($op) {

    case "ouvrages":
    ouvrages();
    break;

    case "fichiers_ouvrage":
    fichiers_ouvrage($pid,$fichier,$action,$newfile);
    break;

    case "upload_ouvrage":
    upload_ouvrage($dossier, $nombre, $pid);
    break;

    case "upload_ouvrage_XML":
    upload_ouvrage_XML($userfile, $nombre, $pid);
    break;

    case "upload_notions_ouvrage":
    upload_notions_ouvrage($userfile, $nombre, $pid);
    break;

    case "ouvrage_edit":
    ouvrage_edit($pid);
    break;

    case "ouvrage_discipline":
    ouvrage_discipline($pid);
    break;

    case "add_discipline":
    add_discipline($pid, $did);
    break;

    case "del_discipline":
    del_discipline($pid, $did);
    break;

    case "add_domaine":
    add_domaine($pid, $did);
    break;

    case "del_domaine":
    del_domaine($pid, $did);
    break;

    case "ouvrage_delete":
    ouvrage_delete($pid, $ok);
    break;

    case "ouvrage_review":
    content_review($title, $subtitle, $page_header, $text, $page_footer, $signature, $clanguage, $active);
    break;

    case "ouvrage_save_edit":
    ouvrage_save_edit($dossier, $titre, $auteur, $debut, $nombre_noeuds, $nombre_pages, $signature, $active, $texteActif, $notionActif, $facActif);
    break;

    case "ouvrage_change_status":
    ouvrage_change_status($pid, $active);
    break;

    case "add_ouvrage":
    add_ouvrage($dossier, $active, $auteur, $titre, $debut, $nombre_pages, $nombre_noeuds, $texteActif, $notionActif, $facActif);
    break;

    case "ouvrage_upload_comment":
    ouvrage_upload_comment($pid, $file);
    break;
}

} else {
    echo "Access Denied";
}

?>