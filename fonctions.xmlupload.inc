<?php

/**
 * decodeDocument()
 * 
 * @param $file
 * @return 
 */
function decodeDocument($file) {	
    global $ouvrage;
// variables correspondant aux entités de la DTD
    global $titre;
    global $nom, $prenom;
    global $editeur, $ville, $date;
    global $numero, $partie, $chapitre, $souschapitre;
// objets de stockage des entités de la DTD
    global $flag , $notionsParagraphe;
    global $glossaire, $motclef;

    $flag              = new pile;
    $notionsParagraphe = new analyse;
    $glossaire         = new pile;
    $motclef           = new pile;

// Initialisation pour préparer le parsing d'un fichier XML

    $partie = "";
    $chapitre ="";
    $souschapitre = "";

    if (!(list($xml_parser, $fp) = new_xml_parser($file))) {
	    die("Je n'ai pas trouvé de fichier XML correspondant à votre requête");
    }

    while ($data = fread($fp, 4096)) {
	    if (!xml_parse($xml_parser, $data, feof($fp))) {
    	    die(sprintf("XML error: %s at line %d\n", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser)));
        }
    }
	messageLog("Fin","Fin du traitement");
    xml_parser_free($xml_parser);
}

/**
 * trustedFile()
 * 
 * @param $file
 * @return 
 */
function trustedFile($file) {
// only trust local files owned by ourselves
	if (!eregi("^([a-z]+)://", $file) && fileowner($file) == getmyuid()) {
		return TRUE;
	}
	return FALSE;
}

/**
 * startElement()
 * 
 * @param $parser
 * @param $name
 * @param $attribs
 * @return 
 */
function startElement($parser, $name, $attribs) {

// variables correspondant aux entités de la DTD
global $titre, $baseDir, $front , $type;
global $nom, $prenom;
global $editeur, $ville, $date;
global $numero, $page, $partie, $chapitre, $souschapitre;
global $front, $texteParagraphe ;
global $niveau;
global $notionsParagraphe, $flagPartiesLiminaires, $flagPageDeTitre ;
global $flag, $flagNote , $flagSP, $flagNumAppel ;

$flag->ajoute($name);

switch ($name) {
	case "OUVRAGE":
    if (sizeof($attribs)) {
    	while (list($k, $v) = each($attribs)) {
        	switch ($k) {
            	case "TYPE":
                	$type = $v;
               	break;
            }
        }
    }
    break;
	case "PARTIESLIMINAIRES":
		$flagPartiesLiminaires = 1;
    break;
    case "AUTEUR":
        if (sizeof($attribs)) {
        	while (list($k, $v) = each($attribs)) {
            	switch ($k) {
            		case "NOM":
                   		$nom = $v;
               		break;
               		case "PRENOM":
                   		$prenom = $v;
               		break;
				}
            }
         }
	break;
    case "NOTION":
         if (sizeof($attribs)) {
            while (list($k, $v) = each($attribs)) {
               switch ($k) {
               case "RELATION":
                   $notionsParagraphe->relation($v);
               break;
               case "LIEN-NOTION":
                   $notionsParagraphe->liennotion($v);
               break;
               }
            }
     }
     break;
     case "EDITION":
         if (sizeof($attribs)) {
            while (list($k, $v) = each($attribs)) {
               switch ($k) {
               case "EDITEUR":
                   $editeur = $v;
               break;
               case "LIEU":
                   $ville = $v;
               break;
               case "VILLE":
                   $ville = $v;
               break;
               case "DATE":
                   $date = $v;
               break;
               }
            }
	}
    break;
    case "P":
	 	if ((strlen($front)>0)&&($page <> "")&&($texteParagraphe<>"")&&($flagNote<>"1")) {
	    	messageLog("Debug", "<p>Traitement du paragraphe : $numero.");
		}
    break;
    case "SP":
    	if (sizeof($attribs)) {
        	while (list($k, $v) = each($attribs)) {
            	switch ($k) {
           		case "NUM":
					if ($page+1 != $v){
						messageLog ("Erreur", "Rupture de séquence des pages de $page à $v");
					}
			   		$page = $v;
					messageLog ("Info", "page = $v");
					$texteParagraphe .= " ";
               	break;
				}
		    }
		 }
		 break;  
         case "PARAGRAPHE":
         if (sizeof($attribs)) {
            while (list($k, $v) = each($attribs)) {
               switch ($k) {
               case "NUMERO":
                   $numero = $v;
               break;
               case "PARTIE":
                    $partie = $v;
               break;
               case "CHAPITRE":
                    $chapitre = $v;
               case "SOUS-CHAPITRE":
                    $souschapitre = $v;
               break;
               }
            }
         }
         break;
         case "GLOSSAIRE":
//         $texte = $texte."<a class=\"glossaire\" href=\"#";
         break;
         case "NOTE":
		 	$flagNote = 1;
		 	$texteParagraphe .= "<note";
         break;
         case "NUMAPPEL":
		 	$flagNumAppel = 1;
		 	$texteParagraphe .= " numappel =\"";
         break;
         case "ILLUSTRATION":
         if (sizeof($attribs)) {
            while (list($k, $v) = each($attribs)) {
               switch ($k) {
               case "LIEN":
                   $tmp = "<img src=\"$v\"/>";
				   traitementData($tmp);
				   messageLog("Info","Insertion de l'illustration: $v");
               break;
               }
            }
         }
         break;
         case "TOMAISON":
         break;
         case "DIV":
         break;
         case "BODY":
         break;
         case "DIV1":
		 	$niveau = "partie";
         break;
         case "DIV2":
		 	$niveau = "chapitre";
         break;
         case "DIV3":
		 	$niveau = "souschapitre";
         break;
         case "LIST":
		 	$texteParagraphe .= "<ul>";
         break;
         case "ITEM":
		 	$texteParagraphe .= "";
         break;
         case "FRONT":
         break;
         case "PAGEDETITRE":
 		 	$flagPageDeTitre = 1;
         break;
         case "TITRE":
         break;
         case "TITREPROPRE":
         break;
         case "TITREPARTIE":
         break;
         case "MOT-CLEF":
//         $texte = $texte."<a class=\"motclef\" href=\"#";
         break;
         default:
// On affiche tels quels les éléments HTML qui pourraient apparaitre dans le document XML.
// BTW, les balises XML inconnues subiront le même sort.
//         print "<p>Element XML inconnu: $name";
messageLog ("Erreur", "<p>Balise < $name > détectée mais non traitée");
         $texteParagraphe = $texteParagraphe."<".$name.">";

    }
}

