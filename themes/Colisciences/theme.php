<?php

$bgcolor2 = "#FFFFFF";
$bgcolor1 = "#fffacd";
$bgcolor3 = "#e6e6e6";
$bgcolor4 = "#660000";

$textcolor1 = "#660000";
$textcolor2 = "#000000";

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
    global $slogan, $sitename, $userinfo, $user, $cookie;

echo "\n<body>
<!-- Message pour les browsers ne supportant pas CSS  -->
<p class=\"hide\">This page uses CSS style sheets, if you see this
message it may not display properly because your browser does not
support CSS.<br>Please consider upgrading your browser.</p>

<!-- Début de l'en-tête  -->
<table border=\"0\" width=\"100%\" id=\"titre\">
<tr>
    <td valign=\"top\" align=\"center\" colspan=\"3\">
	<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0\" WIDTH=800 HEIGHT=75>
    <PARAM NAME=movie VALUE=\"themes/Colisciences/sitename.swf?text=$sitename\"> 
    <PARAM NAME=quality VALUE=high>
    <PARAM NAME=bgcolor VALUE=$bgcolor4>
    <PARAM NAME=menu VALUE=false>
    <EMBED src=\"themes/Colisciences/sitename.swf?text=$sitename\" quality=\"high\" bgcolor=$bgcolor4  WIDTH=600 HEIGHT=75 TYPE=\"application/x-shockwave-flash\" PLUGINSPAGE=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" menu=\"false\">
    </OBJECT>
	</td>
</tr>
<tr>
    <td width=\"33%\" align=\"center\"><a href=\"http://www.cnrs.fr\" ><img src=\"images/logo-cnrs.gif\" align=\"right\" alt=\"CNRS\"></a></td>
    <td width=\"33%\" align=\"center\"><a href=\"http://www.lcp.cnrs.fr\"><img src=\"images/logo-lettres.gif\" align=\"right\" alt=\"LCP\"></a></td>
    <td width=\"33%\" align=\"center\"><a href=\"http://www.unice.fr/MediaTec/\"><img src=\"images/logo-mediatec.gif\" align=\"right\" alt=\"MédiaTeC\"></a></td>
</tr>
</table>\n"
	."<table cellpadding=\"0\" background=\"themes/Colisciences/images/tophighlight.gif\" cellspacing=\"0\" width=\"100%\" border=\"0\" align=\"center\" >\n"
 	."<tr valign=\"middle\" >\n"
	."<td height=\"20\"><font class=\"content\" color=\"#363636\">\n";

    cookiedecode($user);
    $username =$cookie[1];
		
    if ($username == ""||$username=="Anonymous") {
	echo "&nbsp;&nbsp;Bonjour, vous pouvez vous <font ><a href=\"modules.php?name=Your_Account\">connecter</a></font>.\n";
    } else {
	echo "&nbsp;&nbsp;Bonjour $username!";
    }
    echo "</font></td>\n"
        ."<td align=\"right\" width=\"140\" ><font class=\"content\">\n"
        ."<script type=\"text/javascript\">\n\n"
        ."<!--   // Array ofmonth Names\n"
        ."var monthNames = new Array( \"Janvier\",\"Février\",\"Mars\",\"Avril\",\"Mai\",\"Juin\",\"Juillet\",\"Août\",\"Septembre\",\"Octobre\",\"Novembre\",\"Décembre\");\n"
        ."var now = new Date();\n"
        ."thisYear = now.getYear();\n"
        ."if(thisYear < 1900) {thisYear += 1900}; // corrections if Y2K display problem\n"
        ."document.write(now.getDate() + \" \" + monthNames[now.getMonth()] + \" \" + thisYear);\n"
        ."// -->\n\n"
        ."</script></font>&nbsp;</td>\n"
        ."</tr>\n"
        ."<tr>\n"
        ."</table>\n
<table width=99% align=center cellpadding=0 cellspacing=0 border=0><tr><td valign=top rowspan=5>
<td valign=top width=100%>";
}

/**
 * themeheader()
 * 
 * @return 
 */
