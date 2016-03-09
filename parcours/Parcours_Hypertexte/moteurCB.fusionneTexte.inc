<?php

/******************************************************************************/
/*                                                                            */
/* moteurCB.php - phpCB                                                       */
/*                                                                            */
/******************************************************************************/
/*                                                                            */
/* Requirements: PHP, MySQL and web-browser                                   */
/*                                                                            */
/* Author: Marc Augier                                                        */
/*         <marc.augier@cote-azur.cci.fr>                                     */
/*                                                                            */
/* Created: 29 March 2002                                                     */
/*                                                                            */
/* Copyright (c) 2002 LCP - MEDIATEC                                          */
/*                                                                            */
/* This file is part of phpCB (http://colisciences.net/)                      */
/*                                                                            */
/******************************************************************************/

if (!eregi("parcours.php", $PHP_SELF)) {
    die ("You can't access this file directly...");
}

// Initialisation pour préparer le parsing d'un fichier XML
$file1 = "/var/www/html/Colis/$ouvrage/$valeur.xml";
$suivant = $valeur + 1;
$file2 = "/var/www/html/Colis/$ouvrage/$suivant.xml";
		        
// Include the <phpXML/> class.
require_once("xml.php");
            
// Create an XML object for the XML file.
$xml1 = new XML($file1);
$xml2 = new XML($file2);

// Selectionne toutes les balises des ouvrages.
$ouvrageXML1 = $xml1->evaluate("//ouvrage");
$ouvrageXML2 = $xml2->evaluate("//ouvrage");

$arbreXML1 = $ouvrageXML1[0];
$arbreXML2 = $ouvrageXML2[0];

$paragraphe1 = $xml1->get_attributes($arbreXML1."/paragraphe[1]");
$paragraphe2 = $xml2->get_attributes($arbreXML2."/paragraphe[1]");

if (($paragraphe1['partie'] == $paragraphe2['partie'])&&($paragraphe1['chapitre'] == $paragraphe2['chapitre'])&&($paragraphe1['souschapitre'] == $paragraphe2['souschapitre'])) {
//Fusion du texte
    $texte2      = "<p/>".$xml2->get_content($arbreXML2."/paragraphe[1]");
    $xml1->add_content($arbreXML1."/paragraphe[1]",$texte2);
//Fusion des notions
	$i = 1;
	do {
        $notion     = $xml2->get_content($arbreXML2."/paragraphe[1]/notion[".$i."]");
   	    if ($notion != "") {
	        $relation     = $xml2->get_attributes($arbreXML2."/paragraphe[1]/notion[".$i."]");
		    $ouvrageXML1 = $xml1->add_node("/ouvrage[1]/paragraphe[1]","notion");
			$xml1->set_content($ouvrageXML1, $notion);

			$xml1->set_attributes($ouvrageXML1, $relation);
			$i++;
		} else {
			$i = 0;
		}
	} while ($i>0);
//
//    $glossaire  = $xml->get_content($arbreXML."/paragraphe[1]/glossaire[1]");
//Fusion des notes
	$i = 1;
	do {
        $texteNote  = $xml2->get_content($arbreXML2."/paragraphe[1]/note[".$i."]");
		if ($texteNote != "") {
    	    $numappel   = $xml2->get_attributes($arbreXML2."/paragraphe[1]/note[".$i."]");
		    $ouvrageXML1 = $xml1->add_node("/ouvrage[1]/paragraphe[1]","note");
			$xml1->set_content($ouvrageXML1, $texteNote);

			$xml1->set_attributes($ouvrageXML1, $numappel);
			$i++;
		} else {
			$i = 0;
		}
	} while ($i>0);
	
//Création du fichier XML pour écriture
	$tmp = createHeaderXML().$xml1->get_file();

    if ($pointer = @fopen($file1, "w")) {
		fwrite($pointer,$tmp);
	}
	echo "<h3>Les paragraphes $valeur et $suivant ont été fusionnés.</h3>";

//Efface le deuxième paragraphe 
	unlink($file2);	
	echo "<h3>Le paragraphe $suivant est effacé.</h3>";

//Renumérotation des paragraphes à partir de suivant
	for ($i=$suivant;$i<$nombre_noeuds;$i++){
		$destination = "/var/www/html/Colis/$ouvrage/$i.xml";
		$j = $i + 1;
		$source = "/var/www/html/Colis/$ouvrage/$j.xml";
		copy ($source,$destination);
//On renumérote le paragraphe comme attribut XML dans le fichier
	    $xml = new XML($destination);
   
// Selectionne toutes les balises de l'ouvrage.
    	$ouvrageXML = $xml->evaluate("/ouvrage");
		$arbreXML = $ouvrageXML[0];
        $paragraphe = $xml->get_attributes($arbreXML."/paragraphe[1]");
		$paragraphe['numero'] = $i;
		$xml->set_attributes($arbreXML."/paragraphe[1]",$paragraphe);
//Création du fichier XML pour écriture
	$tmp = createHeaderXML().$xml->get_file();

    if ($pointer = @fopen($destination, "w")) {
		fwrite($pointer,$tmp);
	}
		unset($xml);
	}
	$suivant = $suivant + 1;
	echo "<h3>Les paragraphes $suivant à $nombre_noeuds ont été renumérotés.</h3>";

	$nextStep = "paragraphe";

} else {
	echo "<h3>Les paragraphes $valeur et $suivant ne peuvent pas être fusionnés.</h3>Ils ne font pas partie de la même unité de texte.";
}

 ?>