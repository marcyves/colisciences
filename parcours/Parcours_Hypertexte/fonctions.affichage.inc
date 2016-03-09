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


/**
 * afficheEchelleCouleurs()
 * 
 * @param $maxCount
 * @param $compteur
 * @return 
 */
function afficheEchelleCouleurs($maxCount, $compteur,$type){
//debug echo "<p>Appel afficheEchelleCouleurs $maxCount, $compteur,$type)";
	$tmp = "";
	if ($type!="style"){
		if ($maxCount==0){
			$tmp .= "<td width=\"15%\" align=\"center\"><h2>Il n'y a pas encore de parcours de lecture enregistré</h2>";
			$i = 0;
			$l = substr("__________".$i." _",-8);
			$tmp .= "<br>".boutonSelfCommande("&mode=short&limit=$i",$l,"_self","L$i");
			//On s'assure que la couleur du bouton sera dans la feuille de style
			$compteur->ajouteUnique($i);
		}else{
			//L'échelle de couleurs est affichée par l'intermédiaire d'une feuille de style
			$tmp .= "<td width=\"15%\" align=\"center\"><small>cliquer une couleur limite l'affichage à ce niveau.</small>";
			//On affiche toujours 20 boutons de couleur pour l'échelle
			if ($maxCount>20){
				$step = ceil($maxCount/20)-1;
			}else {
				$step = 1;
			}
			for ($i=0;$i<=$maxCount;$i=$i+$step) {
				$l = substr("__________".$i." _",-8);
				$tmp .= "<br>".boutonSelfCommande("&mode=short&limit=$i",$l,"_self","L$i");
				//		echo creeLabel("L".$i, $i);
				//On s'assure que la couleur du bouton sera dans la feuille de style
				$compteur->ajouteUnique($i);
			}
		}
		echo $tmp;
		$compteur->ajouteUnique($maxCount);
	}

// echo $compteur->liste("");
// On a fini d'afficher les boutons, on passe à la feuille de style qui va afficher les
// couleurs correspondantes

	echo "\n<style>";

	$c1 = "00";
	$c2 = "00";
	$c3 = "00";
//debug$log = "";	
	do {
		$i = $compteur->depile();
		if ($i=="debut"){
			$i = 0;
		}
//debug$log .= "<br>pour i=$i";
		$taux  = $i/($maxCount+1);
		$taux1 = pow($taux,2);
		$taux2 = pow($taux,1/2);
		
		$taux  = ceil($taux*255);
		$taux1 = ceil($taux1*255);
		$taux2 = ceil($taux2*255);

		$rouge = substr("00".DecHex($taux2),-2);
		$vert  = substr("00".DecHex($taux1),-2);
//		$bleu  = substr("00".DecHex($taux2),-2);
if ($rouge=="ff"){
	$bleu = "ff";
}else if ($rouge=="00"){
	$bleu = "00";
}else if ($taux2<150){
	$bleu = "60";
}else {
	$bleu = "10";
}

//		$c = DecHex($i*hexdec("ff")/$maxCount);
	
		$c = $rouge.$vert.$bleu;
//debug$log .= " c=$c";
		if ($c == "000000"){
			$textColor = "#eeeeee";
		} else if ($c < "555555"){
			$textColor = "white";
		} else if ($c < "aaaaaaaa"){
			$textColor = "yellow";
		} else {
			$textColor = "blue";		
		}
		echo "
.C$i {
	background-color: #$c;
	text-align : center;
	font-size : small;
	text-decoration: none;
	border : thin groove;
	height: 20px;
}
.C$i:hover {
	color: $textColor;
}
.L$i {
	FONT-FAMILY: \"Courier New\", Courier, monospace;
	background-color: #$c;
	text-align : center;
	font-size : x-small;
	text-decoration: none;
	color: $textColor;
	border : thin groove;
	height: 15px;
	width: 70%;
}
.L$i:hover {
	FONT-FAMILY: \"Courier New\", Courier, monospace;
	background-color: white;
	text-align : center;
	font-size : x-small;
	color: $textColor;
	text-decoration: none;
	border : thin groove;
	height: 15px;
	width: 70%;
}";
	} while($i!="debut");

	echo "\n</style>\n";
//debugecho $log;
	return $step;
}

