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

$compteur = new pile;
$compteur->ajoute("debut");

switch ($scope){
case "moi":

	$visiteur = api_colis_user_id($userColis);	

	if ($pid!=""){
			$sql = sql_query("select titre,nombre_noeuds,date_titre,type_book from cb_ouvrages where pid='$pid'",$dbi);
			list($titre,$nb_noeuds, $date_titre,$type_book)=sql_fetch_row($sql, $dbi);
			$label = afficheTitreOuvrage($titre, $date_titre, $type_book);
			afficheLeTitre("Les parcours dans $label pour $visiteur","du nombre de paragraphes visualisés par ce visiteur",$mode, $limit);
			$maxCount = afficheLesParagraphes($lecture,$visiteur,$mode, $limit,$pid,$nb_noeuds);
			$limit = afficheEchelleCouleurs($maxCount, $compteur);
	}else{
			afficheLeTitre("Les parcours dans les ouvrages du corpus pour $visiteur","du nombre de paragraphes visualisés par ce visiteur",$mode, $limit);
			$maxCount = afficheListeOuvrages($lecture,$visiteur,$mode, $limit);
			$limit = afficheEchelleCouleurs($maxCount, $compteur);
	}
break;
case "visiteur":
	if ($visiteur!=""){
		if ($pid!=""){
			$sql = sql_query("select titre,nombre_noeuds,date_titre,type_book from cb_ouvrages where pid='$pid'",$dbi);
			list($titre,$nb_noeuds, $date_titre,$type_book)=sql_fetch_row($sql, $dbi);
			$label = afficheTitreOuvrage($titre, $date_titre, $type_book);
			afficheLeTitre("Les parcours dans $label pour $visiteur","du nombre de paragraphes visualisés par ce visiteur",$mode, $limit);
			$maxCount = afficheLesParagraphes($lecture,$visiteur,$mode, $limit,$pid,$nb_noeuds);
			$limit = afficheEchelleCouleurs($maxCount, $compteur);
		}else{
			afficheLeTitre("Les parcours dans les ouvrages du corpus pour $visiteur","du nombre de paragraphes visualisés par ce visiteur",$mode, $limit);
			$maxCount = afficheListeOuvrages($lecture,$visiteur,$mode, $limit);
			$limit = afficheEchelleCouleurs($maxCount, $compteur);
		}
	}else{
		afficheLeTitre("Les parcours dans les ouvrages du corpus par visiteur","du nombre de paragraphes visualisés par ce visiteur",$mode, $limit);
		$maxCount = 0;
		$tmp = "";
		$sql = sql_query("select distinct user from cb_parcours order by user",$dbi);
		while (list($visiteur)=sql_fetch_row($sql, $dbi)){
		//	echo "<p>recherche pour $titre";
//creation table cb_parcours_count initialisation		
//			$result = sql_query("select pid from cb_ouvrages",$dbi);
//			while (list($pid)=sql_fetch_row($result, $dbi)){
//				$requete = sql_query("select count(source) from cb_parcours where user='$visiteur' and ouvrage='$pid'",$dbi);
//				list($count) = sql_fetch_row($requete, $dbi);
//				if ($count>0){
//					$insert = sql_query("INSERT INTO `cb_parcours_count` ( `ouvrage` , `count` , `user` ) VALUES ('$pid','$count','$visiteur' )", $dbi);
//				}
//			}

			$result = sql_query("select pid from cb_ouvrages",$dbi);
			while (list($pid)=sql_fetch_row($result, $dbi)){
				$requete = sql_query("select sum(elapsed) from cb_parcours where user='$visiteur' and ouvrage='$pid'",$dbi);
				list($count) = sql_fetch_row($requete, $dbi);
				if ($count>0){
					sql_query("update `cb_parcours_count` set elapsed='$count' where `ouvrage`='$pid' and `user`='$visiteur'", $dbi);
				}
			}
//fin creation
		
			if ($lecture=="click"){
				$requete = sql_query("select sum(count) from cb_parcours_count where user='$visiteur'",$dbi);
			}else if ($lecture=="elapse"){
				$requete = sql_query("select sum(elapsed) from cb_parcours where user='$visiteur'",$dbi);
			}	
			list($count) = sql_fetch_row($requete, $dbi);
			$compteur->ajouteUnique($count);
			if (($mode!="short")||($count>=$limit)) {
				$tmp .= "<div class=\"C$count\">".boutonSelfCommande("&visiteur=$visiteur","Les lectures de $visiteur ($count)","_self","")."</div>";	
			}
			if ($count>$maxCount) {
				$maxCount= $count;
			}
		}
		echo $tmp
		."</td>";
		$limit = afficheEchelleCouleurs($maxCount, $compteur);
	}
break;
case "corpus":
	afficheLeTitre("Les parcours dans les ouvrages du corpus","du nombre de fois où le paragraphe a été visualisé",$mode, $limit);
	$maxCount = afficheListeOuvrages($lecture,"",$mode, $limit);
	$limit = afficheEchelleCouleurs($maxCount, $compteur);
break;
case "this":
	afficheLeTitre("Les parcours dans cet ouvrage","du nombre de fois où le paragraphe a été visualisé",$mode, $limit);
	$maxCount = afficheLesParagraphes($lecture,"",$mode, $limit,$ouvrage,$nombre_noeuds);
	afficheEchelleCouleurs($maxCount, $compteur);
break;
default:
	echo "<h2>Lectochromie</h2>
	<font class=\"content\">
	Vous devez choisir dans la liste ci-dessous le type d'analyse des parcours que vous voulez effectuer.
	<br>
	Vous pouvez choisir d'une part de traiter les informations sur les parcours au niveau d'un utilisateur, d'un ouvrage ou sur l'ensemble du corpus.
	D'autre part vous pouvez aussi choisir de baser votre analyse sur la durée de lecture ou simplement sur l'appel d'un paragraphe particulier.
	</font>
	<p>
	<h3>Afficher les parcours ...</h3>
	<table align=\"center\" width=\"85%\">
	<tr>
	<td colspan=\"4\" align=\"center\" class=\"old\">
	<h3>...par nombre de lectures...</h3>
	</tr>
	<tr>
	<td width=\"25%\" align=\"center\">".boutonSelfCommande("&scope=moi&lecture=click","... les votres","_self")."</td>
	<td width=\"25%\" align=\"center\">".boutonSelfCommande("&scope=visiteur&lecture=click","... des visiteurs","_self")."</td>
	<td width=\"25%\" align=\"center\">".boutonSelfCommande("&scope=this&lecture=click","... de cet ouvrage","_self")."</td>
	<td align=\"center\">".boutonSelfCommande("&scope=corpus&lecture=click","... dans les ouvrages du corpus","_self")."</td>
	</tr>
	<tr>
	<td colspan=\"4\" align=\"center\" class=\"old\">
	<h3>...par durée de lecture...</h3>
	</tr>
	<tr>
	<td width=\"25%\" align=\"center\">".boutonSelfCommande("&scope=moi&lecture=elapse","... les votres","_self")."</td>
	<td width=\"25%\" align=\"center\">".boutonSelfCommande("&scope=visiteur&lecture=elapse","... des visiteurs","_self")."</td>
	<td width=\"25%\" align=\"center\">".boutonSelfCommande("&scope=this&lecture=elapse","... de cet ouvrage","_self")."</td>
	<td align=\"center\">".boutonSelfCommande("&scope=corpus&lecture=elapse","... dans les ouvrages du corpus","_self")."</td>
	</tr>
	</table>
	<br>";
break;
}
	$flagParcours = false;

