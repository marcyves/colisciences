<?php

/************************************************************************/
/* PHP-NUKE: Advanced Content Management System                         */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi (fbc@mandrakesoft.com)         */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

require_once("mainfile.php");
get_lang("admin");
$adminpage = 1;
$wysiwyg = 1;

function create_first($name, $url, $email, $pwd, $user) {
    global $prefix, $dbi, $user_prefix;
    $first = sql_num_rows(sql_query("select * from ".$prefix."_authors", $dbi),$dbi);
    if ($first == 0) {
        $pwd = md5($pwd);
        $the_adm = "God";
        $result = sql_query("insert into ".$prefix."_authors values ('$name', '$the_adm', '$url', '$email', '$pwd', 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1, '')", $dbi);
        if ($user == 1) {
            $user_regdate = date("M d, Y");
            $user_avatar = "blank.gif";
            $commentlimit = 4096;
            $result = sql_query("insert into ".$user_prefix."_users values (NULL,'','$name','$email','','$url','$user_avatar','$user_regdate','','','','','','0','','','','','$pwd',10,'','0','0','0','','0','','$Default_Theme','$commentlimit','0','0','0','0','0','1')", $dbi);
        }
        login();
    }
}

$the_first = sql_num_rows(sql_query("select * from ".$prefix."_authors", $dbi), $dbi);
if ($the_first == 0) {
    if (!$name) {
    include("header.php");
    title("$sitename: "._ADMINISTRATION."");
    OpenTable();
    echo "<center><b>"._NOADMINYET."</b></center><br><br>"
        ."<form action=\"admin.php\" method=\"post\">"
        ."<table border=\"0\">"
        ."<tr><td><b>"._NICKNAME.":</b></td><td><input type=\"text\" name=\"name\" size=\"30\" maxlength=\"25\"></td></tr>"
        ."<tr><td><b>"._HOMEPAGE.":</b></td><td><input type=\"text\" name=\"url\" size=\"30\" maxlength=\"255\" value=\"http://\"></td></tr>"
        ."<tr><td><b>"._EMAIL.":</b></td><td><input type=\"text\" name=\"email\" size=\"30\" maxlength=\"255\"></td></tr>"
        ."<tr><td><b>"._PASSWORD.":</b></td><td><input type=\"password\" name=\"pwd\" size=\"11\" maxlength=\"10\"></td></tr>"
        ."<tr><td colspan=\"2\">"._CREATEUSERDATA."  <input type=\"radio\" name=\"user\" value=\"1\" checked>"._YES."&nbsp;&nbsp;<input type=\"radio\" name=\"user\" value=\"0\">"._NO."</td></tr>"
        ."<tr><td><input type=\"hidden\" name=\"fop\" value=\"create_first\">"
        ."<input type=\"submit\" value=\""._SUBMIT."\">"
        ."</td></tr></table></form>";
    CloseTable();
    include("footer.php");
    }
    switch($fop) {
        case "create_first":
        create_first($name, $url, $email, $pwd, $user);
        break;
    }
    die();
}

require("auth.php");

if(!isset($op)) { $op = "adminMain"; }
$pagetitle = "- "._ADMINMENU."";

/*********************************************************/
/* Login Function                                        */
/*********************************************************/

function login() {
    include ("header.php");
    OpenTable();
    echo "<center><font class=\"title\"><b>"._ADMINLOGIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<form action=\"admin.php\" method=\"post\">"
        ."<table border=\"0\">"
        ."<tr><td>"._ADMINID."</td>"
        ."<td><input type=\"text\" NAME=\"aid\" SIZE=\"20\" MAXLENGTH=\"20\"></td></tr>"
        ."<tr><td>"._PASSWORD."</td>"
        ."<td><input type=\"password\" NAME=\"pwd\" SIZE=\"20\" MAXLENGTH=\"18\"></td></tr>"
        ."<tr><td>"
        ."<input type=\"hidden\" NAME=\"op\" value=\"login\">"
        ."<input type=\"submit\" VALUE=\""._LOGIN."\">"
        ."</td></tr></table>"
        ."</form>";
    CloseTable();
    include ("footer.php");
}

function deleteNotice($id, $table, $op_back) {
    global $dbi;
    sql_query("delete from $table WHERE id = $id", $dbi);
    Header("Location: admin.php?op=$op_back");
}

/*********************************************************/
/* Administration Menu Function                          */
/*********************************************************/

