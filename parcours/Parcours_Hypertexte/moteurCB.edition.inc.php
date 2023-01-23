<?php

/******************************************************************************/
/*                                                                            */
/* moteurCB.php - phpCB                                                       */
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

include "wysiwygeditor.php";

function edition($valeur){
    global $ouvrage;
    global $texte, $texteNotion ;
// variables correspondant aux entités de la DTD
    global $titre;
    global $nom, $prenom;
    global $editeur, $ville, $date;
    global $paragraphe;
// objets de stockage des entités de la DTD
    global $flag , $notionsParagraphe;
    global $glossaire, $motclef, $note ;

	decodeDocument($valeur);
	$texte = trim($texte);

	$page = $paragraphe[page];
	$partie = stripslashes($paragraphe[partie]);
	$chapitre = stripslashes($paragraphe[chapitre]);
	$souschapitre = stripslashes($paragraphe['sous-chapitre']);
	
echo "<h3 align=center>Modification du paragraphe $valeur</h3>
<form method=\"post\" action=\"parcours.php?name=Parcours_Hypertexte&file=moteurCB\">
<input type=\"hidden\" name=\"ouvrage\" value=\"$ouvrage\">
<input type=\"hidden\" name=\"valeur\" value=\"$valeur\">
<input type=\"hidden\" name=\"titre\" value=\"$titre\">
<input type=\"hidden\" name=\"texteNotion\" value='$texteNotion'>
<input type=\"hidden\" name=\"parcours\" value=\"edition\">
<table>
<tr>
    <td width=25%><b>Auteur</b></td><td><b>Edition</b></td>
</tr>
<tr>
    <td width=25%>
	<table>
	<tr><td>Nom:</td><td><input type=\"text\" name=\"nom\" value=\"".stripslashes($nom)."\" size=40></td></tr>
	<tr><td>Prénom:</td><td><input type=\"text\" name=\"prenom\" value=\"".stripslashes($prenom)."\" size=40></td></tr>
	</table>
	</td>
    <td><table>
			<tr>
				<td>Editeur:</td>
				<td><input type=\"text\" name=\"editeur\" value=\"".stripslashes($editeur)."\" size=60></td>
			</tr>
			<tr>
				<td>Ville:</td>
				<td><input type=\"text\" name=\"ville\" value=\"".stripslashes($ville)."\" size=40></td>
			</tr>
			<tr>
				<td>Date:</td>
				<td><input type=\"text\" name=\"date\" value=\"".stripslashes($date)."\" size=20></td>
			</tr>
	</table></td>
<tr>
    <td width=25%><b>Paragraphe</b></td>
</tr>
<tr>
	<td colspan=\"2\"><table>
		<tr>
		    <td>Partie:</td><td><input type=\"text\" name=\"partie\" value=\"$partie\" size=\"80\"></td>
		<tr>
			<td>Chapitre:</td><td><input type=\"text\" name=\"chapitre\" value=\"$chapitre\" size=\"80\">
		<tr>
			<td>Sous - Chapitre:</td><td><input type=\"text\" name=\"souschapitre\" value=\"$souschapitre\" size=\"80\">
		<tr>
			<td>Page:</td><td><input type=\"text\" name=\"page\" value=\"$page\">
	</table></td>
<tr>
    <td colspan=\"2\"><b>Texte du paragraphe:</b><br><br>";
	
//	if(ereg("MSIE", getenv("HTTP_USER_AGENT")))	{
		html_editor("texte",$texte,"myEditor");
//    } else	{
//		echo "<textarea name=\"texteParagraphe\" rows=15 cols=100>$texte</textarea>";
//	}
	echo "</td>
<tr>
	<td align=center>";
	
	echo "<input type=\"submit\" name=\"op\" value=\""._OK."\" onClick=\"copyValue_texte('myEditor');\">";
	echo "</td>
</tr>
</table><p></form>";
}

function PreviewStory ($valeur, $titre, $nom,$prenom,$editeur,$ville,$date,$partie,$chapitre, $souschapitre, $texte, $texteNotion, $page) {
    global $user, $cookie, $ouvrage, $baseDir, $texteParagraphe;

	include_once("header.php");
	include_once("wysiwyg.inc");

    $nom      = stripslashes($nom);
    $prenom   = stripslashes($prenom);
    $editeur  = stripslashes($editeur);
    $ville    = stripslashes($ville);
    $date     = stripslashes($date);
    $partie   = stripslashes($partie);
	$chapitre = stripslashes($chapitre);
	$souschapitre = stripslashes($souschapitre);
    $texteParagraphe = stripslashes($texte);

    echo "<center><font class=\"title\"><b>Edition : prévisualisation et sauvegarde</b></font><br>";
    echo "<br>";
    echo "<center><font class=\"title\"><b>Informations non visibles</b></font><br>";
	echo "<p>Auteur : $prenom $nom<br>
	Editeur : $editeur, $ville, $date<br>";
    echo "<br>";
    echo "<center><font class=\"title\"><b>Voici comment va apparaitre le paragraphe</b></font><br>";
//Affichage des titres quand ils apparaissent, sinon vu que html ignore les balises on économise les tests.
	echo "<h3>$partie</h3><h4>$chapitre</h4><h5>$souschapitre</h5>";

//Affiche le corps du texte

    echo "<blockquote>$texteParagraphe</blockquote>";		
    echo "<center><font class=\"title\"><b>Voici les balises notionnelles préservées</b></font><br>";
    echo "<blockquote>".htmlspecialchars($texteNotion)."</blockquote>";		

    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"title\"><b>Enregistrement des modifications</b></font><br>";
$front = "<?xml version=\"1.0\" encoding=\"iso-8859-1\" standalone=\"yes\"?>
<?xml-stylesheet href=\"ouvrage.css\" type=\"text/css\" ?>
<!--Generated On-line by CoLiSciences.-->
<!--Updated on ".date("F j, Y, g:i a").".-->
    <ouvrage titre=\"$titre\"";
			if ($type<>""){ $front .= " type=\"$type\""; }
			if ($tome<>""){	$front .= " tome=\"$tome\""; }
			$front .= " >
	   <!--(auteur+ , edition , tome+)-->
	      <auteur nom=\"$nom\" prenom=\"$prenom\"/>
		     <edition editeur=\"$editeur\" ville=\"$ville\" date=\"$date\"/>
			 ";
	$texteParagraphe = $texteParagraphe."\n".stripslashes($texteNotion);
	$texte = $front.ecritParagraphe($valeur);

    $baseDir = "/var/www/html/Colis/$ouvrage/";
	addQuotes($texte);
	ecritFichier($valeur, $texte);
    CloseTable();
    echo "<br>";

    include ('footer.php');
}

//print_r($HTTP_POST_VARS );

switch ($op) {

    case ""._OK."":
	PreviewStory($valeur, $titre, $nom,$prenom,$editeur,$ville,$date,$partie,$chapitre, $souschapitre, $texte, $texteNotion, $page);
	break;

	default:
	edition($valeur);
	break;
}
?>