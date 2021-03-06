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
	if ($notion == "s�lectionner une notion principale") {
		echo "<p>Veuillez s�lectionner une notion principale.<br>";
	} else {
		if ($liennotion == "s�lectionner une notion li�e") {
			$liennotion = "";
		}
// Initialisation pour pr�parer le parsing d'un fichier XML
  	 	$file = "/var/www/html/Colis/$ouvrage/$valeur.xml";
		        
// Include the <phpXML/> class.
	    require_once("xml.php");
            
// Create an XML object for the XML file.
    	$xml = new XML($file);

	    $ouvrageXML = $xml->add_node("/ouvrage[1]/paragraphe[1]","notion");
		$xml->set_content($ouvrageXML, $notion);

		$attributes['relation'] = $relation;
		if ($liennotion!=""){
			$attributes['lien-notion'] = $liennotion;
		}
	
		$xml->set_attributes($ouvrageXML, $attributes);
		$tmp = createHeaderXML().$xml->get_file();

//	echo "<P>Le document XML g�n�r�<pre>$tmp</pre>";

	    if ($pointer = @fopen($file, "w")) {
			fwrite($pointer,$tmp);
		}
	echo "<h3>La notion : <b>$notion</b> a �t� ajout�e.</h3>Vous pouvez cliquer sur un onglet pour continuer.<p>";
	}

/* Ceci devrait permettre d'afficher directement le paragraphe modifi�
*  mais cela ne marche pas ????
	$nextStep = "notion";
	$notion = "";
	$parcours = "notion";
	*/ 
?>