/**
 * endElement()
 * 
 * @return 
 */
function endElement($parser, $name) {
	global $titreTMP, $partieTMP, $partiesLiminairesTMP, $chapitreTMP, $souschapitreTMP ,$pageDeTitreTMP ;
    global $titre, $baseDir, $front , $type;
    global $nom, $prenom;
    global $editeur, $ville, $date;
    global $numero, $page, $partie, $chapitre, $souschapitre;

    global $notionsParagraphe, $flagPartiesLiminaires, $partiesLiminaires, $flagPageDeTitre, $pageDeTitre ;
    global $flag, $flagNote, $flagSP, $flagNumAppel , $texteParagraphe ;
	global $niveau,$tome ;

    switch ($name) {
         case "FRONT":
// C'est la fin de l'entête, on peut écrire l'entête générique pour tous les documents

	$front = "<?xml version=\"1.0\" encoding=\"iso-8859-1\" standalone=\"yes\"?>
<?xml-stylesheet href=\"ouvrage.css\" type=\"text/css\" ?>
<!--Generated On-line by CoLiSciences.-->
    <ouvrage titre=\"$titre\"";

	if ($type<>""){
		$front .= " type=\"$type\"";
	}
	if ($tome<>""){
		$front .= " tome=\"$tome\"";
	}
	$front .= " >
	   <!--(auteur+ , edition , tome+)-->
	      <auteur nom=\"$nom\" prenom=\"$prenom\"/>
		     <edition editeur=\"$editeur\" ville=\"$ville\" date=\"$date\"/>
			 ";
		 messageLog ("Info", "L'entête est prêt");
		 $page = "0";
		 ecritFichier($page,$pageDeTitre.$partiesLiminaires);
         break;
		 /* Traitement de la balise "début de paragraphe" pour identifier s'il s'agit d'un réel début
		 *  de paragraphe textuel ou simplement d'un séparateur.
		 * On doit donc vérifier:
		 *   la partie front est terminée
		 *   un début de page a déjà été identifié */
         case "P":
		 	if ((strlen($front)>0)&&($texteParagraphe<>"")&&($flagNote<>"1")) {
			 messageLog ("Debug", "Fin de paragraphe Jouve");
				$l = strlen($texteParagraphe) - 1;
				$pos1 = strrpos($texteParagraphe,":");
				$pos2 = strrpos($texteParagraphe,"»");
				$pos3 = strrpos($texteParagraphe,",");
				$pos4 = strrpos($texteParagraphe,";");
				messageLog ("Debug", "len: $l pos: $pos1 $pos2 $pos3 $pos4");
				if (($pos1>=$l)||($pos2>=$l)||($pos3>=$l)||($pos4>=$l)){
					 messageLog ("Debug", "Poursuite du paragraphe");
					 $texteParagraphe .= "<br/>";
				} else {
				 	messageLog ("Debug", "Ecriture du paragraphe");
					messageLog ("Debug", "texteParagraphe:<p>$texteParagraphe");
					$numero = $numero + 1;
					ecritFichier($numero,ecritParagraphe($numero));
				}
			}
         break;
         case "TITREPROPRE":
              $titre = $titreTMP;
              $titreTMP = "";
  			 messageLog ("Info", "<p>J'ai trouvé le titre de l'ouvrage: <b>$titre</b>");
         break;
         case "TITREPARTIE":
		 	switch ($niveau){
			case "partie":
              $partie = $partieTMP;
              $partieTMP = "";
  			 messageLog ("Info", "J'ai trouvé le titre de la partie: <b>$partie</b>");
			break;
			case "chapitre":
              $chapitre = $chapitreTMP ;
              $chapitreTMP = "";
  			 messageLog ("Info", "J'ai trouvé le titre du chapitre: <b>$chapitre</b>");
			break;
			case "souschapitre":
              $souschapitre = $souschapitreTMP ;
              $souschapitreTMP = "";
  			 messageLog ("Info", "J'ai trouvé le titre du sous-chapitre: <b>$souschapitre</b>");
			break;
			}
         break;
         case "SP":
         break;
         case "PARTIESLIMINAIRES":
			$flagPartiesLiminaires = 0;
            $partiesLiminaires = $partiesLiminairesTMP ;
            $partiesLiminairesTMP = "";
			 messageLog ("Info", "J'ai trouvé les parties liminaires: <b>$partiesLiminaires</b>");
         break;
         case "NOTE":
			if ($flagNumAppel == 0){
				messageLog("Erreur","La note en cours n'a pas de numéro.");
			}
		 	$flagNote = 0;
			$flagNumAppel = 0;
			$texteParagraphe .= "</note>";
         break;
         case "NUMAPPEL":
//		 	$texte .= "</numappel>";
         break;
         case "OUVRAGE":
         break;
         case "BODY":
         break;
         case "LIST":
		 	$texteParagraphe .= "</ul>";
         break;
         case "ITEM":
		 	$texteParagraphe .= "</li>";
         break;
         case "ILLUSTRATION":
         break;
         case "PAGEDETITRE":
			$flagPageDeTitre = 0;
            $pageDeTitre = $pageDeTitreTMP ;
            $pageDeTitreTMP = "";
			 messageLog ("Info", "J'ai trouvé la page de titre: <b>$pageDeTitre</b>");
         break;
         case "TOMAISON":
         break;
         case "DIV1":
			traitementFinDivision();
         break;
         case "DIV2":
			traitementFinDivision();
         break;
         case "DIV3":
			traitementFinDivision();
         break;
         case "DIV":
         break;
         case "AUTEUR":
         break;
         case "EDITION":
         break;
         case "PARAGRAPHE":
         break;
         case "GLOSSAIRE":
//         $texte = $texte."</a>";
         break;
         case "MOT-CLEF":
//         $texte = $texte."</a>";
         break;
         case "FRONT":
         break;
         case "TITRE":
         break;
         default:
//         print "<p>Element XML inconnu: $name";
			messageLog("Erreur","Balise < /$name > détectée mais non traitée");
         $texteParagraphe = $texteParagraphe ."</".$name.">";

    }
         if (!$flag->ote($name)) {echo "<p>Document XML Invalide -- Elément fermant <$name> asymétrique";}
}

