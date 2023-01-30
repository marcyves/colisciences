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

$compteur = new pile;
$compteur->ajoute("debut");

switch ($scope){
case "moi":

	$visiteur = api_colis_user_id($userColis);	

	if ($pid!=""){
			$sql = mysqli_query($dbi, "select titre,nombre_noeuds,date_titre,type_book from cb_ouvrages where pid='$pid'");
			list($titre,$nb_noeuds, $date_titre,$type_book)=mysqli_fetch_row($sql);
			$label = afficheTitreOuvrage($titre, $date_titre, $type_book);
			afficheLeTitre("Les parcours dans $label pour $visiteur","du nombre de paragraphes visualisés par ce visiteur",$mode, $limit);
			$maxCount = afficheLesParagraphes($lecture,$visiteur,$mode, $limit,$pid,$nb_noeuds);
			$limit = afficheEchelleCouleurs($maxCount, $compteur,"");
			echo "</table>";
	}else{
			afficheLeTitre("Les parcours dans les ouvrages du corpus pour $visiteur","du nombre de paragraphes visualisés par ce visiteur",$mode, $limit);
			$maxCount = afficheListeOuvrages($lecture,$visiteur,$mode, $limit);
			$limit = afficheEchelleCouleurs($maxCount, $compteur,"");
			echo "</table>";
	}
break;
case "parcours":
	if ($visiteur!=""){
		if ($pid!=""){
			$sql = mysqli_query($dbi, "select titre,nombre_noeuds,date_titre,type_book from cb_ouvrages where pid='$pid'");
			list($titre,$nb_noeuds, $date_titre,$type_book)=mysqli_fetch_row($sql);
			$label = afficheTitreOuvrage($titre, $date_titre, $type_book);
			afficheLeTitre("Les parcours dans $label de $visiteur"," de l'étape du parcours",$mode, $limit);
			$maxCount = afficheLeParcours($lecture,$visiteur,$mode, $limit,$pid);
			$limit = afficheEchelleCouleurs($maxCount, $compteur,"");
			echo "</table>";
		}else{
			afficheLeTitre("Les parcours de $visiteur dans les ouvrages"," de l'étape du parcours",$mode, $limit);
			$maxCount = afficheLeParcours($lecture,$visiteur,$mode, $limit,0);
			$limit = afficheEchelleCouleurs($maxCount, $compteur,"");
			echo "</table>";
		}
	}else{
			echo "Choix invalide";
	}
break;
// affichage du lectochrome des noms des visiteurs
case "visiteur":
	if ($visiteur!=""){
		if ($pid!=""){
		//Pour un ouvrage particulier
			$sql = mysqli_query($dbi, "select titre,nombre_noeuds,date_titre,type_book from cb_ouvrages where pid='$pid'");
			list($titre,$nb_noeuds, $date_titre,$type_book)=mysqli_fetch_row($sql);
			$label = afficheTitreOuvrage($titre, $date_titre, $type_book);
			afficheLeTitre("Les lectures dans $label de $visiteur","du nombre de paragraphes visualisés par ce visiteur",$mode, $limit);
			echo boutonSelfCommande("&scope=parcours","Ses parcours","_self","bouton")."<p>";
			$maxCount = afficheLesParagraphes($lecture,$visiteur,$mode, $limit,$pid,$nb_noeuds);
			$limit = afficheEchelleCouleurs($maxCount, $compteur,"");
			echo "</table>";
		}else{
		//pour tous les ouvrages
			afficheLeTitre("Les parcours dans les ouvrages du corpus pour $visiteur","du nombre de paragraphes visualisés par ce visiteur",$mode, $limit);
			echo boutonSelfCommande("&scope=parcours","Ses parcours","_self","bouton")."<p>";
			$maxCount = afficheListeOuvrages($lecture,$visiteur,$mode, $limit);
			$limit = afficheEchelleCouleurs($maxCount, $compteur,"");
			echo "</table>";
		}
	}else{
		if($pid!=""){
		//Pour un ouvrage particulier
			$sql = mysqli_query($dbi, "select titre,nombre_noeuds,date_titre,type_book from cb_ouvrages where pid='$pid'");
			list($titre,$nb_noeuds, $date_titre,$type_book)=mysqli_fetch_row($sql);
			$label = afficheTitreOuvrage($titre, $date_titre, $type_book);
			afficheLeTitre("Les lectures dans $label de $visiteur","du nombre de paragraphes visualisés par ce visiteur",$mode, $limit);
			$maxCount = 0;
			$tmp = "";
			$sql = mysqli_query($dbi, "select distinct user from cb_parcours_count where ouvrage='$pid' order by user");
			while (list($visiteur)=mysqli_fetch_row($sql)){
			//	echo "<p>recherche pour $titre";
				if ($lecture=="click"){
					$requete = mysqli_query($dbi, "select sum(count) from cb_parcours_count where user='$visiteur' and ouvrage='$pid'");
				}else if ($lecture=="elapse"){
					$requete = mysqli_query($dbi, "select sum(elapsed) from cb_parcours_count where user='$visiteur' and ouvrage='$pid'");
				}	
				list($count) = mysqli_fetch_row($requete);
				$compteur->ajouteUnique($count);
				if (($mode!="short")||($count>=$limit)) {
//					$tmp .= "<div class=\"C$count\">".boutonSelfCommande("&visiteur=$visiteur"," $visiteur ($count) ","_self","")."</div>";	
					$tmp .= boutonSelfCommande("&visiteur=$visiteur"," $visiteur ($count) ","_self","C$count");
				}
				if ($count>$maxCount) {
					$maxCount= $count;
				}
			}
		}else{
			//pour tous les ouvrages
			afficheLeTitre("Les parcours dans les ouvrages du corpus par visiteur","du nombre de paragraphes visualisés par un visiteur",$mode, $limit);
			$maxCount = 0;
			$tmp = "";
			$sql = mysqli_query($dbi, "select distinct user from cb_parcours_count order by user");
			while (list($visiteur)=mysqli_fetch_row($sql)){
			//	echo "<p>recherche pour $titre";
				if ($lecture=="click"){
					$requete = mysqli_query($dbi, "select sum(count) from cb_parcours_count where user='$visiteur'");
				}else if ($lecture=="elapse"){
					$requete = mysqli_query($dbi, "select sum(elapsed) from cb_parcours_count where user='$visiteur'");
				}	
				list($count) = mysqli_fetch_row($requete);
				$compteur->ajouteUnique($count);
				if (($mode!="short")||($count>=$limit)) {
//					$tmp .= "<div class=\"C$count\">".boutonSelfCommande("&visiteur=$visiteur"," $visiteur ($count) ","_self","")."</div>";	
					$tmp .= boutonSelfCommande("&visiteur=$visiteur"," $visiteur ($count) ","_self","C$count");
				}
				if ($count>$maxCount) {
					$maxCount= $count;
				}
			}
		}
		echo $tmp
		."</td>";
		$limit = afficheEchelleCouleurs($maxCount, $compteur,"");
		echo "</table>";
	}
break;
case "visiteurthis":
	$sql = mysqli_query($dbi, "select titre,nombre_noeuds,date_titre,type_book from cb_ouvrages where pid='$ouvrage'");
	list($titre,$nb_noeuds, $date_titre,$type_book)=mysqli_fetch_row($sql);
	$label = afficheTitreOuvrage($titre, $date_titre, $type_book);
	afficheLeTitre("Les lectures dans $label de tous les visiteurs","du nombre de paragraphes visualisés par les visiteurs.",$mode, $limit);

	$maxCount = afficheLesParagraphes($lecture,"",$mode, $limit,$ouvrage,$nb_noeuds);

	$limit = afficheEchelleCouleurs($maxCount, $compteur,"");
	echo "</table>";
break;
// On a demandé l'affichage du lectochrome représentant tous les ouvrages
case "corpus":
	afficheLeTitre("Les parcours dans les ouvrages du corpus","du nombre de fois oé le paragraphe a été visualisé",$mode, $limit);
	$maxCount = afficheListeOuvrages($lecture,"",$mode, $limit);
	$limit = afficheEchelleCouleurs($maxCount, $compteur,"");
	echo "</table>";
break;
case "moithis":
	afficheLeTitre("Mes parcours dans cet ouvrage","du nombre de fois oé le paragraphe a été visualisé",$mode, $limit);
	$maxCount = afficheLesParagraphes($lecture,$userColis,$mode, $limit,$ouvrage,$nombre_noeuds);
	afficheEchelleCouleurs($maxCount, $compteur,"");
	echo "</table>";
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
	
	<table align=\"center\" width=\"85%\">
	<tr>
	<td align=\"center\" class=\"old\"><h3>Afficher les parcours de lecture...</h3></td>
	<td align=\"center\" class=\"old\">
	<h3>...par visites</h3>
	<td align=\"center\" class=\"old\">
	<h3>...par durée</h3>
	</tr>
	<tr>
	<td>Vous($userColis), dans cet ouvrage</td>
	<td width=\"25%\" align=\"center\">".boutonSelfCommande("&scope=moithis&lecture=click","voir","_self")."</td>
	<td width=\"25%\" align=\"center\">".boutonSelfCommande("&scope=moithis&lecture=elapse","voir","_self")."</td>
	</tr>
	<tr>
	<td>Vous, dans le corpus</td>
	<td width=\"25%\" align=\"center\">".boutonSelfCommande("&scope=moi&lecture=click","voir","_self")."</td>
	<td width=\"25%\" align=\"center\">".boutonSelfCommande("&scope=moi&lecture=elapse","voir","_self")."</td>
	</tr>
	<tr>
	<td>Tous les visiteurs, cet ouvrage</td>
	<td width=\"25%\" align=\"center\">".boutonSelfCommande("&scope=visiteurthis&lecture=click","voir","_self")."</td>
	<td width=\"25%\" align=\"center\">".boutonSelfCommande("&scope=visiteurthis&lecture=elapse","voir","_self")."</td>
	</tr>
	<tr>
	<td>Tous les visiteurs, tout le corpus</td>
	<td width=\"25%\" align=\"center\">".boutonSelfCommande("&scope=visiteur&lecture=click","voir","_self")."</td>
	<td width=\"25%\" align=\"center\">".boutonSelfCommande("&scope=visiteur&lecture=elapse","voir","_self")."</td>
	</tr>
	<tr>
	<td>Tous les ouvrages</td>
	<td align=\"center\">".boutonSelfCommande("&scope=corpus&lecture=click","voir","_self")."</td>
	<td align=\"center\">".boutonSelfCommande("&scope=corpus&lecture=elapse","voir","_self")."</td>
	</tr>

	</table>
	<br>";
break;
}
	$flagParcours = false;

