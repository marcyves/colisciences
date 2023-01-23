<?php
/******************************************************************************/
/*                                                                            */
/* fonctions.affichage.inc                                                    */
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
/* This file is part of phpCB (http://colisciences/)                          */
/*                                                                            */
/******************************************************************************/

// Get current .php name
$phpSelfArray = explode('/', $PHP_SELF);
$phpName = $phpSelfArray[sizeof($phpSelfArray) - 1];

/*                                                                            */
/* title - Affiche le titre de la page                                        */

function titre($texte)
{
echo '<div id="title"><h1>'.$texte.'</h1></div>';
}

/*                                                                            */
/* parcoursBox - Display user menu                                            */

/**
 * affichageFacSimile()
 * 
 * @param $num
 * @return 
 */

function affichageFacSimile($num) {
global $ouvrage, $taille, $nombre_noeuds, $valeur;

	if ($num>$nombre_noeuds) $num=$nombre_noeuds;
	if ($num<1) $num=1;

	$i = substr("00000".$num,-4);
  $file = "/Fac/$ouvrage/$i.jpg";
 	creeLienNavigation($valeur);
	if (file_exists("/var/www/html/".$file)) {
	    echo "<p align=\"center\"><a href=\"$file\" target=\"popup\"><img src=\"$file\" alt=\"Ouvrir la page $num dans une fenétre\" width=\"$taille%\"></a><p>";
	} else {
		echo "<h3>Désolé, le fac-similé de la page $num n'est pas encore disponible</h3>";
	}
}

/**
 * affichageIcone()
 * 
 * @param $num
 * @param $classe
 * @return 
 */
function affichageIcone($num, $classe) {
        return creeLien($num, $classe, substr("___".$num,-3),"notion")." ";
}

/**
 * afficheLabel()
 * 
 * @param $num
 * @param $classe
 * @return 
 */
//function afficheLabel($num, $classe) {
//        return creeLabel($num, $classe);
//}

/**
 * affiche()
 * Cette fonction sert é afficher tout ce qui apparait dans la boite sous les onglets.
 * Au départ prévue pour afficher les textes du corpus, elle a été "étendue" pour afficher
 * aussi de simples messages. 
 * @param $num
 * @param $texte
 * @param $titre1
 * @param $notion
 * @param $titre2
 * @param $glossaire
 * @param $titre3
 * @param $parcours
 * @return 
 */
function affiche($titre1, $notion, $titre2, $glossaire, $titre3, $parcours3) {
	global $numappel, $note , $parcours ;
	global $paragraphe, $texte , $admin , $ouvrage;
	global $prefix, $dbi;
	
//debug	echo "<p>affiche avec les paramétres: $titre1, $notion, $titre2, $glossaire, $titre3, $parcours3";
		
if ($titre3 == "msg") {
//Nous ne sommes pas en train de présenter un paragrpahe mais un simple message
	echo "<table><tr><td valign=\"top\">";
	afficheShowHide("NOT$num", $titre1, $notion);
	echo "</td></tr></table>";
} else {
//Nous sommes en train de présenter un paragraphe
//Nous commenéons par récupérer l'identifiant du paragraphe et de la page'
	$num   = $paragraphe['numero'];
	$page  = $paragraphe['page'];
    if ($num == ""){
		$num = 1;
	}
//Affiche les boutons de navigation si on n'est ni sur la page sommaire ni sur la page signets
	if (($parcours!="sommaire")&&($parcours!="signets")){
		creeLienNavigation($num);
	}
//Affichage des titres quand ils apparaissent, sinon vu que html ignore les balises on économise les tests.
	echo "<h3>".$paragraphe['partie']."</h3>
	<h4>".$paragraphe['chapitre']."</h4>
	<h5>".$paragraphe['sous-chapitre']."</h5>";

	if (($parcours!="sommaire")&&($parcours!="signets")){
//Affiche le corps du texte
	$tmp = $texte;
	if ($parcours=="paragraphe") {
		$tmp = parseEncyclopedie($tmp,_GLOSSAIRE);
		$tmp = parseEncyclopedie($tmp,_AUTEURS);
		}

    echo "<blockquote>$tmp</blockquote>";		

	$tmp = $note->liste("note");
	if ($tmp){
		echo "<hr width=\"90%\" align=\"center\" />$tmp";
	}
//Affiche la liste des notions
	afficheShowHide("NOT$num", $titre1, $notion);

	if ((substr($parcours,0,6) == "notion") && ($titre3!= "short")) {
		parcoursBox($num);
	}
	
//Affiche le bouton de fusion du texte
	if (($admin)&&($parcours=="paragraphe")) {
		$suivant = $num + 1;
        echo "<form method=\"post\" action=\"parcours.php?name=Parcours_Hypertexte&file=moteurCB&ouvrage=$ouvrage&valeur=$num\">
<input type=hidden name=\"parcours\" value=\"fusionneTexte\">
&nbsp;<input type=\"submit\" value=\"Fusionner avec $suivant\">
</form>";
	}

//Affiche la boite d'ajout de nouvelles notions
	if (($admin)&&($parcours=="notion")) {
		$listeNotions = "";
        $tmp .= "<form method=\"post\" action=\"parcours.php?name=Parcours_Hypertexte&file=moteurCB&ouvrage=$ouvrage&valeur=$num\">
<input type=hidden name=\"parcours\" value=\"ajouteNotion\">
<table>
	<tr><td>
		<select name=\"notion\">
				<option>sélectionner une notion principale</option>";

	$result = mysqli_query($dbi, "select tid, title from ".$prefix."_encyclopedia_text WHERE eid='"._NOTION."'");
	while(list($tid, $title) = mysqli_fetch_row($result)) {
		$listeNotions .= "<option>$title</option>";
	}
$tmp .= "
		$listeNotions
		</select>
	</td><td>
		<select name=\"relation\">
		<option>sélectionner une relation</option>
		<option>association</option>
		<option>opposition</option>
		<option>composition</option>
		<option>réitération</option>
		</select>
	</td><td>
		<select name=\"liennotion\">
		<option>sélectionner une notion liée</option>
		$listeNotions
		</select>
	</td></tr>
</table>
<input type=\"submit\" value=\"Ajouter\">
</form>";
		afficheShowHide("EDT","Edition des Notions", $tmp);
	}
}
//Affiche le parcours
//		afficheShowHide("PAR",$titre3, $parcours3);
	}
}