function themeheader() {
    global $slogan, $sitename, $userinfo, $user, $cookie;

echo "\n<body>
<!-- Message pour les browsers ne supportant pas CSS  -->
<p class=\"hide\">This page uses CSS style sheets, if you see this
message it may not display properly because your browser does not
support CSS.<br>Please consider upgrading your browser.</p>

<!-- Début de l'en-tête  -->\n"
	."<table cellpadding=\"0\" background=\"themes/Colisciences/images/tophighlight.gif\" cellspacing=\"0\" width=\"100%\" border=\"0\" align=\"center\" >\n"
 	."<tr valign=\"middle\" >\n"
	."<td height=\"20\"><font class=\"content\" color=\"#363636\">\n";

    cookiedecode($user);
    $username =$cookie[1];
		
    if ($username == ""||$username=="Anonymous") {
	echo "&nbsp;&nbsp;Bonjour, vous pouvez vous <font ><a href=\"modules.php?name=Your_Account\">connecter</a></font>.\n";
    } else {
	echo "&nbsp;&nbsp;Bonjour $username!";
    }
    echo "</font></td>\n"
        ."<td align=\"right\" width=\"140\" ><font class=\"content\">\n"
        ."<script type=\"text/javascript\">\n\n"
        ."<!--   // Array ofmonth Names\n"
        ."var monthNames = new Array( \"Janvier\",\"Février\",\"Mars\",\"Avril\",\"Mai\",\"Juin\",\"Juillet\",\"Août\",\"Septembre\",\"Octobre\",\"Novembre\",\"Décembre\");\n"
        ."var now = new Date();\n"
        ."thisYear = now.getYear();\n"
        ."if(thisYear < 1900) {thisYear += 1900}; // corrections if Y2K display problem\n"
        ."document.write(now.getDate() + \" \" + monthNames[now.getMonth()] + \" \" + thisYear);\n"
        ."// -->\n\n"
        ."</script></font>&nbsp;</td>\n"
        ."</tr>\n"
        ."<tr>\n"
        ."</table>\n";

//<table width=100% align=center cellpadding=0 cellspacing=0 border=\"1\"><tr><td valign=top rowspan=5>
//<td valign=top width=100%>";

}


function themefooter() {
	global $index;
	
	echo "<td>&nbsp;</td><td valign=\"top\">";
  blocks(left);
	if ($index == 1) {
    echo "<td>&nbsp;</td><td valign=\"top\">";
    blocks(right);
    echo "</td>";
	}
	echo "</tr></table></td></tr></table>
	<!-- Footer -->
	<table cellpadding=\"0\" background=\"themes/Colisciences/images/tophighlight.gif\" cellspacing=\"0\" width=\"100%\" border=\"0\" align=\"center\" >\n
	<tr valign=\"middle\" >\n
	<td height=\"20\"><font class=\"content\" color=\"#363636\">\n
	&nbsp;Dernière modification: " . date("D j F Y g:i a", getlastmod()) . "</td></tr></table>";

footmsg();
}

