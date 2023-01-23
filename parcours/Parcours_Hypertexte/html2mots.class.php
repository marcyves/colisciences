<?php
///////////////////////////////////////////////////////////////////////////////////////////
/////////// Author    : Reza Salehi
///////////	Contact   : zaalion@yahoo.com
/////////// Copyright : free for non-commercial use . 
///////////////////////////////////////////////////////////////////////////////////////////

	class html2word
	{
		///////////////////////////////////////////////////////////////////////////////////////////
		//---- Class Properties
		///////////////////////////////////////////////////////////////////////////////////////////
		
		var $uniq; 		//---- Array which holds page words.
		var $coun; 		//---- Array which holds word numbers.
		var $total;		//---- Number of words in a page or file.
		var $unum;		//---- Number of unique words in a page or file.
		
		///////////////////////////////////////////////////////////////////////////////////////////
		//---- Private variables
		///////////////////////////////////////////////////////////////////////////////////////////
		
		var $all;
		var $str;
		var $mm;
		var $f;
		
		///////////////////////////////////////////////////////////////////////////////////////////
		//---- Class constructor which initialize internal variables and runs main method of class.
		///////////////////////////////////////////////////////////////////////////////////////////
		
		function html2word($url, $max, $filter)
		{
			$this->f=$filter;
			$this->mm=$max;
			if(substr($url,0,4)=="http")
				$mode='u';
			else
				if(substr($url,0,4)=="inc:")
					$mode='i';
				else
					$mode='f';
			if($mode=='u')
			{
				$fp = fopen ($url, "r");
				if($fp)
				{
					while(!feof($fp))
					{
						$contents.= fgetss ($fp,1024);
					}
				}
				else
				{
					$this->str='';
				}
			}
			else if($mode=='f')
			{
				
				$fp = fopen ($url, "r");
				if($fp)
				{
					$contents = fread ($fp, filesize ($url));
				}
				else
				{
					$contents='';
				}
			}
			else if($mode=='i')
			{
					$contents = substr($url,4,strlen($url)-4);
			}
			else
			{
				$contents='';
			}
			$j=0;
			$contents = str_replace("'"," ",$contents);
			$contents = str_replace("-"," ",$contents);
			$contents = str_replace("."," ",$contents);
			$contents = strip_tags($contents);
			for($i=0;$i<strlen($contents);$i++)
			{
				$char=ord($contents[$i]);
				if( ($char>=97 && $char<=122) || ($char>=65 && $char<=90) || ($char>=48 && $char<=57) || $char==32 || chr($char)=='$' || chr($char)=='.' ||chr($char)=="é"||chr($char)=="é"||chr($char)=="é"||chr($char)=="é"||chr($char)=="é"||chr($char)=="é"||chr($char)=="é")
				{	
					$this->str.=chr($char);
				}
			}
//			echo "<br>".$this->str;
			$this->getstat();
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////
		//---- main function which parses web page or file and returnes 2 arrays which hold -
		//	   list of unique words and number of them.
		///////////////////////////////////////////////////////////////////////////////////////////
		
		function getstat()
		{	
			global $dbi;
			
			$word=explode(" ",$this->str);
			sort($word);
			$j=1;
			$k=0; 	
 			for($i=0;$i<count($word);$i++)			
			{				
 				if(strlen($word[$i])>$this->mm || $word[$i]==' ' || ord($word[$i])==13 || ord($word[$i])==10 || ord($word[$i])==0)
				{
					$i++;
					continue;
				}
				if($this->f=='1')
				{	
					if(strlen($word[$i])<3)
						$word[$i]=' ';
					$word[$i]=str_replace(' ','',$word[$i]);
					$mix=strtolower($word[$i]);
					if ($mix!=''){
//debug						echo "<br>recherche $mix";
						$mix = lemme($mix);
					    $sql = mysqli_query($dbi, "select swid from cb_occurences_stopwords where stop = '$mix'");
						if ($sql->num_rows==0) {
							while(($word[$i]==$word[$i+1]))
							{
								$i++;
								$j++;
							}
							$this->uniq[$k]=$word[$i];
							$this->coun[$k++]=$j;
							$j=1;
						}	
					}
 				}
			}
			$this->unum=count($this->uniq);
			$this->total=count($word);
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////
		//---- this is a simple function which shows the result. but you can get array 'uniq' &
		//	   'coun' and manually print them .
		///////////////////////////////////////////////////////////////////////////////////////////
		
		function show()
		{			
			for($i=0;$i<count($this->uniq);$i++)
			{
				$zero=str_repeat('0',5-strlen($this->coun[$i]));
				$this->coun[$i]=$zero.$this->coun[$i];
				$this->all[$i]=$this->coun[$i]." ---> ".$this->uniq[$i];
			}
			sort($this->all);
			for($i=count($this->all);$i>-1;$i--)
			{				
				print $this->all[$i]."<br>";
			}
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////
		//---- this is a simple function which stores the result.
		///////////////////////////////////////////////////////////////////////////////////////////
		
		function store($ouvrage,$paragraphe)
		{			

			for($i=0;$i<count($this->uniq);$i++)
			{
				storeDB($this->uniq[$i],$this->coun[$i],$ouvrage,$paragraphe);
			}
			storeDB('***total***',$this->total,$ouvrage,$paragraphe);
			storeDB('***unum***',$this->unum,$ouvrage,$paragraphe);
//debug			print "Number of words : ".$this->total."<br>";					   	   // number of words in the file or html page.
//debug			print "Number of unique words : ".$this->unum."<br>";					   // number of unique words in the page.
		}
	}

	function storeDB($mot,$count,$ouvrage,$paragraphe)
	{
		global $dbi;
		
	    $sql = mysqli_query($dbi, "select motId, count from cb_occurences_cumul where mot ='$mot'");
		if (@list($motId, $cumul) = mysqli_fetch_row($sql)){
			$cumul = $cumul + $count;
		    mysqli_query($dbi, "update cb_occurences_cumul set count = '$cumul' where motId = '$motId'");
		}else{
	    	mysqli_query($dbi, "insert into cb_occurences_cumul values (NULL, '$mot', '$count')");
	    	$sql = mysqli_query($dbi, "select motId from cb_occurences_cumul where mot ='$mot'");
			list($motId) = mysqli_fetch_row($sql);
		}
    	mysqli_query($dbi, "insert into cb_occurences values (NULL, '$motId', '$ouvrage', '$paragraphe', '$count')");
	}
	
	function lemme($mot){
		global $dbi;
/*	
	$mot = "<".$mot.">";
	$mot = str_replace("ais>","*",$mot);	
	$mot = str_replace("ait>","*",$mot);	
	$mot = str_replace("aient>","*",$mot);	
	$mot = str_replace("ions>","*",$mot);	
	$mot = str_replace("iez>","*",$mot);	
	$mot = str_replace("ez>","*",$mot);	
	$mot = str_replace("ons>","*",$mot);
	$mot = str_replace("s>",">",$mot);
//formes du verbe avoir
	$mot = str_replace("av*","avoir",$mot);
//formes du verbe étre
	$mot = str_replace("ét*","étre",$mot);
	$mot = str_replace("ser*","étre",$mot);

	$mot = str_replace("<","",$mot);
	$mot = str_replace(">","",$mot);
*/
	    $sql = mysqli_query($dbi, "select lemme from cb_dictionnaire where mot ='$mot'");
		if (@list($lemme) = mysqli_fetch_row($sql)){
			$mot = strtolower(trim($lemme));
		}
	
	return $mot;	
	}

?>
