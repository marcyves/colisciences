<?php

if (!eregi("modules.php", $PHP_SELF)) {
    die ("You can't access this file directly...");
}

$index = 1;

function Informations() {
    include("header.php");
    OpenTable();
    echo "<p>";
    echo 'La solution technique retenue pour animer ce site est bas�e sur les logiciels suivants:
	<p>
	Le serveur Web est anim� par <a href="http://www.apache.org" target="blank"><img src="images/powered/apache.gif" Alt="Apache Web Server" border="0" hspace="10"></a> sur lequel <a href="http://www.php.net" target="blank"><img src="images/powered/php2.gif" Alt="PHP Scripting Language" border="0" hspace="10"></a> a permis d\'impl�menter <a href="http://phpnuke.org" target="blank"><img src="images/powered/nuke.gif" border="0" Alt="Web site powered by PHP-Nuke" hspace="10"></a> qui a �t� revu et adapt� � nos besoins sp�cifiques.<br><br>
La grande modularit� de PHP-Nuke a �t� utilis�e pour ins�rer les modules suivants, certains �taient sp�cifiquement d�velopp�s pour Nuke, d\'autres ont du �tre adapt�s.<br>
Le moteur de recherche de mots dans la totalit� du corpus est bas� sur <A href="http://www.blork.net/#scripts" target="_blank"><img src=images/blork_engine.gif border=0></A><br><p>';
    echo 'Tous les logos et nom de marque cit�s sur ce site sont la propri�t� de leurs propri�taires respectifs. Les commentaires sont la propri�t� de leurs auteurs respectifs, tout le reste est la propri�t� du Laboratoire Communication et Politique CNRS � 2002 - LCP CNRS';
    echo "<p>";
//    echo 'Free Software released under the <a href="http://www.gnu.org">GNU/GPL license</a>.';
    echo '';
    CloseTable();
    include("footer.php");
}

switch($func) {

    default:
    Informations();
    break;
    
    case "one":
    one();
    break;

    case "two":
    two();
    break;

}

?>