function themeindex ($aid, $informant, $datetime, $title, $counter, $topic, $thetext, $notes, $morelink, $topicname, $topicimage, $topictext) {
	global $anonymous;
	if ("$aid" == "$informant") { ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#DDDDDD"><tr valign="top" bgcolor="#e6e6fa">
			<td><img src="themes/Colisciences/images/pix.gif" width="4" height="4" alt=""></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="2" cellspacing="0"><tr><td>
			<font class="option" color="#FFFFFF"><B><?php echo"$title"; ?></B></font>
			</td></tr></table>
			</td><td align="right"><img src="themes/Colisciences/images/pix.gif" width="4" height="4" alt=""></td>
         	</tr></table>
		<table border="0" cellpadding="0" cellspacing="0"><tr bgcolor="#e6e6e6">
			<td background="themes/Colisciences/images/gl.gif"></td>
			<td width="100%">
				<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr>
				<td><font class="tiny"><?php echo translate("Posted by "); ?> <?php formatAidHeader($aid) ?> <?php echo translate("on"); ?> <?php echo"$datetime $timezone"; ?> (<?php echo $counter; ?> <?php echo translate("reads"); ?>)</font></td>
				</tr></table>
			</td>
			<td background="themes/Colisciences/images/gr.gif"></td>
		</tr>
		<tr bgcolor="#006666"><td colspan="3"></td></tr>
		<tr bgcolor="#ffffff">
			<td background="themes/Colisciences/images/wl.gif"></td>
			<td width="100%"><table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td>
			
			<a href="modules.php?name=Search&amp;query=&topic=<?php echo"$topic"; ?>&author="><img src=<?php echo"images/topics/$topicimage"; ?> border=0 Alt=<?php echo"\"$topictext\""; ?> align=right hspace=10 vspace=10></a>
			
			<?php echo "$thetext"; ?>
                 </td></tr></table></td>
                 <td background="themes/Colisciences/images/wr.gif"></td>
		</tr>
		<tr bgcolor="#e6e6fa"><td colspan="3"></td></tr>
		</table><table border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor="#ffffff">
			<td background="themes/Colisciences/images/wl_cccccc.gif"></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="5" cellspacing="0"> 
			<tr><td bgcolor="#cccccc"><font class="content"><?php echo"$morelink"; ?></font></td></tr></table>
			</td>
			<td background="themes/Colisciences/images/wr_cccccc.gif"></td>
		</tr>
		<tr bgcolor="#e6e6fa"><td colspan="3"></td></tr>
		</table><BR><BR>
<?php	} else {
		if($informant != "") $boxstuff = "<a href=\"modules.php?name=Your_Account&amp;op=userinfo&uname=$informant\">$informant</a> ";
		else $boxstuff = "$anonymous ";
		$boxstuff .= "".translate("writes")." <i>\"$thetext\"</i> $notes";
?>		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#DDDDDD"><tr valign="top" bgcolor="#e6e6fa">
			<td><img src="themes/Colisciences/images/pix.gif" width="4" height="4" alt=""></td>
			<td width="100%"><table width="100%" border="0" cellpadding="2" cellspacing="0"><tr><td>
			<font class="option" color="#FFFFFF"><B><?php echo"$title"; ?></B></font>
			</td></tr></table></td>
                 	<td align="right"><img src="themes/Colisciences/images/pix.gif" width="4" height="4" alt=""></td>
         	</tr></table>
		<table border="0" cellpadding="0" cellspacing="0"><tr bgcolor="#e6e6e6">
			<td background="themes/Colisciences/images/gl.gif"></td>
			<td width="100%">
				<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr>
				<td><font class="tiny"><?php echo translate("Posted by "); ?> <?php formatAidHeader($aid) ?> <?php echo translate("on"); ?> <?php echo"$datetime $timezone"; ?> (<?php echo $counter; ?> <?php echo translate("reads"); ?>)</font></td>
				</tr></table>
			</td>
			<td background="themes/Colisciences/images/gr.gif"></td>
		</tr>
		<tr bgcolor="#006666"><td colspan="3"></td></tr>
		<tr bgcolor="#ffffff">
			<td background="themes/Colisciences/images/wl.gif"></td>
			<td width="100%"><table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td>
			<a href="modules.php?name=Search&amp;query=&topic=<?php echo"$topic"; ?>&author="><img src=<?php echo"images/topics/$topicimage"; ?> border=0 Alt=<?php echo"\"$topictext\""; ?> align=right hspace=10 vspace=10></a>
			<?php echo "$boxstuff"; ?>
                 </td></tr></table></td>
                 <td background="themes/Colisciences/images/wr.gif"></td>
		</tr>
		<tr bgcolor="#e6e6fa"><td colspan="3"></td></tr>
		</table><table border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor="#ffffff">
			<td background="themes/Colisciences/images/wl_cccccc.gif"></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td bgcolor="#cccccc"><font class="content"><?php echo"$morelink"; ?></font></td></tr></table>
			</td>
			<td background="themes/Colisciences/images/wr_cccccc.gif"></td>
		</tr>
		<tr bgcolor="#e6e6fa"><td colspan="3"></td></tr>
		</table><BR><BR>
<?php	}
}

