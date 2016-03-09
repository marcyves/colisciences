<?php

function decodeDocument($p) {
    global $ouvrage, $colisroot;
    global $texte;
// variables correspondant aux entités de la DTD
    global $titre;
    global $nom, $prenom;
    global $editeur, $ville, $date;
    global $paragraphe;
// objets de stockage des entités de la DTD
    global $flag , $notionsParagraphe;
    global $glossaire, $motclef, $note ;


    $flag = new pile;
    $notionsParagraphe = new analyse;
    $glossaire = new pile;
    $motclef = new pile;
	$note    = new pile;

// Initialisation pour préparer le parsing d'un fichier XML
    $file = $colisroot.$ouvrage."/$p.xml";
//debug echo "<p>Analyse du fichier XML: $file";
// Include the <phpXML/> class.
    require_once("xml.php");

// Create an XML object for the XML file.
//debug echo "<p>fonctions.xml.inc : debut";            
    $xml = new XML($file);
//debug echo "<p>fonctions.xml.inc : XML file created";            
// Selectionne toutes les balises de l'ouvrage.
    $ouvrageXML = $xml->evaluate("/ouvrage");
//debug echo "<p>fonctions.xml.inc : ouvrageXML created";

// Run through all tags.
    foreach ( $ouvrageXML as $arbreXML )
	{
    	// Retrieve information about the person.
      $titre    = $xml->get_attributes($arbreXML);
      $auteur   = $xml->get_attributes($arbreXML."/auteur[1]");
			$nom      = $auteur['nom'];
			$prenom   = $auteur['prenom'];
      $edition  = $xml->get_attributes($arbreXML."/edition[1]");
			$editeur  = $edition['editeur'];
			$ville    = $edition['ville'];
			$date     = $edition['date'];
      $paragraphe = $xml->get_attributes($arbreXML."/paragraphe[1]");
//        $texte      = $xml->get_content($arbreXML."/paragraphe[1]");

		$i = 1;
		do {
	        $notion     = $xml->get_content($arbreXML."/paragraphe[1]/notion[".$i."]");
    	    if ($notion != "") {
		        $relation     = $xml->get_attributes($arbreXML."/paragraphe[1]/notion[".$i."]");
				$notionsParagraphe->relation($relation);
				$notionsParagraphe->ajoute($notion);
				$i++;
			} else {
				$i = 0;
			}
		} while ($i>0);

//        $glossaire  = $xml->get_content($arbreXML."/paragraphe[1]/glossaire[1]");

		$i = 1;
		do {
	        $texteNote  = $xml->get_content($arbreXML."/paragraphe[1]/note[".$i."]");
    	    $numappel   = $xml->get_attributes($arbreXML."/paragraphe[1]/note[".$i."]");
//debug echo "<p>fonctions.xml.inc: decode document - note ".$numappel['numappel']." texteNote $texteNote ";
			if (($texteNote != "")or($numappel !="")) {
			 	$note->ajoute($numappel['numappel']." - ".$texteNote);
				$i++;
			} else {
				$i = 0;
			}
		} while ($i>0);

	}
unset($xml);
}

function createHeaderXML(){

    $today = getdate();
    $day = $today[mday];
    if ($day < 10) {
        $day = "0$day";
    }
    $month = $today[mon];
    if ($month < 10) {
        $month = "0$month";
    }
    $year = $today[year];
    $hour = $today[hours];
    $min = $today[minutes];

	$tmp = "<?xml version=\"1.0\" encoding=\"iso-8859-1\" standalone=\"yes\"?>
<!--Generated On-line by CoLiSciences.-->
<!--Modified On-line on ".$day."/".$month."/".$year." at ".$hour.":".$min.".-->
";

return $tmp;
}


?>