/**
 * characterData()
 * 
 * @param $parser
 * @param $data
 * @return 
 */
function characterData($parser, $data) {
	global $titreTMP, $partieTMP, $partiesLiminairesTMP, $chapitreTMP, $souschapitreTMP, $pageDeTitreTMP ;
    global $texteParagraphe, $flag, $flagNote, $flagNumAppel ;
    global $glossaire, $motclef;
    global $notionsParagraphe, $flagPartiesLiminaires, $partiesLiminaires, $flagPageDeTitre, $pageDeTitreTMP ;
	global $niveau, $tome ;
	
	if ($data<>"\n") {
         switch ($flag->valeur()) {
         case "TITREPARTIE":
		 	switch ($niveau){
			case "partie":
              $partieTMP .= $data;
			break;
			case "chapitre":
              $chapitreTMP .= $data;
			break;
			case "souschapitre":
              $souschapitreTMP .= $data;
			break;
			}
         break;
         case "TITREPROPRE":
              $titreTMP .= $data;
         break;
         case "PARAGRAPHE":
              $texteParagraphe = $texteParagraphe .$data;
         break;
         case "P":
		    traitementData($data);
         break;
         case "NUMAPPEL":
			$flagNumAppel = 2;
		 	$texteParagraphe .= "$data\" >";
         break;
         case "LIST":
		 	$texteParagraphe .= $data;
         break;
         case "ITEM":
		 	$texteParagraphe .= $data;
         break;
         case "MOT-CLEF":
//              $texte = $texte.$data."\">".$data;
//              $motclef->ajouteUnique($data);
         break;
         case "GLOSSAIRE":
//			  $texteParagraphe .= creeLienPage("Glossaire", $data);
//              $texte = $texte.$data."\">".$data;
//              $glossaire->ajouteUnique($data);
         break;
         case "TOMAISON":
		 	$tome = $data;
         break;
         case "OUVRAGE":
         break;
         case "BODY":
         break;
         case "DIV1":
         break;
         case "FRONT":
         break;
         case "PAGEDETITRE":
         break;
         case "TITRE":
         break;
         default:
		 	if (($data <> "")&&($data <> "\n")) {
			 messageLog ("Erreur","Les données de la balise ".$flag->valeur()." n'ont pas été traitées:<br>Data = $data");
    	     $texteParagraphe = $texteParagraphe .$data;
			}
         }
	}
}