function themearticle ($aid, $informant, $datetime, $title, $thetext, $topic, $topicname, $topicimage, $topictext) {
	global $admin, $sid;
	if ("$aid" == "$informant") { ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#DDDDDD"><tr valign="top" bgcolor="#e6e6fa">
			<td><img src="themes/Colisciences/images/pix.gif" width="4" height="4" alt=""></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="2" cellspacing="0"><tr><td>
			<font class="option" color="#ffffff"><B><?php echo"$title"; ?></B></font>
			</td></tr></table>
			</td><td align="right"><img src="themes/Colisciences/images/pix.gif" width="4" height="4" alt=""></td>
         	</tr></table>
		<table border="0" cellpadding="0" cellspacing="0"><tr bgcolor="#e6e6e6">
			<td background="themes/Colisciences/images/gl.gif"></td>
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
			<td background="themes/Colisciences/images/gr.gif"></td>
		</tr>
		<tr bgcolor="#006666"><td colspan="3"></td></tr>
		<tr bgcolor="#ffffff">
			<td background="themes/Colisciences/images/wl.gif"></td>
			<td width="100%"><table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td>
		<?php echo "<a href=modules.php?name=Search&amp;query=&topic=$topic&author=><img src=images/topics/$topicimage border=0 Alt=\"$topictext\" align=right hspace=10 vspace=10></a>"; ?>
			<?php echo "$thetext"; ?>
                 </td></tr></table></td>
                 <td background="themes/Colisciences/images/wr.gif"></td>
		</tr>
		<tr bgcolor="#e6e6fa"><td colspan="3"></td></tr>
		</table>
<?php	} else {
		if($informant != "") $boxstuff = "<a href=\"modules.php?name=Your_Account&amp;op=userinfo&uname=$informant\">$informant</a> ";
		else $boxstuff = "$anonymous ";
		$boxstuff .= "".translate("writes")." <i>\"$thetext\"</i> $notes";
?>		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#DDDDDD"><tr valign="top" bgcolor="#e6e6fa">
			<td><img src="themes/Colisciences/images/pix.gif" width="4" height="4" alt=""></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="2" cellspacing="0"><tr><td>
			<font class="option" color="#FFFFFF"><B><?php echo"$title"; ?></B></font>
			</td></tr></table>
			</td><td align="right"><img src="themes/Colisciences/images/pix.gif" width="4" height="4" alt=""></td>
         	</tr></table>
		<table border="0" cellpadding="0" cellspacing="0"><tr bgcolor="#e6e6e6">
			<td background="themes/Colisciences/images/gl.gif"></td>
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
			<td background="themes/Colisciences/images/gr.gif"></td>
		</tr>
		<tr bgcolor="#006666"><td colspan="3"></td></tr>
		<tr bgcolor="#ffffff">
			<td background="themes/Colisciences/images/wl.gif"></td>
			<td width="100%"><table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td>
		<?php echo "<a href=modules.php?name=Search&amp;query=&topic=$topic&author=><img src=images/topics/$topicimage border=0 Alt=\"$topictext\" align=right hspace=10 vspace=10></a>"; ?>
			<?php echo "$thetext"; ?>
                 </td></tr></table></td>
                 <td background="themes/Colisciences/images/wr.gif"></td>
		</tr>
		<tr bgcolor="#e6e6fa"><td colspan="3"></td></tr>
		</table>
<?php	}
}

function themesidebox($title, $content) { 
?>	
    <table width="150" cellpadding="0" cellspacing="0">
	<tr valign="top" bgcolor="#e6e6fa">
		<td bgcolor="#dddddd"></td>
		<td></td>
		<td><font class="tiny" color="#555555"><B><?php echo"$title"; ?></B></font></td>
		<td align="right"></td>
		<td bgcolor="#dddddd" align="right"></td>
	</tr>
	</table>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr bgcolor="#e6e6fa"><td colspan="3"></td></tr>
	<tr bgcolor="#ffffff">
		<td background="themes/Colisciences/images/sl.gif"></td>
		<td width="100%">
		<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td><?php echo"$font2"; ?><?php echo"$content"; ?>
		</td></tr></table></td>
		<td background="themes/Colisciences/images/sr.gif" align="right"></td>
	</tr>
	<tr bgcolor="#e6e6fa"><td colspan="3"></td></tr>
	</table><br><br>
<?php
}

