<?php

/* * * * * * * * * * * * * * * * * * * * * * *

			A Blork Engine v0.2 
			Fichier de configuration 
			zulios@blork.net

			* * * * * * * * * * * * * * * * * * * * * * */

// 1- Personnalisation de la longueur du résumé 
// Placez entre les guillemets sur la ligne ci dessous
// le nombre de mots maximum À afficher 
// dans la description de chaque résultat  

$maxmots="20";

// Fin de la personnalisation de la longueur du résumé

// 2- Personnalisation des couleurs
// Placez entre les guillemets sur la ligne ci dessous
// le code hexadécimal
// de la couleur du texte recherché é afficher 

$color="#00aabb";

// Fin de la personnalisation des couleurs

// 3- Personnalisation des dossiers À scanner 
//para rapport aux ouvrages actifs
	    $sql = mysqli_query($dbi, "select pid,titre, debut, auteur, date_titre, type_book from cb_ouvrages where active='1' order by date_titre, titre");
		echo "<h2>Les ouvrages sur lesquels se fait la recherche</h2>";
    	while (list($pid, $titre, $debut, $auteur, $date_titre ,$type_book) = mysqli_fetch_row($sql)) {
 	    	echo "<table><tr><td valign=\"top\"><img width=\"30\" height=\"12\"  src=\"themes/$theme/img/plot.gif\"></td><td>".creeLienOuvrage($pid,$titre,$debut,$date_titre,$type_book) ."</td></tr></table>";
			$dossier[$titre]= $pid ;
    	}

//

/*$dossier=array( 
// Placez en dessous de cette ligne
// Le code des différents dossiers é scanner
// Ne mettez pas de / é la fin du chemin d'accés au dossier
// Car il est rajouté automatiquement 

"1"=>"1",
"2"=>"3",
"3"=>"5",
"4"=>"14",
"5"=>"15",
"6"=>"16",
"7"=>"17",
"8"=>"18",
"9"=>"19",
"10"=>"20",
"11"=>"21",
"12"=>"22",
"13"=>"23",
"14"=>"24",
"15"=>"26",
"16"=>"27",
"17"=>"29",
"18"=>"30",
"19"=>"31"

// Ne mettez plus de dossiers é scanner en dessous de cette ligne. 
); // Fin de la personnalisation des dossiers é scanner 
*/

// 4- Personnalisation du nombre de résultats affichés par page
// Indiquez é la ligne ci dessous entre les guillemets
// Le nombre de résultats maximum é afficher par page

$maxipage="20";

// Fin de la personnalisation du nombre de résultats affichés par page

// 5- Gestion des pseudo frames
// Indiquez é la ligne ci dessous entre les guillemets on ou off :
// on      pour afficher l'extension des fichiers
// off     pour désactiver l'affichage de l'extension des fichiers

$montre_ext="on"; 

// Indiquez é la ligne ci dessous entre les guillemets 
// l'url type é utiliser dans le moteur 
// si vous n'utilisez pas les pseudos frames ne modifiez pas cette url type
// utilisez [dossier] pour indiquer le dossier
// et [fichier] pour indiquer le fichier 

$go2url="parcours.php?name=Parcours_Hypertexte&file=moteurCB&parcours=paragraphe&ouvrage=[dossier]&valeur=[fichier]";

// Fin de la gestion des pseudo frames 

// 6- Personnalisation des fichiers é exclure de la recherche 
// Indiquez dans la liste ci dessous 
// Les fichiers é exclure de la recherche 
// entre guillemets et suivis d'une virgule
// Pensez bien a mettre le nom complet du fichier
// avec son extension
// par exemple : "fichier.html",
// vous pouvez aussi indiquer des fichiers images de type gif, jpg, ou png.
// Ne mettez pas le chemin d'accés au fichier mais seulement son nom. 

$exclu=array(
"sommaire.html"

// Ne mettez plus de fichiers é exclure en dessous de cette ligne. 
); // Fin de la personnalisation des dossiers é scanner 
// La configuration du script s'arréte ici. 
?>