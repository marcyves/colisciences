<?php

/******************************************************************************/
/*                                                                            */
/* module Bibliothèque pour PHP-NUKE: Web Portal System                       */
/*                                                                            */
/******************************************************************************/
/*                                                                            */
/* Requirements: PHP, MySQL and web-browser                                   */
/*                                                                            */
/* Author: Marc Augier                                                        */
/*         <marc.augier@cote-azur.cci.fr>                                     */
/*                                                                            */
/* Created: 29 March 2002                                                     */
/*                                                                            */
/* Copyright (c) 2002 LCP - MEDIATEC                                          */
/*                                                                            */
/* This file is part of phpCB (http://colisciences.net/)                      */
/*                                                                            */
/******************************************************************************/


require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);

if (isset($newetagere)) {
    	setcookie("etagere",$newetagere,time()+3600);
		$etagere = $newetagere;
}

function lienBiblio($aid,$categorie, $alt){
    global $theme,$prefix, $dbi;
    $sql = mysqli_query($dbi, "select Dates, Type, Titre, Compil, Lieu, EditeurRevue, Reference, Commentaires, Auteurs   from cb_biblio where Biblio='$aid' and categorie='$categorie' order by Dates");
	if (mysqli_num_rows($sql) == 0) {
		return "&nbsp;";
	} else {
		return "&nbsp;<a href=\"parcours.php?name=Bibliotheque&pa=biblio&id=$aid&categorie=$categorie\"><img src=\"themes/$theme/img/bilbio.gif\" alt=\"$alt\"></a>&nbsp;";	}

}

/**
 * showbiblio()
 * 
 * @param $id
 * @param $categorie
 * @return 
 */
function showbiblio($id, $categorie){
    global $theme,$prefix, $dbi, $sitename, $admin, $module_name,$colispage;

	switch ($categorie){
	case "bio":
	    $sql = mysqli_query($dbi, "select nom, prenom from cb_auteurs where aid='$id'");
   		list($nom, $prenom) = mysqli_fetch_row($sql);
 		echo "<font class=\"title4\">La bibliographie sur $prenom $nom</font>";
	break;
	case "biblio":
	    $sql = mysqli_query($dbi, "select nom, prenom from cb_auteurs where aid='$id'");
   		list($nom, $prenom) = mysqli_fetch_row($sql);
 		echo "<font class=\"title4\"> Les oeuvres de $prenom $nom</font>";
	break;
	case "discipline":
		$sql = mysqli_query($dbi, "select title from ".$prefix."_encyclopedia_text WHERE tid='$id' and eid='"._DISCIPLINE."'");
   		list($title) = mysqli_fetch_row($sql);
 		echo "<font class=\"title3\">La bibliographie sur $title (discipline)</font>";
	break;
	case "domaine":
		$sql = mysqli_query($dbi, "select title from ".$prefix."_encyclopedia_text WHERE tid='$id' and eid='"._DOMAINE."'");
   		list($title) = mysqli_fetch_row($sql);
 		echo "<font class=\"title3\">La bibliographie sur $title (domaine)</font>";
	break;
	}	
	if ($categorie=="bio"||$categorie=="biblio"){
		$sel = "auteurs";
	}else {
		$sel = $categorie;
	}

    $sql = mysqli_query($dbi, "select Dates, Type, Titre, Compil, Lieu, EditeurRevue, Reference, Commentaires, Auteurs   from cb_biblio where Biblio='$id' and categorie='$categorie' order by Dates");
echo "<br>";
	if (mysqli_num_rows($sql) == 0) {
	    echo "<center><i>Il n'y a pas d'entrée de bibliographie.</i></center>";
	}
	afficheEntreesBiblio($sql);
}

/**
 * afficheNomEtagere()
 * 
 * @param $etagere
 * @return 
 */
function afficheNomEtagere($etagere){
	global $dbi;
	if ($etagere!=0){
		$sql = mysqli_query($dbi, "select nom, description from cb_etagere where eid='$etagere'");
	    list($nom, $description) = mysqli_fetch_row($sql);
		echo "<h3>$nom</h3>$description
		<P class=\"menubar2\"><A href=\"parcours.php?name=Bibliotheque&newetagere=0\">Revenir à l'ensemble des ouvrages</A></P>";
	}else{
		echo "<b>Tous les ouvrages sont affichés</b> (Vous pouvez personnaliser l'affichage en sélectionnant <i>Mon CoLiSciences</i>).";
	}
	echo "<p>";
}

