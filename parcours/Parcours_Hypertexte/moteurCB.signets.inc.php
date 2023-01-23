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
/* Created: 26 November 2002                                                  */
/*                                                                            */
/* Copyright (c) 2002 LCP - MEDIATEC                                          */
/*                                                                            */
/* This file is part of phpCB (http://colisciences.net/)                      */
/*                                                                            */
/******************************************************************************/


//ALTER TABLE `cb_signets` ADD `auth` INT NOT NULL ;

function editSignet($sid){
	global $dbi;

	$sql = mysqli_query($dbi, "select categorie, ouvrage, user, paragraphe, commentaire, parcours, auth from cb_signets where  sid='$sid'");
	list($categorie, $ouvrage, $user, $paragraphe, $commentaire, $parcours, $auth ) = mysqli_fetch_row($sql);
	
	echo "<font class=\"title2\">Modification d'un signet</font>
		  <form method=\"post\" action=\"\">
			<table align=\"center\" width=\"80%\">
			<tr><td>
			<select name=\"cid\">";

	$result = mysqli_query($dbi, "select distinct cid, description from cb_signets_categories order by description");
	while(list($cid, $categorie0) = mysqli_fetch_row($result)) {
		if ($categorie==$cid){
			$select = " selected";
		}else{
			$select = "";	
		}
		echo "<option value=\"$cid\"$select>$categorie0</option>";
	}
	echo "</select></td>
	<td>Pour créer une nouvelle catégorie:<br>
		Catégorie:<input type=\"text\" name=\"categorie\"><br>
		Commentaire: <input type=\"text\" name=\"commentaire\"></td>
	</tr><tr>
	<td colspan=\"2\" align=\"center\">
			<input type=\"hidden\" name=\"p\" value=\"$parcours\">
			<input type=\"hidden\" name=\"ouvrage\" value=\"$ouvrage\">
			<input type=\"hidden\" name=\"valeur\" value=\"$paragraphe\">
				
			<input type=\"hidden\" name=\"cmd\" value=\"enregistre\">
			<input type=\"hidden\" name=\"parcours\" value=\"signets\">
			Texte du signet: <input type=\"text\" name=\"signet\" value=\"$commentaire\">
			<input type=\"submit\" value=\"créer\">
	</td>
	</tr>
	</table>
	</form>";

//				$sql = "update cb_signets set auth='$auth' where sid='$sid'";
//				mysqli_query($dbi, $sql);

}

function addSignet($parcours, $ouvrage, $valeur){
	global $dbi;
	
	echo "<font class=\"title2\">Création d'un nouveau signet</font>
		  <form method=\"post\" action=\"\">
			<table align=\"center\" width=\"80%\">
			<tr><td>
			<select name=\"cid\">";
	$result = mysqli_query($dbi, "select distinct cid, description from cb_signets_categories order by description");
	while(list($cid, $categorie) = mysqli_fetch_row($result)) {
		echo "<option value=\"$cid\">$categorie</option>";
	}
	echo "</select></td>
	<td>Pour créer une nouvelle catégorie:<br>
		Catégorie:<input type=\"text\" name=\"categorie\"><br>
		Commentaire: <input type=\"text\" name=\"commentaire\"></td>
	</tr><tr>
	<td colspan=\"2\" align=\"center\">
			<input type=\"hidden\" name=\"p\" value=\"$parcours\">
			<input type=\"hidden\" name=\"ouvrage\" value=\"$ouvrage\">
			<input type=\"hidden\" name=\"valeur\" value=\"$valeur\">
				
			<input type=\"hidden\" name=\"cmd\" value=\"enregistre\">
			<input type=\"hidden\" name=\"parcours\" value=\"signets\">
			Texte du signet: <input type=\"text\" name=\"signet\">
			<input type=\"submit\" value=\"créer\">
	</td>
	</tr>
	</table>
	</form>";
}

/**
 * showSignets()
 * 
 * @param $ouvrage
 * @param $userColis
 * @param $auth
 * @return 
 */