/**
 * parseEncyclopedie()
 * 
 * @param $texte
 * @param $eid
 * @return 
 */
function parseEncyclopedie($texte, $eid){
	// Variables globales pour accés é l'encyclopédie
    global $prefix, $dbi, $sitename, $admin, $multilingual, $module_name;

	// pad it with a space so we can match things at the start of the 1st line.
	$ret = " " . $texte;
	switch ($eid) {
	case _GLOSSAIRE:
		$label = "Glossaire";
	break;
	case _AUTEURS:
		$label = "Auteurs_cités";
	break;
		}
	// On lit la table des mots du glossaire par ordre alphabétique décroissant pour lire les mots composés
	// avant le mot simple.
	// Par exemple 'sulfate de magnésium' avant 'sulfate'
	// et pour les remplacer par un lien dans le texte
	$result = mysqli_query($dbi, "select tid, title from ".$prefix."_encyclopedia_text WHERE eid='$eid' order by title desc");
	if ($result->num_rows == 0) {
	    echo "<center>Le Glossaire n'est pas disponible.</center>";
	}
	$i = 0;
	while(list($tid, $title) = mysqli_fetch_row($result)) {
//debug 	echo "#$title#<br>";
// Premier test pour ne pas imbriquer les liens dans le glossaire (mots composés)
		if (!preg_match ( "{>".$title."}i", $ret) ){
			$ret = preg_replace("{(".$title.")\b}i", 
					"<a href=\"parcours.php?name=$label&op=content&tid=".$tid."\" class=\"$label\" target=\"_blank\">\\1</a>", 
					$ret);
//debug echo "<blockquote>$ret</blockquote>";
		}
	}

	$ret = substr($ret, 1);	
	return($ret);	
}

/**
 * creeLabel()
 * 
 * @param $display
 * @param $label
 * @return 
 */
function creeLabel($display, $label) {
        return "<div class=\"$display\" >$label</div>";
}

/**
 * creeLien()
 * 
 * @param $num
 * @param $display
 * @param $label
 * @param $parcours
 * @return 
 */
function creeLien($num, $display, $label, $parcours) {
        global $ouvrage;

        $texte = "<a class=\"$display\" href=\"parcours.php?name=Parcours_Hypertexte&file=moteurCB&ouvrage=$ouvrage&parcours=$parcours";
        if ($num != "") {
                $texte =$texte."&valeur=$num";
        }
        $texte =$texte."\">$label</a>";
        return $texte;

}

/**
 * creeLienFac()
 * 
 * @param $num
 * @param $display
 * @param $label
 * @param $parcours
 * @return 
 */
function creeLienFac($num, $display, $label, $parcours) {
	global $ouvrage;

  $texte = "<a class=\"$display\" href=\"parcours.php?name=Parcours_Hypertexte&file=moteurCB&ouvrage=$ouvrage&parcours=$parcours";
        if ($num != "") {
                $texte =$texte."&numPage=$num";
        }
        $texte =$texte."\">$label</a>";
        return $texte;

}

