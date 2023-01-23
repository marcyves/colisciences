<?php 

$index = 1;

/* * * * * * * * * * * * * * * * * * * * * * *

			A Blork Engine v0.2 
			Script du moteur 
			zulios@blork.net

Pour les gens qui veulent modifier le moteur � leur go�t, je vous ai 
mis des commentaires au cours du script qui vous aideront � vous 
rep�rer. Je ne vous demande qu'une chose : ne supprimez pas l'image 
avec le lien vers mon forum, ne serait-ce que par respect pour mon 
travail. Merci.

* * * * * * * * * * * * * * * * * * * * * * */
 
    include("header.php");
    OpenTable();

    include("blork_engine_config.php"); 
    $version="version 0.2";

// Variables par d�faut 

if($color==""){ $color="#ff0000"; } 
if($maxmots==""){ $maxmots="20"; } 
if($maxipage==""){ $maxipage="20"; } 
if($start==""){ $start="0"; } 
$blork2=htmlspecialchars($blork); 
$ouin="0"; 

?>
<form method="post" action="parcours.php?name=Recherche">
<script src=blork_engine_screens.js></script>

<?php 

if($action=="go") { 
	if($blork==""){
	   echo("Merci de bien vouloir remplir le champ de recherche.");
	}else{ 
       // R�sultats � 0 
       $compteresultats = 0; 

       foreach($dossier as $nomdos=>$d){
	      $dossier_ouvrages = "/var/www/colisciences/Colis/$d"; 
          // On ouvre le dossier
          $fp=@opendir($dossier_ouvrages);
		  $flagDossier = TRUE;
		  if ($fp){
          while($file = readdir($fp)){  		// D�but de la boucle 
              if($file!='.' || $file!='..'){ 		// On �vite la balade 
                  $ext=strrchr($file,'.'); 		// On r�cup�re l'extension 

                  // S�lection des extensions
                  if($ext==".xml"){ 
				    // On ne scanne pas les fichiers exclus 
                      if(!in_array($file, $exclu)) { 
						// On incr�mente le nb de fichier scann�s 
						$zetotal++; 
						// Passage en minuscules de la recherche
						$blork=strtolower($blork); 
						// On vire le html  
	  					$tout=strip_tags(join('',file("$dossier_ouvrages/$file")),'<title></title><TITLE></TITLE><script></script>'); 
						// Passage en minuscules 
						$tout=strtolower($tout); 
						// Si on trouve la recherche
						$pos = strpos($tout,$blork);
						if($pos>0){
							// R�sultats +1 
							$compteresultats++;
							$pos1 = $pos - 40;
							if ($pos1<0) {$pos1 = 0;}
							  $titre = substr($tout, $pos1, 80);
							  $pos1  = strpos($titre," ");
							  $titre = substr($titre, $pos1,100);
							  $titre = str_replace("$blork","<b><font color=$color>$blork2</font></b>",$titre);
							  // Calcul du pourcentage
							  similar_text($blork, $tout, $p1);
							  similar_text($blork, $titre, $p2);
							  $p=intval($p1+$p2);
							  if($p>100){ $p="100"; }
							  // On v�rifie qu'il y a deux chiffres
							  if (strlen($p)<2){
							     $temp=str_repeat("0",2-strlen($p)).$p;
								 $p=$temp;
							  }
							  $end=intval($start+$maxipage);
							  // URL par d�faut
							  if($go2url==""){ $go_2_url="$dossier_ouvrages/$file"; }
							  else{
							    $go_2_url = $go2url;
								$go_2_url=str_replace("[dossier]",$d,$go_2_url);
								$num = str_replace(".xml","",$file); 		// On r�cup�re le num�ro de page
								$go_2_url=str_replace("[fichier]",$num,$go_2_url); 
							  }
  							  $go2new="<A href='$go_2_url' target=_blank>";
							  // Source du r�sultat 
							if ($flagDossier){
								$flagDossier = FALSE;
    $sql = sql_query("select titre from cb_ouvrages where pid=$d",$dbi);
    list($titreO) = sql_fetch_row($sql, $dbi);
								$src = "<p><b><u>$titreO</u></b>";
							}
							  $src .= "<br>$go2new<img src=images/blork_engine_new_window.gif border=0 width=17 height=14 alt='ouvrir dans une nouvelle fen�tre'></A><A href='$go_2_url'>$titre... </A>"; 
							  // On enregistre 
							  $zeresults["$p,$compteresultats"] = $src;
							  $src = "";
						}
					}
				}
			}
		}
		closedir($fp);
	}
} 
//print_r($zeresults);
if($compteresultats>0){ 
//	krsort($zeresults); 
	foreach($zeresults as $key=>$value){
		$vrai[]=$value;
	}
}
// On affiche 
$pourvoir=intval($start+$maxipage);
if($pourvoir>$compteresultats){
	$pourvoir=$compteresultats;
} 
echo("R�sultats de votre recherche pour <font color=$color><b>$blork2</b></font><br> $compteresultats trouv�s sur $zetotal paragraphes. - Affichage des r�sultats $start � $pourvoir<p>");  
$end=intval($start+$maxipage); 
if($compteresultats>0){ 
	foreach($vrai as $key=>$value){ 
		if($key>=$start && $key<=$end){ 
			echo($value); 
//			$enfin++;
		}
	}
}
if($compteresultats>$maxipage){ 
	echo("<br><br><center>"); 
	$finstart=intval($compteresultats-$maxipage); 
	$prevbarre=intval($start-$maxipage);
	$nextbarre=intval($start+$maxipage); 
	$nb_barre="1"; 
	if($start!="0"){ 
		echo("<A href='parcours.php?name=Recherche&blork=$blork&action=go'><< D�but</A> "); }
	else{ echo("<< D�but "); }
	if($start!="0"){ echo(" <A href='parcours.php?name=Recherche&blork=$blork&action=go&start=$prevbarre'>< Page pr�c�dente</A> ("); } 
	else{ echo(" < Page pr�c�dente ("); } 
	for($barre=0;$barre<$compteresultats;){ 
		$finbarre=intval($compteresultats-$barre); 
		echo(" <A href='parcours.php?name=Recherche&blork=$blork&action=go&start=$barre'>$nb_barre</A> "); 
		$nb_barre++; 
		$barre=intval($barre+$maxipage);
	} 

	if($start<=$finstart){ echo(") <A href='parcours.php?name=Recherche&blork=$blork&action=go&start=$nextbarre'>Page suivante ></A>"); } 
	else{ echo(") Page suivante > "); } 
	if($compteresultats>$maxipage){ 
		echo(" <A href='parcours.php?name=Recherche&blork=$blork&action=go&start=$finstart'>Fin >></A>");
	} 
} 

if($compteresultats=="0"){ 
	echo("<br> Votre recherche sur le terme <font color=$color><b>$blork2</b></font> n'a donn� aucun r�sultat. Essayez d'�largir votre recherche en y mettant moins de mots ou v�rifiez son orthographe.");
} elseif($compteresultats=="1"){ 
	echo("<center><br> $compteresultats r�sultat trouv� sur $zetotal paragraphes.</center>");
} else{ 
	echo("<center><br> $compteresultats r�sultats trouv�s sur $zetotal paragraphes.</center>");
}

}
}

echo("<div align=\"center\"><br>Nouvelle recherche :<br><input type=text value=\"$blork2\" maxlength=50 size=20 name=blork> <input type=submit value='Chercher'><br><input type=hidden value=\"go\" name=action></form>"); 

CloseTable();
include("footer.php");

?>