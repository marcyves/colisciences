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
/* This file is part of CoLiSciences (https://github.com/marcyves/colisciences)                      */
/*                                                                            */
/******************************************************************************/

	decodeDocument($valeur);
	affiche( "Notions et relations de ce paragraphe", $notionsParagraphe->lien($valeur), "Mots et notions", $motclef->liste("motclef"),"Parcours", $memoire->paragraphe("icone"));
	$flagParcours = true;
?>