/**
 * PIHandler()
 * 
 * @param $parser
 * @param $target
 * @param $data
 * @return 
 */
function PIHandler($parser, $target, $data) {
         switch (strtolower($target)) {
         case "php":
         global $parser_file;
// If the parsed document is "trusted", we say it is safe
// to execute PHP code inside it. If not, display the code
// instead.
   if (trustedFile($parser_file[$parser])) {
      eval($data);
   } else {
     printf("Code PHP peu sÛr : <B>%s</B>",
     htmlspecialchars($data));
   }
   break;
   }
}

/**
 * defaultHandler()
 * 
 * @param $parser
 * @param $data
 * @return 
 */
function defaultHandler($parser, $data) {
if (substr($data, 0, 1) == "&" && substr($data, -1, 1) == ";") {
printf("<font color=\"#aa00aa\">%s</font>",htmlspecialchars($data));
} else {
printf("<font size=\"-1\">%s</font>",htmlspecialchars($data));
}
}

/**
 * externalEntityRefHandler()
 * 
 * @param $parser
 * @param $openEntityNames
 * @param $base
 * @param $systemId
 * @param $publicId
 * @return 
 */
function externalEntityRefHandler($parser, $openEntityNames, $base, $systemId,$publicId) {
if ($systemId) {
   if (!list($parser, $fp) = new_xml_parser($systemId)) {
      printf("Could not open entity %s at %s\n", $openEntityNames,$systemId);
      return FALSE;
   }
   while ($data = fread($fp, 4096)) {
         if (!xml_parse($parser, $data, feof($fp))) {
            printf("XML error: %s at line %d while parsing entity %s\n",xml_error_string(xml_get_error_code($parser)),xml_get_current_line_number($parser), $openEntityNames);
            xml_parser_free($parser);
            return FALSE;
         }
   }
   xml_parser_free($parser);
   return TRUE;
}
return FALSE;
}

/**
 * new_xml_parser()
 * 
 * @param $file
 * @return 
 */
function new_xml_parser($file) {
         global $parser_file;

         $xml_parser = xml_parser_create();
         xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 1);
         xml_set_element_handler($xml_parser, "startElement", "endElement");
         xml_set_character_data_handler($xml_parser, "characterData");
         xml_set_processing_instruction_handler($xml_parser, "PIHandler");
//         xml_set_default_handler($xml_parser, "defaultHandler");
         xml_set_external_entity_ref_handler($xml_parser, "externalEntityRefHandler");
         if (!($fp = @fopen($file, "r"))) {
            return FALSE;
         }
         if (!is_array($parser_file)) {
            settype($parser_file, "array");
         }
         $parser_file[$xml_parser] = $file;
         return array($xml_parser, $fp);
}

/**
 * traitementData()
 * 
 * @param $data
 * @return 
 */
function traitementData($data){
global $flagPartiesLiminaires, $partiesLiminairesTMP ;
global $flagPageDeTitre, $pageDeTitreTMP ;
global $flagNote, $flagNumAppel, $texteParagraphe ;

	if ($flagPartiesLiminaires == 1) {
		$partiesLiminairesTMP .= $data;
	} else if ($flagNote==1){
		if ($flagNumAppel != 2){
			$texteParagraphe .= " numappel =\"1\">" ;
    	    messageLog("Info", "Texte de note trouvé avant le numéro d'appel, <b>1</b> assigné arbitrairement.");
		}
		$texteParagraphe .= $data;
   	    messageLog("Debug", "Texte de la note: <b>$data</b>");
	} else if ($flagPageDeTitre ==1){
		$pageDeTitreTMP .= $data;
	} else {
        $texteParagraphe .= $data;
	}
}

function traitementFinDivision(){
Global $texteParagraphe, $numero ;
if (strlen($texteParagraphe)>0){
	$numero = $numero + 1 ;
 	messageLog("Warning", "Ecriture de la dernière page par défaut<br>le paragraphe $numero devra être vérifié.");
	ecritFichier($numero,ecritParagraphe($numero));
}
}
?>