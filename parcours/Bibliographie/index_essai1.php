<?php

/***********************************************************
*modification Vincent LE Druillennec Romain Ferran 12 février 2004
* but refaire le moteur de recherche
************************************************************/	

######################################################################
# PHP-NUKE: Web Portal System
# ===========================
#
# Copyright (c) 2002 by Francisco Burzi (fbc@mandrakesoft.com)
# http://phpnuke.org
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
######################################################################

print_r($_GET);

// d'abord les donnees users puis les donnees liaisons
if(isset($_GET["op"]))$op=$_GET["op"];
if(isset($_GET["methrecherhe"]))$methrecherhe=$_GET["methrecherhe"];
if(isset($_GET["opmulit"]))$opmulit=$_GET["opmulit"];
if(isset($_GET["titres"]))$titres=$_GET["titres"];
if(isset($_GET["auteurs"]))$auteurs=$_GET["auteurs"];
if(isset($_GET["firstdates"]))$firstdates=$_GET["firstdates"];		
if(isset($_GET["seconddates"]))$seconddates=$_GET["seconddates"];		
if(isset($_GET["formats"]))$formats=$_GET["formats"];


if(isset($_GET["firstetou"]))$firstetou=$_GET["firstetou"];
if(isset($_GET["secondetou"]))$secondetou=$_GET["secondetou"];	

/*
* on verifie si l'operateur methrecherhe existe sinon on le cré et on mets rien dedans
* ceci impliquera l'affichage par default dans le switch
*/

if (!isset($methrecherhe))
{
	$methrecherhe="";
}

/*
*switch principale de la page qui permet d'aller au moteur à un critère au mutlicritère
* par défaut mutlicritère
*/

switch($methrecherhe) {

    case "content":
	//print("content<br>\n");
    content($tid, $ltr, $page, $query);
    break;

    case "simple":
	//print("simple<br>\n");
	recherchesimple($op,$eid, $query, $ltr);
    break;

    case "mutli":
	//print("multi<br>\n");
	recherchemulti($opmulit,$titres,$auteurs,$firstdates,$seconddates,$formats,$firstetou,$quatriemeetou);
    break;

    default:
	//print("default<br>\n");
	recherchemulti($opmulit,$titres,$auteurs,$firstdates,$seconddates,$formats,$firstetou,$quatriemeetou);
    break;

}


/*
* fonction d'origine 
*/

function content($tid, $ltr, $page=0, $query="") {
    global $prefix, $dbi, $sitename, $admin, $module_name;
    include("header.php");
    OpenTable();
    $result = sql_query("SELECT Dates, Type, Titre, Compil, Lieu, EditeurRevue, Reference, Commentaires, Auteurs, Hyperlien , TypeHyperlien from cb_biblio where Numero='$tid'", $dbi);		
	//afficheEntreesBiblio($result);
	newafficheEntreesBiblio($result);
    CloseTable();
    include("footer.php");
}

/**
 * newafficheEntresBiblio()
 * Meme fonction que lla fonction afficheEntresBiblio.  La différence elle affiche les hyperliens
 * @return 
 */
