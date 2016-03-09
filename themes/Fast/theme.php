<?php


function OpenTable() {
    global $bgcolor1, $bgcolor2;
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" id=\"content\"><tr><td>\n";
}

function CloseTable() {
    echo "</td></tr></table>\n";
}

function OpenTable2() {
    global $bgcolor1, $bgcolor2;
    echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>\n";
    echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"8\" bgcolor=\"$bgcolor1\"><tr><td>\n";
}

function CloseTable2() {
    echo "</td></tr></table></td></tr></table>\n";
}


function FormatStory($thetext, $notes, $aid, $informant) {
    global $anonymous;
    if ($notes != "") {
	$notes = "<b>"._NOTE."</b> <i>$notes</i>\n";
    } else {
	$notes = "";
    }
    if ("$aid" == "$informant") {
	echo "<font class=\"content\" color=\"#505050\">$thetext<br>$notes</font>\n";
    } else {
	if($informant != "") {
	    $boxstuff = "<a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;uname=$informant\">$informant</a> ";
	} else {
	    $boxstuff = "$anonymous ";
	}
	$boxstuff .= "".translate("writes")." <i>\"$thetext\"</i> $notes\n";
	echo "<font class=\"content\" color=\"#505050\">$boxstuff</font>\n";
    }
}

/**
 * themeheaderMain()
 * 
 * @return 
 */
