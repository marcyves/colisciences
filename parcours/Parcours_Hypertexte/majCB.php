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
/* This file is part of CoLiSciences (https://github.com/marcyves/colisciences)                      */
/*                                                                            */
/******************************************************************************/

$colispage = 1;
$index = 1;
$parcourspage = 1;

//require_once "class.inc";
//require_once "fonctions.xml.inc";
require_once "fonctions.affichage.inc";

/*
$titre = "Mise à jour de la table cb_parcours_paragraphe";
$sql = mysqli_query($dbi, "select max(pid)  from cb_ouvrages");
list( $maxOuvrage) = mysqli_fetch_row($sql);

*/
$titre = "Mise à jour de la table cb_parcours_count";
 $titre = stripslashes($titre);


// --------------------------------------------------------------------------------//	
// On envoie l'en-téte de la page
// --------------------------------------------------------------------------------//	
include("header.php");

// --------------------------------------------------------------------------------//	
// On ouvre la table dans laquelle va s'afficher le paragraphe
// --------------------------------------------------------------------------------//	
OpenTable();
/*
echo "<p>maxOuvrage = $maxOuvrage";
// Boucle sur tous les ouvrages
for ($i=0;$i<=$maxOuvrage;$i++){
	echo "<p>Ouvrage = $i";

	$sql = mysqli_query($dbi, "select nombre_noeuds from cb_ouvrages where pid='$i'");
	list( $maxNoeud) = mysqli_fetch_row($sql);
	echo " maxNoeud = $maxNoeud<br>";
	if ($maxNoeud>0){
		//Boucle sur tous les paragraphes de l'ouvrage
//		for ($paragraphe=1;$paragraphe<$maxNoeud;$paragraphe++){
$paragraphe = $maxNoeud;
			$sql = mysqli_query($dbi, "select count(source) from cb_parcours where ouvrage=$i and source='$paragraphe'");
			mysqli_query($dbi, $sql);
			list($count) = mysqli_fetch_row($sql);
			echo "$paragraphe = $count |";
			$sql = "INSERT INTO cb_parcours_paragraphe ( pid , ouvrage , paragraphe , count ) VALUES ('', '$i', '$paragraphe', '$count')";
			if (!mysqli_query($dbi, $sql)) echo "<font color=red>ERREUR</font>";			
//		}
	}

}

echo "<h1>Traitement cb_parcours</h1>";
		$sql = mymysqli_query($dbi, "select distinct idParcours, user from cb_parcours order by user");
		while (list($idParcours, $visiteur)=mysqli_fetch_row($sql)){
			$user = clean_user_id($visiteur);
			if ($user!=$visiteur){
//			echo "change $visiteur en $user<br>";
				$sql2 = "update cb_parcours set user='$user' where idParcours='$idParcours'";
				$result = mymysqli_query($dbi, $sql2);
				if (!($result)) {
					echo "<font color=red>ERREUR: $result</font>";
				}
				$rc = mysqli_query($dbi, $sql2);
//				echo $sql2.";<br>";
			}else{
				echo "$visiteur é revoir<br>";
			}
		}

echo "<h1>traitement cb_parcours_count</h1>";
		$sql = mysqli_query($dbi, "select distinct user from cb_parcours_count order by user");
		while (list($visiteur)=mysqli_fetch_row($sql)){
			$user = clean_user_id($visiteur);
			if ($user!=$visiteur){
				echo "change $visiteur en $user<br>";
				$sql2 = mysqli_query($dbi, "update cb_parcours_count set user='$user' where user='$visiteur'");
			}else{
				echo "$visiteur é revoir<br>";
			}
		}

*/		
echo "<h1>traitement cb_parcours_count</h1>";
		$sql1 = mymysqli_query($dbi, "select distinct ouvrage, user from cb_parcours_count order by user");
		while (list($ouvrage, $visiteur)=mymysqli_fetch_row($sql1)){
			echo "<br>traitement pour $ouvrage et $visiteur: <br>";
			$sql = "select sum(count) from cb_parcours_count where ouvrage='$ouvrage' and user='$visiteur'";
//			echo $sql;
			$result = mymysqli_query($dbi, $sql);
			list($count) = mymysqli_fetch_row($result);
			echo "count=$count ";
			$sql = "select sum(elapsed) from cb_parcours_count where ouvrage='$ouvrage' and user='$visiteur'";
			$result = mymysqli_query($dbi, $sql);
			list($elapsed) = mymysqli_fetch_row($result);
			echo "elapsed=$elapsed";
			$sql = "delete from cb_parcours_count where ouvrage='$ouvrage' and user='$visiteur'";
			mymysqli_query($dbi, $sql);
			$sql = "insert into cb_parcours_count values( NULL, '$ouvrage', '$count', '$elapsed', '$visiteur')";
			mymysqli_query($dbi, $sql);
		}


CloseTable();
include("footer.php");

?>
