<?php
/*
Informations à lire dans le document:
ouvrage titre		    dtd[0]

auteur  nom		    dtd[1]
        prenom		    dtd[2]

edition editeur		    dtd[3]
        ville		    dtd[4]
        date		    dtd[5]

paragraphe numero	    dtd[6]
           partie	    dtd[7]
           chapitre	    dtd[8]
           sous-chapitre    dtd[9]

mot-clef

notion relation
       liennotion
*/


function decodeDocument($i) {
    global $ouvrage;
    global $texte;
// variables correspondant aux entités de la DTD
    global $titre;
    global $nom, $prenom;
    global $editeur, $ville, $date;
    global $numero, $partie, $chapitre, $souschapitre;
// objets de stockage des entités de la DTD
    global $flag , $notionsParagraphe;
    global $glossaire, $motclef, $note ;

        $flag = new pile;
        $notionsParagraphe = new analyse;
        $glossaire = new pile;
        $motclef = new pile;
		$note    = new pile;

// Initialisation pour préparer le parsing d'un fichier XML

        $file = "/var/www/html/Colis/$ouvrage/$i.xml";

        $texte = "";
        $partie = "";
        $chapitre ="";
        $souschapitre = "";

        if (!(list($xml_parser, $fp) = new_xml_parser($file))) {
           echo("Je n'ai pas trouvé la page $i");
        } else {
	        while ($data = fread($fp, 4096)) {
    	       if (!xml_parse($xml_parser, $data, feof($fp))) {
        	      die(sprintf("XML error: %s at line %d\n", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser)));
            	}
        	}
	        xml_parser_free($xml_parser);
		}

}

function trustedFile($file) {
// only trust local files owned by ourselves
if (!eregi("^([a-z]+)://", $file) && fileowner($file) == getmyuid()) {
return TRUE;
}
return FALSE;
}

function startElement($parser, $name, $attribs) {

// variables correspondant aux entités de la DTD
    global $titre, $ouvrage ;
    global $nom, $prenom;
    global $editeur, $ville, $date;
    global $numero, $partie, $chapitre, $souschapitre;
    global $texte, $page ;
	
	global $numappel;

    global $notionsParagraphe;
    global $flag ;

    $flag->ajoute($name);

    switch ($name) {
         case "OUVRAGE":
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
         case "PARAGRAPHE":
         if (sizeof($attribs)) {
            while (list($k, $v) = each($attribs)) {
               switch ($k) {
               case "NUMERO":
                   $numero = $v;
//				   $texte .= "<b>§ $numero</b> -";
               break;
               case "PAGE":
                   $page = $v;
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
         if (sizeof($attribs)) {
            while (list($k, $v) = each($attribs)) {
               switch ($k) {
               case "NUMAPPEL":
                   $numappel = $v;
				   $texte .= "<sup> $numappel </sup>";
               break;
               }
            }
         }
         break;
         case "IMG":
         if (sizeof($attribs)) {
            while (list($k, $v) = each($attribs)) {
               switch ($k) {
               case "SRC":
                   $texte .= "<img src=\"/Colis/$ouvrage/$v\" alt=\"Illustration\">";
               break;
               }
            }
         }
         break;
         case "MOT-CLEF":
//         $texte = $texte."<a class=\"motclef\" href=\"#";
         break;
         default:
// On affiche tels quels les éléments HTML qui pourraient apparaitre dans le document XML.
// BTW, les balises XML inconnues subiront le même sort.
//         print "<p>Element XML inconnu: $name";
         $texte = $texte."<".$name.">";

    }
}

function endElement($parser, $name) {
         global $flag, $texte;

    switch ($name) {
         case "OUVRAGE":
         break;
         case "AUTEUR":
         break;
         case "NOTION":
         break;
         case "EDITION":
         break;
         case "IMG":
         break;
         case "PARAGRAPHE":
		 $texte .= "<p>";
         break;
         case "GLOSSAIRE":
//         $texte = $texte."</a>";
         break;
         case "NOTE":
         break;
         case "MOT-CLEF":
//         $texte = $texte."</a>";
         break;
         default:
//         print "<p>Element XML inconnu: $name";
         $texte = $texte."</".$name.">";

    }
         if (!$flag->ote($name)) {echo "<p>Document XML Invalide -- Elément fermant <$name> asymétrique";}
}

function characterData($parser, $data) {
         global $texte, $notionsParagraphe, $flag;
         global $glossaire, $motclef;
		 global $note, $numappel ;

         switch ($flag->valeur()) {
         case "PARAGRAPHE":
              $texte = $texte.$data;
         break;
         case "NOTION":
              $notionsParagraphe->ajoute($data);
         break;
		 case "NOTE":
		 	$note->ajoute($numappel." - ".$data);
         case "MOT-CLEF":
//              $texte = $texte.$data."\">".$data;
//              $motclef->ajouteUnique($data);
         break;
         case "GLOSSAIRE":
			  $texte .= creeLienPage("Glossaire", $data);
//              $texte = $texte.$data."\">".$data;
//              $glossaire->ajouteUnique($data);
         break;
         default:
//         print "<p>Element XML inconnu: $name";
         $texte = $texte.$data;
         }
}

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

function defaultHandler($parser, $data) {
if (substr($data, 0, 1) == "&" && substr($data, -1, 1) == ";") {
printf("<font color=\"#aa00aa\">%s</font>",htmlspecialchars($data));
} else {
printf("<font size=\"-1\">%s</font>",htmlspecialchars($data));
}
}

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

?>