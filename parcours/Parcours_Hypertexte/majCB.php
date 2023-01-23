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

$colispage = 1;
$index = 1;
$parcourspage = 1;

//require_once "class.inc";
//require_once "fonctions.xml.inc";
require_once "fonctions.affichage.inc";

/*
$titre = "Mise � jour de la table cb_parcours_paragraphe";
$sql = sql_query("select max(pid)  from cb_ouvrages",$dbi);
list( $maxOuvrage) = sql_fetch_row($sql, $dbi);

*/
$titre = "Mise � jour de la table cb_parcours_count";
 $titre = stripslashes($titre);


// --------------------------------------------------------------------------------//	
// On envoie l'en-t�te de la page
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

	$sql = sql_query("select nombre_noeuds from cb_ouvrages where pid='$i'",$dbi);
	list( $maxNoeud) = sql_fetch_row($sql, $dbi);
	echo " maxNoeud = $maxNoeud<br>";
	if ($maxNoeud>0){
		//Boucle sur tous les paragraphes de l'ouvrage
//		for ($paragraphe=1;$paragraphe<$maxNoeud;$paragraphe++){
$paragraphe = $maxNoeud;
			$sql = sql_query("select count(source) from cb_parcours where ouvrage=$i and source='$paragraphe'",$dbi);
			sql_query($sql, $dbi);
			list($count) = sql_fetch_row($sql, $dbi);
			echo "$paragraphe = $count |";
			$sql = "INSERT INTO cb_parcours_paragraphe ( pid , ouvrage , paragraphe , count ) VALUES ('', '$i', '$paragraphe', '$count')";
			if (!sql_query($sql, $dbi)) echo "<font color=red>ERREUR</font>";			
//		}
	}

}

echo "<h1>Traitement cb_parcours</h1>";
		$sql = mysql_query("select distinct idParcours, user from cb_parcours order by user",$dbi);
		while (list($idParcours, $visiteur)=sql_fetch_row($sql, $dbi)){
			$user = clean_user_id($visiteur);
			if ($user!=$visiteur){
//			echo "change $visiteur en $user<br>";
				$sql2 = "update cb_parcours set user='$user' where idParcours='$idParcours'";
				$result = mysql_query($sql2, $dbi);
				if (!($result)) {
					echo "<font color=red>ERREUR: $result</font>";
				}
				$rc = sql_query($sql2,$dbi);
//				echo $sql2.";<br>";
			}else{
				echo "$visiteur � revoir<br>";
			}
		}

echo "<h1>traitement cb_parcours_count</h1>";
		$sql = sql_query("select distinct user from cb_parcours_count order by user",$dbi);
		while (list($visiteur)=sql_fetch_row($sql, $dbi)){
			$user = clean_user_id($visiteur);
			if ($user!=$visiteur){
				echo "change $visiteur en $user<br>";
				$sql2 = sql_query("update cb_parcours_count set user='$user' where user='$visiteur'",$dbi);
			}else{
				echo "$visiteur � revoir<br>";
			}
		}

*/		
echo "<h1>traitement cb_parcours_count</h1>";
		$sql1 = mysql_query("select distinct ouvrage, user from cb_parcours_count order by user");
		while (list($ouvrage, $visiteur)=mysql_fetch_row($sql1)){
			echo "<br>traitement pour $ouvrage et $visiteur: <br>";
			$sql = "select sum(count) from cb_parcours_count where ouvrage='$ouvrage' and user='$visiteur'";
//			echo $sql;
			$result = mysql_query($sql);
			list($count) = mysql_fetch_row($result);
			echo "count=$count ";
			$sql = "select sum(elapsed) from cb_parcours_count where ouvrage='$ouvrage' and user='$visiteur'";
			$result = mysql_query($sql);
			list($elapsed) = mysql_fetch_row($result);
			echo "elapsed=$elapsed";
			$sql = "delete from cb_parcours_count where ouvrage='$ouvrage' and user='$visiteur'";
			mysql_query($sql);
			$sql = "insert into cb_parcours_count values( NULL, '$ouvrage', '$count', '$elapsed', '$visiteur')";
			mysql_query($sql);
		}


CloseTable();
include("footer.php");

?>
