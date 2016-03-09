<?
function enlever_controlM($fichier)
	{
	$fic=file($fichier);
	$fp=fopen($fichier,"w");
	while (list ($cle, $val) = each ($fic)) 
		{
		$val=str_replace(CHR(10),"",$val);
		$val=str_replace(CHR(13),"",$val);
		fputs($fp,"$val\n");
		}
	fclose($fp);
	}

// ----------------------------- Fonctions de gestion du chemin --------------------------- //

function ModifChemin($chemin)
{
	$taille = strlen($chemin);
	$i = $taille;
	$fin = 0;

	while((!$fin) || (i > 0))
	{
		$i--;
		if($chemin[$i] == "/") $fin = $i;
	}

	$newchemin = substr($chemin,0,$fin);
	return $newchemin;
}

function is_editable($fichier)
	{
	$retour=0;
	if(eregi("\.txt$|\.sql$|\.php$|\.php3$|\.phtml$|\.htm$|\.html$|\.cgi$|\.pl$|\.js$|\.css$|\.inc$|\.xml$",$fichier)) {$retour=1;}
	return $retour;
	}


/*
function DecomposerChemin($chemin)
{
	$taille = strlen($chemin);
	$partie = "";

	$chemindecompose = "<A HREF=./dir.php?chemin="."&pagecentrale=1".">.</A>";

	if($taille > 1)
	{
		$cumul = ".";

		for($i=2;$i<$taille;$i++)
		{
			if($chemin[$i] == "/")
			{
				$cumul = "$cumul/$partie";
				$cumulencode = rawurlencode($cumul);
				$chemindecompose = $chemindecompose."/<A HREF=./dir.php?chemin=$cumulencode"."&pagecentrale=1".">$partie</A>";
				$partie = "";
			}
			else $partie = $partie.$chemin[$i];
		}
		$cumul = "$cumul/$partie";
		$cumulencode = rawurlencode($cumul);
		$chemindecompose = $chemindecompose."/<A HREF=./dir.php?chemin=$cumulencode"."&pagecentrale=1".">$partie</A>";
	}

	return $chemindecompose;
}
*/

// =================== Fonction de gestions des extension et ic�nes =========== //

function GetIcone($ext)
{
	switch($ext)
	{
		case "xml"  : $icone = "xml.gif";			break;
		case "jpg"  : $icone = "image1.gif";			break;
		case "gif"  : $icone = "image1.gif";			break;
		case "png"  : $icone = "image1.gif";			break;
		case "bmp"  : $icone = "image1.gif";			break;
		case "tif"  : $icone = "image1.gif";			break;
		case "c"    : $icone = "script.gif";			break;
		case "cpp"  : $icone = "script.gif";			break;
		case "mpg"  : $icone = "movie.gif";			break;
		case "avi"  : $icone = "movie.gif";			break;
		case "mov"  : $icone = "movie.gif";			break;
		case "pdf"  : $icone = "pdf.gif";			break;
		case "ps"   : $icone = "script.gif";			break;
		case "zip"  : $icone = "zip.gif";			break;
		case "ace"  : $icone = "zip.gif";			break;
		case "tar"  : $icone = "zip.gif";			break;
		case "gz"   : $icone = "zip.gif";			break;
		case "uu"   : $icone = "zip.gif";			break;
		case "bat"  : $icone = "bat.gif";			break;
		case "css"  : $icone = "css.gif";			break;
		case "sql"  : $icone = "texte.gif";			break;
		case "txt"  : $icone = "texte.gif";			break;
		case "mp3"  : $icone = "sound.gif";			break;
		case "wav"  : $icone = "sound.gif";			break;
		case "au"   : $icone = "sound.gif";			break;
		case "mid"  : $icone = "sound.gif";			break;
		case "rtf"  : $icone = "quill.gif";			break;
		case "doc"  : $icone = "doc.gif";			break;
		case "xls"  : $icone = "excel.gif";			break;
		case "ppt"  : $icone = "ppt.gif";			break;
		case "pps"  : $icone = "script.gif";			break;
		case "inc"  : $icone = "script.gif";			break;
		case "php"  : $icone = "script.gif";			break;
		case "php3" : $icone = "script.gif";			break;
		case "php4" : $icone = "script.gif";			break;
		case "com"  : $icone = "com.gif";			break;
		case "css"  : $icone = "script.gif";			break;
		case "inc"  : $icone = "script.gif";			break;
		case "asp"  : $icone = "script.gif";			break;
		case "html" : $icone = "html.gif";			break;
		case "htm"  : $icone = "html.gif";			break;
		case "exe"  : $icone = "exe.gif";			break;
		default     : $icone = "unknown.gif";		break;
	}
    return $icone;
}

function GetExtension($fichier)
{
	$pos = strrpos($fichier,".");
	$extension = substr($fichier,$pos+1,strlen($fichier)-$pos);
	return $extension;
}

function GetFileName($fichier)
{
	$pos = strrpos($fichier,".");
	$extension = substr($fichier,1,$pos);
	$extension = $extension/1;
	return $extension;
}

// ----------------------- Fonction de gestions de la taille du fichier ------------------------ //

function FormatTailleFichier($Taille)
{
		 if($Taille == 0)					$format = "";
	else if($Taille <= 1024)				$format = $Taille." oct";
	else if($Taille <= (10*1024))			$format = sprintf ("%.2f k%s",($Taille/1024),"o");
	else if($Taille <= (100*1024))			$format = sprintf ("%.1f k%s",($Taille/1024),"o");
	else if($Taille <= (1024*1024))			$format = sprintf ("%d k%s",($Taille/1024),"o");
	else if($Taille <= (10*1024*1024))		$format = sprintf ("%.2f M%s",($Taille/(1024*1024)),"o");
	else if($Taille <= (100*1024*1024))		$format = sprintf ("%.1f M%s",($Taille/(1024*1024)),"o");
	else									$format = sprintf ("%d M%s",($Taille/(1024*1024)),"o");

	return $format;
}


?>