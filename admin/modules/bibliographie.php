<?php

/************************************************************************/
/* PHP-COLIS                                                            */
/* =========                                                            */
/*                                                                      */
/* Copyright (c) 2003 by Marc Augier (marc.augier@ceram.fr)             */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!("admin.php", $PHP_SELF)) { die ("Access Denied"); }
$result = sql_query("select radmincontent, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
list($radmincontent, $radminsuper) = sql_fetch_row($result, $dbi);
if (($radmincontent==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Sections Manager Functions                            */
/*********************************************************/

function bibliographies() {
    global $prefix, $dbi, $language, $multilingual, $bgcolor2;

    include("header.php");
    GraphicAdmin();
    title("Gestion des bibliographies en ligne");

    OpenTable();
    echo "<table border=\"1\" width=\"100%\">
	<tr><td bgcolor=\"$bgcolor2\" colspan=\"3\"><b>Liste des auteurs en ligne dans CoLiSciences</b></td></tr>";

    $result0 = sql_query("select aid, nom, prenom from cb_auteurs order by nom, prenom", $dbi);
    while($mypages0 = sql_fetch_array($result0, $dbi)) {
		echo "<tr><td bgcolor=\"#BBCCDD\">$mypages0[prenom] $mypages0[nom]</td><td width=\"8%\" align=\"center\">[ <a href=\"admin.php?op=bibliographie_display&id=$mypages0[aid]&categorie=biblio\"> Biblio</a> ]</td><td width=\"8%\" align=\"center\">[ <a href=\"admin.php?op=bibliographie_display&id=$mypages0[aid]&categorie=bio\"> Bio</a> ]</td></tr>";
	}
    echo "</table>";
    CloseTable();
    echo "<br>";

    OpenTable();
    echo "<table border=\"1\" width=\"100%\">
	<tr><td bgcolor=\"$bgcolor2\" colspan=\"3\"><b>Liste des disciplines cit�es dans CoLiSciences</b></td></tr>";

	$result = sql_query("select tid, title from ".$prefix."_encyclopedia_text WHERE eid='"._DISCIPLINE."'", $dbi);
	if (sql_num_rows($result, $dbi) == 0) {
	    echo "<center><i>Il n'y a pas de discipline d�finie'.</i></center>";
	}
	while(list($tid, $title) = sql_fetch_row($result, $dbi)) {
		echo "<tr><td bgcolor=\"#BBCCDD\">$title</td><td width=\"8%\" align=\"center\">[ <a href=\"admin.php?op=bibliographie_display&id=$tid&categorie=discipline\"> Biblio</a> ]</td></tr>";
	}
    echo "</table>";
    CloseTable();
    echo "<br>";

    OpenTable();
    echo "<table border=\"1\" width=\"100%\">
	<tr><td bgcolor=\"$bgcolor2\" colspan=\"3\"><b>Liste des domaines cit�s dans CoLiSciences</b></td></tr>";

	$result = sql_query("select tid, title from ".$prefix."_encyclopedia_text WHERE eid='"._DOMAINE."'", $dbi);
	if (sql_num_rows($result, $dbi) == 0) {
	    echo "<center><i>Il n'y a pas de discipline d�finie'.</i></center>";
	}
	while(list($tid, $title) = sql_fetch_row($result, $dbi)) {
		echo "<tr><td bgcolor=\"#BBCCDD\">$title</td><td width=\"8%\" align=\"center\">[ <a href=\"admin.php?op=bibliographie_display&id=$tid&categorie=domaine\"> Biblio</a> ]</td></tr>";
	}
    echo "</table>";
    CloseTable();
    echo "<br>";


    title("Ajouter une nouvelle entr�e de bibliographie");
    OpenTable();
	displayForm("Bibliographie sur les auteurs (bio)", "","bio","","","","","","","","","","");
    CloseTable();

    OpenTable();
	displayForm("Bibliographie sur les auteurs (biblio)", "","biblio","","","","","","","","","","");
    CloseTable();

    OpenTable();
	displayForm("Bibliographie sur les disciplines", "","discipline","","","","","","","","","","");
    CloseTable();

    OpenTable();
	displayForm("Bibliographie sur les domaines", "","domaine","","","","","","","","","","");
    CloseTable();
    echo "<br>";

    include("footer.php");
}

function bibliographie_display($id, $categorie){
    global $prefix, $dbi, $sitename, $admin, $module_name,$colispage;
    global $bgcolor2;

    include("header.php");
    GraphicAdmin();

	switch ($categorie){
	case "bio":
	    $sql = sql_query("select nom, prenom from cb_auteurs where aid='$id'",$dbi);
   		list($nom, $prenom) = sql_fetch_row($sql, $dbi);
		$tmp = "Les oeuvres sur $prenom $nom";
	break;
	case "biblio":
	    $sql = sql_query("select nom, prenom from cb_auteurs where aid='$id'",$dbi);
   		list($nom, $prenom) = sql_fetch_row($sql, $dbi);
		$tmp = "Les oeuvres de $prenom $nom";
	break;
	case "discipline":
		$sql = sql_query("select title from ".$prefix."_encyclopedia_text WHERE tid='$id' and eid='"._DISCIPLINE."'", $dbi);
   		list($title) = sql_fetch_row($sql, $dbi);
		$tmp = "Les oeuvres de $title";
	break;
	case "domaine":
		$sql = sql_query("select title from ".$prefix."_encyclopedia_text WHERE tid='$id' and eid='"._DOMAINE."'", $dbi);
   		list($title) = sql_fetch_row($sql, $dbi);
		$tmp = "Les oeuvres de $title";
	break;
	default:
		$tmp = "Bibliographie";
	break;
	}
    title($tmp." ($categorie)");

	OpenTable();
    $sql = sql_query("select Numero, Dates, Type, Titre, Compil, Lieu, EditeurRevue, Reference, Commentaires, Auteurs from cb_biblio where Biblio='$id' and categorie='$categorie' order by Dates",$dbi);

	if (sql_num_rows($sql, $dbi) == 0) {
	    echo "<center><i>Il n'y a pas de r�f�rences bibliographiques.</i></center>";
	} else {
	    echo "<table border=\"1\" width=\"100%\"><tr>"
		."<td bgcolor=\"$bgcolor2\" colspan=\"3\"><b>Liste des r�f�rences bibliographiques</b></td></tr>";
	   	while (list($numero, $Dates, $type, $Titre, $Compil, $Lieu, $EditeurRevue, $Reference, $Commentaires, $Auteurs  ) = sql_fetch_row($sql, $dbi)) {
			$lien = "<td width=\"8%\" align=\"center\">[ <a href=\"admin.php?op=bibliographie_edit&id=$numero&categorie=$categorie\"> Modifier</a> ]</td><td width=\"8%\" align=\"center\">[ <a href=\"admin.php?op=bibliographie_delete&id=$numero&categorie=$categorie\"> Supprimer</a> ]</td>";
			switch ($type){
			case "Livre":
				echo "<tr><td bgcolor=\"#BBCCDD\">($Dates), $Auteurs, <i>$Titre</i>, $Lieu, $EditeurRevue</td>$lien</tr>";
			break;
			case "Article":
				echo "<tr><td bgcolor=\"#BBDDDD\">($Dates), $Auteurs, \"$Titre\", <i>$EditeurRevue</i>, $Reference</td>$lien</tr>";
			break;
			default:
				echo "<tr><td bgcolor=\"#BB0000\">$type<br>($Dates), $Titre, $Lieu, $EditeurRevue, $Reference, $Auteurs</td>$lien</tr>";
			break;
			}
   		}
	}

echo "</table>";
    CloseTable();
    include("footer.php");
}

// Formulaire d'�dition des caract�ristiques d'un bibliographie

function displayForm($caption, $numero, $categorie, $biblio, $dates, $type, $titre, $compil, $lieu, $editeurRevue, $reference, $commentaires, $auteurs ) {
    global $prefix, $dbi, $language, $multilingual, $bgcolor2;

//Traduction champ type pour des boites � cocher "radio"
	$checkLivre = "";
	$checkArticle = "";
	if ($type=="Livre") {$checkLivre = "checked";}
	if ($type=="Article") {$checkArticle = "checked";}
	if ($categorie == "discipline"){
		$tmp = "<b>Discipline</b><br><select name=\"biblio\">";
		$result = sql_query("select tid, title from ".$prefix."_encyclopedia_text WHERE eid='"._DISCIPLINE."'", $dbi);
		while(list($tid, $title) = sql_fetch_row($result, $dbi)) {
			$tmp .= "<option value=\"$tid\"";
			if ($biblio==$tid) { $tmp .= "selected";}
			$tmp .= ">$title</option>";
		}
		$tmp .= "</select>";
	} else 	if ($categorie == "domaine"){
		$tmp = "<b>Domaine</b><br><select name=\"biblio\">";
		$result = sql_query("select tid, title from ".$prefix."_encyclopedia_text WHERE eid='"._DOMAINE."'", $dbi);
		while(list($tid, $title) = sql_fetch_row($result, $dbi)) {
			$tmp .= "<option value=\"$tid\"";
			if ($biblio==$tid) { $tmp .= "selected";}
			$tmp .= ">$title</option>";
		}
		$tmp .= "</select>";
	} else {
	//Traduction champ biblio en nom d'auteur CoLiSciences
		$tmp ="Auteur concern�<br>dans CoLiSciences <select name=\"biblio\">";
   		$result = sql_query("select aid, nom, prenom from cb_auteurs order by nom, prenom", $dbi);
		while (list($aid, $nom, $prenom) = sql_fetch_row($result, $dbi)) {
			$tmp .= "<option value=\"$aid\"";
			if ($biblio==$aid) { $tmp .= "selected";}
			$tmp .= ">$prenom $nom</option>";
		}
		$tmp .= "</select>";
	}
	
    echo "<center><b>$caption</b></center><br><br>
<form action=\"admin.php\" method=\"post\">
<input type=\"hidden\" name=\"numero\" value=\"$numero\">
<input type=\"hidden\" name=\"categorie\" value=\"$categorie\">
<table width=\"80%\" align=\"center\">
<tr><td colspan=\"4\"><b>"._TITLE.":</b><br><input type=\"text\" name=\"titre\" value=\"$titre\" size=\"100\">
<tr><td>$tmp
    <td><b>dates</b><br><input type=\"text\" name=\"dates\" value=\"$dates\" size=\"10\">
	<td><b>type</b><br><input type=\"radio\" name=\"type\" value=\"Livre\" size=\"10\" $checkLivre> Livre<br><input type=\"radio\" name=\"type\" value=\"Article\" size=\"10\" $checkArticle> Article
	<td><b>compil</b><br><input type=\"text\" name=\"compil\" value=\"$compil\" size=\"10\">
<tr><td><b>lieu</b><br><input type=\"text\" name=\"lieu\" value=\"$lieu\" size=\"10\">
	<td><b>editeurRevue</b><br><input type=\"text\" name=\"editeurRevue\" value=\"$editeurRevue\" size=\"10\">
	<td><b>reference</b><br><input type=\"text\" name=\"reference\" value=\"$reference\" size=\"10\">
	<td><b>Auteur</b><br><input type=\"text\" name=\"auteurs\" value=\"$auteurs\" size=\"30\">
<tr><td colspan=\"4\" ><b>commentaires</b><br><input type=\"text\" name=\"commentaires\" value=\"$commentaires\" size=\"100\">";

//Les boutons de commande affich�s d�pendent de la fonction, identifi�e par le numero d'enregistrement � modifier vide
 	if ($numero ==""){
		echo "<input type=\"hidden\" name=\"op\" value=\"add_bibliographie\">"
		."<tr><td colspan=\"4\" align=\"center\"><input type=\"submit\" value=\""._ADD."\">";
	} else {
		echo "<input type=\"hidden\" name=\"op\" value=\"bibliographie_save_edit\">"
		."<tr><td colspan=\"4\" align=\"center\"><input type=\"submit\" value=\""._SAVECHANGES."\">";
	}
	echo "</table></form>";
}

function add_bibliographie($Biblio, $categorie , $Dates, $Type, $Titre, $Compil, $Lieu, $EditeurRevue, $Reference, $Commentaires, $Auteurs) {
    global $dbi;

    sql_query("insert into cb_biblio values (NULL, '$Biblio', '$categorie', '$Dates', '$Type', '$Titre', '$Compil', '$Lieu', '$EditeurRevue', '$Reference', '$Commentaires', '$Auteurs')", $dbi);
//echo "insert into cb_biblio values (NULL, '$Biblio', '$categorie', '$Dates', '$Type', '$Titre', '$Compil', '$Lieu', '$EditeurRevue', '$Reference', '$Commentaires', '$Auteurs')";
    Header("Location: admin.php?op=biblio");
}

function bibliographie_edit($pid, $type) {
    global $prefix, $dbi, $language, $multilingual, $bgcolor2;

    include("header.php");
    GraphicAdmin();
    title("Modification d'une entr�e de bibliographie");
    $result = sql_query("select * from cb_biblio WHERE Numero='$pid'", $dbi);
    $mypages = sql_fetch_array($result, $dbi);

    OpenTable();
	displayForm(_EDITPAGECONTENT, $pid, $type, $mypages[Biblio], $mypages[Dates] , $mypages[Type],$mypages[Titre],$mypages[Compil],$mypages[Lieu], $mypages[EditeurRevue], $mypages[Reference], $mypages[Commentaires], $mypages[Auteurs]);
    CloseTable();
    include("footer.php");
}


function bibliographie_save_edit($Numero, $Biblio, $categorie , $Dates, $Type, $Titre, $Compil, $Lieu, $EditeurRevue, $Reference, $Commentaires, $Auteurs) {
    global $prefix, $dbi;
//Numero  Biblio  categorie  Dates  Type  Titre  Compil  Lieu  EditeurRevue  Reference  Commentaires  Auteurs 
    sql_query("update cb_biblio set  Biblio='$Biblio', categorie='$categorie', Dates='$Dates', Type='$Type', Titre='$Titre', Compil='$Compil', Lieu='$Lieu', EditeurRevue='$EditeurRevue', Reference='$Reference', Commentaires='$Commentaires', Auteurs='$Auteurs' where Numero='$Numero'", $dbi);
    Header("Location: admin.php?op=bibliographie_display&categorie=$categorie&id=$Biblio");
}

function bibliographie_delete($pid, $ok=0) {
    global $prefix, $dbi;
    if ($ok==1) {
        sql_query("delete from cb_biblio where Numero='$pid'", $dbi);
        Header("Location: admin.php?op=biblio");
    } else {
        include("header.php");
        GraphicAdmin();
		title(""._CONTENTMANAGER."");
		$result = sql_query("select Titre from cb_biblio where Numero='$pid'", $dbi);
		list($title) = sql_fetch_row($result, $dbi);
		OpenTable();
		echo "<center><b>Supprimer $title</b><br><br>"
	    ."Etes-vous sur de vouloir supprimer cette r�f�rence?<br><br>"
	    ."[ <a href=\"admin.php?op=biblio\">"._NO."</a> | <a href=\"admin.php?op=bibliographie_delete&amp;id=$pid&amp;ok=1\">"._YES."</a> ]</center>";
		CloseTable();
        include("footer.php");
    }
}

//print_r($HTTP_POST_VARS);

switch ($op) {

    case "biblio":
    bibliographies();
    break;
    case "bibliographie_display":
    bibliographie_display($id, $categorie);
    break;
    case "bibliographie_edit":
    bibliographie_edit($id, $categorie);
    break;
    case "bibliographie_save_edit":
    bibliographie_save_edit($numero, $biblio, $categorie , $dates, $type, $titre, $compil, $lieu, $editeurRevue, $reference, $commentaires, $auteurs);
    break;
    case "bibliographie_delete":
    bibliographie_delete($id, $ok);
    break;
    case "add_bibliographie":
	add_bibliographie($biblio, $categorie , $dates, $type, $titre, $compil, $lieu, $editeurRevue, $reference, $commentaires, $auteurs);
	break;
}

} else {
    echo "Access Denied";
}

?>