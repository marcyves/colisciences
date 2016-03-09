<? 

/* * * * * * * * * * * * * * * * * * * * * * *

			A Blork Engine v0.2 
			Fichier de configuration 
			zulios@blork.net


N'effacez pas ce message 
---------------------------------------------------

Cette page est destinée à configurer votre moteur de recherche. 
Elle sert à définir la couleur du texte correspondant au résultat dans le résumé des pages et de définir les dossiers à scanner sur votre site.


Comment ajouter un dossier a scanner ? 
---------------------------------------------------

Il suffit d'ajouter pour chaque dossier à scanner le code suivant à l'endroit signalé plus bas dans la page :

"la description de votre dossier"=>"le chemin d'accès au dossier",

Le chemin d'accès au dossier pour scanner les fichiers au même endroit que le moteur de recherche (si vous le placez a la base de votre ftp par exemple) est un simple point comme ceci . Pour remonter en arrière d'un dossier il faut mettre un double point comme ceci ..

Voici quelques exemples d'ajout :

- Pour scanner le dossier ou est placé le moteur de recherche : 
"le dossier ou est placé le moteur de recherche"=>".",

- Pour scanner le dossier parent de celui du moteur de recherche :
"le dossier parent de celui ou est situé le moteur de recherche"=>"..",

- Pour scanner un dossier situé au même niveau que celui du moteur :
"un dossier au même niveau que celui du moteur"=>"../ledossier",

etc...


Comment changer la couleur du texte recherché ? 
---------------------------------------------------

Par défaut la couleur du texte recherché est affiché en rouge dans le résumé et dans les différentes indications de la page. Par exemple si vous cherchez le mot "php", le mot php sera mis en rouge a chaque fois qu'il est trouvé dans le résumé des résultats, et dans les indications diverses comme l'affichage du mot recherché. Pour changer cette couleur, il suffit d'indiquer le code hexadécimal de la couleur que vous souhaitez afficher à l'endroit signalé plus bas dans la page, entre les guillemets. 

Si vous ne connaissez pas les codes des couleurs en hexadécimal, pas de panique ! Je vous ai inclus dans les fichiers de l'archive le fichier couleurs.html qui vous aidera à trouver la couleur que vous souhaitez.


Comment changer la longueur du résumé affiché pour chaque résultat ? 
---------------------------------------------------

Par défaut le résumé est de 20 mots. Vous pouvez changer cette longueur en entrant un nombre de mots maximum à afficher à l'endroit signalé plus bas dans la page. Sachez toutefois que plus le résumé est long, plus le script prendra plus de temps à s'éxécuter et plus la navigation dans les résultats sera fastidieuse.


Comment changer le nombre de résultats affichés par page ? 
---------------------------------------------------

Par défaut ce nombre est fixé à 20 résultats maximum par page, mais vous pouvez l'adapter en fonction de la taille de votre site. Il suffit d'indiquer le nombre de fichiers dans l'endroit signalé plus bas dans la page. Vous devez mettre un nombre entier supérieur à 0, pas de chiffres a virgule ou de fractions et autres formules mathématiques bien sur sinon vous provoquez une erreur... 


Comment gérer les pseudo frames ? 
---------------------------------------------------

Par défaut le script fait comme si vous n'utilisiez pas de script de pseudo frames (script php qui permet d'avoir une page par défaut utilisée partout). Si vous n'utilisez pas les pseudos frames inutile donc de toucher à quoi que ce soit. Si votre site utilise un script de pseudo frames il faut pour cela indiquer à l'endroit signalé plus bas dans la page l'url type de votre pseudo frame et si l'extension du fichier doit être affichée dans l'url ou non. 

Dans votre url type vous aurez à remplacer le nom du fichier et le nom du dossier par [fichier] et [dossier]. Voici quelques exemples de pseudo frames les plus courants avec la configuration à adopter :

monsite.com/mapage.php?page=mondossier/la_page_a_afficher.html
Laissez activé l'extension du fichier. 
Pour votre url type mettez : mapage.php?page=[dossier]/[fichier]

monsite.com/mapage.php?page=mondossier/la_page_a_afficher
Désactivez l'extension du fichier. 
Pour votre url type mettez : mapage.php?page=[dossier]/[fichier]

monsite.com/mapage.php?rub=mondossier&page=la_page_a_afficher.html
Laissez activé l'extension du fichier.
Pour votre url type mettez : mapage.php?rub=[dossier]&page=[fichier]

monsite.com/mapage.php?rub=mondossier&page=la_page_a_afficher
Désactivez l'extension du fichier.
Pour votre url type mettez : mapage.php?rub=[dossier]&page=[fichier]


Comment gérer les fichiers à exclure de la recherche ? 
---------------------------------------------------

Pour éviter qu'un fichier puisse être scanné et affiché dans les résultats, indiquez dans la liste à l'endroit signalé plus bas dans la page le nom complet du fichier avec son extension et entre guillemets, suivi d'une virgule. Par défaut les fichiers du script sont exclus de la recherche. Si vous voulez en rajouter rajoutez ceci :

"monfichier.html",

pour chaque fichier à exclure. Si vous voulez en exclure plusieurs cela donnera donc :

"monfichier1.html",
"monfichier2.html", 
et ainsi de suite. 

* * * * * * * * * * * * * * * * * * * * * * */



