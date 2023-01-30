<?php

function OpenTable() {}
function CloseTable() {}
function OpenTable2() {}
function CloseTable2() {}

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
<!-- Début de l'en-tête  -->
<table border=\"0\" width=\"100%\">
<tr>
    <td valign=\"top\" align=\"center\" colspan=\"3\">Cette page a été imprimée sur CoLiSciences</td>
</tr>
<tr>
    <td width=\"33%\" align=\"center\"><a href=\"http://www.cnrs.fr\" ><img src=\"images/logo-cnrs.gif\" align=\"right\" alt=\"CNRS\"></a></td>
    <td width=\"33%\" align=\"center\"><a href=\"http://www.lcp.cnrs.fr\"><img src=\"images/logo-lettres.gif\" align=\"right\" alt=\"LCP\"></a></td>
    <td width=\"33%\" align=\"center\"><a href=\"http://www.unice.fr/MediaTec/\"><img src=\"images/logo-mediatec.gif\" align=\"right\" alt=\"M�diaTeC\"></a></td>
</tr>
</table>\n"
	."<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\" >\n"
 	."<tr valign=\"middle\" >\n"
	."<td height=\"20\"><font class=\"content\" ></font></td>\n"
        ."<td align=\"right\" width=\"140\" ><font class=\"content\">\n"
        ."<script type=\"text/javascript\">\n\n"
        ."<!--   // Array ofmonth Names\n"
        ."var monthNames = new Array( \"Janvier\",\"F�vrier\",\"Mars\",\"Avril\",\"Mai\",\"Juin\",\"Juillet\",\"Ao�t\",\"Septembre\",\"Octobre\",\"Novembre\",\"D�cembre\");\n"
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

<!-- D�but de l'en-t�te  -->
<table border=\"0\" width=\"100%\">
<tr>
    <td valign=\"top\" align=\"center\" colspan=\"3\">Cette page a été imprimée sur CoLiSciences</td>
</tr>
<tr>
    <td width=\"33%\" align=\"center\"><a href=\"http://www.cnrs.fr\" ><img src=\"images/logo-cnrs.gif\" align=\"right\" alt=\"CNRS\"></a></td>
    <td width=\"33%\" align=\"center\"><a href=\"http://www.lcp.cnrs.fr\"><img src=\"images/logo-lettres.gif\" align=\"right\" alt=\"LCP\"></a></td>
    <td width=\"33%\" align=\"center\"><a href=\"http://www.unice.fr/MediaTec/\"><img src=\"images/logo-mediatec.gif\" align=\"right\" alt=\"M�diaTeC\"></a></td>
</tr>
</table>\n"
	."<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\" align=\"center\" >\n"
 	."<tr valign=\"middle\" >\n"
	."<td height=\"20\"></td>\n"
        ."<td align=\"right\" width=\"140\" ><font class=\"content\">\n"
        ."<script type=\"text/javascript\">\n\n"
        ."<!--   // Array ofmonth Names\n"
        ."var monthNames = new Array( \"Janvier\",\"F�vrier\",\"Mars\",\"Avril\",\"Mai\",\"Juin\",\"Juillet\",\"Ao�t\",\"Septembre\",\"Octobre\",\"Novembre\",\"D�cembre\");\n"
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
	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\" align=\"center\" >\n
	<tr valign=\"middle\" >\n
	<td height=\"20\"><font class=\"content\" color=\"#363636\">\n
	&nbsp;Cette page a �t� modidi�e le: " . date("D j F Y g:i a", getlastmod()) . "</td></tr></table>";

footmsg();
}