//
// Fonctions locales
//

function afficheLeParcours($lecture,$visiteur,$mode, $limit,$ouvrage){
	global $dbi, $compteur;

	$maxCount = 0;
	$source0 = 0;
	$pid0 = 0;
	if ($ouvrage!=0){
		$where = "and ouvrage=$ouvrage";
	} else {
		$where = "";
	}

//debug echo "<p>appel afficheLeParcours $visiteur,$mode, $limit,$ouvrage,$nombre_noeuds<br>";
	$sql = mysqli_query($dbi, "select timestamp, etape, ouvrage, source, parcours, cible, type, notion from cb_parcours where user='$visiteur' $where order by timestamp asc");
//debug		echo "select count(source) from cb_parcours where $sqlvisiteur ouvrage=$ouvrage and source=$i";
	while (list($timestamp, $etape, $pid, $source, $parcours, $cible, $type, $notion) = mysqli_fetch_row($sql)){
//		echo "<p>etape: $etape, source $source, $parcours,cible $cible,type $type,notion $notion";
		if ($pid!=$pid0){
		//Initialisation de fakeEtape au changement d'ouvrage
		//Cette variable sert é afficher une progression par etape méme avec time-out
			if ($etape>0){
				$fakeEtape = $etape;
			}else{
				$fakeEtape = 1;
			}
		//Lecture des détails de l'ouvrage pour affichage
		    $sql_pid = mysqli_query($dbi, "select titre, nom, prenom from cb_ouvrages, cb_auteurs where aid=auteur and pid=$pid");
	 		list($titre, $nom, $prenom) = mysqli_fetch_row($sql_pid);
			echo "\n<h3>$titre ($prenom $nom)</h3><h4>".api_colis_affiche_date($timestamp,"")."</h4>";
		//Sauvegarde de la date pour identifier les changements
			$date0 = api_colis_affiche_date($timestamp,"d");
		}
		if ($source!=$source0){
		//Ce n'est pas un vrai parcours?
		//sans doute du aux time-outs
//			if ($pid==$pid0) echo "<br>";
			if ($date0 != api_colis_affiche_date($timestamp,"d")){
			//Si changement de date, on l'affiche au complet
				$date0 = api_colis_affiche_date($timestamp,"d");
				echo "\n<h4>".api_colis_affiche_date($timestamp,"")."</h4>".affichageIcone($source, "C".$fakeEtape);		
			}else{
			//Affichage de l'heure si méme jour
				echo "\n ".api_colis_affiche_date($timestamp,"t")." ".affichageIcone($source, "C".$fakeEtape);
			}
		}
		if ($type!="") $type = " ($type)";
		if ($notion!="") $notion = " = $notion";

		if ($cible=="") {
			$cible = "????";		
			echo "==".$parcours."==>".$type.$notion;
			$etape= $fakeEtape++;
		} else {
			echo "==".$parcours."==>".$type.$notion." ".affichageIcone($cible, "C".$etape);
			$fakeEtape = $etape;
			if ($etape==0){
				echo "<br>";
			}
		}

		$compteur->ajouteUnique($etape);

		$source0 = $cible;
		$pid0 = $pid;
		if ($etape>$maxCount) {
			$maxCount= $etape;
		}
	}
	echo "</td><td>";
	
	return $maxCount;
}