function newafficheEntreesBiblio($sql){
	global $dbi, $theme;

   	while (list($Dates, $type, $Titre, $Compil, $Lieu, $EditeurRevue, $Reference, $Commentaires, $Auteurs, $Hyperlien , $TypeHyperlien ) = sql_fetch_row($sql, $dbi)) 
	{
		$type=strtolower($type);
		switch ($type)
		{
		case "livre":
			echo "<br><table><tr><td valign=\"top\"><img width=\"12\" height=\"10\"  src=\"themes/$theme/img/point_bleu.gif\"></td><td class=\"liste1\">"
 			."($Dates), $Auteurs, "
 			."<font class=\"liste2\"><i>$Titre</i>,</font>"
 			." $Lieu, $EditeurRevue"
 			."</td></tr></table>";
		break;
		case "article":
 			echo "<br><table><tr><td valign=\"top\"><img width=\"12\" height=\"10\"  src=\"themes/$theme/img/point_bleu.gif\"></td><td class=\"liste1\">"
 			."($Dates), $Auteurs, "
 			."<font class=\"liste2\">\"$Titre\",</font>"
 			." $EditeurRevue, $Reference"
 			."</td></tr></table>";
		break;
		default:
			echo "<li>$type<br>($Dates), $Titre, $Lieu, $EditeurRevue, $Reference, $Auteurs";
		break;
		}
		if ($Commentaires != "")
		{
 			echo "<font class=\"tiny3\">$Commentaires,$Compil</font><br>";
		}
		if ($TypeHyperlien == "1")
		{
 			echo "<font class=\"liste1\"><p align=\"right\">Nous avons un lien interne au site : <a href=\"http://$Hyperlien\">cliquez ici </a> .</p></font><br>";
		}
		elseif ($TypeHyperlien == "2")
		{
 			echo "<font class=\"liste1\"><p align=\"right\">Nous avons un lien externe au site :<a href=\"http://$Hyperlien\" target=\"_blank\">cliquez ici</a> .</p></font><br>";
		}

   	}
	echo "<center><br><br>[ <a href=\"javascript:history.go(-1)\">Retour</a> ]</center>";
}

//****************************debut de la fonction à un critère************************************

function recherchesimple($op,$eid, $query, $ltr)
{
	global $module, $prefix, $sitename, $dbi, $admin;

	include("header.php");
	OpenTable();
	print("<a href=\"index.php?module=$module&methrecherhe=mutli\">recherche à multicritère</a><br> \n");//&amp;file=search
	
	if (!isset($op))
	{
		$op="";
		$eid = "titre";
	}
	
	switch($op) {
//**************A  supprimer
	    case "content":
		print("content2<br>\n");
	   // content($tid, $ltr, $page, $query);
    	    break;
//****************************
	    case "list_content":
		//print("list_content<br>\n");
		alpha($eid);
		encysearch();
	    break;

	    case "terms":
		//print("terms<br>\n");
		terms($eid, $ltr);
	    break;

	    case "search":
		//print("search2<br>\n");
		alpha($eid);
		encysearch();
		/*if($eid == "Auteurs_foie")
		{
			$eid="Titre";
			$query="sucre%foie";
		}*/
		if($query != "")
		{
			terms($eid, $query);
		}
		else
		{
			echo "<p align=\"justify\"><i> Le champs de la requete est vide. </i></p><br>";
		}
	    break;

	    default:
		//print("default2<br>\n");
		alpha($eid);
		encysearch();
	    break;
	}
      echo "<center>"._GOBACK."</center>";
	CloseTable();
	include("footer.php");
}

function encysearch() {
    global $module_name;
    echo "<center><form action=\"parcours.php?name=$module_name \" method=\"post\">" //parcours.php?name=$module_name&amp;file=search 
	
	 	."<input type=hidden name=methrecherhe value=simple>"
	     ." <select name=\"eid\" size=\"1\">
           	<option value=\"\" selected>-- Mots Clés --</option>
            <option value=\"Auteurs\">Auteur</option>
	    	<option value=\"Titre\">Début du titre</option>
            <option value=\"Article\">Article</option>
	    	<option value=\"Livre\">Livre</option>
            <option value=\"EditeurRevue\">Editeur</option>
		     	</select> &nbsp;&nbsp;";
				
/*	<option value=\"Auteurs_foie\">Auteurs qui ont travaillé sur le foie</option>*/

	echo "<input type=\"text\" size=\"20\" name=\"query\">&nbsp;&nbsp;"
	."<input type=\"hidden\" name=\"op\" value=\"search\">"
	."<input type=\"submit\" value=\""._SEARCH."\">"
	."</form>"
	."</center>";
}