//
// Fonctions locales
//

function afficheLesParagraphes($lecture,$visiteur,$mode, $limit,$ouvrage,$nombre_noeuds){
	global $dbi;
	global $compteur;

	$maxCount = 0;
	$tmp = "";
	if ($visiteur!=""){
		$sqlvisiteur = "user='$visiteur' and";
	}

//debug echo "<p>appel afficheLesParagraphes $visiteur,$mode, $limit,$ouvrage,$nombre_noeuds";
	for ($i=2;$i<=$nombre_noeuds;$i++){
	//  $sql = sql_query("select etape, ouvrage, source, parcours, cible, type, notion, user from cb_parcours where ouvrage=$ouvrage",$dbi);
		if ($lecture=="click"){
			$sql = sql_query("select count(source) from cb_parcours where $sqlvisiteur ouvrage=$ouvrage and source=$i",$dbi);
		}else if ($lecture=="elapse"){
			$sql = sql_query("select sum(elapsed) from cb_parcours where $sqlvisiteur ouvrage=$ouvrage and source=$i",$dbi);
		}	
//debug		echo "select count(source) from cb_parcours where $sqlvisiteur ouvrage=$ouvrage and source=$i";
		list($count) = sql_fetch_row($sql, $dbi);
		$compteur->ajouteUnique($count);
		if (($mode!="short")||($count>=$limit)) {
			$tmp .= "\n".affichageIcone($i, "C".$count);
		}
		if ($count>$maxCount) {
			$maxCount= $count++;
		}
	}
	echo affichageIcone("1", "C".$maxCount)
	.$tmp
	."</td><td>";
	return $maxCount;
}
/**
 * afficheListeOuvrages()
 * 
 * @param $visiteur
 * @param $mode
 * @param $limit
 * @return 
 */
