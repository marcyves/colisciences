<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi (fbc@mandrakesoft.com)         */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }
$result = sql_query("select radmincontent, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
list($radmincontent, $radminsuper) = sql_fetch_row($result, $dbi);
if (($radmincontent==1) OR ($radminsuper==1)) {


/*********************************************************/
/* Sections Manager Functions                            */
/*********************************************************/

function accueil() {
    global $prefix, $dbi, $language, $multilingual, $bgcolor2;

include_once("header.php");

GraphicAdmin();
title(""._HOMEMANAGER."");

// Les paratextes de la page d'ACCUEIL
    OpenTable();
    echo "<table border=\"0\" width=\"100%\"><tr>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\" colspan=\"4\"><b>La page d'accueil</b></td></tr>"
	."<td bgcolor=\"$bgcolor2\"><b>"._TITLE."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._CURRENTSTATUS."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._CATEGORY."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._FUNCTIONS."</b></td></tr>";
    $result = sql_query("select p.*, c.title from ".$prefix."_pages p, ".$prefix."_pages_categories c where description='accueil' and p.cid=c.cid order by pid", $dbi);
    while($mypages = sql_fetch_array($result, $dbi)) {
	if ($mypages[cid] == "0" OR $mypages[cid] == "") {
	    $cat_title = _NONE;
	} else {
		$cat_title = $mypages[title];
	}
	if ($mypages[active] == 1) {
	    $status = _ACTIVE;
	    $status_chng = _DEACTIVATE;
	    $active = 1;
	} else {
	    $status = "<i>"._INACTIVE."</i>";
	    $status_chng = _ACTIVATE;
	    $active = 0;
	}
	echo "<tr><td><a href=\"modules.php?name=accueil&pa=showpage&pid=$mypages[pid]\">$mypages[2]</a></td><td align=\"center\">$status</td><td align=\"center\">$cat_title</td><td align=\"center\">[ <a href=\"admin.php?op=accueil_edit&pid=$mypages[pid]\">"._EDIT."</a> | <a href=\"admin.php?op=accueil_change_status&pid=$mypages[pid]&active=$active\">$status_chng</a> | <a href=\"admin.php?op=accueil_delete&pid=$mypages[pid]\">"._DELETE."</a> ]</td></tr>";
    }
    echo "</table>";
    CloseTable();
    echo "<br>";
//Ajouter un menu sur la page d'accueil
    OpenTable();
    echo "<center><b>"._ADDCATEGORY."</b></center><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._TITLE.":</b> <input type=\"text\" name=\"cat_title\" size=\"50\">"
	."<input type=\"hidden\" name=\"description\" value=\"accueil\">"
	."<input type=\"hidden\" name=\"op\" value=\"add_category\"> "
	."<input type=\"submit\" value=\""._ADD."\">"
	."</form>";
    CloseTable();
    echo "<br>";

// L'annonce de la page de couverture
    OpenTable();
    echo "<table border=\"0\" width=\"100%\"><tr>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\" colspan=\"4\"><b>Le texte d'annonce de la page d'accueil</b></td></tr>"
	."<td bgcolor=\"$bgcolor2\"><b>"._TITLE."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._CURRENTSTATUS."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._CATEGORY."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._FUNCTIONS."</b></td></tr>";
    $result = sql_query("select * from ".$prefix."_pages where title='Annonce' order by pid", $dbi);
    while($mypages = sql_fetch_array($result, $dbi)) {
	if ($mypages[cid] == "0" OR $mypages[cid] == "") {
	    $cat_title = _NONE;
	} else {
		$cat_title = $mypages[title];
	}
	if ($mypages[active] == 1) {
	    $status = _ACTIVE;
	    $status_chng = _DEACTIVATE;
	    $active = 1;
	} else {
	    $status = "<i>"._INACTIVE."</i>";
	    $status_chng = _ACTIVATE;
	    $active = 0;
	}
	echo "<tr><td><a href=\"modules.php?name=accueil&pa=showpage&pid=$mypages[pid]\">$mypages[2]</a></td><td align=\"center\">$status</td><td align=\"center\">$cat_title</td><td align=\"center\">[ <a href=\"admin.php?op=accueil_edit&pid=$mypages[pid]\">"._EDIT."</a> | <a href=\"admin.php?op=accueil_change_status&pid=$mypages[pid]&active=$active\">$status_chng</a> | <a href=\"admin.php?op=accueil_delete&pid=$mypages[pid]\">"._DELETE."</a> ]</td></tr>";
    }
    echo "</table>";
    CloseTable();

// Les autres paratextes
    OpenTable();
    echo "<table border=\"0\" width=\"100%\"><tr>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\" colspan=\"4\"><b>Les autres textes</b></td></tr>"
	."<td bgcolor=\"$bgcolor2\"><b>"._TITLE."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._CURRENTSTATUS."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._CATEGORY."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._FUNCTIONS."</b></td></tr>";
    $result = sql_query("select distinct p.pid, c.cid, p.title, c.title, active from ".$prefix."_pages p, ".$prefix."_pages_categories c where description<>'accueil' and p.cid=c.cid order by pid", $dbi);
    while(list($pid, $cid, $titre, $menu, $active) = sql_fetch_row($result, $dbi)) {
	if ($cid == "0" OR $cid == "") {
	    $cat_title = _NONE;
	} else {
		$cat_title = $menu;
	}
	if ($active == 1) {
	    $status = _ACTIVE;
	    $status_chng = _DEACTIVATE;
	    $active = 1;
	} else {
	    $status = "<i>"._INACTIVE."</i>";
	    $status_chng = _ACTIVATE;
	    $active = 0;
	}
	echo "<tr><td><a href=\"modules.php?name=accueil&pa=showpage&pid=$pid\">$titre</a></td><td align=\"center\">$status</td><td align=\"center\">$cat_title</td><td align=\"center\">[ <a href=\"admin.php?op=accueil_edit&pid=$pid\">"._EDIT."</a> | <a href=\"admin.php?op=accueil_change_status&pid=$pid&active=$active\">$status_chng</a> | <a href=\"admin.php?op=accueil_delete&pid=$pid\">"._DELETE."</a> ]</td></tr>";
    }
    $result = sql_query("select distinct pid, cid, title, active from ".$prefix."_pages where cid=0 and title<>'Annonce' order by pid", $dbi);
    while(list($pid, $cid, $titre, $active) = sql_fetch_row($result, $dbi)) {
	if ($cid == "0" OR $cid == "") {
	    $cat_title = _NONE;
	} else {
		$cat_title = $menu;
	}
	if ($active == 1) {
	    $status = _ACTIVE;
	    $status_chng = _DEACTIVATE;
	    $active = 1;
	} else {
	    $status = "<i>"._INACTIVE."</i>";
	    $status_chng = _ACTIVATE;
	    $active = 0;
	}
	echo "<tr><td><a href=\"modules.php?name=accueil&pa=showpage&pid=$pid\">$titre</a></td><td align=\"center\">$status</td><td align=\"center\">$cat_title</td><td align=\"center\">[ <a href=\"admin.php?op=accueil_edit&pid=$pid\">"._EDIT."</a> | <a href=\"admin.php?op=accueil_change_status&pid=$pid&active=$active\">$status_chng</a> | <a href=\"admin.php?op=accueil_delete&pid=$pid\">"._DELETE."</a> ]</td></tr>";
    }
    echo "</table>";
    CloseTable();

    $rescat = sql_query("select cid, title from ".$prefix."_pages_categories where description='accueil' order by title", $dbi);
    if (sql_num_rows($rescat, $dbi) > 0) {
	echo "<br>";
	OpenTable();
	echo "<center><b>"._EDITCATEGORY."</b></center><br><br>"
	    ."<form action=\"admin.php\" method=\"post\">"
	    ."<b>"._CATEGORY.":</b> "
	    ."<select name=\"cid\">";
	while (list($cid, $cat_title) = sql_fetch_row($rescat, $dbi)) {
	    echo "<option value=\"$cid\">$cat_title</option>";
	}
	echo "</select>&nbsp;&nbsp;"
	    ."<input type=\"hidden\" name=\"op\" value=\"edit_category\">"
	    ."<input type=\"submit\" value=\""._EDIT."\">"
	    ."</form>";
	CloseTable();
    }
    
    echo "<br>";
   	display_form(_ADDANEWPAGE,$pid,$mypages[title],$mypages[cid], $mypages[subtitle], $mypages[page_header], $mypages[text],$mypages[page_footer], $mypages[signature], $mypages[clanguage], $mypages[active]);
    include("footer.php");
}

function add_category($cat_title, $description) {
    global $prefix, $dbi;
    sql_query("insert into ".$prefix."_pages_categories values (NULL, '$cat_title', 'accueil')", $dbi);
    Header("Location: admin.php?op=accueil");
}

function edit_category($cid) {
    global $prefix, $dbi;

include_once("header.php");
include_once("wysiwyg.inc");

GraphicAdmin();
title(""._HOMEMANAGER."");

    OpenTable();
    $result = sql_query("select title, description from ".$prefix."_pages_categories where description='accueil' and cid='$cid'", $dbi);
    list($title, $description) = sql_fetch_row($result, $dbi);
    echo "<center><b>"._EDITCATEGORY."</b></center><br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._TITLE."</b><br>"
	."<input type=\"text\" name=\"cat_title\" value=\"$title\" size=\"50\"><br><br>"
	."<b>"._DESCRIPTION."</b>:<br>"
	."<textarea cols=\"50\" rows=\"10\" name=\"description\">$description</textarea><br><br>"
	."<input type=\"hidden\" name=\"cid\" value=\"$cid\">"
	."<input type=\"hidden\" name=\"op\" value=\"save_category\">"
	."<input type=\"submit\" value=\""._SAVECHANGES."\">&nbsp;&nbsp;"
	."[ <a href=\"admin.php?op=del_accueil_cat&amp;cid=$cid\">"._DELETE."</a> ]"
	."</form>";
    CloseTable();
    include("footer.php");
}

function save_category($cid, $cat_title, $description) {
    global $prefix, $dbi;
    sql_query("update ".$prefix."_pages_categories set title='$cat_title', description='accueil' where cid='$cid'", $dbi);
    Header("Location: admin.php?op=accueil");
}

function del_accueil_cat($cid, $ok=0) {
    global $prefix, $dbi;
    if ($ok==1) {
        sql_query("delete from ".$prefix."_pages_categories where cid='$cid'", $dbi);
	$result = sql_query("select pid from ".$prefix."_pages where cid='$cid'", $dbi);
	while (list($pid) = sql_fetch_row($result, $dbi)) {
	    sql_query("update ".$prefix."_pages set cid='0' where pid='$pid'", $dbi);
	}
        Header("Location: admin.php?op=accueil");
    } else {
include_once("header.php");
include_once("wysiwyg.inc");

GraphicAdmin();
title(""._HOMEMANAGER."");

	$result = sql_query("select title from ".$prefix."_pages_categories where cid='$cid'", $dbi);
	list($title) = sql_fetch_row($result, $dbi);
	OpenTable();
	echo "<center><b>"._DELCATEGORY.": $title</b><br><br>"
	    .""._DELCONTENTCAT."<br><br>"
	    ."[ <a href=\"admin.php?op=accueil\">"._NO."</a> | <a href=\"admin.php?op=del_accueil_cat&amp;cid=$cid&amp;ok=1\">"._YES."</a> ]</center>";
	CloseTable();
        include("footer.php");
    }
}

function accueil_edit($pid) {
    global $prefix, $dbi, $language, $multilingual, $bgcolor2;

	
    $result = sql_query("select * from ".$prefix."_pages WHERE pid='$pid'", $dbi);
    $mypages = sql_fetch_array($result, $dbi);

//$body = ' onload="Start(\''.addslashes($mypages[text]).'\')"';
include_once("header.php");

GraphicAdmin();
title(""._HOMEMANAGER."");

	display_form(_EDITPAGECONTENT,$pid,$mypages[title],$mypages[cid], $mypages[subtitle], $mypages[page_header], $mypages[text],$mypages[page_footer], $mypages[signature], $mypages[clanguage], $mypages[active]);

    include("footer.php");
}

function display_form($caption,$pid,$title,$topic, $subtitle, $page_header, $text,$page_footer, $signature, $clanguage, $active) {
    global $prefix, $dbi, $bgcolor2;

    OpenTable();
	if ($active == 1) {
	    $sel1 = "checked";
	    $sel2 = "";
	} else {
	    $sel1 = "";
	    $sel2 = "checked";
	}
    echo "<center><b>".$caption."</b></center><br><br>"
		."<form name=\"RTEDemo\" action=\"admin.php\" method=\"post\" onsubmit=\"return submitForm();\">"
		."<b>"._TITLE.":</b><br>"
		."<input type=\"text\" name=\"title\" size=\"50\" value=\"$title\"> ";

    $res = sql_query("select cid, title from ".$prefix."_pages_categories", $dbi);
    if (sql_num_rows($res, $dbi) > 0) {
		echo "<b>"._CATEGORY.":</b>&nbsp;&nbsp;"
	    ."<select name=\"cid\">";
		if ($mypages[cid] == 0) {
	    	$sel = "selected";
		} else {
	    	$sel = "";
		}
		echo "<option value=\"0\" $sel>"._NONE."</option>";
		while(list($cid, $cat_title) = sql_fetch_row($res, $dbi)) {
	    	if ($topic == $cid) {
				$sel = "selected";
	    	} else {
				$sel = "";
	    	}
	    	echo "<option value=\"$cid\" $sel>$cat_title</option>";
		}
		echo "</select><br><br>";
    } else {
		echo "<input type=\"hidden\" name=\"cid\" value=\"0\">";
    }
    echo "<b>"._CSUBTITLE.":</b><br>"
	."<input type=\"text\" name=\"subtitle\" size=\"50\" value=\"$subtitle\"><br><br>"
	."<b>"._HEADERTEXT.":</b><br>";
?>
<script language="JavaScript" type="text/javascript">
<!--
function submitForm() {
	//make sure hidden and iframe values are in sync before submitting form
	//to sync only 1 rte, use updateRTE(rte)
	//to sync all rtes, use updateRTEs
	//updateRTE('rte1');
	updateRTEs();
//	alert(document.RTEDemo.rte1.value);
	//change the following line to true to submit form
	return true;
}

<?
$content = RTESafe($page_header);
?>//Usage: initRTE(imagesPath, includesPath, cssFile)
initRTE("images/", "", "");

//Usage: writeRichText(fieldname, html, width, height, buttons)
writeRichText('page_header', '<? echo $content?>', 800, 100, true, false);

//uncomment the following to see a demo of multiple RTEs on one page
//document.writeln('<br><br>');
//writeRichText('rte2', 'read-only text', 450, 100, true, false);
//-->
</script>
<noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>
<?php

	echo "<b>"._PAGETEXT.":</b><br>"
	."<font class=\"tiny\">"._PAGEBREAK."</font>";
?>
<script language="JavaScript" type="text/javascript">
<!--

<?
$content = RTESafe($text);
?>//Usage: initRTE(imagesPath, includesPath, cssFile)
initRTE("images/", "", "");

//Usage: writeRichText(fieldname, html, width, height, buttons)
writeRichText('texte', '<? echo $content?>', 800, 600, true, false);

//uncomment the following to see a demo of multiple RTEs on one page
//document.writeln('<br><br>');
//writeRichText('rte2', 'read-only text', 450, 100, true, false);
//-->
</script>
<noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>
<?php
	echo "<b>"._FOOTERTEXT.":</b><br>";
//	."<textarea name=\"page_footer\" cols=\"60\" rows=\"10\">$page_footer</textarea><br><br>";
?>
<script language="JavaScript" type="text/javascript">
<!--

<?
$content = RTESafe($page_footer);
?>//Usage: initRTE(imagesPath, includesPath, cssFile)
initRTE("images/", "", "");

//Usage: writeRichText(fieldname, html, width, height, buttons)
writeRichText('page_footer', '<? echo $content?>', 800, 100, true, false);

//uncomment the following to see a demo of multiple RTEs on one page
//document.writeln('<br><br>');
//writeRichText('rte2', 'read-only text', 450, 100, true, false);
//-->
</script>
<noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>
<?php



	echo "<b>"._SIGNATURE.":</b><br>"
	."<textarea name=\"signature\" cols=\"60\" rows=\"5\">$signature</textarea><br><br>";

	echo "<input type=\"hidden\" name=\"clanguage\" value=\"$clanguage\">";

    echo "<b>"._ACTIVATEPAGE."</b><br>"
	."<input type=\"radio\" name=\"active\" value=\"1\" $sel1>&nbsp;"._YES."&nbsp&nbsp;<input type=\"radio\" name=\"active\" value=\"0\" $sel2>&nbsp;"._NO."<br><br>"
	."<input type=\"hidden\" name=\"pid\" value=\"$pid\">";

	if ($caption == _ADDANEWPAGE){
		echo "<input type=\"hidden\" name=\"op\" value=\"accueil_save\">";
	} else {
		echo "<input type=\"hidden\" name=\"op\" value=\"accueil_save_edit\">";
	}
   	echo "<input type=\"submit\" value=\""._OK."\" onClick=\"copyValue_texte('myEditor');\">";
	echo "</form>";
    CloseTable();
}

function accueil_save($title, $subtitle, $page_header, $text, $page_footer, $signature, $clanguage, $active, $cid) {
    global $prefix, $dbi;
    sql_query("insert into ".$prefix."_pages values (NULL, '$cid', '$title', '$subtitle', '$active', '$page_header', '$text', '$page_footer', '$signature', now(), '0', '$clanguage')", $dbi);
    Header("Location: admin.php?op=accueil");
}

function accueil_save_edit($pid, $title, $subtitle, $page_header, $text, $page_footer, $signature, $clanguage, $active, $cid) {
    global $prefix, $dbi;

// la ligne suivante ne sert qu'à debugger: elle génère une erreur de header
//	echo "<h3>Le texte a été sauvé</h3>".stripslashes($text)."<p>";
    sql_query("update ".$prefix."_pages set cid='$cid', title='$title', subtitle='$subtitle', active='$active', page_header='$page_header', text='$text', page_footer='$page_footer', signature='$signature', clanguage='$clanguage' where pid='$pid'", $dbi);
    Header("Location: admin.php?op=accueil");
}

function accueil_change_status($pid, $active) {
    global $prefix, $dbi;
    if ($active == 1) {
	$new_active = 0;
    } elseif ($active == 0) {
	$new_active = 1;
    }
    sql_query("update ".$prefix."_pages set active='$new_active' WHERE pid='$pid'", $dbi);
    Header("Location: admin.php?op=accueil");
}

function accueil_delete($pid, $ok=0) {
    global $prefix, $dbi;

    if ($ok==1) {
        sql_query("delete from ".$prefix."_pages where pid='$pid'", $dbi);
        Header("Location: admin.php?op=accueil");
    } else {
include_once("header.php");
include_once("wysiwyg.inc");

GraphicAdmin();
title(""._HOMEMANAGER."");

	$result = sql_query("select title from ".$prefix."_pages where pid='$pid'", $dbi);
	list($title) = sql_fetch_row($result, $dbi);
	OpenTable();
	echo "<center><b>"._DELCONTENT.": $title</b><br><br>"
	    .""._DELCONTWARNING." $title?<br><br>"
	    ."[ <a href=\"admin.php?op=accueil\">"._NO."</a> | <a href=\"admin.php?op=accueil_delete&amp;pid=$pid&amp;ok=1\">"._YES."</a> ]</center>";
	CloseTable();
        include("footer.php");
    }
}

function submitStory($name, $address, $subject, $story, $storyext, $topic, $alanguage, $posttype) {
    global $user, $EditedMessage, $cookie, $anonymous, $notify, $notify_email, $notify_subject, $notify_message, $notify_from, $prefix, $dbi;

    if (is_user($user)) {
    	cookiedecode($user);
	$uid = $cookie[0];
	$name = $cookie[1];
    } else {
    	$uid = 1;
	$name = "$anonymous";
    }
    $subject = ereg_replace("\"", "''", $subject);
    $subject = FixQuotes(filter_text($subject, "nohtml"));
    if($posttype=="exttrans") {
    	$story = FixQuotes(nl2br(htmlspecialchars(check_words($story))));
	$storyext = FixQuotes(nl2br(htmlspecialchars(check_words($storyext))));
    } elseif($posttype=="plaintext") {
    	$story = FixQuotes(nl2br(filter_text($story)));
	$storyext = FixQuotes(nl2br(filter_text($storyext)));
    } else {
	$story = FixQuotes(filter_text($story));
	$storyext = FixQuotes(filter_text($storyext));
    }
    $result = sql_query("insert into $prefix"._queue." values (NULL, '$uid', '$name', '$subject', '$story', '$storyext', now(), '$topic', '$alanguage')", $dbi);
    if(!$result) {
    	echo ""._ERROR."<br>";
	exit();
    }
    if($notify) {
	$notify_message = "$notify_message\n\n\n========================================================\n$subject\n\n\n$story\n\n$storyext\n\n$name";
    	mail($notify_email, $notify_subject, $notify_message, "From: $notify_from\nX-Mailer: PHP/" . phpversion());
    }
	
    OpenTable();
    $result = sql_query("select * from $prefix"._queue."", $dbi);
    $waiting = sql_num_rows($result, $dbi);
    echo "<center><font class=\"title\">"._SUBSENT."</font><br><br>"
	."<font class=\"accueil\"><b>"._THANKSSUB."</b><br><br>"
	.""._SUBTEXT.""
	."<br>"._WEHAVESUB." $waiting "._WAITING."";
    CloseTable();
    include ('footer.php');
}

//echo "op accueil $op<p>";
//print_r($HTTP_POST_VARS );
//print_r($HTTP_GET_VARS );
//echo"<p>	PreviewStory($pid, $title, $subtitle, $page_header, $texte, $page_footer, $signature, $clanguage, $active, $cid);";

switch ($op) {
    case "accueil":
	    accueil();
    break;
    case "accueil_edit":
	    accueil_edit($pid);
    break;
    case "accueil_delete":
	    accueil_delete($pid, $ok);
    break;
    case "accueil_review":
	    accueil_review($title, $subtitle, $page_header, $text, $page_footer, $signature, $clanguage, $active);
    break;
    case "accueil_save":
	    accueil_save($title, $subtitle, $page_header, $texte, $page_footer, $signature, $clanguage, $active, $cid);
    break;
    case "accueil_save_edit":
	    accueil_save_edit($pid, $title, $subtitle, $page_header, $texte, $page_footer, $signature, $clanguage, $active, $cid);
    break;
    case "accueil_change_status":
	    accueil_change_status($pid, $active);
    break;
    case "add_category":
	    add_category($cat_title, $description);
    break;
    case "edit_category":
	    edit_category($cid);
    break;
    case "save_category":
	    save_category($cid, $cat_title, $description);
    break;
    case "del_accueil_cat":
	    del_accueil_cat($cid, $ok);
    break;
}

} else {
    echo "Access Denied";
}

?>