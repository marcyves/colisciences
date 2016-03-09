<? 

/* * * * * * * * * * * * * * * * * * * * * * *

			A Blork Engine v0.2 
			Fichier de configuration 
			zulios@blork.net


N'effacez pas ce message 
---------------------------------------------------

Cette page est destin�e � configurer votre moteur de recherche. 
Elle sert � d�finir la couleur du texte correspondant au r�sultat dans le r�sum� des pages et de d�finir les dossiers � scanner sur votre site.


Comment ajouter un dossier a scanner ? 
---------------------------------------------------

Il suffit d'ajouter pour chaque dossier � scanner le code suivant � l'endroit signal� plus bas dans la page :

"la description de votre dossier"=>"le chemin d'acc�s au dossier",

Le chemin d'acc�s au dossier pour scanner les fichiers au m�me endroit que le moteur de recherche (si vous le placez a la base de votre ftp par exemple) est un simple point comme ceci . Pour remonter en arri�re d'un dossier il faut mettre un double point comme ceci ..

Voici quelques exemples d'ajout :

- Pour scanner le dossier ou est plac� le moteur de recherche : 
"le dossier ou est plac� le moteur de recherche"=>".",

- Pour scanner le dossier parent de celui du moteur de recherche :
"le dossier parent de celui ou est situ� le moteur de recherche"=>"..",

- Pour scanner un dossier situ� au m�me niveau que celui du moteur :
"un dossier au m�me niveau que celui du moteur"=>"../ledossier",

etc...


Comment changer la couleur du texte recherch� ? 
---------------------------------------------------

Par d�faut la couleur du texte recherch� est affich� en rouge dans le r�sum� et dans les diff�rentes indications de la page. Par exemple si vous cherchez le mot "php", le mot php sera mis en rouge a chaque fois qu'il est trouv� dans le r�sum� des r�sultats, et dans les indications diverses comme l'affichage du mot recherch�. Pour changer cette couleur, il suffit d'indiquer le code hexad�cimal de la couleur que vous souhaitez afficher � l'endroit signal� plus bas dans la page, entre les guillemets. 

Si vous ne connaissez pas les codes des couleurs en hexad�cimal, pas de panique ! Je vous ai inclus dans les fichiers de l'archive le fichier couleurs.html qui vous aidera � trouver la couleur que vous souhaitez.


Comment changer la longueur du r�sum� affich� pour chaque r�sultat ? 
---------------------------------------------------

Par d�faut le r�sum� est de 20 mots. Vous pouvez changer cette longueur en entrant un nombre de mots maximum � afficher � l'endroit signal� plus bas dans la page. Sachez toutefois que plus le r�sum� est long, plus le script prendra plus de temps � s'�x�cuter et plus la navigation dans les r�sultats sera fastidieuse.


Comment changer le nombre de r�sultats affich�s par page ? 
---------------------------------------------------

Par d�faut ce nombre est fix� � 20 r�sultats maximum par page, mais vous pouvez l'adapter en fonction de la taille de votre site. Il suffit d'indiquer le nombre de fichiers dans l'endroit signal� plus bas dans la page. Vous devez mettre un nombre entier sup�rieur � 0, pas de chiffres a virgule ou de fractions et autres formules math�matiques bien sur sinon vous provoquez une erreur... 


Comment g�rer les pseudo frames ? 
---------------------------------------------------

Par d�faut le script fait comme si vous n'utilisiez pas de script de pseudo frames (script php qui permet d'avoir une page par d�faut utilis�e partout). Si vous n'utilisez pas les pseudos frames inutile donc de toucher � quoi que ce soit. Si votre site utilise un script de pseudo frames il faut pour cela indiquer � l'endroit signal� plus bas dans la page l'url type de votre pseudo frame et si l'extension du fichier doit �tre affich�e dans l'url ou non. 

Dans votre url type vous aurez � remplacer le nom du fichier et le nom du dossier par [fichier] et [dossier]. Voici quelques exemples de pseudo frames les plus courants avec la configuration � adopter :

monsite.com/mapage.php?page=mondossier/la_page_a_afficher.html
Laissez activ� l'extension du fichier. 
Pour votre url type mettez : mapage.php?page=[dossier]/[fichier]

monsite.com/mapage.php?page=mondossier/la_page_a_afficher
D�sactivez l'extension du fichier. 
Pour votre url type mettez : mapage.php?page=[dossier]/[fichier]

monsite.com/mapage.php?rub=mondossier&page=la_page_a_afficher.html
Laissez activ� l'extension du fichier.
Pour votre url type mettez : mapage.php?rub=[dossier]&page=[fichier]

monsite.com/mapage.php?rub=mondossier&page=la_page_a_afficher
D�sactivez l'extension du fichier.
Pour votre url type mettez : mapage.php?rub=[dossier]&page=[fichier]


Comment g�rer les fichiers � exclure de la recherche ? 
---------------------------------------------------

Pour �viter qu'un fichier puisse �tre scann� et affich� dans les r�sultats, indiquez dans la liste � l'endroit signal� plus bas dans la page le nom complet du fichier avec son extension et entre guillemets, suivi d'une virgule. Par d�faut les fichiers du script sont exclus de la recherche. Si vous voulez en rajouter rajoutez ceci :

"monfichier.html",

pour chaque fichier � exclure. Si vous voulez en exclure plusieurs cela donnera donc :

"monfichier1.html",
"monfichier2.html", 
et ainsi de suite. 

* * * * * * * * * * * * * * * * * * * * * * */



