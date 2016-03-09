<?php

// Vous pouvez changer le chemin de départ, par default "." //
//$depart=".";
$depart = $baseDir;

// ----- Affiche arborescence ----- Auteur : fgauharou@yahoo.fr ------------- //
require("fonctions.php");

// ------------------------------ Initialisation des variables ----------------------------------------------- //

if(!empty($chemin))     $chemin     = stripslashes($chemin); else $chemin = $depart;


// ----------------------------------- Sécurité navigation -------------------------------------------------- //

$chemintotal = $chemin;


// ============================= Affichage =================================== //

?>

<! ----------------------- Affichage du chemin ------------------>
<table width=90% border="0" cellspacing="0" cellpadding="0" BGCOLOR="#b0bace">
  <tr>
    <td><b><? echo " Vous êtes dans le dossier : ";?></b><?echo $chemin; // -- Affiche le dossier courant -- // ?>
  </td>
  </tr>
</table>

<TABLE WIDTH="90%" BORDER="0" CELLPADDING="0" CELLSPACING="1" nowrap>
<TR bgcolor="#AABBFF">
	<TD  width=35>&nbsp;</TD>
	<TD  ALIGN="center"><b>Nom</b></TD>
	<TD width=65 ALIGN="center" ><B>Taille</B></TD>
	<TD width=200 ALIGN="center" COLSPAN="3"><B>Action</B></TD>
</TR>
<TR><TD COLSPAN="6"><HR NOSHADE></TD></TR>

<?
// ------ Si on clique sur renommer le fichier --------------- //


switch($action) {
//-----------------------------------------------------------------------------------------------------------------------------------------
//	EDITER / EDIT
//-----------------------------------------------------------------------------------------------------------------------------------------

case "editer";
	$code=stripslashes($code);

	if($save==1)
	{
		echo "<p align=\"center\">Modifications enregistrées\n";
		$code=str_replace("&lt;","<",$code);
		$fp=fopen($baseDir.$fichier,"w");
		fputs ($fp,$code);
		fclose($fp);
//		enlever_controlM("$colisroot$pid/$fichier");
	}
	echo "<p align=\"center\">\n";
	echo "<font size=\"2\">Edition du fichier <b>$fichier</b></font><br>";
	echo "<form action=\"$PHP_SELF\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"pid\" value=\"$pid\">\n";
	echo "<input type=\"hidden\" name=\"fichier\" value=\"$fichier\">\n";
//	echo "<input type=\"hidden\" name=\"rep\" value=\"$rep\">\n";
	echo "<input type=\"hidden\" name=\"save\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"editer\">\n";
//	echo "<input type=\"hidden\" name=\"ordre\" value=\"$ordre\">\n";
//	echo "<input type=\"hidden\" name=\"sens\" value=\"$sens\">\n";
	echo "<TEXTAREA NAME=\"code\" rows=\"30\" cols=\"100\" wrap=\"OFF\">\n";

if ($fp=fopen($baseDir.$fichier,"r"))
{
	while (!feof($fp)) 
	{ 
		$tmp=fgets($fp,4096);
		$tmp=str_replace("<","&lt;",$tmp);
		echo "$tmp";
	}
	fclose($fp);
}else{
echo "$fichier introuvable";

}
	echo "</TEXTAREA>\n";
	echo "<p align=\"center\">\n";
	echo "<input type=\"image\" src=\"images/enregistrer.gif\" alt=\"Enregistrer\">\n";
	echo "<a href=\"$PHP_SELF?pid=$pid&op=$op&fichier=$fichier\"><img src=\"images/fermer.gif\" alt=\"Fermer\"></a>\n";
	echo "</form>\n";
	echo "</p>\n";
break;
case "renomme";
    echo "<br>Changer le nom du fichier: <b>".$fichier . "</b> en<BR>\n"; 
    echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF?op=$op&pid=$pid\">\n"; 
    echo "Nouveau nom: <INPUT TYPE=\"TEXT\" NAME=\"newfile\" value=$fichier><BR>\n"; 
    echo "<INPUT TYPE=\"SUBMIT\" NAME=\"confirm\" VALUE=\"Change\">\n"; 
    echo "<INPUT TYPE=\"SUBMIT\" NAME=\"cancel\" VALUE=\"Cancel\">\n"; 
    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"action\" VALUE=\"renommeok\">\n"; 
    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"fichier\" VALUE=$fichier>\n"; 
    echo "</FORM>";
break;
case "detruit";
    echo "<h3>Etes-vous sur de vouloir détruire le fichier $fichier ?
	<A HREF=\"$PHP_SELF?op=$op&pid=$pid&action=detruitok&fichier=$fichier\"> OUI </A> / <A HREF=\"$PHP_SELF?op=$op&pid=$pid\"> NON </A></h3>";
break;
case  "renommeok";
	if($newfile) { 
		echo "Le fichier : $baseDir.$fichier a été renommé en $baseDir.$newfile";
    	rename($baseDir.$fichier,$baseDir.$newfile);
		$action="";
	}
break;
case "detruitok";
// ------ Si on clique sur détruire le fichier --------------- //
		echo "Le fichier : $colisroot$pid/$fichier a été éffacé";
		unlink($colisroot.$pid."/".$fichier);
		$action="";
break;
}

