<?php

/******************************************************************************/
/*                                                                            */
/* moteurCB.compte.inc - phpCB                                                       */
/*                                                                            */
/******************************************************************************/
/*                                                                            */
/* Requirements: PHP, MySQL and web-browser                                   */
/*                                                                            */
/* Author: Marc Augier                                                        */
/*         <marc.augier@cote-azur.cci.fr>                                     */
/*                                                                            */
/* Created: 26 November 2002                                                  */
/*                                                                            */
/* Copyright (c) 2002 LCP - MEDIATEC                                          */
/*                                                                            */
/* This file is part of phpCB (http://colisciences.net/)                      */
/*                                                                            */
/******************************************************************************/

function supprimer($mot){
	global $dbi;
// Ajouter à la liste des stopwords
   	sql_query("insert into cb_occurences_stopwords values (NULL, '$mot')", $dbi);
	echo "<p>$mot a été ajouté à la liste des mots ignorés par le comptage.";
//Effacer les entrées existantes
   	$sql = sql_query("select motId from cb_occurences_cumul where mot ='$mot'", $dbi);
	list($motId) = sql_fetch_row($sql, $dbi);
   	sql_query("delete from cb_occurences where mot='$motId'", $dbi);
   	sql_query("delete from cb_occurences_cumul where mot='$mot'", $dbi);
	echo "<p>Les données sur $mot ont été effacées.";

   	$sql = sql_query("select distinct mot from cb_occurences", $dbi);
	$motNB = sql_num_rows($sql, $dbi);
	afficheListe(0,$motNB);
}

function creation(){
	global $valeur, $nombre_noeuds, $ouvrage, $texte ;
	set_time_limit(120);
	
	include("html2mots.class.php");

	$valeur = 1;
	$max_length=15;				 					// max length of words you want to get.
	$filter='1';								   // determine if you want to filter unnecessary words like 'am','is',....

	echo "<p>Construction de la liste de mots.";

	do {
//debug			echo "<p>Construction de la liste de mots pour le paragraphe: $valeur sur $nombre_noeuds";
		decodeDocument($valeur);
//	    $url = $colisroot.$ouvrage."/$valeur.xml";
		$url = "inc:".$texte;
		$f=new html2word($url, $max_length, $filter);     // make new instance of object html2word.
//debug			$f->show();
		$f->store($ouvrage,$valeur);
		$valeur++;
	} while ($valeur < $nombre_noeuds);
	echo "<p>Construction terminée.";
}

function recherche($query){
	global $dbi;
	
	echo "<h2>Les occurences de $query dans les ouvrages</h2>";

	$compteur = new pile;
	$compteur->ajoute("debut");
	
	$maxCount = 0;

    $sql = sql_query("select distinct ouvrage_id, paragraphe, o.count from cb_occurences o, cb_occurences_cumul c where c.mot = '$query' and o.mot=motId order by ouvrage_id, paragraphe", $dbi);
//	echo "select ouvrage_id, paragraphe, count from cb_occurences o, cb_occurences_cumul c where c.mot = '$query' and o.mot=motId order by ouvrage_id";
	$save_ouvrage = 0;

	while (list($ouvrage_id, $paragraphe, $count) = sql_fetch_row($sql, $dbi)){
		if ($save_ouvrage!=$ouvrage_id){
			$save_ouvrage = $ouvrage_id;
			$sql2 = sql_query("select titre,nombre_noeuds,date_titre,type_book from cb_ouvrages where pid='$ouvrage_id'",$dbi);
			list($titre,$nb_noeuds, $date_titre,$type_book)=sql_fetch_row($sql2, $dbi);
			echo "<h3>".afficheTitreOuvrage($titre, $date_titre, $type_book)."</h3>";
		}

//		echo "<br> $ouvrage_id, $paragraphe, $count ";
		echo affichageIcone($paragraphe, "C".$count);
		if ($count>$maxCount) {
			$maxCount = $count;
		}
		$compteur->ajouteUnique($count);
	}
	echo "</td><td>";
	afficheEchelleCouleurs($maxCount, $compteur,"");
}

function afficheListe($offset, $motNB){
	global $dbi, $tri;
	// pagination - don't show the pager link if there are less than 50 words

	$step = 50;
	if ($motNB > $step){
		if(!isset($offset)){
			$offset=0;
		}
		$next     = $offset + $step;
		$previous = $offset - $step;

		$navLink = "<table width=\"100%\" border=\"0\">\n"
       	  ."<tr>\n"
		  ."<td align=\"left\">";

		if ($previous >= 0){
//		$navLink .= "<small><a href=\"$PHP_SELF?offset=$previous\">&lt;&lt; 50 précédents</a></small>";
			$navLink .= boutonSelfCommande("&offset=$previous","&lt;&lt; 50 précédents","_self");
		}
		$cmd = boutonSelfCommande("&tri=count","Trier par nb+","_self");
		$cmd .= boutonSelfCommande("&tri=tnuoc","Trier par nb-","_self");
		$cmd .= boutonSelfCommande("&tri=mot","Trier par mot+","_self");
		$cmd .= boutonSelfCommande("&tri=tom","Trier par mot-","_self");
		$navLink .= "</td>\n<td>$offset/$motNB</td><td>$cmd</td>"
       	  ."<td align=\"right\">";

		if ($next < $motNB){
//		$navLink .= "<small><a href=\"$PHP_SELF?offset=$next\">50 suivants &gt;&gt;</a></small>";
			$navLink .= boutonSelfCommande("&offset=$next","50 suivants &gt;&gt;","_self");
		}

		$navLink .= "</td>\n"
          ."</tr>\n"
          ."</table>\n";
	} else {
		$offset = 0;
	}
	echo $navLink;

	if ($tri=="count"){
		$order = "count desc";
	}else if ($tri=="tnuoc"){
		$order = "count asc";
	}else if ($tri=="mot"){
		$order = "mot desc";
	}else if ($tri=="tom"){
		$order = "mot asc";
	}else{
		$order = "count desc";
	}
   	$sql = sql_query("select mot, count from cb_occurences_cumul ORDER BY $order LIMIT $offset, $step", $dbi);

	echo "<table width=\"80%\" align=\"center\" border=\"0\" bgcolor=\"white\">\n";
	while (list($mot, $count) = sql_fetch_row($sql, $dbi)){
		echo "<tr><td width=\"20\"><a href=\"parcours.php?name=Parcours_Hypertexte&file=moteurCB&parcours=compte&op=stop&word=$mot\"><img src=\"images/delete.gif\" alt=\"Supprimer\"></a></td><td align=\"left\"><a href=\"parcours.php?name=Parcours_Hypertexte&file=moteurCB&parcours=compte&op=search&query=$mot\">$mot</a></td><td align=\"right\">$count</td></tr>\n";
	}
	echo "</table>";
	echo $navLink;
}

switch ($op){
	case "stop":
		supprimer($word);
	break;
	case "search":
		recherche($query);
	break;
	default:
	//set_time_limit(120);
    	$sql = sql_query("select distinct mot from cb_occurences where ouvrage_id = '$ouvrage'", $dbi);
		$motNB = sql_num_rows($sql, $dbi);
		if ( $motNB > 0) {
			echo "<p>Le comptage a déjà été effectué pour cet ouvrage<br>Voici la liste des $motNB mots enregistrés<br>";
			afficheListe($offset, $motNB);
		} else {
			creation();
		}	
	break;
}

?>