function themeindexboxopen($span) {

return '
<table width="180" border="0" cellspacing="0" cellpadding="0" >
  <tr> 
    <td width="15" height="15" background="themes/Colisciences/images/cadre/coinsupg.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
    <td height="15" colspan="'.$span.'" background="themes/Colisciences/images/cadre/sup.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
    <td width="15" height="15" background="themes/Colisciences/images/cadre/coinsupd.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
  </tr>';
}

function themeindexboxlien($title) {

return '
  <tr> 
    <td width="15" rowspan="4" background="themes/Colisciences/images/cadre/g.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
    <td height="15" width="15" background="themes/Colisciences/images/cadre/carrebsupg.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
    <td height="15" background="themes/Colisciences/images/cadre/carrebsup.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
    <td height="15" width="15" background="themes/Colisciences/images/cadre/carrebsupd.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
    <td width="15" rowspan="4" background="themes/Colisciences/images/cadre/d.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
  </tr>
  <tr> 
    <td background="themes/Colisciences/images/cadre/carrebg.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
    <td background="themes/Colisciences/images/cadre/carrebfond.gif" align="center" width="80%">'.$title.'</td>
    <td width="15" background="themes/Colisciences/images/cadre/carrebd.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
  </tr>
    <tr> 
    <td height="15" width="15" background="themes/Colisciences/images/cadre/carrebinfg.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
    <td height="15" background="themes/Colisciences/images/cadre/carrebinf.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
    <td height="15" width="15" background="themes/Colisciences/images/cadre/carrebinfd.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
  </tr>
    <tr> 
      <td colspan="3" background="themes/Colisciences/images/cadre/fond.gif"  align="left"  width="180" >
      <BR>
      </td>
  </tr>';
}

function themeboxgauche() {

return '
  <tr> 
    <td width="15" background="themes/Colisciences/images/cadre/g.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>';
}

function themeboxdroite() {

return '
    <td width="15" background="themes/Colisciences/images/cadre/d.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
  </tr>';
}

function themeboxlien($title) {

return '
<td background="themes/Colisciences/images/cadre/fond.gif"  align="left"  width="180" >
<table width="150" border="0" cellspacing="0" cellpadding="0" >
  <tr> 
    <td height="15" width="15" background="themes/Colisciences/images/cadre/carrebsupg.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
    <td height="15" background="themes/Colisciences/images/cadre/carrebsup.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
    <td height="15" width="15" background="themes/Colisciences/images/cadre/carrebsupd.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
  </tr>
  <tr> 
    <td background="themes/Colisciences/images/cadre/carrebg.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
    <td background="themes/Colisciences/images/cadre/carrebfond.gif" align="center" width="80%">'.$title.'</td>
    <td width="15" background="themes/Colisciences/images/cadre/carrebd.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
  </tr>
    <tr> 
    <td height="15" width="15" background="themes/Colisciences/images/cadre/carrebinfg.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
    <td height="15" background="themes/Colisciences/images/cadre/carrebinf.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
    <td height="15" width="15" background="themes/Colisciences/images/cadre/carrebinfd.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
  </tr>
</table>
';
}

function themeindexboxclose($content,$span) {

return '
  <tr> 
    <td width="15" height="15" background="themes/Colisciences/images/cadre/coininfg.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
    <td height="15" colspan="'.$span.'" background="themes/Colisciences/images/cadre/inf.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
    <td width="15" height="15" background="themes/Colisciences/images/cadre/coininfd.gif"><img src="themes/Colisciences/images/space15_15.gif" width="15" height="15"></td>
  </tr>
</table>';
}
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