// 1- Personnalisation de la longueur du résumé 
// Placez entre les guillemets sur la ligne ci dessous
// le nombre de mots maximum à afficher 
// dans la description de chaque résultat  

$maxmots="20";

// Fin de la personnalisation de la longueur du résumé




// 2- Personnalisation des couleurs
// Placez entre les guillemets sur la ligne ci dessous
// le code hexadécimal
// de la couleur du texte recherché à afficher 

$color="#00aabb";

// Fin de la personnalisation des couleurs




// 3- Personnalisation des dossiers à scanner 
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
// Le code des différents dossiers à scanner
// Ne mettez pas de / à la fin du chemin d'accès au dossier
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

// Ne mettez plus de dossiers à scanner en dessous de cette ligne. 
); // Fin de la personnalisation des dossiers à scanner 
*/

// 4- Personnalisation du nombre de résultats affichés par page
// Indiquez à la ligne ci dessous entre les guillemets
// Le nombre de résultats maximum à afficher par page

$maxipage="20";

// Fin de la personnalisation du nombre de résultats affichés par page

// 5- Gestion des pseudo frames
// Indiquez à la ligne ci dessous entre les guillemets on ou off :
// on      pour afficher l'extension des fichiers
// off     pour désactiver l'affichage de l'extension des fichiers

$montre_ext="on"; 

// Indiquez à la ligne ci dessous entre les guillemets 
// l'url type à utiliser dans le moteur 
// si vous n'utilisez pas les pseudos frames ne modifiez pas cette url type
// utilisez [dossier] pour indiquer le dossier
// et [fichier] pour indiquer le fichier 

$go2url="parcours.php?name=Parcours_Hypertexte&file=moteurCB&parcours=paragraphe&ouvrage=[dossier]&valeur=[fichier]";

// Fin de la gestion des pseudo frames 

// 6- Personnalisation des fichiers à exclure de la recherche 
// Indiquez dans la liste ci dessous 
// Les fichiers à exclure de la recherche 
// entre guillemets et suivis d'une virgule
// Pensez bien a mettre le nom complet du fichier
// avec son extension
// par exemple : "fichier.html",
// vous pouvez aussi indiquer des fichiers images de type gif, jpg, ou png.
// Ne mettez pas le chemin d'accès au fichier mais seulement son nom. 

$exclu=array(
"sommaire.html"

// Ne mettez plus de fichiers à exclure en dessous de cette ligne. 
); // Fin de la personnalisation des dossiers à scanner 
// La configuration du script s'arrête ici. 
?>