/*                                                                            */
/* title - Affiche le titre de la page                                        */

function titre($texte)
{
echo '<div id="title"><h1>'.$texte.'</h1></div>';
}

/**
 * affichageFacSimile()
 * 
 * @param $num
 * @return 
 */

function affichageFacSimile($num) {
    global $ouvrage, $taille, $nombre_noeuds, $valeur,$webroot;

//	if ($num>$nombre_noeuds) $num=$nombre_noeuds;
//	if ($num<1) $num=1;

	$i = substr("00000".$num,-4);
	$file = "Fac/$ouvrage/$i.jpg";
	
 	creeLienNavigation($valeur,$num);

	if (file_exists($webroot.$file)) {
	    echo "<p align=\"center\"><a href=\"$file\" target=\"popup\"><img src=\"$file\" alt=\"Ouvrir la page $num dans une fenêtre\" width=\"$taille%\"></a><p>";
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
        return creeLien($num, $classe, substr("___".$num,-4),"notion")." ";
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
 * Cette fonction sert à afficher tout ce qui apparait dans la boite sous les onglets.
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
	global $paragraphe, $texte , $admin , $ouvrage, $webroot ;
	global $prefix, $dbi;
	
//debug	echo "<p>affiche avec les paramètres: $titre1, $notion, $titre2, $glossaire, $titre3, $parcours3";
		
if ($titre3 == "msg") {
//Nous ne sommes pas en train de présenter un paragraphe mais un simple message
	echo "<table><tr><td valign=\"top\">";
	afficheShowHide("NOT$num", $titre1, $notion);
	echo "</td></tr></table>";
} else {
//Nous sommes en train de présenter un paragraphe
//Nous commençons par récupérer l'identifiant du paragraphe et de la page'
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
	echo "<table>
	<tr><td width=\"80%\"><h3>".$paragraphe['partie']."</h3>
	<h4>".$paragraphe['chapitre']."</h4>
	<h5>".$paragraphe['sous-chapitre']."</h5></td>\n
	<td align=\"center\">";

	$i = substr("00000".$page,-4);
	$file = "$ouvrage/$i.jpg";
	if (file_exists($webroot."vignettes/".$file)) {
	    echo "<a href=\"Fac/$file\" target=\"popup\">
		<img src=\"vignettes/$file\" alt=\"Ouvrir la page $num dans une fenêtre\">
		</a>";
	} else {
//		echo "Désolé<br>Le fac-similé ".$webroot.$file." de la page $num n'est pas encore disponible.";
		echo "<small>Désolé<br>L'imagette de la page $num n'est pas disponible.</small>";
	}
	echo "</td></tr>\n</table>\n";
	
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
		$tmp = parseEncyclopedie($tmp,_GLOSSAIRE);
		$tmp = parseEncyclopedie($tmp,_AUTEURS);
		echo "<hr width=\"90%\" align=\"center\" />$tmp";
	}
	//Affiche la liste des notions
	afficheShowHide("NOT$num", $titre1, $notion);

	if ((substr($parcours,0,6) == "notion") && ($titre3!= "short")) {
	//Affiche la boite de selectionet de navigation notionnelle
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

	$result = sql_query("select tid, title from ".$prefix."_encyclopedia_text WHERE eid='"._NOTION."' order by title", $dbi);
	while(list($tid, $title) = sql_fetch_row($result, $dbi)) {
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
	// Variables globales pour accès à l'encyclopédie
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
	$result = sql_query("select tid, title from ".$prefix."_encyclopedia_text WHERE eid='$eid' order by title desc", $dbi);
	if (sql_num_rows($result, $dbi) == 0) {
	    echo "<center>Le Glossaire n'est pas disponible.</center>";
	}
	$i = 0;
	while(list($tid, $title) = sql_fetch_row($result, $dbi)) {
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

function lienOuvrage($num, $display, $label, $parcours, $ouvrage) {

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
function creeLienFac($num, $display, $label, $parcours, $valeur) {
	global $ouvrage;
//echo "<p>($num, $display, $label, $parcours, $valeur)";
  $texte = "<a class=\"$display\" href=\"parcours.php?name=Parcours_Hypertexte&file=moteurCB&ouvrage=$ouvrage&parcours=$parcours";
        if ($num != "") {
                $texte =$texte."&numPage=$num";
        }
        if ($valeur != "") {
                $texte =$texte."&valeur=$valeur";
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
function creeLienNavigation($paragraphe,$numPage="") {
    global $admin,$ouvrage,$parcours,$nombre_noeuds, $nombre_pages, $taille, $dbi ;
	// variables correspondant aux entités de la DTD
    global $titre;
    global $nom, $prenom;
    global $editeur, $ville, $date;
    global $numero, $partie, $chapitre, $souschapitre;
    global $texte;

	//Calibrage des liens 'suivant' et 'précédent'
    $suivant = $paragraphe + 1;
    $precedent = $paragraphe - 1;
    if ($suivant > $nombre_noeuds ) { $suivant = 1; }
    if ($precedent < 1 ) { $precedent = $nombre_noeuds; }

	echo "<table><tr><td valign=\"top\">";
    $lien = "";
	//les administrateurs peuvent éditer le fichier XML du paragraphe
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
//       $lien .= "\n<input type=hidden name=texte value='$tmp'>\n";
    }
	
    $lien .= "<h5>";

    $lien .= creeLien("1", "menunav", "<<", $parcours);
    $lien .= " ";
    $lien .= creeLien($precedent, "menunav", "< ", $parcours);
    if ($admin) {
         $lien .= "<input type=submit value=\"Modifier $paragraphe\">";
    } else {  
         $lien .= "§: $paragraphe";
    }
	$lien .= creeLien($suivant, "menunav", " >", $parcours);
	$lien .= " ";
    $lien .= creeLien($nombre_noeuds, "menunav", ">>", $parcours);
	if ($parcours=="Fac") {
		$lien .= "<br>";
		
		$suivant = $numPage + 1;
  		$precedent = $numPage - 1;
  		if ($suivant > $nombre_pages ) { $suivant = 1; }
		if ($precedent < 1 ) { $precedent = $nombre_pages; }

		$lien .= creeLienFac("1", "menunav", "<<", $parcours, $paragraphe)." ".
			     creeLienFac($precedent, "menunav", "< ", $parcours, $paragraphe)."page: $numPage". 
				 creeLienFac($suivant, "menunav", " >", $parcours, $paragraphe)." ".
				 creeLienFac($nombre_pages, "menunav", ">>", $parcours, $paragraphe);
	}

	$lien .= "<a href=\"parcours.php?name=Parcours_Hypertexte&file=moteurCB&ouvrage=$ouvrage&parcours=signets&cmd=ajoute&p=$parcours&valeur=$paragraphe\"><img src=\"images/add_signet.gif\" alt=\"Ajouter aux signets\"></a>";
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
	echo "<td valign=\"top\">";


	//Pour affichage lecto sur ce paragraphe
	echo afficheIconeLecto($paragraphe)."</td></table><table><tr>";

	echo"<td>";
}

function afficheIconeLecto($paragraphe){
	global $dbi, $ouvrage;
	
	$compteur = new pile;
	$compteur->ajoute("debut");

	$tmp = "";
		
	$sql = sql_query("select count from cb_parcours_paragraphe where ouvrage=$ouvrage and source='$paragraphe'",$dbi);
	sql_query($sql, $dbi);
	list($count) = sql_fetch_row($sql, $dbi);
	$compteur->ajouteUnique($count);

	$sql = sql_query("select max(count) from cb_parcours_paragraphe where ouvrage='$ouvrage' and source != '1' ",$dbi);
	sql_query($sql, $dbi);
	list($maxCount) = sql_fetch_row($sql, $dbi);

	$tmp .= affichageIcone("$count", "C".$count);
	$limit = afficheEchelleCouleurs($maxCount, $compteur,"style");

	return $tmp;
}

/**
 * traitementNotions()
 * 
 * @param $typeParcours
 * @return 
 */
function traitementNotions($typeParcours){
	global $memoire, $motclef ,$notion, $notionsParagraphe, $transCorpus, $nextStep , $parcours, $flagTitreNotion, $valeur, $type ;
	global $nombre_noeuds,$titre, $titreOuvrage, $nom, $prenom, $flagParcours ;
	//echo "<p>relance pour $titre($ouvrage) de $prenom $nom pour la notion $notion type $type";

if ($notion == "") {
// Ce n'est pas une recherche de notion spécifique
	decodeDocument($valeur);
	affiche( "Notions et relations de ce paragraphe", $notionsParagraphe->lien($valeur), "Mots et notions", $motclef->liste("motclef"),"Parcours", $memoire->paragraphe("icone"));
	$flagParcours = true;
} else {
//on est en train de lire un ouvrage à la recherche de notions
	if ($valeur >= $nombre_noeuds) {
	//on a dépassé la fin: on termine la boucle dans le moteur
		if ($transCorpus) {
			affiche( "La recherche continue sur l'ouvrage suivant",  "", "", "","msg","");
			$nextStep = "ouvrage_suivant";
			$parcours = $typeParcours;		
		} else {
		//affiche( "Recherche terminée",  "La fin de l'ouvrage est atteinte.", "", "","msg","");
			$nextStep = "";
			$flagParcours = true;
		}
	} else {
	//on étudie ce paragraphe
	//debug echo "<h2>on décode le paragraphe $valeur</h2>";
		decodeDocument($valeur);

		//on affiche le titre la première fois
		if (!$flagTitreNotion){
			echo "<font class=\"title2\">Etude de la notion $notion apparaissant comme";
			switch ($typeParcours){
			case "notion_principal":
				echo " notion principale</font><br>";
			break;
			case "notion_secondaire":
				echo " notion liée</font><br>";
			break;
			case "notion_entrambi":
				echo " notion principale ou liée</font><br>";
			break;
			}
			if($transCorpus){
				echo "<font class=\"title2\"><ul><li>$titreOuvrage ($prenom $nom)</ul><br>";
			}
			$flagTitreNotion = TRUE;
		}
		//recherche de la notion
//debug		echo "<p>typeParcours $typeParcours notion $notion";
		switch ($typeParcours){
		case "notion_principal":
			$flagParcours = $notionsParagraphe->cherche1($notion);
		break;
		case "notion_secondaire":
			$flagParcours = $notionsParagraphe->cherche2($notion);
		break;
		case "notion_entrambi":
			$flagParcours = $notionsParagraphe->chercheTout($notion);
		break;
		}
//debug echo " flagParcours $flagParcours";
    	if ($flagParcours!='0') {
			switch ($type) {
            case "icone":
                echo affichageIcone($valeur, "actif$flagParcours");
			break;
			case "montre":
				affiche( "Notions de ce paragraphe", $notionsParagraphe->lien($valeur), "", "","short", "");
                $cnt = $cnt + 1;
                $ancienTitre = $titre;
			break;
			case "cache":
				affiche( "Notions de ce paragraphe", $notionsParagraphe->lien($valeur), "", "","short", "");
                  $cnt = $cnt + 1;
                  $ancienTitre = $titre;
			break;
            case "simple":
				$notion = "";
				$valeur--;
            break;
            }
		} else {
            switch ($type) {
            case "montre":
				echo "<div class=\"petit\">";
				affiche( "Notions de ce paragraphe", $notionsParagraphe->lien($valeur), "", "","short", "");
		        echo "</div>";
            break;
            case "icone";
                echo affichageIcone($valeur, "inactif");
            break;
            }
            $ancienTitre = $titre;
		}
				//préparation pour le paragraphe suivant
		$nextStep = $typeParcours;
		$valeur = $valeur + 1;

	}
}

}

function api_colis_affiche_date($date,$format){
	$y = substr($date,6,2)."/".substr($date,4,2)."/".substr($date,0,4);
	$t = substr($date,8,2).":".substr($date,10,2).":".substr($date,12,2);
	switch ($format){
	case "d":
		return $y;
	break;
	case "t":
		return $t;
	break;
	default:
		return "le ". $y." à ".$t;
	}
}

/**
 * api_gethost()
 *  Fonction de remplacement de gethostbyaddr qui ne marche plus depuis quelques mois (semaines?) 21/01/05
 * @param $ip
 * @return 
 */
function api_gethost ($ip) {
 $host = `host $ip`;
 return (($host ? end ( explode (' ', $host)) : $ip));
}

/**
 * api_colis_user_id()
 * 
 * @param $userColis
 * @return 
 */
function api_colis_user_id($userColis){
	global $HTTP_SERVER_VARS;
	global $debut_execution, $admin;
// Identification des surfeurs non loggés
//debug   if ($admin) {echo "<br>Dans api_colis_user_id ($userColis): ".ecrire_temps($debut_execution, "4");}

	if ($userColis == "anonyme") {
//		$userColis = gethostbyaddr($HTTP_SERVER_VARS['REMOTE_ADDR']);
		$userColis = api_gethost($HTTP_SERVER_VARS['REMOTE_ADDR']);
//debug	if ($admin) {echo "<br>Après api_gethost($userColis): ".ecrire_temps($debut_execution, "4");}
		if ($userColis=="")
		{
			$userColis = $HTTP_SERVER_VARS['REMOTE_ADDR'];
			if ($userColis=="")
			{
				$userColis = "anonyme";
			}
		}
//echo "<p>user=".$userColis;
	}
	//                                123456789012345678901234567890
	$userColis = clean_user_id($userColis);
	if ($admin) {echo "<br>Sortie api_colis_user_id ($userColis): ".ecrire_temps($debut_execution, "4");}
	return $userColis;
}

function clean_user_id($userColis){

	if      (substr($userColis,-13)=="googlebot.com"){
		$userColis = "Crawler_Google";
	//                                123456789012345678901234567890
	}else if(substr($userColis,-15)=="univ-avignon.fr"){
		$userColis = "univ-avignon.fr";
	}else if(substr($userColis,-16)=="public.alexa.com"){
		$userColis = "Crawler_Alexa";
	}else if(substr($userColis,-15)=="proxy.skynet.be"){
		$userColis = "Skynet.be";
	//                                123456789012345678901234567890
	}else if(substr($userColis,-10)=="tiscali.fr"){
		$userColis = "Internaute_Tiscali.fr";
	}else if(substr($userColis,-14)=="adsl.skynet.be"){
		$userColis = "Internaute_Skynet.be";
	}else if(substr($userColis,-22)=="proxycache.rima-tde.net"){
		$userColis = "Internaute_rima-tde.net";
	}else if(substr($userColis,-14)=="ac-bordeaux.fr"){
		$userColis = "ac-bordeaux.fr";
	}else if(substr($userColis,-15)=="cust.bluewin.ch"){
		$userColis = "bluewin.ch";
	}else if(strpos($userColis,"abes.bu")!== false){
		$pos = strpos($userColis, "-");
		$userColis = "ABES.BU";
	}else if(strpos($userColis,"cache")!== false){
		$userColis = str_replace("cache.","",$userColis);
	}else if(strpos($userColis,"abo.wa")!== false){
		$userColis = str_replace("Mix-","X",$userColis);
		$userColis = str_replace("ca-","X",$userColis);
		$userColis = str_replace("St-Lambert","St_Lambert",$userColis);
		$userColis = str_replace("Ste-Genev-Bois","Ste_Genev_Bois",$userColis);
		
		$pos = strpos($userColis, "-");
		$userColis = substr($userColis,1,$pos)."-wanadoo.fr";
	}else  if(substr($userColis,-12)=="sympatico.ca"){
		$pos = strpos($userColis, "-");
		$userColis = substr($userColis,0,$pos)."-sympatico.ca";
	}
	return $userColis;
}

/**
 * enregistreLeParcours()
 * 
 * @param $userColis
 * @param $ouvrage
 * @param $source
 * @param $time
 * @param $etape
 * @param $parcours
 * @param $cible
 * @param $type
 * @param $notion0
 * @return 
 */
function enregistreLeParcours($userColis, $ouvrage, $source, $time, $etape, $parcours, $cible, $type, $notion0 )
{
	global $dbi, $memoire;
	global $debut_execution, $admin;

//	$userColis = api_colis_user_id($userColis);	

//	if ($admin) {echo "<br>Après api_colis_user_id : ".ecrire_temps($debut_execution, "4");}

	$sql = sql_query("select avg(elapsed) from cb_parcours where ouvrage='$ouvrage' and source='$source'",$dbi);
	sql_query($sql, $dbi);
	list($moyenne) = sql_fetch_row($sql, $dbi);
	$moyenne = round($moyenne,1);
	if ($admin) {echo "<br>Après avg(elapsed)=<$moyenne> : ".ecrire_temps($debut_execution, "4");}

	$tmp = "<p>Vous venez du § $source<br>Vous l'avez lu pendant $time sec. soit ".calcElapsedTime($memoire->quand());

	if ($moyenne>0){
		$sql = sql_query("select count from cb_parcours_paragraphe where ouvrage=$ouvrage and source='$source'",$dbi);
		sql_query($sql, $dbi);
		list($count) = sql_fetch_row($sql, $dbi);
		if ($admin) {echo "<br>Après count=<$count> : ".ecrire_temps($debut_execution, "4");}

		if ($count>1){
			$tmp .= "<br>Les $count lecteurs précédents ont passé en moyenne $moyenne sec. sur le même paragraphe.";
		}else{
			$tmp .= "<br>Le lecteur précédent a passé $moyenne sec. sur ce paragraphe.";
		}
/* Il semble que cette joyeuseté nous pompe les perfs
* On la commente vu qu'on ne l'utilise pas
		$sql = sql_query("select distinct source from cb_parcours where ouvrage=$ouvrage and cible='$cible'",$dbi);
		sql_query($sql, $dbi);
		$tmp .= "<br><br>Quand ils sont arrivés sur $cible<br>... les autres lecteurs venaient de";
		while (list($tmp1) = sql_fetch_row($sql, $dbi)){
			$tmp .=" $tmp1,";
//			$tmp .= " ".afficheIconeLecto($paragraphe).",";
		}
		if ($admin) {echo "<br>Après distinct source   : ".ecrire_temps($debut_execution, "4");}

		$sql = sql_query("select distinct cible from cb_parcours where ouvrage=$ouvrage and source='$source'",$dbi);
		sql_query($sql, $dbi);
		$tmp .="<br>... les autres lecteurs sont partis vers";
		while (list($tmp1) = sql_fetch_row($sql, $dbi)){
			$tmp .=" $tmp1,";
//			$tmp .= " ".afficheIconeLecto($paragraphe).",";
		} */
	}else{
		$tmp .= "<br>Ce paragraphe n'a pas encore été lu.";
	}

	if ($time>500){
		$tmp .= "<br>Votre temps de lecture parait très élevé, il ne sera pas comptabilisé.";
	}else{
		$sql = "insert into cb_parcours values ( NULL, NULL,'$etape','$ouvrage','$source', '$parcours', '$cible', '$type', '$notion0', '$userColis','$time')";
		sql_query($sql, $dbi);

		$count += 1;
		$sql = "update cb_parcours_paragraphe set count=$count where ouvrage='$ouvrage' and paragraphe='$source'";
		sql_query($sql, $dbi);
//A ajouter pour mise à jour 	 cb_parcours_count	
		$sql = "select count, elapsed from cb_parcours_count where ouvrage='$ouvrage' and user='$userColis'";
//debug		echo "<p>$sql";
		$result = sql_query($sql, $dbi);
		list($count, $elapsed) = sql_fetch_row($result, $dbi);
		$count +=  1;
		$elapsed += $time;
		if ($count==1){
			$sql = "insert into cb_parcours_count values( NULL, '$ouvrage', '$count', '$elapsed', '$userColis')";
		} else {
			$sql = "update cb_parcours_count set count=$count, elapsed=$elapsed where ouvrage='$ouvrage' and user='$userColis'";
		}
//debug		echo "<p>$sql";
		sql_query($sql, $dbi);
////////////////////////////////////

	}
	
	$memoire->ajoute($cible);

	return $tmp;
}
?>