function alpha($quoi) {
    	global $module, $prefix, $dbi;
	echo "<h2>Bibliographie complète</h2><br>";

	if ($quoi == "Article")
	{
		print("<p align=\"justify\">Triée par titre et uniquement les articles :</p>\n");
	}
	else
	{
		if ($quoi == "Livre")
		{
			print("<p align=\"justify\">Triée par titre et uniquement les livres :</p>\n");
		}
		else
		{
			echo"<p align=\"justify\">Triée par : $quoi</p>\n";
    		}
	}
	echo "<center>Choisissez une lettre</center><br><br>";
    	$alphabet = array ("A","B","C","D","E","F","G","H","I","J","K","L","M",
                       "N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
    	$num = count($alphabet) - 1;
	
	echo "<center>[ ";
	$counter = 0;
	while (list(, $ltr) = each($alphabet)) {
	$quoi= ucfirst($quoi);
	
	if($quoi == "Auteurs" || $quoi == "Titre"  || $quoi == "EditeurRevue" )
	{
		$sql = "select $quoi from cb_biblio where $quoi LIKE '$ltr%'";
	}
	elseif ($quoi == "Article" || $quoi == "Livre")
	{
		$sql = "select Titre from cb_biblio where Titre LIKE '$ltr%' AND Type LIKE '$quoi'";
	}
	else
	{
		$sql = "select Titre from cb_biblio where Titre LIKE '$ltr%'";
	}
	$result = mysql_query($sql,$dbi);
	$count = mysql_num_rows($result);

	//print("$count");
	if ( $count > 0) {
	    echo "<a href=\"?module=$module&amp;op=terms&amp;eid=$quoi&amp;ltr=$ltr&methrecherhe=simple\">$ltr</a><small>($count)</small>";// -----------> parcours.php
	} else {
	    echo "$ltr";
	}
		if ( $counter == round($num/2) ) {
	     	// *******************Version  pour trois ligne
		//  if ( $counter == round($num/3) || $counter == round(2*$num/3)) {
            //*********************************************************
		echo " ]\n<br>\n[ ";
        } elseif ( $counter != $num ) {
            echo "&nbsp;|&nbsp;\n";  //&amp; ---<&
        }
        $counter++;
    }
    echo " ]<br><br>\n\n\n</center>";
}


function terms($quoi, $ltr) {
	global $module_name, $prefix, $sitename, $dbi, $admin;
	$ltr=addslashes($ltr);
	title("Bibliographie");
	echo "<center>Vous pouvez sélectionner un terme dans la liste ci-dessous:</center><br><br>"
	."<table border=\"1\" align=\"center\">";
	
	$quoi= ucfirst($quoi);
	if($quoi == "Auteurs" || $quoi == "Titre"  || $quoi == "EditeurRevue" )
	{
		//print("boucle 1 \n");
		$result = sql_query("select Numero, $quoi, Titre from cb_biblio where $quoi LIKE '$ltr%' order by $quoi", $dbi); //------------->    UPPER($quoi)     , $dbi);
	}
	elseif ($quoi == "Article" || $quoi == "Livre")
	{
		//print("boucle 2 \n");
		$result = sql_query("select Numero, Type, Titre from cb_biblio where Titre LIKE '$ltr%' AND Type LIKE '$quoi' order by Titre ", $dbi);
	}
	else
	{
		//print("boucle 4 \n");
		$result = sql_query("select Numero, Titre, Titre from cb_biblio where Titre LIKE '$ltr%' order by Titre", $dbi);
	}

	//    $result = mysql_query("select Numero, $quoi, Titre from cb_biblio where $quoi LIKE '$ltr%' order by $quoi"); // , $dbi
    	//list($numero, $biblio, $categorie, $dates, $type, $titre, $compil, $lieu, $editeurRevue, $reference, $commentaires, $auteurs) = mysql_fetch_row($result, $dbi);
	if (sql_num_rows($result) == 0)///, $dbi
	{  
		echo "<center><i>"._NOCONTENTFORLETTER." $ltr.</i></center>";
	}
	while(list($tid, $title, $detail) = sql_fetch_row($result, $dbi)) 
		{  
			echo "<tr><td><a href=\"parcours.php?name=$module_name&amp;methrecherhe=content&amp;tid=$tid\">$title</a>";
			if ($title != $detail)
			{
				echo " ($detail)";
			}
			echo "</td></tr>";
		}
		echo "</table><br><br>";
		//alpha($quoi);
}
//****************************fin de la fonction à un critère *************************************

//****************************debut de la fonction multicritere************************************

function recherchemulti($opmulit,$titres,$auteurs,$firstdates,$seconddates,$formats,$firstetou,$quatriemeetou)
{
	include("header.php");
	OpenTable();
global $module, $prefix, $sitename, $dbi, $admin;
//global $module_name, $db;

	/*
	* on verifie si l'operateur op existe sinon on le cré et on mets rien dedans
	* ceci impliquera l'affichage par default dans le switch
	*/

	if (!isset($opmulit))
	{
		$opmulit="";
	}
	
	print("<a href=\"index.php?methrecherhe=simple&module=$module\">recherche simple</a><br> \n"); 
			
	/*
	*Switch principale de la page multicritere
	*/
	switch($opmulit) {

	    case "result":
		result($titres,$auteurs,$firstdates,$seconddates,$formats,$firstetou,$quatriemeetou);//$disciplines,$secondetou
		search($titres,$auteurs,$firstdates,$seconddates,$formats,$firstetou,$quatriemeetou);
	    break;

	    default:
		search($titres,$auteurs,$firstdates,$seconddates,$formats,$firstetou,$quatriemeetou);
	    break;

	}
	CloseTable();
	include("footer.php");

}






function result($titres,$auteurs,$firstdates,$seconddates,$formats,$firstetou,$quatriemeetou)  //,$disciplines,$secondetou
{
global $module, $prefix, $sitename, $dbi, $admin;

	/*variable qui permet de savoir si il y a deja un element dans la requete qu'on va executer 
	*		=0(NON pas d'elements) 
	*		=1 (OUi deja au minimun un element)
	*/
	$nbreelementrequete=0;

	/*variable qui permet de savoir si on realise une requete qu'on va executer 
	*		=0(NON ) 
	*		=1 (OUi)
	*/
	$realiserequete=0;

/* appel de la fonction title*/
	title("Bibliographie");


	OpenTable();
	echo "<center>Vous pouvez sélectionner un terme dans la liste ci-dessous:</center><br><br>";

/*
*Verification si il ya ua moin 1 des 3 critères
*/
    if($titres !="" || $auteurs !="" || $firstdates !="" || $seconddates !="" )
     {
/*
*si oui on prepare la requete
*/
	$realiserequete=1;
	$query="SELECT * FROM cb_biblio where";

/*
*Verifie si l'auteur contient des donnes
*/	
	if($auteurs !="")
	{	
		$auteurs=addslashes($auteurs);
		$query.=" Auteurs LIKE '%$auteurs%'";
		$nbreelementrequete=1;
	}

/*
*Verifie si le titre contient des donnes
*/
	if($titres !="")
	{
		$titres=addslashes($titres);
		if($nbreelementrequete == 1)
		{
			/*
			* verifie si il y a deja un premier element dans la requete 
			* si oui on mets le pots de liaison choisit par l'utilisateur
			*/
			if($firstetou =="ET")
			{
			$query.=" AND";
			}
			else
			{
			$query.=" OR";
			}
		}
		$query.=" Titre LIKE '%$titres%'";
		$nbreelementrequete=1;
	}
/*
//*****************************************************************************************		
//parti non utilisé pour l'instant tant que les colonnes Disciplines et Domaines ne seront pas defini dans cb_biblio
//
//
//Verifie si la discipline contient des donnes
//
	if($disciplines != "")
	{

		if($nbreelementrequete == 1)
		{
			//
			//verifie si il y a deja un premier element dans la requete 
			// si oui on mets le pots de liaison choisit par l'utilisateur
			//
			if($secondetou =="ET")
			{
			$query.=" AND";
			}
			else
			{
			$query.=" OR";
			}
		}
		$disciplines=addslashes($disciplines);


		$queryencyclo1 = "SELECT eid FROM nuke_encyclopedia where title LIKE 'disciplines' "; 
		$resultencyclo1 = mysql_query($queryencyclo1);
       		while($row = mysql_fetch_array($resultencyclo1))
       		{	
			$netid=$row[eid];
		}

		$queryencyclo = "SELECT tid FROM nuke_encyclopedia_text where title LIKE '$disciplines' AND eid LIKE '$netid' "; 
		$resultencyclo = mysql_query($queryencyclo);
       		while($rowencyclo = mysql_fetch_array($resultencyclo))
       		{
			$id=$rowencyclo[tid];
		}
		
		

	$query.=" Biblio LIKE '$id' AND categorie LIKE 'discipline'";
	$nbreelementrequete=1;

	}
*/

	/*
	*Verifie si la date contient des dates
	*/

	if($firstdates !="" && $seconddates !="")
	{

		$firstdates=addslashes($firstdates);
		$seconddates=addslashes($seconddates);
	//verifier aue les dates soient des chiffress
		if($nbreelementrequete == 1)
		{
			/*
			* verifie si il y a deja un premier element dans la requete 
			* si oui on mets le pots de liaison choisit par l'utilisateur
			*/
			if($quatriemeetou =="ET")
			{
			$query.=" AND";
			}
			else
			{
			$query.=" OR";
			}
		}
		$query.=" Dates>='$firstdates' And Dates<='$seconddates'";
		$nbreelementrequete=1;
	}
	else
	{
		if($firstdates !="")
		{
			$firstdates=addslashes($firstdates);	
			/*
			* verifie si il y a deja un premier element dans la requete 
			* si oui on mets le pots de liaison choisit par l'utilisateur
			*/
			if($nbreelementrequete == 1)
			{
				if($quatriemeetou =="ET")
				{
				$query.=" AND";
				}
				else
				{
				$query.=" OR";
				}
			}
		$query.=" Dates = '$firstdates'";
		$nbreelementrequete=1;
		}
		else
		{
			if($seconddates !="")
			{	

				$seconddates=addslashes($seconddates);
				if($nbreelementrequete == 1)
				{
			/*
			* verifie si il y a deja un premier element dans la requete 
			* si oui on mets le pots de liaison choisit par l'utilisateur
			*/
					if($quatriemeetou =="ET")
					{
					$query.=" AND";
					}
					else
					{
					$query.=" OR";
					}
				}
			$query.=" Dates = '$seconddates'";
			$nbreelementrequete=1;
			}
		}
	}
	/*
	*Verifie le type de format
	* 1 tous les formats -> implique pas code mis la requete 
	* 2 tous les livres-> implique selection	
	* 3 tous les articles -> implique selection
	*/
	if($formats == "2")
	{	
		$query.=" AND Type = 'Livre'";
		$nbreelementrequete=1;
	}

	if($formats == "3")
	{	
		$query.=" AND Type = 'Article'";
		$nbreelementrequete=1;
	}
	/*
	* trier par titre
	*/
	$query.=" order by Titre";

//*****************************************A SUPPRIMER********************
//$query1=$query;
//print("2--  $query1 \n<br>\n");
//********************************************************************

	/*
	*realise la requete
	*/
	$result = sql_query($query, $dbi);
     }
	echo "<table border=\"1\" align=\"center\" valign=\"center\">";

	/*
	*si dans le cas une requete existe on affiche le contenu
	* sinon voir( else ) affichage d'un message indiquant l'absence de donnée
	*/

 	if($realiserequete == "1")
	{
			$num_rows = sql_num_rows($result);

//*****************************************A SUPPRIMER********************
			print(" le nombre de resultat correspondant à la requete est : $num_rows .\n");
//********************************************************************

			if($num_rows != FALSE)
			{
	/*
	*Affichage des données s'il y en a!!!!
	*/			
				while($row = sql_fetch_array($result, $dbi))
				{
				echo "<tr>\n";
				print("<td><a href=\"parcours.php?name=$module_name&methrecherhe=content&tid=$row[Numero]\">$row[Titre]</a> : <br> $row[Auteurs], $row[Dates] <br>\n");
//				print("<td><a href=\"?name=$module_name&methrecherhe=content&tid=$row[Numero]\">$row[Titre]</a> : <br> $row[Auteurs], $row[Dates] <br>\n");
				print("$row[Commentaires]</td>\n");
				echo "</tr>\n";
				}
			}
			else
	/*
	*Affichage d'un message indiquant via les critères il n'y a aucune solution
	*/
			{
				echo "<tr><td> Il n'y a aucune donnée correspondant à votre demande (";
				if($titres !="")
				{
					$mtitres=stripslashes($titres);					
					echo" titre = <i> $mtitres </i>";
				}
				else
				{
					echo" titre = <i> rien </i> ";
				}
				if($auteurs !="")
				{
					$auteurs=stripslashes($auteurs);
					echo" et auteur = <i> $auteurs </i>";
				}
				else
				{
					echo" et auteur = <i> rien </i> ";
				}
				if($firstdates !="" && $seconddates !="")
				{
					$firstdates=stripslashes($firstdates);
					$seconddates=stripslashes($seconddates);
					echo" et periode de = <i> $firstdates </i> à <i> $seconddates </i>";
				}
				else
				{
					if($firstdates !="")
					{
						$firstdates=stripslashes($firstdates);
						echo" et annee = <i> $firstdates </i> ";
					}
					if($seconddates !="")
					{
						$seconddates=stripslashes($seconddates);
						echo" et annee = <i> $seconddates </i>";
					}
				}
			
			echo").</td></tr>\n";
			}	
	}
	else
	{
		echo "<tr><td> Il n'y a aucune information dans le champs auteur, titre et date.</td></tr>\n";
	}
	echo "</table>\n<br>\n";

    CloseTable();

}



function search($titresinfo,$auteursinfo,$firstdatesinfo,$seconddatesinfo,$formatsinfo,$firstetouinfo,$quatriemeetouinfo)  //,$disciplines,$secondetou  ()
 {
global $module, $prefix, $sitename, $dbi, $admin;
//global $module_name;
print("<center><b> Rechercher à multiples critères :</b></center>");

		print("<form name=\"form1\" method=\"post\" action=\"index.php?module=$module\">");
		print("<input type=hidden name=opmulit value=result>");
	     	print("<input type=hidden name=methrecherhe value=mutli>");
		print("<center>");
        print("<table width=\"50%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" >");
		print("<tr bordercolor=#cccccc><td ><b>Auteur : </b></td>");
		print("<td class=box><b><input class=inpt_main type=\"text\" name=\"auteurs\" value=\"$auteursinfo\"></td></tr>");
	if($firstetouinfo == "OU")
	{
		print("<tr bgcolor=#999999><td>Pour rechercher avec plusieurs mots, remplacer l'espace par un %. </td><td > <b><input type=\"radio\" name=\"firstetou\" value=\"ET\" >ET / ");
		print("<input type=\"radio\" name=\"firstetou\" value=\"OU\" checked>OU<br></td></tr>");
	}
	else
	{
		print("<tr bgcolor=#999999><td>Pour rechercher avec plusieurs mots, remplacer l'espace par un %.</td><td > <b><input type=\"radio\" name=\"firstetou\" value=\"ET\" checked>ET / ");
		print("<input type=\"radio\" name=\"firstetou\" value=\"OU\" >OU<br></td></tr>");
	}
		print("<tr bordercolor=#cccccc><td ><b>Titre :</b> </td>");
		print("<td ><b><input class=inpt_main type=\"text\" name=\"titres\" value=\"$titresinfo\"><br></td></tr>");		


//*************************************************************************************************************
/*
*Partis non utilisé pour l'instant peut servir lorsqu'il y aura les collonnes disciplines et domaines dans la table cb-biblio
*
*
		print("<tr><td></td><td class=box> <b><input type=\"radio\" name=\"secondetou\" value=\"ET\" checked>ET / ");
		print("<input type=\"radio\" name=\"secondetou\" value=\"OU\" >OU<br></td></tr>");		

		print("<tr bordercolor=#cccccc><td class=box>Discipline : </td>");
		print("<td class=box><select class=inpt_main name=disciplines :>");                                                                                                      
            	print(" <option value=\"\" selected>- - - - - - - Mots Clés - - - - - - - </option>");

		$query = "SELECT eid FROM nuke_encyclopedia where title LIKE 'disciplines' "; 
		$result = mysql_query($query);
       		while($row = mysql_fetch_array($result))
       		{	
			$netid=$row[eid];
		}

		$query = "SELECT tid, title FROM nuke_encyclopedia_text where eid LIKE '$netid' "; 
		$result = mysql_query($query);
       		while($row = mysql_fetch_array($result))
       		{	
			print("<option> $row[title] </option>");
		}
		
               	print("</select></td></tr>");

		print("<tr><td></td><td class=box> <b><input type=\"radio\" name=\"troisiemeetou\" value=\"ET\" checked>ET / ");
		print("<input type=\"radio\" name=\"troisiemeetou\" value=\"OU\" >OU<br></td></tr>");

		print("<tr bordercolor=#cccccc><td class=box>Domaine : </td>");

		print("<td class=box><select class=inpt_main name=domaines>");
		print(" <option value=\"\" selected>-- Mots Clés --</option>");
	
		$query = "SELECT eid FROM nuke_encyclopedia where title LIKE 'Domaines' "; 
		$result = mysql_query($query);
       		while($row = mysql_fetch_array($result))
       		{	
			$netid=$row[eid];
		}

		$query = "SELECT title FROM nuke_encyclopedia_text where eid LIKE '$netid' "; 
		$result = mysql_query($query);
       		while($row = mysql_fetch_array($result))
       		{	
			print("<option> $row[title] </option>");
		}


	
		print("</select></td></tr>");
*/
//*******************************************************************************

	if($quatriemeetouinfo == "OU")
	{	
		print("<tr bgcolor=#999999><td></td><td > <b><input type=\"radio\" name=\"quatriemeetou\" value=\"ET\" >ET / ");
		print("<input type=\"radio\" name=\"quatriemeetou\" value=\"OU\" checked>OU<br></td></tr>");
	}
	else
	{		
		print("<tr bgcolor=#999999><td></td><td > <b><input type=\"radio\" name=\"quatriemeetou\" value=\"ET\" checked>ET / ");
		print("<input type=\"radio\" name=\"quatriemeetou\" value=\"OU\" >OU<br></td></tr>");
	}

		print("<tr bordercolor=#cccccc><td ><b>Annee de : </b> </td>");
		print("<td ><b><input class=inpt_main type=\"text\" name=\"firstdates\" size=\"4\" maxlength=\"4\" value=\"$firstdatesinfo\">&nbsp;&nbsp;");
		print("<b> à &nbsp;</b><input class=inpt_main type=\"text\" name=\"seconddates\" size=\"4\" maxlength=\"4\" value=\"$seconddatesinfo\"></td></tr>");

/*
*Verifie le type de format
* 1 tous les formats -> implique pas code mis la requete 
* 2 tous les livres-> implique selection	
* 3 tous les articles -> implique selection
*/

		print("<tr bordercolor=#cccccc><td >Selectionner le type de format de l'oeuvre : </td>");
	if($formatsinfo == "2")
	{
		print("<td > <b><input type=\"radio\" name=\"formats\" value=\"1\" >Tous les documents<br>");
		print("<input type=\"radio\" name=\"formats\" value=\"2\" checked> livres<br>");
		print("<input type=\"radio\" name=\"formats\" value=\"3\" >Articles<br></td></tr>");
	}
	else
	{	
		if($formatsinfo == "3")
		{
			print("<td > <b><input type=\"radio\" name=\"formats\" value=\"1\">Tous les documents<br>");
			print("<input type=\"radio\" name=\"formats\" value=\"2\" > livres<br>");
			print("<input type=\"radio\" name=\"formats\" value=\"3\" checked>Articles<br></td></tr>");
		}
		else
		{
			print("<td > <b><input type=\"radio\" name=\"formats\" value=\"1\" checked>Tous les documents<br>");
			print("<input type=\"radio\" name=\"formats\" value=\"2\" > livres<br>");
			print("<input type=\"radio\" name=\"formats\" value=\"3\" >Articles<br></td></tr>");
		}
	}
		print("<tr bordercolor=#cccccc><td colspan=2><center><input class=btn_main type=\"submit\" name=\"bouton\" value=\"Rechercher\"></center></td></tr>");
		print("</table><br><br>");
		print("</center>");

}

//****************************fin de la fonction multicritere************************************

?>