function rendre_public($eid){
	global $dbi;

	$sql = mysqli_query($dbi, "update cb_etagere set public='1' where eid='$eid'");
	$sql = mysqli_query($dbi, "select nom from cb_etagere where eid='$eid'");
	list($nom) = mysqli_fetch_row($sql);
	echo "<p><b>$nom</b> est maintenant visible de tous.";
}

function effacer($eid){
	global $dbi;

	echo "select nom from cb_etagere where eid='$eid'";
	
	$sql = mysqli_query($dbi, "select nom from cb_etagere where eid='$eid'");
	list($nom) = mysqli_fetch_row($sql);
//
//Il faut la vider !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//	$sql = mysqli_query($dbi, "select evid, ouvrage from cb_etagere_ouvrages where eid='$eid'");
//
//
	$sql = mysqli_query($dbi, "delete from cb_etagere where eid='$eid'");
	echo "<p><b>$nom</b> est maintenant effacée.";
}

function cacher($eid){
	global $dbi;

	$sql = mysqli_query($dbi, "update cb_etagere set public='0' where eid='$eid'");
	$sql = mysqli_query($dbi, "select nom from cb_etagere where eid='$eid'");
	list($nom) = mysqli_fetch_row($sql);
	echo "<p><b>$nom</b> est maintenant cachée.";
}

function creation_etageres(){
//<input type=\"text\" name=\"description_etagere\"><br>

	echo "<p>
<form name=\"RTEDemo\" method=\"post\" action=\"$PHP_SELF\" onsubmit=\"return submitForm();\">
Nom: <input type=\"text\" name=\"nom_etagere\"><br>
Description: ";
?>
<script language="JavaScript" type="text/javascript">
<!--
function submitForm() {
	updateRTEs();
	return true;
}

<? $description_etagere = RTESafe($description_etagere); ?>
initRTE("images/", "", "");

//Usage: writeRichText(fieldname, html, width, height, buttons)
writeRichText('description_etagere', '<? echo $description_etagere ?>', 800, 100, true, false);

//uncomment the following to see a demo of multiple RTEs on one page
//document.writeln('<br><br>');
//writeRichText('rte2', 'read-only text', 450, 100, true, false);
//-->
</script>
<noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>
<?php
//fin wysiwyg
echo "<input type=\"hidden\" name=\"pa\"  value=\"inserer_etagere\">
<input type=\"hidden\" name=\"name\"  value=\"Bibliotheque\">

<input type=\"submit\" Value=\"Créer\">
</form>";
}

function inserer_etagere($uid, $nom, $description){
	global $dbi;

	$sql = mysqli_query($dbi, "insert into cb_etagere values (NULL, '$uid', '$nom','$description','0','0')");
	echo "<p><b>$nom</b> est maintenant crée, vous pouvez commencer à y déposer des ouvrages.";

	$sql = mysqli_query($dbi, "select eid from cb_etagere where nom='$nom'");
	list($eid) = mysqli_fetch_row($sql);
	
	return $eid;
}

function modifier_ouvrages_etagere($uid, $eid){
	global $dbi;
	
	$sql = mysqli_query($dbi, "select pid, titre, debut, auteur, date_titre, type_book from cb_ouvrages where active='1' order by date_titre,titre");
   	while (list($pid, $titre, $debut, $auteur, $date_titre ,$type_book) = mysqli_fetch_row($sql)) {
		$titre = stripslashes($titre);
		if ($date_titre!="0"){
			$date_titre = "($date_titre)";
		}else{
			$date_titre = "";
		}
		$result = mysqli_query($dbi, "select evid from cb_etagere_ouvrage where eid='$eid' and ouvrage='$pid'");
		$count = mysqli_num_rows($result);
		if ($count>0){
			$selected = "checked";
		}else{
			$selected = "";
		}
    	$tmp[$auteur] .= "<li><input type=\"checkbox\" name=\"ouvrage_list['$pid']\" $selected>$titre $date_titre</li>";
   	}
	echo "<h3>Les ouvrages par auteur dans CoLiSciences</h3>Vous pouvez sélectionner les ouvrages que vous voulez voir apparaitre sur votre étagère dans la liste suivante.<br><form method=\"post\" action=\"$PHP_SELF\">";
	$sql = mysqli_query($dbi, "select aid, nom, prenom from cb_auteurs order by aid");
    while (list($aid, $nom, $prenom) = mysqli_fetch_row($sql)) {
//			$lienBiblio = lienBiblio($aid,"biblio","Sa bibliographie");
//			$lienBiblio .= lienBiblio($aid,"bio","Ses biographes");
//			afficheShowHide("AUT$aid",creeLienAuteur($aid, $nom, $prenom)."&nbsp;$lienBiblio", $debutForm.$tmp[$aid].$finForm);
		afficheShowHide("AUT$aid","$prenom $nom", $debutForm.$tmp[$aid].$finForm);
   	}
	echo "<input type=\"hidden\" name=\"pa\"  value=\"inserer_ouvrage_etagere\">
		<input type=\"hidden\" name=\"name\"  value=\"Bibliotheque\">
		<input type=\"submit\" Value=\"Modifier\">
		</form>";
}

