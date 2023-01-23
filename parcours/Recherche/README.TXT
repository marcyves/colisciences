Cette page est destinée é configurer votre moteur de recherche. 
Elle sert é définir la couleur du texte correspondant au résultat dans le résumé des pages et de définir les dossiers é scanner sur votre site.


Comment ajouter un dossier a scanner ? 
---------------------------------------------------

Il suffit d'ajouter pour chaque dossier é scanner le code suivant é l'endroit signalé plus bas dans la page :

"la description de votre dossier" = "le chemin d'accés au dossier",

Le chemin d'accés au dossier pour scanner les fichiers au méme endroit que le moteur de recherche (si vous le placez a la base de votre ftp par exemple) est un simple point comme ceci . Pour remonter en arriére d'un dossier il faut mettre un double point comme ceci ..

Voici quelques exemples d'ajout :

- Pour scanner le dossier ou est placé le moteur de recherche : 
"le dossier ou est placé le moteur de recherche" = ".",

- Pour scanner le dossier parent de celui du moteur de recherche :
"le dossier parent de celui ou est situé le moteur de recherche" = "..",

- Pour scanner un dossier situé au méme niveau que celui du moteur :
"un dossier au méme niveau que celui du moteur" = "../ledossier",

etc...


Comment changer la couleur du texte recherché ? 
---------------------------------------------------

Par défaut la couleur du texte recherché est affiché en rouge dans le résumé et dans les différentes indications de la page. Par exemple si vous cherchez le mot "php", le mot php sera mis en rouge a chaque fois qu'il est trouvé dans le résumé des résultats, et dans les indications diverses comme l'affichage du mot recherché. Pour changer cette couleur, il suffit d'indiquer le code hexadécimal de la couleur que vous souhaitez afficher é l'endroit signalé plus bas dans la page, entre les guillemets. 

Si vous ne connaissez pas les codes des couleurs en hexadécimal, pas de panique ! Je vous ai inclus dans les fichiers de l'archive le fichier couleurs.html qui vous aidera é trouver la couleur que vous souhaitez.


Comment changer la longueur du résumé affiché pour chaque résultat ? 
---------------------------------------------------

Par défaut le résumé est de 20 mots. Vous pouvez changer cette longueur en entrant un nombre de mots maximum é afficher é l'endroit signalé plus bas dans la page. Sachez toutefois que plus le résumé est long, plus le script prendra plus de temps é s'éxécuter et plus la navigation dans les résultats sera fastidieuse.


Comment changer le nombre de résultats affichés par page ? 
---------------------------------------------------

Par défaut ce nombre est fixé é 20 résultats maximum par page, mais vous pouvez l'adapter en fonction de la taille de votre site. Il suffit d'indiquer le nombre de fichiers dans l'endroit signalé plus bas dans la page. Vous devez mettre un nombre entier supérieur é 0, pas de chiffres a virgule ou de fractions et autres formules mathématiques bien sur sinon vous provoquez une erreur... 


Comment gérer les pseudo frames ? 
---------------------------------------------------

Par défaut le script fait comme si vous n'utilisiez pas de script de pseudo frames (script php qui permet d'avoir une page par défaut utilisée partout). Si vous n'utilisez pas les pseudos frames inutile donc de toucher é quoi que ce soit. Si votre site utilise un script de pseudo frames il faut pour cela indiquer é l'endroit signalé plus bas dans la page l'url type de votre pseudo frame et si l'extension du fichier doit étre affichée dans l'url ou non. 

Dans votre url type vous aurez é remplacer le nom du fichier et le nom du dossier par [fichier] et [dossier]. Voici quelques exemples de pseudo frames les plus courants avec la configuration é adopter :

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

Pour éviter qu'un fichier puisse étre scanné et affiché dans les résultats, indiquez dans la liste é l'endroit signalé plus bas dans la page le nom complet du fichier avec son extension et entre guillemets, suivi d'une virgule. Par défaut les fichiers du script sont exclus de la recherche. Si vous voulez en rajouter rajoutez ceci :

"monfichier.html",

pour chaque fichier é exclure. Si vous voulez en exclure plusieurs cela donnera donc :

"monfichier1.html",
"monfichier2.html", 
et ainsi de suite. 