/**
 * creeLienPage()
 * 
 * @param $type
 * @param $id
 * @return 
 */
function creeLienPage($type, $id) {
         return "<a name=\"#$id\"></a><a class=\"$type\" href=\"parcours.php?name=$type&amp;func=select&amp;nom=$id\">$id</a>";
}

/**
 * creeLienNotion()
 * 
 * @param $paragraphe
 * @param $notion
 * @param $type
 * @return 
 */
function creeLienNotion($paragraphe, $notion, $type) {
        global $ouvrage;

        return "<a class=\"$type\" href=\"parcours.php?name=Parcours_Hypertexte
		&file=moteurCB
		&ouvrage=$ouvrage
		&parcours=notion_$type
		&notion=$notion
		&type=simple
		&valeur=$paragraphe
		\">$notion</a>";

//		&type2=$type

}

/**
 * creeLienNavigation()
 * 
 * @param $paragraphe
 * @return 
 */
function creeLienNavigation($paragraphe) {
    global $admin,$ouvrage,$parcours,$nombre_noeuds, $taille ;
		// variables correspondant aux entités de la DTD
    global $titre;
    global $nom, $prenom;
    global $editeur, $ville, $date;
    global $numero, $partie, $chapitre, $souschapitre;
    global $texte;

    $suivant = $paragraphe + 1;
    $precedent = $paragraphe - 1;
		echo "<table><tr><td valign=\"top\">";
    if ($suivant > $nombre_noeuds ) { $suivant = 1; }
    if ($precedent < 1 ) { $precedent = $nombre_noeuds; }
    $lien = "";
    if ($admin) {
         $lien .= "<form method=\"post\" action=\"parcours.php?name=Parcours_Hypertexte&file=moteurCB&ouvrage=$ouvrage&parcours=edition&valeur=$paragraphe\">";
         $lien .= "\n<input type=hidden name=titre value=\"$titre\">";
         $lien .= "\n<input type=hidden name=nom value=\"$nom\">";
         $lien .= "\n<input type=hidden name=prenom value=\"$prenom\">";
         $lien .= "\n<input type=hidden name=editeur value=\"$editeur\">";
         $lien .= "\n<input type=hidden name=ville value=\"$ville\">";
         $lien .= "\n<input type=hidden name=date value=\"$date\">";
         $lien .= "\n<input type=hidden name=numero value=\"$numero\">";
         $lien .= "\n<input type=hidden name=partie value=\"$partie\">";
         $lien .= "\n<input type=hidden name=chapitre value=\"$chapitre\">";
         $lien .= "\n<input type=hidden name=souschapitre value=\"$souschapitre\">\n";
//		 $tmp = strtr("'","\'",$texte);
//         $lien .= "\n<input type=hidden name=texte value='$tmp'>\n";
    }
    $lien .= "<h5>";
    $lien .= creeLien("1", "menunav", "<<", $parcours);
    $lien .= " ";
    $lien .= creeLien($precedent, "menunav", "<", $parcours);
    if ($admin) {
         $lien .= "<input type=submit value=\"Modifier $paragraphe\">";
    } else {  
         $lien .= "é: $paragraphe";
    }
		$lien .= creeLien($suivant, "menunav", ">", $parcours);
	  $lien .= " ";
    $lien .= creeLien($nombre_noeuds, "menunav", ">>", $parcours);
		$lien .= "<a href=\"parcours.php?name=Parcours_Hypertexte&file=moteurCB&ouvrage=$ouvrage&parcours=signets&cmd=ajoute&p=$parcours&valeur=$paragraphe\"><img src=\"images\add_signet.gif\" alt=\"Ajouter aux signets\"></a>";
    $lien .=  "</h5>";
    if ($admin) {
         $lien .= "</form>";
    }  
    echo $lien;
echo "</td>
<td valign=\"top\"><form action=\"parcours.php?name=Parcours_Hypertexte&file=moteurCB&ouvrage=$ouvrage&parcours=$parcours\" method=\"post\">
	Paragraphe: <input type=\"text\" name=\"valeur\" size=\"4\" value=\"$paragraphe\">
	<input type=\"submit\" value=\"Aller\">
	</form></td>";
	if ($parcours=="Fac") {
		if (!isset($taille)) $taille=60;
	    echo "<td valign=\"top\"><form action=\"parcours.php?name=Parcours_Hypertexte&file=moteurCB&ouvrage=$ouvrage&parcours=Fac&valeur=$num\" method=\"post\">
		Echelle: <input type=\"text\" name=\"newtaille\" value=\"$taille\" size=\"4\">
		<input type=\"submit\" value=\"Modifier\">(exemple: 50 pour diminuer de moitié)
		</form></td>";
	}
echo "	</tr>
</table>";

}

?>