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

//if (!eregi("parcours.php", $PHP_SELF)) {
//    die ("You can't access this file directly...");
//}

require_once "class.inc";
require_once "fonctions.xml.inc";
require_once "fonctions.affichage.inc";
ob_start();
$debut_execution = debut_calcultemps();

if (isset($newtaille)) {
    	setcookie("taille",$newtaille,time()+3600);
		$taille = $newtaille;
}

//Initialisation des variables d'enregistrement du parcours
//Ici, on identifie l'Internaute 
//			- soit par son nom de connexion,
//			- soit par son ip, traduite ensuite en nom par api_colis_user_id
cookiedecode($user);
$username =$cookie[1];
if (($username!="")&&($username!=$userColis)){
  	unset($userColis);
}

if ($userColis=="") {
	if ($username == "") {
		$userColis = "anonyme";
	} else {
		$userColis = $username;
	}
	$userColis = api_colis_user_id($userColis);	
   	setcookie("userColis",$userColis,time()+3600);
}


if ($admin) {
	$adminpage = 1;
}

$colispage = 1;
$index = 1;
$parcourspage = 1;

session_register("memoire");

if(!isset($memoire)) {
        $memoire = new analyse;
}
//echo $tmpMSG;
// Valeur par d�fault permettant d'assurer un fonctionnement "minimal"

	if (($ouvrage=="")&&($newouvrage=="")) {
	// La variable ouvrage non renseign�e indique que nous avons appel� le moteur deuis l'onglet "Notions"
	// de l'accueil Corpus.
	// Nous allons donc lancer le moteur dans une recherche transcorpus en partant du premier paragraphe 
	// du premier ouvrage
		$ouvrage = 1;
//		$valeur = 1; l'indication du paragraphe par lequel on commence provient de la BDD 
		$transCorpus = true;
		$titre = "Recherche de notions � travers le corpus";
	    $sql = sql_query("select titre, debut, nom, prenom, nombre_noeuds,texteActif,notionActif,facActif,nombre_pages  from cb_ouvrages, cb_auteurs where aid=auteur and pid=$ouvrage",$dbi);
   		list( $titreOuvrage, $valeur, $nom, $prenom, $nombre_noeuds,$texteActif,$notionActif,$facActif,$nombre_pages ) = sql_fetch_row($sql, $dbi);
		$newouvrage = 1;
	} else {
		$transCorpus = false;

		if (isset($newouvrage)) {	
			$ouvrage = $newouvrage;
		}
	    $sql = sql_query("select aid, titre, debut,nom, prenom, nombre_noeuds,texteActif,notionActif,facActif,nombre_pages  from cb_ouvrages, cb_auteurs where aid=auteur and pid=$ouvrage",$dbi);
 		list($aid, $titre, $debut, $nom, $prenom, $nombre_noeuds,$texteActif,$notionActif,$facActif,$nombre_pages ) = sql_fetch_row($sql, $dbi);
		$auteur = $prenom." ".$nom;
	    if (@$valeur == "" ) {
			$valeur = $debut;
		}

		if (isset($newouvrage)) {	
			if ($texteActif=="1") {
				$parcours = "paragraphe";
			} else if ($facActif=="1") {
				$parcours = "Fac";
			} else {
				$parcours = "Vide";
			}
		}
	}
	$titre = stripslashes($titre);

// --------------------------------------------------------------------------------//	
// Pr�parations de l'Affichage de l'aide en ligne
// --------------------------------------------------------------------------------//	
	$texteAide = "";
	if ($transCorpus){
	}else{
// 		Commentaires sur l'auteur
//		$tmp = afficheDetailsAuteur($aid,$nom,$prenom);
//		$texteAide .= afficheShowHideRetour("HLPAUTEUR","$prenom $nom",$tmp);	


// 		La fiche bibliographique
		$txtHLP = "";
		if (@$fp=fopen($colisroot."/".$ouvrage."/presentation.html","r"))
		{
			$txtHLP .= fgets($fp,4096);
			while (!feof($fp)) 
			{ 
				$txtHLP .= fgets($fp,4096)."<br>";
			}
			fclose($fp);
		}

// 		Commentaires sur l'ouvrage
// 		Les disciplines
		$tmp = "";

	    $sql = sql_query("select title from cb_disciplines,".$prefix."_encyclopedia_text  where did='$ouvrage'and eid='"._DISCIPLINE."' and tid=discipline order by title",$dbi);
		while(list($title) = sql_fetch_row($sql, $dbi)) {
			$tmp .= "<li>$title";
   		}

		if ($tmp!=""){
			$txtHLP .= "<h2>Disciplines</h2><ul>$tmp</ul>";
		}

// 		Les domaines
		$tmp = "";
	    $sql = sql_query("select title from cb_domaines, ".$prefix."_encyclopedia_text  where did='$ouvrage'and eid='"._DOMAINE."' and tid=domaine order by title",$dbi);
		while(list($title) = sql_fetch_row($sql, $dbi)) {
			$tmp .= "<li>$title";
   		}
		if ($tmp!=""){
			$txtHLP .= "<h2>Domaines</h2><ul>$tmp</ul>";
		}

		$texteAide .= afficheShowHideRetour("HLPOUVRAGE","A propos de cet ouvrage",$txtHLP);
	}

	// Aide sur la fonction, le parcours
	if (@$fp=fopen($webroot."aide/".$parcours.".html","r"))
	{
		$titreHLP .= fgets($fp,4096);		// la premi�re ligne du c=fichier est le titre
		$txtHLP = "";
		while (!feof($fp)) 					// la suite est le contenu de l'aide
		{ 
			$txtHLP .= fgets($fp,4096)."<br>";
		}
		fclose($fp);
	}else{
		$txtHLP .=  "<p>Pas d'aide disponible sur le parcours <i>$parcours</i>.";
	}
	$texteAide .= afficheShowHideRetour("HLPARCOURS",$titreHLP,$txtHLP);	