function themeindex ($aid, $informant, $datetime, $title, $counter, $topic, $thetext, $notes, $morelink, $topicname, $topicimage, $topictext) {
	global $anonymous;
	if ("$aid" == "$informant") { ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr valign="top">
			<td></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="2" cellspacing="0"><tr><td>
			<font class="option"><B><?php echo"$title"; ?></B></font>
			</td></tr></table>
			</td><td align="right"></td>
         	</tr></table>
		<table border="0" cellpadding="0" cellspacing="0"><tr>
			<td></td>
			<td width="100%">
				<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr>
				<td><font class="tiny"><?php echo translate("Posted by "); ?> <?php formatAidHeader($aid) ?> <?php echo translate("on"); ?> <?php echo"$datetime $timezone"; ?> (<?php echo $counter; ?> <?php echo translate("reads"); ?>)</font></td>
				</tr></table>
			</td>
			<td></td>
		</tr>
		<tr><td colspan="3"></td></tr>
		<tr>
			<td></td>
			<td width="100%"><table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td>
			
			<a href="modules.php?name=Search&amp;query=&topic=<?php echo"$topic"; ?>&author="><img src=<?php echo"images/topics/$topicimage"; ?> border=0 Alt=<?php echo"\"$topictext\""; ?> align=right hspace=10 vspace=10></a>
			
			<?php echo "$thetext"; ?>
                 </td></tr></table></td>
                 <td></td>
		</tr>
		<tr><td colspan="3"></td></tr>
		</table><table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="5" cellspacing="0"> 
			<tr><td><font class="content"><?php echo"$morelink"; ?></font></td></tr></table>
			</td>
			<td></td>
		</tr>
		<tr><td colspan="3"></td></tr>
		</table><BR><BR>
<?php	} else {
		if($informant != "") $boxstuff = "<a href=\"modules.php?name=Your_Account&amp;op=userinfo&uname=$informant\">$informant</a> ";
		else $boxstuff = "$anonymous ";
		$boxstuff .= "".translate("writes")." <i>\"$thetext\"</i> $notes";
?>		<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr valign="top" >
			<td></td>
			<td width="100%"><table width="100%" border="0" cellpadding="2" cellspacing="0"><tr><td>
			<font class="option"><B><?php echo"$title"; ?></B></font>
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
		<tr>
			<td background="themes/Colisciences/images/wl.gif"></td>
			<td width="100%"><table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td>
			<a href="modules.php?name=Search&amp;query=&topic=<?php echo"$topic"; ?>&author="><img src=<?php echo"images/topics/$topicimage"; ?> border=0 Alt=<?php echo"\"$topictext\""; ?> align=right hspace=10 vspace=10></a>
			<?php echo "$boxstuff"; ?>
                 </td></tr></table></td>
                 <td background="themes/Colisciences/images/wr.gif"></td>
		</tr>
		<tr ><td colspan="3"></td></tr>
		</table><table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td background="themes/Colisciences/images/wl_cccccc.gif"></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td bgcolor="#cccccc"><font class="content"><?php echo"$morelink"; ?></font></td></tr></table>
			</td>
			<td background="themes/Colisciences/images/wr_cccccc.gif"></td>
		</tr>
		<tr ><td colspan="3"></td></tr>
		</table><BR><BR>
<?php	}
}

function themearticle ($aid, $informant, $datetime, $title, $thetext, $topic, $topicname, $topicimage, $topictext) {
	global $admin, $sid;
	if ("$aid" == "$informant") { ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr valign="top" >
			<td><img src="themes/Colisciences/images/pix.gif" width="4" height="4" alt=""></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="2" cellspacing="0"><tr><td>
			<font class="option"><B><?php echo"$title"; ?></B></font>
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
		<tr>
			<td background="themes/Colisciences/images/wl.gif"></td>
			<td width="100%"><table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td>
		<?php echo "<a href=modules.php?name=Search&amp;query=&topic=$topic&author=><img src=images/topics/$topicimage border=0 Alt=\"$topictext\" align=right hspace=10 vspace=10></a>"; ?>
			<?php echo "$thetext"; ?>
                 </td></tr></table></td>
                 <td background="themes/Colisciences/images/wr.gif"></td>
		</tr>
		<tr ><td colspan="3"></td></tr>
		</table>
<?php	} else {
		if($informant != "") $boxstuff = "<a href=\"modules.php?name=Your_Account&amp;op=userinfo&uname=$informant\">$informant</a> ";
		else $boxstuff = "$anonymous ";
		$boxstuff .= "".translate("writes")." <i>\"$thetext\"</i> $notes";
?>		<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr valign="top" >
			<td><img src="themes/Colisciences/images/pix.gif" width="4" height="4" alt=""></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="2" cellspacing="0"><tr><td>
			<font class="option"><B><?php echo"$title"; ?></B></font>
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
		<tr>
			<td background="themes/Colisciences/images/wl.gif"></td>
			<td width="100%"><table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td>
		<?php echo "<a href=modules.php?name=Search&amp;query=&topic=$topic&author=><img src=images/topics/$topicimage border=0 Alt=\"$topictext\" align=right hspace=10 vspace=10></a>"; ?>
			<?php echo "$thetext"; ?>
                 </td></tr></table></td>
                 <td background="themes/Colisciences/images/wr.gif"></td>
		</tr>
		<tr ><td colspan="3"></td></tr>
		</table>
<?php	}
}

function themesidebox($title, $content) { 
?>	
    <table width="150" cellpadding="0" cellspacing="0">
	<tr valign="top" >
		<td></td>
		<td></td>
		<td><font class="tiny"><B><?php echo"$title"; ?></B></font></td>
		<td align="right"></td>
		<td align="right"></td>
	</tr>
	</table>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr ><td colspan="3"></td></tr>
	<tr>
		<td></td>
		<td width="100%">
		<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td><?php echo"$font2"; ?><?php echo"$content"; ?>
		</td></tr></table></td>
		<td align="right"></td>
	</tr>
	<tr ><td colspan="3"></td></tr>
	</table><br><br>
<?php
}
function themeindexboxopen($span) {}
function themeindexboxlien($title) {}
function themeboxgauche() {}
function themeboxdroite() {}
function themeboxlien($title) {}
function themeindexboxclose($content,$span) {}
function themecenterbox($title, $content) {}
?>