function inserer_ouvrage_etagere($uid,$eid, $ouvrage_list){
	global $dbi;

	$sql = mysqli_query($dbi, "select nom from cb_etagere where eid='$eid'");
	list($nom) = mysqli_fetch_row($sql);
	
	echo "<h3>Insertion des ouvrages pour $nom</h3>";
	$sql = mysqli_query($dbi, "delete from cb_etagere_ouvrage where eid='$eid'");
	
	while (list($oid,$k)=each($ouvrage_list)){
		$sql = mysqli_query($dbi, "insert into cb_etagere_ouvrage values (NULL, '$eid', $oid)");
	}
}

function creeLienEtagere($eid,$nom,$edit,$public){
	if ($edit){
		$tmp = "<a href=\"parcours.php?name=Bibliotheque&eid=$eid&pa=modifier_ouvrages_etagere\"><img src=\"images/edit.gif\" alt=\"Modifier\"></a>
		 <a href=\"parcours.php?name=Bibliotheque&eid=$eid&pa=effacer\"><img src=\"images/delete.gif\" alt=\"Effacer\"></a>";
		if ($public==0){
			$tmp .= "<a href=\"parcours.php?name=Bibliotheque&eid=$eid&pa=rendre_public\"><img src=\"images/visi0.gif\" alt=\"Rendre public\"></a>";
		}else{
			$tmp .= "<a href=\"parcours.php?name=Bibliotheque&eid=$eid&pa=cacher\"><img src=\"images/visi9.gif\" alt=\"Cacher\"></a>";
		}
	}else{
		$tmp = "";
	}
	return "$tmp <a href=\"parcours.php?name=Bibliotheque&newetagere=$eid\">$nom</a>";
}

/**
 * etageres()
 * 
 * @return 
 */
function etageres($uid){
    global $theme,$prefix, $dbi, $sitename, $admin, $module_name,$colispage, $etagere, $webroot;

	echo "<h2>Personalisation</h2>
		Pour personaliser votre navigation à travers le corpus CoLiSciences, vous pouvez créer des \"étagères\" sur lesquelles vous déposerez les ouvrages sur lesquels vous voulez travailler.<br>
Pour travailler avec uniquement le contenu d'une étagère il vous suffit de cliquer sur son nom. Vous serez ensuite dirigé vers la liste des auteurs présents dans les ouvrages de l'étagère sélectionnée. Le tri par domaine ou discipline est toujours possible, bien entendu toujours réduit aux ouvrages de votre étagère.<br>
Les icones placées devant le nom d'une étagère donnent accès aux outils de gestion habituels: modification ou annulation, mais aussi la possibilité de rendre l'étagère visible de tous (accès public) et ainsi partager des thématiques.<p>";
		echo creeLienEtagere(0,"Tous les ouvrages",false,0)." permet de redonner accès à l'ensemble du corpus CoLiSciences.";
		if ($admin){
			echo "<h3>Vos étagères</h3>
			<ul>";
			$sql = mysqli_query($dbi, "select eid, nom, public from cb_etagere where uid='$uid'");
		    while (list($eid, $nom, $public) = mysqli_fetch_row($sql)){
				echo "<li>".creeLienEtagere($eid,$nom,true,$public)."</li>";
			}
			echo "<li><a href=\"$PHP_SELF?name=Bibliotheque&pa=creation_etagere\"><img src=\"\" alt=\"Créer une nouvelle étagère\"></a></li>";
			echo "</ul><p>";
		}else{
			echo "Vous devez être connecté pour pouvoir créer ou modifier vos étagères personnelles.";
		}
		echo "<h3>Les étagères publiques</h3>
		<ul>";
		$sql = mysqli_query($dbi, "select eid, nom from cb_etagere where public='1'");
	    while (list($eid, $nom) = mysqli_fetch_row($sql)){
			echo "<li>".creeLienEtagere($eid,$nom,false,$public);
		}
		echo "</ul><p>";
}