function afficheListeOuvrages($lecture,$visiteur,$mode, $limit){
	global $dbi;
	global $compteur;
	
	$maxCount = 0;
	$tmp = "";
	$sql = "select";
	if ($lecture=="click"){
		$sql .= " sum(count) from cb_parcours_count where";
	}else if ($lecture=="elapse"){
		$sql .= " sum(elapsed) from cb_parcours where";
	}
	if ($visiteur!=""){
		$sql .= " user='$visiteur' and";
	}
	// On fait la liste des ouvrages
	$result = sql_query("select pid, titre,debut,date_titre,type_book from cb_ouvrages where active=1 order by date_titre, titre",$dbi);
	while (list($pid, $titre, $debut,$date_titre,$type_book)=sql_fetch_row($result, $dbi)){
		// Pour chaque ouvrage, on vérifie combien de fois l'internaute l'a lu
//	echo "<p>recherche pour $titre";
		$requete = sql_query("$sql ouvrage=$pid",$dbi);
		list($count) = sql_fetch_row($requete, $dbi);
		if ($count != 0){
			$compteur->ajouteUnique($count);
			if (($mode!="short")||($count>=$limit)) {
				if ($visiteur ==""){
					$tmp .= "<div class=\"C$count\">".creeLienOuvrage($pid,$titre,$debut,$date_titre,$type_book)."</div>";
				}else{
					$label = afficheTitreOuvrage($titre, $date_titre, $type_book);
					$tmp .= "<div class=\"C$count\">".boutonSelfCommande("&pid=$pid",$label,"_self","")."</div>";	
				}
			}
			if ($count>$maxCount) {
				$maxCount= $count;
			}
		}
	}
	echo $tmp
	."</td><td>";
	return $maxCount;
}

/**
 * afficheLeTitre()
 * 
 * @param $titre
 * @param $quoi
 * @param $mode
 * @param $limit
 * @return 
 */
function afficheLeTitre($titre, $quoi, $mode,$limit){
	echo "<h2>$titre</h2>
	<font class=\"content\">
	L'échelle de couleur sur le côté donne les variation de teinte en fonction $quoi";
	if ($mode=="short") echo "<br>Ne sont présentés que ceux qui ont été lus $limit fois ou plus.";
	echo "</font>
	<table>
	<tr><td>";
}

?>