function themeheaderMain() {
    global $theme,$slogan, $sitename, $userinfo, $user, $cookie;



echo "
<!-- Message pour les browsers ne supportant pas CSS  -->
<P class=hide>This page uses CSS style sheets, if you see this message it may 
not display properly because your browser does not support CSS.<BR>Please 
consider upgrading your browser.</P>

<!-- Début de l'en-tête  -->

<table width=\"100%\" border=\"0\">
  <tr> 
   <td> <img src=\"themes/$theme/img/logo.gif\" width=\"185\" height=\"134\" usemap=\"#Logo\" alt=\"CNRS - LCP - MediaTeC\">
      <map name=\"Logo\" id=\"Logo\">
        <area shape=\"rect\" coords=\"28,102,159,133\" href=\"http://www.unice.fr/MediaTec/\" target=\"_blank\" alt=\"M&eacute;diaTeC\">
        <area shape=\"rect\" coords=\"58,62,125,94\" href=\"http://www.lcp.cnrs.fr/\" target=\"_blank\" alt=\"LCP\">
        <area shape=\"rect\" coords=\"3,15,70,47\" href=\"http://www.cnrs.fr/\" target=\"_blank\" alt=\"CNRS\">
      </map>
   </td>
   <td width=\"100%\"> <div align=\"center\"> 
        <object classid=clsid:D27CDB6E-AE6D-11cf-96B8-444553540000 
      codebase=http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0 width=100%
      height=75>
          <param name=\"movie\" value=\"themes/$theme/sitename.swf?text=CoLiSciences\">
          <param name=\"quality\" value=\"high\">
          <param name=\"bgcolor\" value=\"\">
          <param name=\"menu\" value=\"false\">
          <embed 
      src=\"themes/$theme/sitename.swf?text=CoLiSciences\" width=\"500\" height=75 align=\"middle\" quality=\"high\" type=\"application/x-shockwave-flash\" 
pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" 
      menu=\"false\"></embed> </object>
      </div>
	  </td>
  </tr>
</table>

 <table width=\"100%\" height=\"20\" border=0 cellPadding=0 cellSpacing=0 class=\"boxcontent\"><tr><td>";

    cookiedecode($user);
    $username =$cookie[1];
    if ($username == ""||$username=="Anonymous") {
	echo "&nbsp;&nbsp;Bonjour, vous pouvez vous <a href=\"modules.php?name=Your_Account\">connecter</a>";
    } else { echo "&nbsp;&nbsp;Bonjour $username !";}
    echo "</td>
 
       <td><font class=\"tiny\"><script type=\"text/javascript\">
        var monthNames = new Array( \"Janvier\",\"Février\",\"Mars\",\"Avril\",\"Mai\",\"Juin\",\"Juillet\",\"Août\",\"Septembre\",\"Octobre\",\"Novembre\",\"Décembre\");
        var now = new Date();
        thisYear = now.getYear();
        if(thisYear < 1900) {thisYear += 1900};
        document.write(now.getDate() + \" \" + monthNames[now.getMonth()] + \" \" + thisYear);
        </script></font>
		</td>
        </tr>
        </table>


<br>";



}

/**
 *themeheader()
 * 
 * @return 
 */
function themeheader() {
    global $theme,$slogan, $sitename, $userinfo, $user, $cookie;
	

echo "
<br>";
}

function themefooter() {
    global $index; $titre; $barcolor;

    blocks(left);

if ($index == 1) {
    echo "<td>&nbsp;</td><td valign=\"top\">"; /*xc3 peut-etre a l'origine de code sup*/
    blocks(right);
    echo "</td>";/*xc3*/
}

/*if ($titre=="") { 
echo "$barcolor=\"boxcontent\" ";
}
else { 
echo "$barcolor=\"boxcontent2\" ";
 }*/  
if (is_admin($admin)) {
/*echo "</tr></table></td></tr></table>";*/ /*xc3 genere du code sup*/
echo "<!-- Footer -->
 <table width=\"100%\" height=\"20\" border=0 cellPadding=0 cellSpacing=0 class=\"boxcontent\">
<tr>
<td class=\"tiny\">Dernières modifications: " 
. date("D j F Y g:i a", getlastmod()) 
. "</td></tr></table>";
}
else {
echo "<!-- Footer -->
 <table width=\"100%\" height=\"20\" border=0 cellPadding=0 cellSpacing=0 class=\"boxcontent\">
<tr>
<td class=\"tiny2\">Copyright CNRS - MEDIATEC, 2002. <A HREF=\"modules.php?name=Informations\">Tous droits réservés</A>.</td>
<td class=\"tiny2\" align=\"right\">Nous contacter : <A HREF=\"mailto: msilberstein@mshparisnord.org\">LCP</A>.</td></tr></table>";
}

footmsg();
}



function themeindex ($aid, $informant, $datetime, $title, $counter, $topic, $thetext, $notes, $morelink, $topicname, $topicimage, $topictext) {
	global $anonymous;
	if ("$aid" == "$informant") { ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#DDDDDD"><tr valign="top" bgcolor="#e6e6fa">
			<td><img src="themes/$theme/images/pix.gif" width="4" height="4" alt=""></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="2" cellspacing="0"><tr><td>
			<font class="option" color="#FFFFFF"><B><?php echo"$title"; ?></B></font>
			</td></tr></table>
			</td><td align="right"><img src="themes/$theme/images/pix.gif" width="4" height="4" alt=""></td>
         	</tr></table>
		<table border="0" cellpadding="0" cellspacing="0"><tr bgcolor="#e6e6e6">
			<td background="themes/$theme/images/gl.gif"></td>
			<td width="100%">
				<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr>
				<td><font class="tiny"><?php echo translate("Posted by "); ?> <?php formatAidHeader($aid) ?> <?php echo translate("on"); ?> <?php echo"$datetime $timezone"; ?> (<?php echo $counter; ?> <?php echo translate("reads"); ?>)</font></td>
				</tr></table>
			</td>
			<td background="themes/$theme/images/gr.gif"></td>
		</tr>
		<tr bgcolor="#006666"><td colspan="3"></td></tr>
		<tr bgcolor="#ffffff">
			<td background="themes/$theme/images/wl.gif"></td>
			<td width="100%"><table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td>
			
			<a href="modules.php?name=Search&amp;query=&topic=<?php echo"$topic"; ?>&author="><img src=<?php echo"images/topics/$topicimage"; ?> border=0 Alt=<?php echo"\"$topictext\""; ?> align=right hspace=10 vspace=10></a>
			
			<?php echo "$thetext"; ?>
                 </td></tr></table></td>
                 <td background="themes/$theme/images/wr.gif"></td>
		</tr>
		<tr bgcolor="#e6e6fa"><td colspan="3"></td></tr>
		</table><table border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor="#ffffff">
			<td background="themes/$theme/images/wl_cccccc.gif"></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="5" cellspacing="0"> 
			<tr><td bgcolor="#cccccc"><font class="content"><?php echo"$morelink"; ?></font></td></tr></table>
			</td>
			<td background="themes/$theme/images/wr_cccccc.gif"></td>
		</tr>
		<tr bgcolor="#e6e6fa"><td colspan="3"></td></tr>
		</table><BR><BR>
<?php	} else {
		if($informant != "") $boxstuff = "<a href=\"modules.php?name=Your_Account&amp;op=userinfo&uname=$informant\">$informant</a> ";
		else $boxstuff = "$anonymous ";
		$boxstuff .= "".translate("writes")." <i>\"$thetext\"</i> $notes";
?>		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#DDDDDD"><tr valign="top" bgcolor="#e6e6fa">
			<td><img src="themes/$theme/images/pix.gif" width="4" height="4" alt=""></td>
			<td width="100%"><table width="100%" border="0" cellpadding="2" cellspacing="0"><tr><td>
			<font class="option" color="#FFFFFF"><B><?php echo"$title"; ?></B></font>
			</td></tr></table></td>
                 	<td align="right"><img src="themes/$theme/images/pix.gif" width="4" height="4" alt=""></td>
         	</tr></table>
		<table border="0" cellpadding="0" cellspacing="0"><tr bgcolor="#e6e6e6">
			<td background="themes/$theme/images/gl.gif"></td>
			<td width="100%">
				<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr>
				<td><font class="tiny"><?php echo translate("Posted by "); ?> <?php formatAidHeader($aid) ?> <?php echo translate("on"); ?> <?php echo"$datetime $timezone"; ?> (<?php echo $counter; ?> <?php echo translate("reads"); ?>)</font></td>
				</tr></table>
			</td>
			<td background="themes/$theme/images/gr.gif"></td>
		</tr>
		<tr bgcolor="#006666"><td colspan="3"></td></tr>
		<tr bgcolor="#ffffff">
			<td background="themes/$theme/images/wl.gif"></td>
			<td width="100%"><table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td>
			<a href="modules.php?name=Search&amp;query=&topic=<?php echo"$topic"; ?>&author="><img src=<?php echo"images/topics/$topicimage"; ?> border=0 Alt=<?php echo"\"$topictext\""; ?> align=right hspace=10 vspace=10></a>
			<?php echo "$boxstuff"; ?>
                 </td></tr></table></td>
                 <td background="themes/$theme/images/wr.gif"></td>
		</tr>
		<tr bgcolor="#e6e6fa"><td colspan="3"></td></tr>
		</table><table border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor="#ffffff">
			<td background="themes/$theme/images/wl_cccccc.gif"></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td bgcolor="#cccccc"><font class="content"><?php echo"$morelink"; ?></font></td></tr></table>
			</td>
			<td background="themes/$theme/images/wr_cccccc.gif"></td>
		</tr>
		<tr bgcolor="#e6e6fa"><td colspan="3"></td></tr>
		</table><BR><BR>
<?php	}
}

function themearticle ($aid, $informant, $datetime, $title, $thetext, $topic, $topicname, $topicimage, $topictext) {
	global $admin, $sid;
	if ("$aid" == "$informant") { ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#DDDDDD"><tr valign="top" bgcolor="#e6e6fa">
			<td><img src="themes/$theme/images/pix.gif" width="4" height="4" alt=""></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="2" cellspacing="0"><tr><td>
			<font class="option" color="#ffffff"><B><?php echo"$title"; ?></B></font>
			</td></tr></table>
			</td><td align="right"><img src="themes/$theme/images/pix.gif" width="4" height="4" alt=""></td>
         	</tr></table>
		<table border="0" cellpadding="0" cellspacing="0"><tr bgcolor="#e6e6e6">
			<td background="themes/$theme/images/gl.gif"></td>
			<td width="100%">
				<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr>
				<td><font class="tiny"><?php echo translate("Posted by "); ?> <?php formatAidHeader($aid) ?> <?php echo translate("on"); ?> <?php echo"$datetime $timezone"; ?></font>

<?php
if ($admin) {
    echo "&nbsp;&nbsp; $font2 [ <a href=admin.php?op=EditStory&sid=$sid>".translate("Edit")."</a> | <a href=admin.php?op=RemoveStory&sid=$sid>".translate("Delete")."</a> ]";
}
?>
				</td>
				</tr></table>
			</td>
			<td background="themes/$theme/images/gr.gif"></td>
		</tr>
		<tr bgcolor="#006666"><td colspan="3"></td></tr>
		<tr bgcolor="#ffffff">
			<td background="themes/$theme/images/wl.gif"></td>
			<td width="100%"><table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td>
		<?php echo "<a href=modules.php?name=Search&amp;query=&topic=$topic&author=><img src=images/topics/$topicimage border=0 Alt=\"$topictext\" align=right hspace=10 vspace=10></a>"; ?>
			<?php echo "$thetext"; ?>
                 </td></tr></table></td>
                 <td background="themes/$theme/images/wr.gif"></td>
		</tr>
		<tr bgcolor="#e6e6fa"><td colspan="3"></td></tr>
		</table>
<?php	} else {
		if($informant != "") $boxstuff = "<a href=\"modules.php?name=Your_Account&amp;op=userinfo&uname=$informant\">$informant</a> ";
		else $boxstuff = "$anonymous ";
		$boxstuff .= "".translate("writes")." <i>\"$thetext\"</i> $notes";
?>		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#DDDDDD"><tr valign="top" bgcolor="#e6e6fa">
			<td><img src="themes/$theme/images/pix.gif" width="4" height="4" alt=""></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="2" cellspacing="0"><tr><td>
			<font class="option" color="#FFFFFF"><B><?php echo"$title"; ?></B></font>
			</td></tr></table>
			</td><td align="right"><img src="themes/$theme/images/pix.gif" width="4" height="4" alt=""></td>
         	</tr></table>
		<table border="0" cellpadding="0" cellspacing="0"><tr bgcolor="#e6e6e6">
			<td background="themes/$theme/images/gl.gif"></td>
			<td width="100%">
				<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr>
				<td><font class="tiny"><?php echo translate("Posted by "); ?> <?php formatAidHeader($aid) ?> <?php echo translate("on"); ?> <?php echo"$datetime $timezone"; ?></font>
				
<?php
if ($admin) {
    echo "&nbsp;&nbsp; $font2 [ <a href=admin.php?op=EditStory&sid=$sid>Editar</a> | <a href=admin.php?op=RemoveStory&sid=$sid>Borrar</a> ]";
}
?>
<br><?php echo "$font1"; ?>
<?php echo "".translate("Contributed by ").""; ?> <?php echo "<a href=\"modules.php?name=Your_Account&amp;op=userinfo&uname=$informant\">$informant</a>"; ?>
				</td>
				</tr></table>
			</td>
			<td background="themes/$theme/images/gr.gif"></td>
		</tr>
		<tr bgcolor="#006666"><td colspan="3"></td></tr>
		<tr bgcolor="#ffffff">
			<td background="themes/$theme/images/wl.gif"></td>
			<td width="100%"><table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td>
		<?php echo "<a href=modules.php?name=Search&amp;query=&topic=$topic&author=><img src=images/topics/$topicimage border=0 Alt=\"$topictext\" align=right hspace=10 vspace=10></a>"; ?>
			<?php echo "$thetext"; ?>
                 </td></tr></table></td>
                 <td background="themes/$theme/images/wr.gif"></td>
		</tr>
		<tr bgcolor="#e6e6fa"><td colspan="3"></td></tr>
		</table>
<?php	}
}

function themesidebox($title, $content) {
    echo "<div class=\"menubar\">$content</div><br>";
}

function themeindexboxlien($title) {
return '';
}

function themeboxgauche() {
return'	';
}

function themeboxdroite() {
return '';
}
/*
function themeboxlien($title) {
	return '<div class="menubar2">'.$title.'</div>';
}
*/
 
function themeMenuOpen() {
global $themeMenuCompteur;
	$themeMenuCompteur = 0;
	return '<table align="center"><tr>';
}
function themeMenuClose() {
	return '</tr></table>';
}
function themeMenuLien($title) {
global $themeMenuCompteur;
	$themeMenuCompteur++;
	if ($themeMenuCompteur>7){
		$tmp = '</tr><tr><td class="menubar2">'.$title.'</td>';
		$themeMenuCompteur = 0;
	}else{
		$tmp = '<td class="menubar2">'.$title.'</td>';
	}
	return $tmp;
}

function themecenterbox($title, $content) {
	if ($title!="Parcours Hypertexte")    echo "<div id=\"titre\">$title</div><br>";
    	echo "<div class=\"menubar\">$content".boutonSelfCommande("&theme=Print","<img src=\"images/imprimer.gif\" alt=\"Imprimer cette page\">","_blank","")."</div><br>";
}

?>