function adminmenu($url, $title, $image) {
    global $counter, $admingraphic;
    if ($admingraphic == 1) {
        $img = "<img src=\"images/admin/$image\" border=\"0\" alt=\"\"></a><br>";
        $close = "";
    } else {
        $image = "";
        $close = "</a>";
    }
    echo "<td align=\"center\"><font class=\"content\"><a href=\"$url\">$img<b>$title</b>$close</font></td>";
    if ($counter == 5) {
        echo "</tr><tr>";
        $counter = 0;
    } else {
        $counter++;
    }
}

function GraphicAdmin() {
    global $aid, $admingraphic, $language, $admin, $banners, $prefix, $dbi;
    $result = sql_query("select radminarticle,radmintopic,radminuser,radminsurvey,radminsection,radminlink,radminephem,radminfaq,radmindownload,radminreviews,radminnewsletter,radminforum,radmincontent,radminency,radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
    list($radminarticle,$radmintopic,$radminuser,$radminsurvey,$radminsection,$radminlink,$radminephem,$radminfaq,$radmindownload,$radminreviews,$radminnewsletter,$radminforum,$radmincontent,$radminency,$radminsuper) = sql_fetch_array($result, $dbi);
    OpenTable();
    echo "<center>";
    echo"<table border=\"0\" width=\"100%\" cellspacing=\"1\"><tr>";
    $linksdir = dir("admin/links");
    while($func=$linksdir->read()) {
        if(substr($func, 0, 6) == "links.") {
            $menulist .= "$func ";
        }
    }
    closedir($linksdir->handle);
    $menulist = explode(" ", $menulist);
    sort($menulist);
    for ($i=0; $i < sizeof($menulist); $i++) {
        if($menulist[$i]!="") {
            $counter = 0;
            include($linksdir->path."/$menulist[$i]");
        }
    }
    adminmenu("admin.php?op=logout", ""._ADMINLOGOUT."", "exit.gif");
    echo"</tr></table></center>";
    CloseTable();
    echo "<br>";
}

/*********************************************************/
/* Administration Main Function                          */
/*********************************************************/

function adminMain() {
    global $language, $admin, $aid, $prefix, $file, $dbi, $sitename;
    include ("header.php");
    $dummy = 0;
    GraphicAdmin();
    $result2 = sql_query("select radminarticle, radminsuper, admlanguage from ".$prefix."_authors where aid='$aid'", $dbi);
    list($radminarticle, $radminsuper, $admlanguage) = sql_fetch_row($result2, $dbi);
    if ($admlanguage != "" ) {
        $queryalang = "WHERE alanguage='$admlanguage' ";
    } else {
        $queryalang = "";
    }
    $main_m = sql_query("select main_module from ".$prefix."_main", $dbi);
    list($main_module) = sql_fetch_row($main_m, $dbi);
    OpenTable();
    echo "<center><b>$sitename: "._DEFHOMEMODULE."</b><br><br>"
        .""._MODULEINHOME." <b>$main_module</b><br>[ <a href=\"admin.php?op=modules\">"._CHANGE."</a> ]</center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    $result = sql_query("SELECT username FROM ".$prefix."_session where guest=1", $dbi);
    $guest_online_num = sql_num_rows($result, $dbi);
    $result = sql_query("SELECT username FROM ".$prefix."_session where guest=0", $dbi);
    $member_online_num = sql_num_rows($result, $dbi);
    $who_online_num = $guest_online_num + $member_online_num;
    $who_online = "<center><font class=\"option\">"._WHOSONLINE."</font><br><br><font class=\"content\">"._CURRENTLY." $guest_online_num "._GUESTS." $member_online_num "._MEMBERS."<br>";
    echo "<center>$who_online</center>";
    CloseTable();
    echo "<br>";
    include ("footer.php");
}

if($admintest) {

    switch($op) {

        case "deleteNotice":
        deleteNotice($id, $table, $op_back);
        break;

        case "GraphicAdmin":
        GraphicAdmin();
        break;

        case "adminMain":
        adminMain();
        break;

        case "logout":
        setcookie("admin");
        include("header.php");
        OpenTable();
        echo "<center><font class=\"title\"><b>"._YOUARELOGGEDOUT."</b></font></center>";
        CloseTable();
        include("footer.php");
        break;

        case "login";
        unset($op);

        default:
        $casedir = dir("admin/case");
        while($func=$casedir->read()) {
		    if(substr($func, 0, 5) == "case.") {
                include($casedir->path."/$func");
            }
        }
        closedir($casedir->handle);
        break;

        }

} else {

    login();

}

?>