/**
 * auteurs()
 * 
 * @return 
 */
function auteurs(){
    global $theme,$prefix, $dbi, $sitename, $admin, $module_name,$colispage, $etagere, $webroot;

	echo "<h1>function auteurs</h1>";
    if ($etagere!=0){
		$where = "o, cb_etagere_ouvrage e where eid='$etagere' and pid=ouvrage and active='1'";
	}else{
		$where = "where active='1'";
	}
	$sql = mysqli_query($dbi, "select distinct pid, titre, debut, auteur, date_titre, type_book from cb_ouvrages $where order by date_titre,titre");
	$tmp[] = [];
   	while (list($pid, $titre, $debut, $auteur, $date_titre ,$type_book) = mysqli_fetch_row($sql)) {
		$titre = stripslashes($titre);
    	$tmp[$auteur] .= "<table><tr><td valign=\"top\"><img width=\"30\" height=\"12\"  src=\"themes/$theme/img/plot.gif\"></td><td>".creeLienOuvrage($pid,$titre, $debut, $date_titre,$type_book) ."</td></tr></table>";
   	}
	echo "<font class=\"title3\">Les ouvrages par auteur dans CoLiSciences</font><p>Pour d'autres informations sur un auteur, cliquer sur son nom.";
	$sql = mysqli_query($dbi, "select aid, nom, prenom from cb_auteurs order by nom");
    while (list($aid, $nom, $prenom) = mysqli_fetch_row($sql)) {
//			$lienBiblio = lienBiblio($aid,"biblio","Sa bibliographie");
//			$lienBiblio .= lienBiblio($aid,"bio","Ses biographes");
//			afficheShowHide("AUT$aid",creeLienAuteur($aid, $nom, $prenom)."&nbsp;$lienBiblio", $debutForm.$tmp[$aid].$finForm);
		afficheShowHide("AUT$aid",creeLienAuteur($aid, $nom, $prenom), $debutForm.$tmp[$aid].$finForm);
   	}
}

function detail_auteur($aid){
    global $theme,$prefix, $dbi, $sitename, $admin, $module_name,$colispage, $etagere, $webroot;

	// On récupère le nom et le prénom de l'auteur
	$sql = mysqli_query($dbi, "select nom, prenom from cb_auteurs where aid=$aid");
    list($nom, $prenom) = mysqli_fetch_row($sql);

	$fichierAuteur = $prenom."_".$nom.".";
	$fichierAuteur = str_replace(" ","_",$fichierAuteur);

	echo "<table>
		<tr><td rowspan=\"3\" valign=\"top\"><img src=\"auteurs/".$fichierAuteur."jpg\" alt=\"$prenom $nom\" width=\"200\"></td>
			<td valign=\"top\">";
	echo creelienBiblio($aid,"biblio","Bibliographie de $prenom $nom");
	echo creelienBiblio($aid,"bio","Bibliographie sur $prenom $nom");
	echo "</td></tr>
		<tr><td>";
	@include($webroot."auteurs/".$fichierAuteur."html");
/* ****************************************************************************************
* infos savant supprimées 
	echo "</td></tr>
		<tr><td>";
	// On retrouve ici l'entrée de l'encyclopédie 'savants cités' quand elle existe
    $result = mysqli_query($dbi, "select title, text from ".$prefix."_encyclopedia_text where title like '".strtoupper($nom)."' and eid='"._AUTEURS."'");
	//echo "select title, text from ".$prefix."_encyclopedia_text where title like '".strtoupper($nom)."' and eid='"._AUTEURS."'";
    if (list($title, $text) = mysqli_fetch_row($result)) {
		$text = autop($text);
		echo "<font class=\"title3\">Nous trouvons dans <a href=\"/parcours.php?name=Auteurs_cités\">L'encyclopédie des Savants</a> de CoLiSciences:</font><br>$text<br>";
	}
****************************************************************************************** */
	echo "</td></tr></table>";
		
	// On affiche les liens vers la biblio et la biographie
/*
 		$tmp .= "<br><font class=\"title3\">Les ouvrages de $prenom $nom</font><br>";

	    $sql = mysqli_query($dbi, "select pid,titre,debut, auteur, date_titre, type_book from cb_ouvrages where active='1' and auteur=$aid order by date_titre, titre");
    	while (list($pid, $titre, $debut, $auteur, $date_titre ,$type_book) = mysqli_fetch_row($sql)) {
 	    	$tmp .= "<table><tr><td valign=\"top\"><img width=\"30\" height=\"12\"  src=\"themes/$theme/img/plot.gif\"></td><td>".creeLienOuvrage($pid,$titre,$debut,$date_titre,$type_book) ."</td></tr></table>";
    	}
		*/ 
}