function showSignets($ouvrage, $userColis, $auth="9",$titre) {
	global $dbi;
// auth donne le niveau de visibilité des signets:
//	9 signet public
//	0 signet privé
	
	$result = mysqli_query($dbi, "select distinct cid, description from cb_signets_categories");
	if ($result->num_rows == 0) {
		echo "<font class=\"title2\">Il n'y a pas encore de catégorie définie, veuillez prévenir le webmaster.</font>";
	} else {
		$num = 0;
		$flagTitre = true;
		while(list($cid, $categorie) = mysqli_fetch_row($result)) {
		//Affiche la liste des categories
//debug echo "<p>categorie $categorie";
			$tmp = "<ul>";
			$num++;
			$flag = false;
			if ($userColis!=""){
				$where = "(user='$userColis' or auth>'$auth')";
			}else{
				$where = "auth>'$auth'";
			}
			$sql = mysqli_query($dbi, "select sid, user, paragraphe, commentaire, parcours, auth from cb_signets where ouvrage=$ouvrage and $where and categorie='$cid'");
			while(list($sid, $userSignet, $paragraphe, $commentaire, $parcours, $auth) = mysqli_fetch_row($sql)) {
				$tmp .= "<li>";
				//Si on est identifié, on a la possibilité de modifier ses propres signets
				if ($userColis==$userSignet){
					if ($auth=="0"){
						$alt="Rendre public";
						$niveau = 9;
					}else{
						$alt="Rendre privé";
						$niveau = 0;							
					}
					$tmp .= "
					<a href=\"".$_SERVER['REQUEST_URI']."&cmd=visibilite&niveau=$niveau&sid=$sid\"><img src=\"images/visi$auth.gif\" alt=\"$alt\"></a>
					<a href=\"".$_SERVER['REQUEST_URI']."&cmd=edit&sid=$sid\"><img src=\"images/edit.gif\" alt=\"Modifier\"></a>
					<a href=\"".$_SERVER['REQUEST_URI']."&cmd=delete&sid=$sid\"><img src=\"images/delete.gif\" alt=\"Effacer\"></a>
					";
				}else {
					$tmp .= "($userSignet) ";
				}
				$tmp .= lienOuvrage($paragraphe, $display, $commentaire, $parcours, $ouvrage);
				$flag = true;
			}
			$tmp .= "</ul>";
			if ($flag) {
				if ($flagTitre){
					$flagTitre = false;
					echo $titre;
				}
				afficheShowHide("CAT$num", $categorie, $tmp);
			}
		}
	}
}

/**
 * enregistreSignet()
 * 
 * @param $categorie
 * @param $ouvrage
 * @param $userColis
 * @param $valeur
 * @param $parcours
 * @param $commentaire
 * @return 
 */
function enregistreSignet($categorie, $ouvrage, $userColis, $valeur, $parcours, $commentaire) {
	global $dbi;
		
	// Enregistrement des informations
	// print_r($HTTP_POST_VARS);
	$sql = "insert into cb_signets values ( NULL,'$categorie', '$ouvrage','$userColis', '$valeur', '$parcours', '$commentaire','0')";
	mysqli_query($dbi, $sql);
}

// -----------------------------------------------------------------------------------------------------------------//
// Début du code
// -----------------------------------------------------------------------------------------------------------------//


	cookiedecode($user);
	$userColis = $cookie[1];
	if ($userColis == "") {
		echo "<font class=\"title2\">Vous devez étre enregistré et correctement authentifié pour ajouter vos propres signets.</font><br> Voici la liste des signets publics.<br>";
	}
	
//debug echo "<p>user=$user userColis=$userColis";
//debug print_r($HTTP_GET_VARS);
	switch($cmd) {
		case "visibilite":
			$sql = "update cb_signets set auth='$niveau' where sid='$sid'";
			mysqli_query($dbi, $sql);
		break;
		case "edit":
			editSignet($sid);
		break;
		case "delete":
			$sql = "delete from cb_signets where sid='$sid'";
			mysqli_query($dbi, $sql);
		break;
		case "ajoute":
			addSignet($p, $ouvrage, $valeur);
			CloseTable();
		
			OpenTable();
		break;
		case "enregistre":
			if ($cid=="0"){
				//Création d'une nouvelle catégorie
				if ($categorie==""){
					echo "<font class=\"title2\">Vous n'avez pas spécifié de nouvelle catégorie, revenez en arriére et recommencez.</font>";
				}else{
					$sql = "insert into cb_signets_categories values ( NULL,'$categorie', '$commentaire')";
					mysqli_query($dbi, $sql);
					$result = mysqli_query($dbi, "select cid from cb_signets_categories where description='$categorie'");
					list($cid) = mysqli_fetch_row($result);						
					echo "<font class=\"title2\">Nouvelle catégorie $categorie ajoutée.</font>";
				}					
			}
			if ($cid!=0) {
				//Soit on utilise une categorie existante, soit une nouvelle catégorie créée correctement au paragrpahe précédent
				enregistreSignet($cid, $ouvrage, $userColis, $valeur, $p, $signet);
			}
			$nextStep = $p;
		break;
		default:
		break;
	}

	showSignets($ouvrage, $userColis, "8","<font class=\"title2\">Les Signets de cet ouvrage</font><br>");

	CloseTable();
	echo "<br>";
	OpenTable();

	$sql = mysqli_query($dbi, "select pid,titre from cb_ouvrages where pid!='$ouvrage' and active='1'");
	while(list($pid, $titre) = mysqli_fetch_row($sql)) {
		showSignets($pid, $userColis, "8","<font class=\"title2\">Les Signets de <i>\"$titre\"</i></font><br>");
	}

?>