function afficheLesParagraphes($lecture,$visiteur,$mode, $limit,$ouvrage,$nombre_noeuds){
	global $dbi;
	global $compteur;

	$maxCount = 0;
	$tmp = "";
	if ($visiteur!=""){
		$sqlvisiteur = "user='$visiteur' and";
		$table  = "cb_parcours";
		if ($lecture=="click"){
			$select = "count(source)";
		}else{
			$select = "sum(elapsed)";
		}
	}else{
		$sqlvisiteur = "";
		if ($lecture=="click"){
			$select = "count";
			$table  = "cb_parcours_paragraphe";
		}else{
			$select = "sum(elapsed)";
			$table  = "cb_parcours";
		}
	}

//debug echo "<p>appel afficheLesParagraphes $visiteur,$mode, $limit,$ouvrage,$nombre_noeuds";
	for ($i=2;$i<=$nombre_noeuds;$i++){
	//  $sql = mysqli_query($dbi, "select etape, ouvrage, source, parcours, cible, type, notion, user from cb_parcours where ouvrage=$ouvrage");
		$sql = "select $select from $table where $sqlvisiteur ouvrage=$ouvrage and source=$i";
//echo $sql;
		$result = mysqli_query($dbi, $sql);
		list($count) = mysqli_fetch_row($result);
//echo $count;
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
		$sql .= " sum(elapsed) from cb_parcours_count where";
	}
	if ($visiteur!=""){
		$sql .= " user='$visiteur' and";
	}
	// On fait la liste des ouvrages
	$result = mysqli_query($dbi, "select pid, titre,debut,date_titre,type_book from cb_ouvrages where active=1 order by date_titre, titre");
	while (list($pid, $titre, $debut,$date_titre,$type_book)=mysqli_fetch_row($result)){
		// Pour chaque ouvrage, on vérifie combien de fois l'internaute l'a lu
//	echo "<p>recherche pour $titre";
		$requete = mysqli_query($dbi, "$sql ouvrage=$pid");
		list($count) = mysqli_fetch_row($requete);
		if ($count != 0){
			$compteur->ajouteUnique($count);
			if (($mode!="short")||($count>=$limit)) {
				if ($visiteur ==""){
					$tmp .= "<div class=\"C$count\">".creeLienOuvrage($pid,$titre,$debut,$date_titre,$type_book)." | ".boutonSelfCommande("&scope=visiteurthis&ouvrage=$pid","Les lecteurs","_self","")."</div>";
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
	L'échelle de couleur sur le cété donne les variation de teinte en fonction $quoi";
	if ($mode=="short") echo "<br>Ne sont présentés que ceux qui ont été lus $limit fois ou plus.";
	echo "</font>
	<table>
	<tr><td>";
}

?>