/**
 * discipline()
 * 
 * @return 
 */
function discipline(){
    global $theme,$prefix, $dbi, $sitename, $admin, $module_name,$colispage, $etagere, $webroot;

	if ($etagere!=0){
		$where = ", cb_etagere_ouvrage e where eid='$etagere' and pid=ouvrage and pid=did and active='1' and auteur=aid";
	}else{
		$where = "where pid=did and active='1' and auteur=aid";
	}
    $sql = mysqli_query($dbi, "select pid, titre, debut, auteur, date_titre, type_book, did, discipline, aid, nom, prenom  from cb_ouvrages, cb_disciplines, cb_auteurs  $where order by date_titre,titre");
   	while (list($pid, $titre, $debut, $auteur, $date_titre, $type_book, $did, $discipline , $aid, $nom, $prenom) = mysqli_fetch_row($sql)) {
		$titre = stripslashes($titre);
    	$tmp[$discipline] .= "<li>".creeLienAuteur($aid, $nom, $prenom)." : ".creeLienOuvrage($pid, $titre, $debut, $date_titre,$type_book);
   	}
	echo "<font class=\"title3\">Les ouvrages par discipline</font><p>Pour d'autres informations sur un auteur, cliquer sur son nom.";
	terms(_DISCIPLINE,$tmp, $selection);
}

/**
 * domaine()
 * 
 * @return 
 */
function domaine(){
    global $theme,$prefix, $dbi, $sitename, $admin, $module_name,$colispage, $etagere, $webroot;
	if ($etagere!=0){
		$where = ", cb_etagere_ouvrage e where eid='$etagere' and pid=ouvrage and pid=did and active='1' and auteur=aid";
	}else{
		$where = "where pid=did and active='1' and auteur=aid";
	}
    $sql = mysqli_query($dbi, "select pid, titre, debut, auteur, date_titre, type_book, did, domaine, aid, nom, prenom from cb_ouvrages, cb_domaines, cb_auteurs $where order by date_titre,titre");
   	while (list($pid, $titre, $debut, $auteur, $date_titre, $type_book, $did, $domaine, $aid, $nom, $prenom) = mysqli_fetch_row($sql)) {
		$titre = stripslashes($titre);
    	$tmp[$domaine] .= "<li>".creeLienAuteur($aid, $nom, $prenom)." : ".creeLienOuvrage($pid, $titre, $debut, $date_titre,$type_book); 
   	}
	echo "<font class=\"title3\">Les ouvrages par domaine</font><p>Pour d'autres informations sur un auteur, cliquer sur son nom.";
	terms(_DOMAINE,$tmp, $selection);
}

/**
 * notions()
 * 
 * @return 
 */
function notions(){
	echo "<font class=\"title3\">Les ouvrages par notion</font>";
	parcoursBox($num);
}