// ------------------------------- Récupération des fichiers et répertoires dans tableau-- //


$handle  = @opendir($chemintotal);
$file    = @readdir($handle);      // repertoire .
$file    = @readdir($handle);      // repertoire ..
$repind  = 0;
$fileind = 0;

while ($file = @readdir($handle))
{
	if(is_dir("$chemintotal/$file"))
	{
		$reptab[$repind]["nom"]           = $file;
		$reptab[$repind]["taille"]        = filesize("$chemintotal/$file");
		$repind++;
	}
	else
	{
		$filetab[$fileind]["nom"]         = $file;
		$filetab[$repind]["taille"]        = filesize("$chemintotal/$file");
		$fileind++;
	}
}

@closedir($handle);

	
// ------ Si on clique sur dossier parent --------------- //

if($chemin != $depart)
{
	$cheminretour = ModifChemin($chemin);
	$cheminretour = rawurlencode($cheminretour);

	?>
	<TR>
		<TD width=35 ALIGN="center"><A HREF="./dir.php?chemin=<? echo $cheminretour; ?>"><IMG SRC="./images/back.gif" BORDER="0"></A></TD>
		<TD ALIGN="left"  ><A HREF="./dir.php?chemin=<? echo $cheminretour; ?>">..</A></TD>
	</TR>
	<?
}


// -------------------------------------- Affichage des répertoires --------------------------------------- //

for($i=0;$i<$repind;$i++)
{
	$nomrep      = $reptab[$i]["nom"];
	$cheminrep   = rawurlencode($chemin."/".$nomrep);
	$IndiceImage = $i;
	$pair	     = $i%2;
	?>
	<TR>
		<TD <? if ($pair==1) {echo "";?>BGCOLOR="#b0bace" bordercolor="#b0bace"<?} else {echo "";?>BGCOLOR="#d8dde7" bordercolor="#d8dde7"<?}?> ALIGN="center"><A HREF="./dir.php?chemin=<? echo $cheminrep; ?>"><IMG SRC="./images/dir.gif" border="0"></A></TD>
		<TD <? if ($pair==1) {echo "";?>BGCOLOR="#b0bace" bordercolor="#b0bace"<?} else {echo "";?>BGCOLOR="#d8dde7" bordercolor="#d8dde7"<?}?> ALIGN="left" ><font color="#000099"><font face=" Verdana,Arial, Helvetica, sans-serif" size="2"><? echo $nomrep; ?></TD>
		<TD <? if ($pair==1) {echo "";?>BGCOLOR="#b0bace" bordercolor="#b0bace"<?} else {echo "";?>BGCOLOR="#d8dde7" bordercolor="#d8dde7"<?}?> ALIGN="left" ><font color="#000099"><font face=" Verdana,Arial, Helvetica, sans-serif" size="2"></TD>

	</TR>
	<?
}

// --------------------------------------- Affichage des fichiers ----------------------------------------- //

$IndiceImage++;


for($i=0;$i<$fileind;$i++)
	{
	$nomfic      = $filetab[$i]["nom"];
	$ext         = GetExtension($nomfic);
	$ext         = strtolower($ext);
	$icone       = GetIcone($ext);
	$IndiceImage = $i;
	$pair	     = $i%2;
	if ($pair==1) {
		$tmp = 'BGCOLOR="#F2F4F7" bordercolor="#F2F4F7"';
	} else {
		$tmp = 'BGCOLOR="#EBEEF3" bordercolor="#EBEEF3"';
	}
	
	echo "<TR>
		<TD $tmp width=35 ALIGN=\"center\"><IMG SRC =\"images/fileext/$icone\"></TD>
		<TD $tmp ALIGN=\"left\"  >$nomfic</TD>
		<TD $tmp ALIGN=\"right\" >".FormatTailleFichier(filesize($chemin."/".$nomfic))."</TD>
		<TD $tmp width=\"100\" ALIGN=\"center\"><A HREF=$PHP_SELF?op=$op&pid=$pid&action=renomme&fichier=\"$nomfic\">renommer</A></TD>
		<TD $tmp width=\"100\" ALIGN=\"center\"><A HREF=$PHP_SELF?op=$op&pid=$pid&action=detruit&fichier=\"$nomfic\">détruire</A></TD>
		<TD $tmp width=\"100\" ALIGN=\"center\">";
	if (is_editable($nomfic)) {
		echo "<A HREF=\"$PHP_SELF?op=$op&pid=$pid&action=editer&fichier=$nomfic\">modifier</A></TD>
	</TR>";
	}else{
		ECHO "</td>
	</TR>";
	}

	}

// ------ fin du tableau ---- //

?>
<TR><TD COLSPAN="10"><HR NOSHADE></TD></TR>
</TABLE><BR>


<! --------- Affiche le nombre de dossiers et de fichiers -------------- >


<table width=400 border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td background="/images/menu.jpg"><B>
	<? echo " Nombre de dossier(s) : ".$repind; ?> - <? echo " Nombre de fichier(s) : ".$fileind; ?></B>
	</td>
  </tr>
</table>
</BODY>
</HTML>