// --------------------------------------------------------------------------------//	
// On envoie l'en-t�te de la page
// --------------------------------------------------------------------------------//	
	include("header.php");
	
// --------------------------------------------------------------------------------//	
// On affiche les onglets
// --------------------------------------------------------------------------------//	
	NavigationMenu($parcours,$texteActif,$notionActif,$facActif);
	if ($transCorpus){
		$titre=$titreOuvrage;
	}
// --------------------------------------------------------------------------------//	
// On ouvre la table dans laquelle va s'afficher le paragraphe
// --------------------------------------------------------------------------------//	
	OpenTable();

// D�but du traitement
// on peut va afficher un noeud en fonction de la mani�re dont il a �t� appel�:
// ouvrage      dans quel ouvrage se situe ce noeud
// parcours
// notion
// relation
// lien-notion
// paragraphe
        
$cnt = 0;

$memoire->relation($parcours);

//Initialisation de la variable de la boucle d'appel des fonctions du moteur
$nextStep = $parcours;
$notion0 = $notion;


do {
//debug	 echo "<p>parcours = $parcours<br>notion = $notion<br>type = $type<br>type2 = $type2<br>valeur = $valeur<br>nombre noeuds = $nombre_noeuds<br>";
	$module = "moteurCB.".$nextStep.".inc";
	$nextStep = "";
	include($module);
//	echo "<p>retour du module $module<br>valeur : $valeur<br>nextStep : $nextStep";
	if ($flagParcours) {
		// Enregistrement de cette �tape du parcours
		$etape = $memoire->nombre();
		if ($etape > 0){
			$source = $memoire->precedent();
			$cible  = $valeur;
	        $time = time()-$memoire->quand();
		}else{
			// Premier paragraphe lu dans l'ouvrage
			$source =	$valeur;
			$time = 0;
		}

		if ($admin) {echo "<br>Avant parcours : ".ecrire_temps($debut_execution, "4");}

		//Pr�pare les statistiques de lecture
		$tmp = enregistreLeParcours($userColis, $ouvrage, $source, $time, $etape, $parcours, $cible, $type, $notion0);

		if ($admin) {echo "<br>Apr�s parcours : ".ecrire_temps($debut_execution, "4");}

		if ($admin){
			//Affiche les statistiques de lecture pour les administrateurs
			afficheShowHide("LECTO", "Statistiques de lecture", $tmp);
		}

	}
	if ($transCorpus) {
		$notion = $notion0;
	}
} while ($nextStep != "");
//
//
//Affichage du temps pass� dans la boucle
if ($cnt > 0) {
	echo "<p><b>$cnt paragraphes affich�s</b>";
	if ($nombre_noeuds!=0) {
		$prcnt = ($cnt/$nombre_noeuds)*100;
		echo " ($prcnt%)";
	}
}

CloseTable();
include("footer.php");
ob_end_flush();

function calcElapsedTime($time)
{

       // calculate elapsed time (in seconds!)

       $diff = time()-$time;
       $daysDiff = floor($diff/60/60/24);
       $diff -= $daysDiff*60*60*24;
       $hrsDiff = floor($diff/60/60);
       $diff -= $hrsDiff*60*60;
       $minsDiff = floor($diff/60);
       $diff -= $minsDiff*60;
       $secsDiff = $diff;

       return ($daysDiff.'d '.$hrsDiff.'h '.$minsDiff.'m '.$secsDiff.'s');

}
function debut_calcultemps() {
$trouver_temps = explode(' ',microtime() );
$temps_debut = $trouver_temps[1].substr($trouver_temps[0], 1);
return $temps_debut;
}

function ecrire_temps($temps_debut,$precision) {
$partie_temps = explode(' ',microtime() );
$fin_temps = $partie_temps[1].substr($partie_temps[0],1);
$chrono = number_format($fin_temps - $temps_debut, 4);
if($precision > strlen($chrono)) {                      //si la precision demand�e est plus grande que la longueur de la chaine
$chrono = substr($chrono, 0, strlen($chrono));          //on donne la precision maximale
} else {
$chrono = substr($chrono, 0, $precision);
}
return $chrono;
} 
?>