// 1- Personnalisation de la longueur du r�sum� 
// Placez entre les guillemets sur la ligne ci dessous
// le nombre de mots maximum � afficher 
// dans la description de chaque r�sultat  

$maxmots="20";

// Fin de la personnalisation de la longueur du r�sum�




// 2- Personnalisation des couleurs
// Placez entre les guillemets sur la ligne ci dessous
// le code hexad�cimal
// de la couleur du texte recherch� � afficher 

$color="#00aabb";

// Fin de la personnalisation des couleurs




// 3- Personnalisation des dossiers � scanner 
//para rapport aux ouvrages actifs
	    $sql = sql_query("select pid,titre, debut, auteur, date_titre, type_book from cb_ouvrages where active='1' order by date_titre, titre",$dbi);
		echo "<h2>Les ouvrages sur lesquels se fait la recherche</h2>";
    	while (list($pid, $titre, $debut, $auteur, $date_titre ,$type_book) = sql_fetch_row($sql, $dbi)) {
 	    	echo "<table><tr><td valign=\"top\"><img width=\"30\" height=\"12\"  src=\"themes/$theme/img/plot.gif\"></td><td>".creeLienOuvrage($pid,$titre,$debut,$date_titre,$type_book) ."</td></tr></table>";
			$dossier[$titre]= $pid ;
    	}

//

/*$dossier=array( 
// Placez en dessous de cette ligne
// Le code des diff�rents dossiers � scanner
// Ne mettez pas de / � la fin du chemin d'acc�s au dossier
// Car il est rajout� automatiquement 

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

// Ne mettez plus de dossiers � scanner en dessous de cette ligne. 
); // Fin de la personnalisation des dossiers � scanner 
*/

// 4- Personnalisation du nombre de r�sultats affich�s par page
// Indiquez � la ligne ci dessous entre les guillemets
// Le nombre de r�sultats maximum � afficher par page

$maxipage="20";

// Fin de la personnalisation du nombre de r�sultats affich�s par page

// 5- Gestion des pseudo frames
// Indiquez � la ligne ci dessous entre les guillemets on ou off :
// on      pour afficher l'extension des fichiers
// off     pour d�sactiver l'affichage de l'extension des fichiers

$montre_ext="on"; 

// Indiquez � la ligne ci dessous entre les guillemets 
// l'url type � utiliser dans le moteur 
// si vous n'utilisez pas les pseudos frames ne modifiez pas cette url type
// utilisez [dossier] pour indiquer le dossier
// et [fichier] pour indiquer le fichier 

$go2url="parcours.php?name=Parcours_Hypertexte&file=moteurCB&parcours=paragraphe&ouvrage=[dossier]&valeur=[fichier]";

// Fin de la gestion des pseudo frames 

// 6- Personnalisation des fichiers � exclure de la recherche 
// Indiquez dans la liste ci dessous 
// Les fichiers � exclure de la recherche 
// entre guillemets et suivis d'une virgule
// Pensez bien a mettre le nom complet du fichier
// avec son extension
// par exemple : "fichier.html",
// vous pouvez aussi indiquer des fichiers images de type gif, jpg, ou png.
// Ne mettez pas le chemin d'acc�s au fichier mais seulement son nom. 

$exclu=array(
"sommaire.html"

// Ne mettez plus de fichiers � exclure en dessous de cette ligne. 
); // Fin de la personnalisation des dossiers � scanner 
// La configuration du script s'arr�te ici. 
?>