/* fonction avec icones des parcours disponibles
function creeLienOuvrage($ouvrage, $titre, $texteActif, $notionActif, $facActif, $date_titre, $type_book){
		if (($facActif == 1)||($texteActif == 1)||($notionActif == 1)) {
		    $tmp = "<a href=\"parcours.php?name=Parcours_Hypertexte&amp;file=moteurCB&amp;valeur=1&amp;newouvrage=$ouvrage\">";
		}else{
		    $tmp = "<div>";
		}
		if ($facActif == 1) {
			$tmp .= "<img src=\"images/fac.gif\" alt=\"Parcours fac-similé actif\" width=\"18\" height=\"18\"> ";
		} else {
			$tmp .= "<img src=\"images/facInactif.gif\" alt=\"Parcours fac-similé inactif\" width=\"18\" height=\"18\"> ";
		}
		if ($texteActif == 1) {
			$tmp .= "<img src=\"images/texte.gif\" alt=\"Parcours texte actif\" width=\"18\" height=\"18\"> ";
		} else {
			$tmp .= "<img src=\"images/texteInactif.gif\" alt=\"Parcours texte inactif\" width=\"18\" height=\"18\"> ";
		}
		if ($notionActif == 1) {
			$tmp .= "<img src=\"images/notion.gif\" alt=\"Parcours notionnel actif\" width=\"18\" height=\"18\"> ";
		} else {
			$tmp .= "<img src=\"images/notionInactif.gif\" alt=\"Parcours notionnel inactif\" width=\"18\" height=\"18\"> ";
		}
		if (($facActif == 1)&&($texteActif == 1)&&($notionActif == 1)) {
			$tmp .= "$titre, ($date_titre, $type_book)</a>";
		}else{
		    $tmp .= "$titre, ($date_titre, $type_book)</div>";
		}
    return $tmp;

}
*/

function terms($eid, $liste, $lien) {
    global $module_name, $prefix, $sitename, $dbi, $admin;

   	$debutForm = "<ul>";
	$finForm = "</ul>";

	echo "<table border=\"0\" align=\"center\">";
	$result = mysqli_query($dbi, "select tid, title from ".$prefix."_encyclopedia_text WHERE eid='$eid' order by title");
	if (mysqli_num_rows($result) == 0) {
 	    echo "<font class=\"info\">"._NOCONTENTFORLETTER. "$ltr.</font>";
	}
	while(list($tid, $title) = mysqli_fetch_row($result)) {
		$title .= lienBiblio($tid,$lien,"Bilbiographie");
		afficheShowHide("DIS$tid",$title, $debutForm.$liste[$tid].$finForm);
	}
	echo "</table><br><br>";
}

// -------------------------------------------------------------------------------------------------------
// Début de la page
// -------------------------------------------------------------------------------------------------------

	cookiedecode($user);

	$uid = $cookie[0];
	$username =$cookie[1];

	$colispage = 1;
	if ($selection=="") $selection = "auteurs";	
    include("header.php");

 	echo "
	<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >
		<tr><td align=\"center\"><div class=\"title\" >Fac similé, Textes, Notions & Relations</div></td></tr>
 		<tr><td align=\"center\"><div class=\"subtitle\" >Accès au corpus hypertextuel</div></td></tr>
	</table>";
	
	NavigationMenu($selection,$pa,2,2,1);
	OpenTable();
	echo afficheNomEtagere($etagere);

	switch($pa) {

	case "biblio":
		showbiblio($id, $categorie);
	break;

	case "creation_etagere":
		creation_etageres();
	break;
	case "etageres":
		etageres($uid);
	break;
	case "auteurs":
		auteurs();
	break;
	case "detail_auteur":
		detail_auteur($aid);
	break;
	case "domaine":
		domaine();
	break;
	case "discipline":
		discipline();
	break;
	case "notions":
		notions();
	break;
	case "rendre_public":
		rendre_public($eid);
		etageres($uid);
	break;
	case "cacher":
		cacher($eid);
		etageres($uid);
	break;
	case "effacer":
		effacer($eid);
		etageres($uid);
	break;
	case "inserer_etagere":
		$eid = inserer_etagere($uid, $nom_etagere, $description_etagere);
		modifier_ouvrages_etagere($uid, $eid);
	break;
	case "modifier_ouvrages_etagere":
		modifier_ouvrages_etagere($uid, $eid);
	break;
	case "inserer_ouvrage_etagere":
		inserer_ouvrage_etagere($uid, $eid, $ouvrage_list);
		modifier_ouvrages_etagere($uid, $eid);
	break;
	
        
    default:
    auteurs();
    break;

}

    CloseTable();
    include("footer.php");

?>
