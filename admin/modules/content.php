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

$result = mysqli_query($dbi, "select radmincontent, radminsuper from ".$prefix."_authors where aid='$aid'");
list($radmincontent, $radminsuper) = mysqli_fetch_row($result);
if (($radmincontent==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Sections Manager Functions                            */
/*********************************************************/

function content() {
    global $prefix, $dbi, $language, $multilingual, $bgcolor2;
    include("header.php");
    GraphicAdmin();
    title(""._CONTENTMANAGER."");
    OpenTable();
    echo "<table border=\"0\" width=\"100%\"><tr>"
	."<td bgcolor=\"$bgcolor2\"><b>"._TITLE."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._CURRENTSTATUS."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._CATEGORY."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._FUNCTIONS."</b></td></tr>";
    $result = mysqli_query($dbi, "select * from ".$prefix."_pages order by pid");
    while($mypages = mysqli_fetch_array($result)) {
	if ($mypages[cid] == "0" OR $mypages[cid] == "") {
	    $cat_title = _NONE;
	} else {
	    $res = mysqli_query($dbi, "select title from ".$prefix."_pages_categories where cid='$mypages[cid]'");
	    list($cat_title) = mysqli_fetch_row($res);
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
	echo "<tr><td><a href=\"modules.php?name=Content&pa=showpage&pid=$mypages[pid]\">$mypages[title]</a></td><td align=\"center\">$status</td><td align=\"center\">$cat_title</td><td align=\"center\">[ <a href=\"admin.php?op=content_edit&pid=$mypages[pid]\">"._EDIT."</a> | <a href=\"admin.php?op=content_change_status&pid=$mypages[pid]&active=$active\">$status_chng</a> | <a href=\"admin.php?op=content_delete&pid=$mypages[pid]\">"._DELETE."</a> ]</td></tr>";
    }
    echo "</table>";
    CloseTable();
    echo "<br>";

    OpenTable();
    echo "<center><b>"._ADDCATEGORY."</b></center><br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._TITLE.":</b><br><input type=\"text\" name=\"cat_title\" size=\"50\"><br><br>"
	."<b>"._DESCRIPTION.":</b><br><textarea name=\"description\" rows=\"10\" cols=\"50\"></textarea><br><br>"
	."<input type=\"hidden\" name=\"op\" value=\"add_category\">"
	."<input type=\"submit\" value=\""._ADD."\">"
	."</form>";
    CloseTable();

    $rescat = mysqli_query($dbi, "select cid, title from ".$prefix."_pages_categories order by title");
    if (sql_num_rows($rescat) > 0) {
	echo "<br>";
	OpenTable();
	echo "<center><b>"._EDITCATEGORY."</b></center><br><br>"
	    ."<form action=\"admin.php\" method=\"post\">"
	    ."<b>"._CATEGORY.":</b> "
	    ."<select name=\"cid\">";
	while (list($cid, $cat_title) = mysqli_fetch_row($rescat)) {
	    echo "<option value=\"$cid\">$cat_title</option>";
	}
	echo "</select>&nbsp;&nbsp;"
	    ."<input type=\"hidden\" name=\"op\" value=\"edit_category\">"
	    ."<input type=\"submit\" value=\""._EDIT."\">"
	    ."</form>";
	CloseTable();
    }
    
    echo "<br>";
    OpenTable();
    $res = mysqli_query($dbi, "select cid, title from ".$prefix."_pages_categories order by title");
    echo "<center><b>"._ADDANEWPAGE."</b></center><br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._TITLE.":</b><br>"
    ."<input type=\"text\" name=\"title\" size=\"50\"><br><br>";
    if (sql_num_rows($res) > 0) {
	echo "<b>"._CATEGORY.":</b>&nbsp;&nbsp;"
	    ."<select name=\"cid\">"
	    ."<option value=\"0\" selected>"._NONE."</option>";
	while(list($cid, $cat_title) = mysqli_fetch_row($res)) {
	    echo "<option value=\"$cid\">$cat_title</option>";
	}
	echo "</select><br><br>";
    } else {
	echo "<input type=\"hidden\" name=\"cid\" value=\"0\">";
    }
    echo "<b>"._CSUBTITLE.":</b><br>"
	."<input type=\"text\" name=\"subtitle\" size=\"50\"><br><br>";
	
	echo "<b>"._HEADERTEXT.":</b><br>"
		."<textarea name=\"page_header\" cols=\"60\" rows=\"10\"></textarea><br><br>"
		."<b>"._PAGETEXT.":</b><br>"
		."<font class=\"tiny\">"._PAGEBREAK."</font><br>"
	    ."<textarea name=\"text\" cols=\"60\" rows=\"40\"></textarea><br><br>"
		."<b>"._FOOTERTEXT.":</b><br>"
		."<textarea name=\"page_footer\" cols=\"60\" rows=\"10\"></textarea><br><br>"
		."<b>"._SIGNATURE.":</b><br>"
		."<textarea name=\"signature\" cols=\"60\" rows=\"5\"></textarea><br><br>";
	
	if ($multilingual == 1) {
			echo "<br><b>"._LANGUAGE.": </b><select name=\"clanguage\">";
			$handle=opendir('language');
		while ($file = readdir($handle)) {
		    	if (preg_match("/^lang\-(.+)\.php/", $file, $matches)) {
	    	    	$langFound = $matches[1];
					$languageslist .= "$langFound ";
		    	}
		}
			closedir($handle);
			$languageslist = explode(" ", $languageslist);
			sort($languageslist);
			for ($i=0; $i < sizeof($languageslist); $i++) {
		    	if($languageslist[$i]!="") {
	    	    	echo "<option value=\"$languageslist[$i]\" ";
	        		if($languageslist[$i]==$language) echo "selected";
					echo ">".ucfirst($languageslist[$i])."</option>\n";
	    		}
			}
			echo "</select><br><br>";
    } else {
			echo "<input type=\"hidden\" name=\"clanguage\" value=\"$language\">";
    }
	
    echo "<b>"._ACTIVATEPAGE."</b><br>"
		."<input type=\"radio\" name=\"active\" value=\"1\" checked>&nbsp;"._YES."&nbsp&nbsp;<input type=\"radio\" name=\"active\" value=\"0\">&nbsp;"._NO."<br><br>"
		."<input type=\"hidden\" name=\"op\" value=\"content_save\">"
		."<input type=\"submit\" value=\""._SEND."\">"
		."</form>";
	
    CloseTable();
    include("footer.php");
}

function add_category($cat_title, $description) {
    global $prefix, $dbi;
    mysqli_query($dbi, "insert into ".$prefix."_pages_categories values (NULL, '$cat_title', '$description')");
    Header("Location: admin.php?op=content");
}

function edit_category($cid) {
    global $prefix, $dbi;
    include("header.php");
    GraphicAdmin();
    title(""._CONTENTMANAGER."");
    OpenTable();
    $result = mysqli_query($dbi, "select title, description from ".$prefix."_pages_categories where cid='$cid'");
    list($title, $description) = mysqli_fetch_row($result);
    echo "<center><b>"._EDITCATEGORY."</b></center><br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._TITLE."</b><br>"
	."<input type=\"text\" name=\"cat_title\" value=\"$title\" size=\"50\"><br><br>"
	."<b>"._DESCRIPTION."</b>:<br>"
	."<textarea cols=\"50\" rows=\"10\" name=\"description\">$description</textarea><br><br>"
	."<input type=\"hidden\" name=\"cid\" value=\"$cid\">"
	."<input type=\"hidden\" name=\"op\" value=\"save_category\">"
	."<input type=\"submit\" value=\""._SAVECHANGES."\">&nbsp;&nbsp;"
	."[ <a href=\"admin.php?op=del_content_cat&amp;cid=$cid\">"._DELETE."</a> ]"
	."</form>";
    CloseTable();
    include("footer.php");
}

function save_category($cid, $cat_title, $description) {
    global $prefix, $dbi;
    mysqli_query($dbi, "update ".$prefix."_pages_categories set title='$cat_title', description='$description' where cid='$cid'");
    Header("Location: admin.php?op=content");
}

function del_content_cat($cid, $ok=0) {
    global $prefix, $dbi;
    if ($ok==1) {
        mysqli_query($dbi, "delete from ".$prefix."_pages_categories where cid='$cid'");
	$result = mysqli_query($dbi, "select pid from ".$prefix."_pages where cid='$cid'");
	while (list($pid) = mysqli_fetch_row($result)) {
	    mysqli_query($dbi, "update ".$prefix."_pages set cid='0' where pid='$pid'");
	}
        Header("Location: admin.php?op=content");
    } else {
        include("header.php");
        GraphicAdmin();
	title(""._CONTENTMANAGER."");
	$result = mysqli_query($dbi, "select title from ".$prefix."_pages_categories where cid='$cid'");
	list($title) = mysqli_fetch_row($result);
	OpenTable();
	echo "<center><b>"._DELCATEGORY.": $title</b><br><br>"
	    .""._DELCONTENTCAT."<br><br>"
	    ."[ <a href=\"admin.php?op=content\">"._NO."</a> | <a href=\"admin.php?op=del_content_cat&amp;cid=$cid&amp;ok=1\">"._YES."</a> ]</center>";
	CloseTable();
        include("footer.php");
    }
}

function content_edit($pid) {
    global $prefix, $dbi, $language, $multilingual, $bgcolor2;
    include("header.php");
    GraphicAdmin();
    title(""._CONTENTMANAGER."");
    $result = mysqli_query($dbi, "select * from ".$prefix."_pages WHERE pid='$pid'");
    $mypages = mysqli_fetch_array($result);
	if ($mypages[active] == 1) {
	    $sel1 = "checked";
	    $sel2 = "";
	} else {
	    $sel1 = "";
	    $sel2 = "checked";
	}
    OpenTable();
    echo "<center><b>"._EDITPAGECONTENT."</b></center><br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._TITLE.":</b><br>"
	."<input type=\"text\" name=\"title\" size=\"50\" value=\"$mypages[title]\"><br><br>";
    $res = mysqli_query($dbi, "select cid, title from ".$prefix."_pages_categories");
    if (sql_num_rows($res) > 0) {
	echo "<b>"._CATEGORY.":</b>&nbsp;&nbsp;"
	    ."<select name=\"cid\">";
	if ($mypages[cid] == 0) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option value=\"0\" $sel>"._NONE."</option>";
	while(list($cid, $cat_title) = mysqli_fetch_row($res)) {
	    if ($mypages[cid] == $cid) {
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
	."<input type=\"text\" name=\"subtitle\" size=\"50\" value=\"$mypages[subtitle]\"><br><br>"
	."<b>"._HEADERTEXT.":</b><br>"
	."<textarea name=\"page_header\" cols=\"60\" rows=\"10\">$mypages[page_header]</textarea><br><br>"
	."<b>"._PAGETEXT.":</b><br>"
	."<font class=\"tiny\">"._PAGEBREAK."</font>"
	."<textarea name=\"text\" cols=\"60\" rows=\"40\">$mypages[text]</textarea><br><br>"
	."<b>"._FOOTERTEXT.":</b><br>"
	."<textarea name=\"page_footer\" cols=\"60\" rows=\"10\">$mypages[page_footer]</textarea><br><br>"
	."<b>"._SIGNATURE.":</b><br>"
	."<textarea name=\"signature\" cols=\"60\" rows=\"5\">$mypages[signature]</textarea><br><br>";
    if ($multilingual == 1) {
	echo "<br><b>"._LANGUAGE.": </b>"
	    ."<select name=\"clanguage\">";
	$handle=opendir('language');
	while ($file = readdir($handle)) {
	    if (preg_match("/^lang\-(.+)\.php/", $file, $matches)) {
	        $langFound = $matches[1];
		$languageslist .= "$langFound ";
	    }
	}
	closedir($handle);
	$languageslist = explode(" ", $languageslist);
	sort($languageslist);
	for ($i=0; $i < sizeof($languageslist); $i++) {
	    if($languageslist[$i]!="") {
	        echo "<option value=\"$languageslist[$i]\" ";
	        if($languageslist[$i]==$language) echo "selected";
		echo ">".ucfirst($languageslist[$i])."</option>\n";
	    }
	}
	echo "</select><br><br>";
    } else {
	echo "<input type=\"hidden\" name=\"clanguage\" value=\"$mypages[clanguage]\">";
    }
    echo "<b>"._ACTIVATEPAGE."</b><br>"
	."<input type=\"radio\" name=\"active\" value=\"1\" $sel1>&nbsp;"._YES."&nbsp&nbsp;<input type=\"radio\" name=\"active\" value=\"0\" $sel2>&nbsp;"._NO."<br><br>"
	."<input type=\"hidden\" name=\"pid\" value=\"$pid\">"
	."<input type=\"hidden\" name=\"op\" value=\"content_save_edit\">"
	."<input type=\"submit\" value=\""._SAVECHANGES."\">"
	."</form>";
    CloseTable();
    include("footer.php");
}

function content_save($title, $subtitle, $page_header, $text, $page_footer, $signature, $clanguage, $active, $cid) {
    global $prefix, $dbi;
    mysqli_query($dbi, "insert into ".$prefix."_pages values (NULL, '$cid', '$title', '$subtitle', '$active', '$page_header', '$text', '$page_footer', '$signature', now(), '0', '$clanguage')");
    Header("Location: admin.php?op=content");
}

function content_save_edit($pid, $title, $subtitle, $page_header, $text, $page_footer, $signature, $clanguage, $active, $cid) {
    global $prefix, $dbi;
    mysqli_query($dbi, "update ".$prefix."_pages set cid='$cid', title='$title', subtitle='$subtitle', active='$active', page_header='$page_header', text='$text', page_footer='$page_footer', signature='$signature', clanguage='$clanguage' where pid='$pid'");
    Header("Location: admin.php?op=content");
}

function content_change_status($pid, $active) {
    global $prefix, $dbi;
    if ($active == 1) {
	$new_active = 0;
    } elseif ($active == 0) {
	$new_active = 1;
    }
    mysqli_query($dbi, "update ".$prefix."_pages set active='$new_active' WHERE pid='$pid'");
    Header("Location: admin.php?op=content");
}

function content_delete($pid, $ok=0) {
    global $prefix, $dbi;
    if ($ok==1) {
        mysqli_query($dbi, "delete from ".$prefix."_pages where pid='$pid'");
        Header("Location: admin.php?op=content");
    } else {
        include("header.php");
        GraphicAdmin();
	title(""._CONTENTMANAGER."");
	$result = mysqli_query($dbi, "select title from ".$prefix."_pages where pid='$pid'");
	list($title) = mysqli_fetch_row($result);
	OpenTable();
	echo "<center><b>"._DELCONTENT.": $title</b><br><br>"
	    .""._DELCONTWARNING." $title?<br><br>"
	    ."[ <a href=\"admin.php?op=content\">"._NO."</a> | <a href=\"admin.php?op=content_delete&amp;pid=$pid&amp;ok=1\">"._YES."</a> ]</center>";
	CloseTable();
        include("footer.php");
    }
}

/* debut wysiwyg */

function PreviewStory($name, $address, $subject, $story, $storyext, $topic, $alanguage, $posttype) {
    global $user, $cookie, $bgcolor1, $bgcolor2, $anonymous, $prefix, $multilingual, $AllowableHTML, $dbi;
    include ('header.php');
    $subject = stripslashes($subject);
    $story = stripslashes($story);
    $storyext = stripslashes($storyext);
    if ($posttype=="exttrans") {
        $f_story = nl2br(htmlspecialchars($story));
	$f_storyext = nl2br(htmlspecialchars($storyext));
    } elseif ($posttype=="plaintext") {
        $f_story = nl2br($story);
	$f_storyext = nl2br($storyext);
    } else {
	$f_story = $story;
	$f_storyext = $storyext;
    }
    $story2 = "$f_story<br><br>$f_storyext";
    OpenTable();
    echo "<center><font class=\"title\"><b>"._NEWSUBPREVIEW."</b></font>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><i>"._STORYLOOK."</i></center><br><br>";
    echo "<table width=\"70%\" bgcolor=\"$bgcolor2\" cellpadding=\"0\" cellspacing=\"1\" border=\"0\"align=\"center\"><tr><td>"
	."<table width=\"100%\" bgcolor=\"$bgcolor1\" cellpadding=\"8\" cellspacing=\"1\" border=\"0\"><tr><td>";
    if ($topic=="") {
        $topicimage="AllTopics.gif";
        $warning = "<center><blink><b>"._SELECTTOPIC."</b></blink></center>";
    } else {
        $warning = "";
        $result = mysqli_query($dbi, "select topicimage from $prefix"._topics." where topicid='$topic'");
        list($topicimage) = mysqli_fetch_row($result);
    }
    echo "<img src=\"images/topics/$topicimage\" border=\"0\" align=\"right\">";
    themepreview($subject, $story2);
    echo "$warning"
	."</td></tr></table></td></tr></table>"
	."<br><br><center><font class=\"tiny\">"._CHECKSTORY."</font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<p><form action=\"./modules.php?name=wysiwyg_editor\" method=\"post\">"
	."<b>"._YOURNAME.":</b> ";
    if (is_user($user)) {
    	cookiedecode($user);
	echo "<a href=\"user.php\">$cookie[1]</a> <font class=\"content\">[ <a href=\"user.php?op=logout\">"._LOGOUT."</a> ]</font>";
    } else {
	echo "$anonymous";
    }
    echo "<br><br><b>"._SUBTITLE.":</b><br>"
	."<input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"80\" value=\"$subject\">"
	."<br><br><b>"._TOPIC.": </b><select name=\"topic\">";
    $toplist = mysqli_query($dbi, "select topicid, topictext from $prefix"._topics." order by topictext");
    echo "<OPTION VALUE=\"\">"._SELECTTOPIC."</option>\n";
    while(list($topicid, $topics) = mysqli_fetch_row($toplist)) {
        if ($topicid==$topic) { $sel = "selected "; }
	    echo "<option $sel value=\"$topicid\">$topics</option>\n";
	    $sel = "";
        }
    echo "</select>";
    if ($multilingual == 1) {
	echo "<br><br><b>"._LANGUAGE.": </b>"
	    ."<select name=\"alanguage\">";
	$handle=opendir('language');
	while ($file = readdir($handle)) {
	    if (preg_match("/^lang\-(.+)\.php/", $file, $matches)) {
	        $langFound = $matches[1];
	        $languageslist .= "$langFound ";
	    }
	}
	closedir($handle);
	$languageslist = explode(" ", $languageslist);
	sort($languageslist);
	for ($i=0; $i < sizeof($languageslist); $i++) {
	    if($languageslist[$i]!="") {
		echo "<option value=\"$languageslist[$i]\" ";
		if($languageslist[$i]==$alanguage) echo "selected";
		echo ">".ucfirst($languageslist[$i])."</option>\n";
	    }
	}
	echo "</select>";
    }
    echo "<br><br><b>"._STORYTEXT.":</b> ("._HTMLISFINE.")<br><br>";
    
     if(ereg("MSIE", getenv("HTTP_USER_AGENT")))
         	{include "./modules/wysiwyg_editor/wysiwygeditor.php";
         	 jscript_wysiwyg();
         	 html_editor("story",$f_story,"myEditor");
         	 echo "<br><br><b>"._EXTENDEDTEXT.":</b><br><br>";
         	 html_editor("storyext",$f_storyext,"myEditor2");
         	 echo "<br><br><input type=\"submit\" name=\"op\" value=\""._PREVIEW."\" onClick=\"copyValue_story('myEditor');copyValue_storyext('myEditor2');\"> <input type=\"submit\" name=\"op\" value=\""._OK."\">";
         	 }
         else
        { echo "<textarea cols=\"50\" rows=\"12\" name=\"story\">$story</textarea><br>"
	."<br><b>"._EXTENDEDTEXT.":</b><br>"
	."<textarea cols=\"50\" rows=\"12\" name=\"storyext\">$storyext</textarea><br>"
	."<font class=\"content\">("._AREYOUSURE.")</font><br><br>"
	.""._ALLOWEDHTML."<br>";
	    while (list($key,) = each($AllowableHTML)) echo " &lt;".$key."&gt;";
	    echo "<br><br>"
	        ."<input type=\"submit\" name=\"op\" value=\""._PREVIEW."\">&nbsp;&nbsp;"
		."<input type=\"submit\" name=\"op\" value=\""._OK."\">&nbsp;&nbsp;"
		."<select name=\"posttype\"><option value=\"exttrans\"";
	    if ($posttype=="exttrans") {
	        echo " selected";
	    }
	    echo ">"._EXTRANS."</option>\n"
		."<OPTION value=\"html\"";;
	    if ($posttype=="html") {
	        echo " selected";
	    }
	    echo ">"._HTMLFORMATED."</option>\n"
		."<OPTION value=\"plaintext\"";
	    if (($posttype!="exttrans") && ($posttype!="html")) {
	        echo " selected";
	    }
	    echo ">"._PLAINTEXT."</option></select>"
		."</form>";
	}
    CloseTable();
    include ('footer.php');
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
    $result = mysqli_query($dbi, "insert into $prefix"._queue." values (NULL, '$uid', '$name', '$subject', '$story', '$storyext', now(), '$topic', '$alanguage')");
    if(!$result) {
    	echo ""._ERROR."<br>";
	exit();
    }
    if($notify) {
	$notify_message = "$notify_message\n\n\n========================================================\n$subject\n\n\n$story\n\n$storyext\n\n$name";
    	mail($notify_email, $notify_subject, $notify_message, "From: $notify_from\nX-Mailer: PHP/" . phpversion());
    }
    include ('header.php');
    OpenTable();
    $result = mysqli_query($dbi, "select * from $prefix"._queue."");
    $waiting = sql_num_rows($result);
    echo "<center><font class=\"title\">"._SUBSENT."</font><br><br>"
	."<font class=\"content\"><b>"._THANKSSUB."</b><br><br>"
	.""._SUBTEXT.""
	."<br>"._WEHAVESUB." $waiting "._WAITING."";
    CloseTable();
    include ('footer.php');
}
/* fin wysiwyg */

switch ($op) {

/* debut wysiwyg */
    case ""._PREVIEW."":
	PreviewStory($name, $address, $subject, $story, $storyext, $topic, $alanguage, $posttype);
	break;

    case ""._OK."":
	SubmitStory($name, $address, $subject, $story, $storyext, $topic, $alanguage, $posttype);
	break;
/* fin wysiwyg */

    case "content":
    content();
    break;

    case "content_edit":
    content_edit($pid);
    break;

    case "content_delete":
    content_delete($pid, $ok);
    break;

    case "content_review":
    content_review($title, $subtitle, $page_header, $text, $page_footer, $signature, $clanguage, $active);
    break;

    case "content_save":
    content_save($title, $subtitle, $page_header, $text, $page_footer, $signature, $clanguage, $active, $cid);
    break;

    case "content_save_edit":
    content_save_edit($pid, $title, $subtitle, $page_header, $text, $page_footer, $signature, $clanguage, $active, $cid);
    break;

    case "content_change_status":
    content_change_status($pid, $active);
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
    
    case "del_content_cat":
    del_content_cat($cid, $ok);
    break;
}

} else {
    echo "Access Denied";
}

?>