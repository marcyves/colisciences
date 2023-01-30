<?php

class analyse
{

        var $item;
        var $relation;
        var $liennotion;
        var $compte;
        var $time;

        function __construct()
        {
                $this->compte = 0;
        }

        function ajoute($nom)
        {
                $this->item[$this->compte++] = $nom;
                $this->time = time();
                //debug echo "<p>notion ajout�e $nom , il y en a ".$this->compte;
        }

        function relation($nom)
        {
                $this->relation[$this->compte] = $nom['relation'];
                $this->liennotion[$this->compte] = $nom['lien-notion'];
                //debug echo "<p>relation ajout�e ".$nom['relation']." , il y en a ".$this->compte;
        }

        function nombre()
        {
                $cnt = $this->compte;
                return $cnt;
        }

        function quand()
        {
                return $this->time;
        }
        function precedent()
        {
                $cnt = $this->compte - 1;
                /*
* 		echo "<p>PRECEDENT, il y en a ".$cnt;
*		for ($i=1;$i<=$cnt;$i++){
*			echo "<br> $i - ".$this->item[$i];
*		}
*/
                return $this->item[$cnt];
        }

        function chercheR($para)
        {
                //debug      echo "<p>chercheR ";
                $cnt = $this->compte;
                for ($i = 0; $i < $cnt; $i++) {
                        //debug      echo "<p>relation cherch�e ".$this->relation[$i]." sur $i, il y en a ".$this->compte;
                        if (strtoupper($this->relation[$i]) == strtoupper($para)) {
                                return TRUE;
                        }
                }
                return FALSE;
        }

        function cherche1($para)
        {
                $cnt = $this->compte;
                for ($i = 0; $i < $cnt; $i++) {
                        //debug		echo "<br>cherche1 $para=".$this->item[$i];
                        if (strtoupper($this->item[$i]) == strtoupper($para)) {
                                return '1';
                        }
                }
                return '0';
        }

        function cherche2($para)
        {
                $cnt = $this->compte;
                for ($i = 0; $i < $cnt; $i++) {
                        if (strtoupper($this->liennotion[$i]) == strtoupper($para)) {
                                return '2';
                        }
                }
                return '0';
        }

        function chercheTout($para)
        {
                $cnt = $this->compte;
                //echo "<p>chercheTout $para parmi $cnt notions ";
                for ($i = 0; $i < $cnt; $i++) {
                        //				echo $this->item[$i]." ".$this->liennotion[$i];
                        if (strtoupper($this->liennotion[$i]) == strtoupper($para)) {
                                return '2';
                        }
                        if (strtoupper($this->item[$i]) == strtoupper($para)) {
                                return '1';
                        }
                }
                return '0';
        }

        function lien($para)
        {
                $texte = "<p>";
                $cnt = $this->compte - 1;
                for ($i = 0; $i <= $cnt; $i++) {
                        $texte = $texte . creeLienNotion($para + 1, $this->item[$i], "principal") . creeLienNotion($para, $this->relation[$i], "relation");
                        if (@$this->liennotion[$i] != "") {
                                $texte = $texte . creeLienNotion($para + 1, $this->liennotion[$i], "secondaire") . "<br>";
                        } else {
                                $texte = $texte . "<br>";
                        }
                }
                return $texte;
        }

        function paragraphe($type)
        {
                global $texte, $partie, $chapitre, $souschapitre;

                $partie0 = "";
                $chapitre0 = "";
                $souschapitre0 = "";

                $tmp = "";
                $cnt = $this->compte;
                for ($i = 0; $i < $cnt; $i++) {
                        if ($type == "icone") {
                                if (isset($this->relation[$i])) {
                                        $tmp .= $this->relation[$i];
                                }
                                if (isset($this->liennotion[$i])) {
                                        $tmp .= " " . $this->liennotion[$i];
                                }
                                $tmp .= affichageIcone($this->item[$i], "actif");
                        } else {
                                decodeDocument($this->item[$i]);
                                if ($partie == $partie0) {
                                        $partie1 = "";
                                } else {
                                        $partie1 = $partie;
                                }

                                if ($chapitre == $chapitre0) {
                                        $chapitre1 = "";
                                } else {
                                        $chapitre1 = $chapitre;
                                }

                                if ($souschapitre == $souschapitre0) {
                                        $souschapitre1 = "";
                                } else {
                                        $souschapitre1 = $souschapitre;
                                }

                                affiche($this->item[$i], $texte, $partie1,  $chapitre1, $souschapitre1, "", "", "", "", "", "");

                                $partie0 = $partie;
                                $chapitre0 = $chapitre;
                                $souschapitre0 = $souschapitre;
                        }
                }
                echo "</b><p>";
                if ($type == "icone") {
                        $tmp .= creeLien("", "menunav", "Afficher le parcours", "parcours");
                }
                return $tmp;
        }
}

class pile
{
        var $item;              // El�ments de la pile
        var $compteur;

        function pile()
        {
                //      constructeur de la classe pile
                $this->compteur = 0;
        }

        function ajoute($nom)
        {
                $this->item[$this->compteur++] = $nom;
        }

        function ajouteUnique($nom)
        {
                echo "<p>class.inc.php j'ajoute  $nom dans la pile";
                $flag = true;
                for ($i = 0; $i < $this->compteur; $i++) {
                        if ($this->item[$i] == $nom) {
                                $flag = false;
                        }
                }
                if ($flag) {
                        $this->item[$this->compteur++] = $nom;
                }
        }

        function ote($nom)
        {
                if ($this->item[$this->compteur - 1] != $nom) {
                        return FALSE;
                } else {
                        $this->item[$this->compteur--] = "";
                        return TRUE;
                }
        }

        function depile()
        {
                $this->compteur--;
                return $this->item[$this->compteur];
        }

        function valeur()
        {
                return $this->item[$this->compteur - 1];
        }

        function liste($type)
        {

                if ($this->compteur > 0) {
                        $texte = "";
                        for ($i = 0; $i < $this->compteur; $i++) {
                                if ($type != "note") {
                                        $texte = $texte . creeLienPage($type, $this->item[$i]) . "<br>";
                                } else {
                                        $texte .=  $this->item[$i] . "<br>";
                                }
                        }
                } else {
                        $texte = "";
                }
                return $texte;
        }
}
