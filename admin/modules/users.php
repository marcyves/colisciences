<?php

/********************************************************/
/* Tweak_Your_Account for PHP-Nuke 5.5.0                */
/* By: NukeScripts Network (webmaster@nukescripts.com)  */
/* http://www.nukescripts.com                           */
/*                                                      */
/********************************************************/

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }

$result = sql_query("select radminuser, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
list($radminuser, $radminsuper) = mysqli_fetch_row($result, $dbi);
if (($radminuser==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Users Functions                                       */
/*********************************************************/

function displayUsers() {
    global $admin, $prefix, $dbi, $user_prefix;
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._USERADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";

/* List Waiting Users */

    $result = sql_query("select upid, uname, email, newsletter from $user_prefix"._users_pending." order by uname", $dbi);
    $numrows = sql_num_rows($result, $dbi);
    if ($numrows>0) {
    OpenTable();
    echo "<center><font class=\"storycat\"><b>"._WAITINGUSERS."</b></font></center><br>"
	."<form action=\"admin.php\" method=\"post\">"
        ."<table border=\"1\" align=\"center\"><tr bgcolor=\"$bgcolor2\">"
	."<td class=\"boxtitle\">"._USERNAME." ("._ID.")</td>"
	."<td align=\"center\" class=\"boxtitle\">"._NEWSLETTER."</td>"
	."<td align=\"center\" class=\"boxtitle\">"._FUNCTIONS."</td></tr>";
    while(list($upid, $uname, $email, $newsletter) = mysqli_fetch_row($result, $dbi)) {
	echo "<tr class=\"$boxcontent\">"
	    ."<td><a href=\"mailto:$email\">$uname</a> ($upid)</td>";
        if ($newsletter == "1" ) {
	    echo "<td align=\"center\">"._YES."</td>";
        } else {
	    echo "<td align=\"center\">"._NO."</td>";
        }
	echo "<td align=\"center\"><font size=\"2\">[<a href=\"admin.php?op=detailsUser&chng_upid=$upid\">"._DETUSER."</a>]<br>[<a href=\"admin.php?op=approveUser&chng_upid=$upid\">"._APPROVE."</a>]<br>[<a href=\"admin.php?op=denyUser&chng_upid=$upid\">"._DENY."</a>]</font></td>"
	    ."</tr>";
    }
    echo "</table></form>";
    CloseTable();
    echo "<br>";
    } else {
    }

    OpenTable();
    $result = sql_query("select uid, uname from $user_prefix"._users." order by uname", $dbi);
    $numrows = sql_num_rows($result, $dbi);
    echo "<center><font class=\"option\"><b>"._EDITUSER."</b></font><br><br>"
	."<form method=\"post\" action=\"admin.php\">"
	."<b>"._USERNAME.": </b>\n"
        ."<select name=\"chng_uid\">";
	
    while(list($uid, $uname) = mysqli_fetch_row($result, $dbi)) {
	echo "<option value=\"$uid\">$uname</option>";
    }

    echo "</select>\n<select name=\"op\">"
	."<option value=\"modifyUser\">"._MODIFY."</option>\n"
	."<option value=\"delUser\">"._DELETE."</option></select>\n"
	."<input type=\"submit\" value=\""._OK."\"></form></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._ADDUSER."</b></font><br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<table border=\"0\" width=\"100%\">"
        ."<tr><td width=\"100\">"._NICKNAME."</td>"
        ."<td><input type=\"text\" name=\"add_uname\" size=\"30\" maxlength=\"25\"> <font class=\"tiny\">"._REQUIRED."</font></td></tr>"
        ."<tr><td>"._NAME."</td>"
        ."<td><input type=\"text\" name=\"add_name\" size=\"30\" maxlength=\"50\"></td></tr>"
        ."<tr><td>"._EMAIL."</td>"
        ."<td><input type=\"text\" name=\"add_email\" size=\"30\" maxlength=\"60\"> <font class=\"tiny\">"._REQUIRED."</font></td></tr>"
        ."<tr><td>"._FAKEEMAIL."</td>"
        ."<td><input type=\"text\" name=\"add_femail\" size=\"30\" maxlength=\"60\"></td></tr>"
        ."<tr><td>"._URL."</td>"
        ."<td><input type=\"text\" name=\"add_url\" size=\"30\" maxlength=\"60\"></td></tr>"
        ."<tr><td>"._ICQ."</td>"
        ."<td><input type=\"text\" name=\"add_user_icq\" size=\"20\" maxlength=\"20\"></td></tr>"
        ."<tr><td>"._AIM."</td>"
        ."<td><input type=\"text\" name=\"add_user_aim\" size=\"20\" maxlength=\"20\"></td></tr>"
        ."<tr><td>"._YIM."</td>"
        ."<td><input type=\"text\" name=\"add_user_yim\" size=\"20\" maxlength=\"20\"></td></tr>"
        ."<tr><td>"._MSNM."</td>"
        ."<td><input type=\"text\" name=\"add_user_msnm\" size=\"20\" maxlength=\"20\"></td></tr>"
        ."<tr><td>"._LOCATION."</td>"
        ."<td><input type=\"text\" name=\"add_user_from\" size=\"25\" maxlength=\"60\"></td></tr>"
        ."<tr><td>"._OCCUPATION."</td>"
        ."<td><input type=\"text\" name=\"add_user_occ\" size=\"25\" maxlength=\"60\"></td></tr>"
        ."<tr><td>"._INTERESTS."</td>"
        ."<td><input type=\"text\" name=\"add_user_intrest\" size=\"25\" maxlength=\"255\"></td></tr>"
	."<tr><td>"._OPTION."</td>"
        ."<td><input type=\"checkbox\" name=\"add_user_viewemail\" VALUE=\"1\"> "._ALLOWUSERS."</td></tr>"
	."<tr><td>"._NEWSLETTER."</td>"
	."<td><input type=\"radio\" name=\"add_newsletter\" value=\"1\">"._YES."<br>"
	."<input type=\"radio\" name=\"add_newsletter\" value=\"0\" checked>"._NO."</td></tr>"
        ."<tr><td>"._SIGNATURE."</td>"
        ."<td><textarea name=\"add_user_sig\" rows=\"6\" cols=\"45\"></textarea></td></tr>"
        ."<tr><td>"._PASSWORD."</td>"
        ."<td><input type=\"password\" name=\"add_pass\" size=\"12\" maxlength=\"12\"> <font class=\"tiny\">"._REQUIRED."</font></td></tr>"
        ."<input type=\"hidden\" name=\"add_avatar\" value=\"blank.gif\">"
        ."<input type=\"hidden\" name=\"op\" value=\"addUser\">"
        ."<tr><td><input type=\"submit\" value=\""._ADDUSERBUT."\"></form></td></tr>"
        ."</table>";
    CloseTable();
    include("footer.php");
}

function modifyUser($chng_user) {
    global $user_prefix, $dbi;
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._USERADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = sql_query("select uid, uname, name, url, email, femail, user_icq, user_aim, user_yim, user_msnm, user_from, user_occ, user_intrest, user_viewemail, user_avatar, user_sig, pass, newsletter from ".$user_prefix."_users where uid='$chng_user' or uname='$chng_user'", $dbi);
    if(sql_num_rows($result, $dbi) > 0) {
        list($chng_uid, $chng_uname, $chng_name, $chng_url, $chng_email, $chng_femail, $chng_user_icq, $chng_user_aim, $chng_user_yim, $chng_user_msnm, $chng_user_from, $chng_user_occ, $chng_user_intrest, $chng_user_viewemail, $chng_avatar, $chng_user_sig, $chng_pass, $chng_newsletter) = mysqli_fetch_row($result, $dbi);
	OpenTable();
	echo "<center><font class=\"option\"><b>"._USERUPDATE.": <i>$chng_user</i></b></font></center>"
	    ."<form action=\"admin.php\" method=\"post\">"
	    ."<table border=\"0\">"
	    ."<tr><td>"._USERID."</td>"
	    ."<td><b>$chng_uid</b></td></tr>"
	    ."<tr><td>"._NICKNAME."</td>"
	    ."<td><input type=\"text\" name=\"chng_uname\" value=\"$chng_uname\"> <font class=\"tiny\">"._REQUIRED."</font></td></tr>"
	    ."<tr><td>"._NAME."</td>"
	    ."<td><input type=\"text\" name=\"chng_name\" value=\"$chng_name\"></td></tr>"
	    ."<tr><td>"._URL."</td>"
	    ."<td><input type=\"text\" name=\"chng_url\" value=\"$chng_url\" size=\"30\" maxlength=\"60\"></td></tr>"
	    ."<tr><td>"._EMAIL."</td>"
	    ."<td><input type=\"text\" name=\"chng_email\" value=\"$chng_email\" size=\"30\" maxlength=\"60\"> <font class=\"tiny\">"._REQUIRED."</font></td></tr>"
	    ."<tr><td>"._FAKEEMAIL."</td>"
	    ."<td><input type=\"text\" name=\"chng_femail\" value=\"$chng_femail\" size=\"30\" maxlength=\"60\"></td></tr>"
	    ."<tr><td>"._ICQ."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_icq\" value=\"$chng_user_icq\" size=\"20\" maxlength=\"20\"></td></tr>"
	    ."<tr><td>"._AIM."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_aim\" value=\"$chng_user_aim\" size=\"20\" maxlength=\"20\"></td></tr>"
	    ."<tr><td>"._YIM."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_yim\" value=\"$chng_user_yim\" size=\"20\" maxlength=\"20\"></td></tr>"
	    ."<tr><td>"._MSNM."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_msnm\" value=\"$chng_user_msnm\" size=\"20\" maxlength=\"20\"></td></tr>"
	    ."<tr><td>"._LOCATION."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_from\" value=\"$chng_user_from\" size=\"25\" maxlength=\"60\"></td></tr>"
	    ."<tr><td>"._OCCUPATION."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_occ\" value=\"$chng_user_occ\" size=\"25\" maxlength=\"60\"></td></tr>"
	    ."<tr><td>"._INTERESTS."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_intrest\" value=\"$chng_user_intrest\" size=\"25\" maxlength=\"255\"></td></tr>"
	    ."<tr><td>"._OPTION."</td>";
	if ($chng_user_viewemail ==1) {
	    echo "<td><input type=\"checkbox\" name=\"chng_user_viewemail\" value=\"1\" checked> "._ALLOWUSERS."</td></tr>";
	} else {
	    echo "<td><input type=\"checkbox\" name=\"chng_user_viewemail\" value=\"1\"> "._ALLOWUSERS."</td></tr>";
	}
	if ($chng_newsletter == 1) {
	    echo "<tr><td>"._NEWSLETTER."</td><td><input type=\"radio\" name=\"chng_newsletter\" value=\"1\" checked>"._YES."<br>"
		."<input type=\"radio\" name=\"chng_newsletter\" value=\"0\">"._NO."<br>";
	} elseif ($chng_newsletter == 0) {
	    echo "<tr><td>"._NEWSLETTER."</td><td><input type=\"radio\" name=\"chng_newsletter\" value=\"1\">"._YES."<br>"
		."<input type=\"radio\" name=\"chng_newsletter\" value=\"0\" checked>"._NO."<br>";
	}
	echo "<tr><td>"._SIGNATURE."</td>"
	    ."<td><textarea name=\"chng_user_sig\" rows=\"6\" cols=\"45\">$chng_user_sig</textarea></td></tr>"
	    ."<tr><td>"._PASSWORD."</td>"
	    ."<td><input type=\"password\" name=\"chng_pass\" size=\"12\" maxlength=\"12\"></td></tr>"
	    ."<tr><td>"._RETYPEPASSWD."</td>"
	    ."<td><input type=\"password\" name=\"chng_pass2\" size=\"12\" maxlength=\"12\"> <font class=\"tiny\">"._FORCHANGES."</font></td></tr>"
	    ."<input type=\"hidden\" name=\"chng_avatar\" value=\"$chng_avatar\">"
	    ."<input type=\"hidden\" name=\"chng_uid\" value=\"$chng_uid\">"
	    ."<input type=\"hidden\" name=\"op\" value=\"updateUser\">"
	    ."<tr><td><input type=\"submit\" value=\""._SAVECHANGES."\"></form></td></tr>"
	    ."</table>";
	CloseTable();
    } else {
	OpenTable();
	echo "<center><b>"._USERNOEXIST."</b><br><br>"
	    .""._GOBACK."</center>";
	CloseTable();
    }
    include("footer.php");
}

function updateUser($chng_uid, $chng_uname, $chng_name, $chng_url, $chng_email, $chng_femail, $chng_user_icq, $chng_user_aim, $chng_user_yim, $chng_user_msnm, $chng_user_from, $chng_user_occ, $chng_user_intrest, $chng_user_viewemail, $chng_avatar, $chng_sig, $chng_pass, $chng_pass2, $chng_newsletter) {
    global $user_prefix, $dbi;
    $tmp = 0;
    if ($chng_pass2 != "") {
        if($chng_pass != $chng_pass2) {
            include("header.php");
	    GraphicAdmin();
	    OpenTable();
	    echo "<center><font class=\"title\"><b>"._USERADMIN."</b></font></center>";
	    CloseTable();
	    echo "<br>";
	    OpenTable();
            echo "<center>"._PASSWDNOMATCH."<br><br>"
		.""._GOBACK."</center>";
	    CloseTable();
            include("footer.php");
            exit;
        }
        $tmp = 1;
    }
    if ($tmp == 0) {
	sql_query("update ".$user_prefix."_users set uname='$chng_uname', name='$chng_name', email='$chng_email', femail='$chng_femail', url='$chng_url', user_icq='$chng_user_icq', user_aim='$chng_user_aim', user_yim='$chng_user_yim', user_msnm='$chng_user_msnm', user_from='$chng_user_from', user_occ='$chng_user_occ', user_intrest='$chng_user_intrest', user_viewemail='$chng_user_viewemail', user_avatar='$chng_avatar', user_sig='$chng_sig', newsletter='$chng_newsletter' where uid='$chng_uid'", $dbi);
    }
    if ($tmp == 1) {
    	$cpass = md5($chng_pass);
        sql_query("update ".$user_prefix."_users set uname='$chng_uname', name='$chng_name', email='$chng_email', femail='$chng_femail', url='$chng_url', user_icq='$chng_user_icq', user_aim='$chng_user_aim', user_yim='$chng_user_yim', user_msnm='$chng_user_msnm', user_from='$chng_user_from', user_occ='$chng_user_occ', user_intrest='$chng_user_intrest', user_viewemail='$chng_user_viewemail', user_avatar='$chng_avatar', user_sig='$chng_sig', pass='$cpass', newsletter='$chng_newsletter' where uid='$chng_uid'", $dbi);
    }
    Header("Location: admin.php?op=adminMain");
}

function detailsUser($chng_user) {
    global $user_prefix, $dbi;
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._USERADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = sql_query("select upid, uname, name, url, email, femail, user_icq, user_aim, user_yim, user_msnm, user_from, user_occ, user_intrest, user_viewemail, user_avatar, user_sig, pass, newsletter from $user_prefix"._users_pending." where upid='$chng_user' or uname='$chng_user'", $dbi);
    if(sql_num_rows($result, $dbi) > 0) {
        list($chng_upid, $chng_uname, $chng_name, $chng_url, $chng_email, $chng_femail, $chng_user_icq, $chng_user_aim, $chng_user_yim, $chng_user_msnm, $chng_user_from, $chng_user_occ, $chng_user_intrest, $chng_user_viewemail, $chng_avatar, $chng_user_sig, $chng_pass, $chng_newsletter) = mysqli_fetch_row($result, $dbi);
	OpenTable();
	echo "<center><font class=\"option\"><b>"._DETUSER.": <i>$chng_user</i></b></font></center>\n"
	    ."<form action=\"admin.php\" method=\"post\">\n"
	    ."<table border=\"0\">\n"
	    ."<tr><td>"._NICKNAME."</td><td>$chng_uname</td></tr>\n"
	    ."<tr><td>"._NAME."</td><td>$chng_name</td></tr>\n"
	    ."<tr><td>"._URL."</td><td>$chng_url</td></tr>\n"
	    ."<tr><td>"._EMAIL."</td><td>$chng_email</td></tr>\n"
	    ."<tr><td>"._FAKEEMAIL."</td><td>$chng_femail</td></tr>\n"
	    ."<tr><td>"._ICQ."</td><td>$chng_user_icq</td></tr>\n"
	    ."<tr><td>"._AIM."</td><td>$chng_user_aim</td></tr>\n"
	    ."<tr><td>"._YIM."</td><td>$chng_user_yim</td></tr>\n"
	    ."<tr><td>"._MSNM."</td><td>$chng_user_msnm</td></tr>\n"
	    ."<tr><td>"._LOCATION."</td><td>$chng_user_from</td></tr>\n"
	    ."<tr><td>"._OCCUPATION."</td><td>$chng_user_occ</td></tr>\n"
	    ."<tr><td>"._INTERESTS."</td><td>$chng_user_intrest</td></tr>\n"
	    ."<tr><td>"._OPTION."</td>\n";
	if ($chng_user_viewemail ==1) {
	    echo "<td><input type=\"checkbox\" name=\"chng_user_viewemail\" value=\"1\" checked disabled> "._ALLOWUSERS."</td></tr>\n";
	} else {
	    echo "<td><input type=\"checkbox\" name=\"chng_user_viewemail\" value=\"1\" disabled> "._ALLOWUSERS."</td></tr>\n";
	}
	if ($chng_newsletter == 1) {
	    echo "<tr><td>"._NEWSLETTER."</td><td>"._YES."</td></tr>\n";
	} else {
	    echo "<tr><td>"._NEWSLETTER."</td><td>"._NO."</td></tr>\n";
	}
	echo "<tr><td>"._SIGNATURE."</td><td>$chng_user_sig</td></tr>\n"
	    ."<tr><td><select name=\"op\">\n<option value=\"approveUser\">"._APPROVE."</option>\n<option value=\"denyUser\">"._DENY."</option></select>\n"
            ."<input type=\"hidden\" name=\"chng_upid\" value=\"$chng_user\">\n<input type=\"submit\" value=\""._OK."\"></form></td></tr>"
	    ."</table>\n";
	CloseTable();
    } else {
	OpenTable();
	echo "<center><b>"._USERNOEXIST."</b><br><br>\n"
	    .""._GOBACK."</center>\n";
	CloseTable();
    }
    include("footer.php");
}

function makePass() {
    $makepass="";
    $syllables="er,in,tia,wol,fe,pre,vet,jo,nes,al,len,son,cha,ir,ler,bo,ok,tio,nar,sim,ple,bla,ten,toe,cho,co,lat,spe,ak,er,po,co,lor,pen,cil,li,ght,wh,at,the,he,ck,is,mam,bo,no,fi,ve,any,way,pol,iti,cs,ra,dio,sou,rce,sea,rch,pa,per,com,bo,sp,eak,st,fi,rst,gr,oup,boy,ea,gle,tr,ail,bi,ble,brb,pri,dee,kay,en,be,se";
    $syllable_array=explode(",", $syllables);
    srand((double)microtime()*1000000);
    for ($count=1;$count<=4;$count++) {
        if (rand()%10 == 1) {
            $makepass .= sprintf("%0.0f",(rand()%50)+1);
        } else {
            $makepass .= sprintf("%s",$syllable_array[rand()%62]);
        }
    }
    return($makepass);
}

switch($op) {

    case "approveUser":
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._USERADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._APPROVEUSER."</b></font><br><br>"
	.""._SURE2APPROVE." $chng_upid?<br><br>"
	."[ <a href=\"admin.php?op=approveUserConf&amp;chng_upid=$chng_upid\">"._YES."</a> | <a href=\"admin.php?op=mod_users\">"._NO."</a> ]</center>";
    CloseTable();
    include("footer.php");
    break;

    case "approveUserConf":
    $result = sql_query("SELECT uname, email, url, user_avatar, user_regdate, user_icq, user_occ, user_from, user_intrest, user_sig, user_viewemail, user_aim, user_yim, user_msnm, newsletter FROM $user_prefix"._users_pending." WHERE upid='$chng_upid'", $dbi);
    list($uname, $email, $url, $user_avatar, $user_regdate, $user_icq, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $user_aim, $user_yim, $user_msnm, $newsletter) = mysqli_fetch_row($result, $dbi);
    $makepass=makePass();
    $cryptpass = md5($makepass);
                $tempto = sql_query("SELECT max(uid) AS latest_uid FROM $user_prefix"._users."", $dbi);
                list($latest_uid) = mysqli_fetch_array($tempto, $dbi);
                if ($latest_uid == "-1") {
                    $latest_uid = 1;
                } else {
                    $latest_uid = $latest_uid+1;
                }
    $result2 = sql_query("insert into $user_prefix"._users." values ('$latest_uid','','$uname','$email','','$url','$user_avatar','$user_regdate','$user_icq','$user_occ','$user_from','$user_intrest','$user_sig','$user_viewemail','','$user_aim','$user_yim','$user_msnm','$cryptpass',10,'',0,0,0,'',0,'','','$commentlimit',0,'$newsletter',0,0,0,1)", $dbi);
                    $message = ""._WELCOMETO." $sitename!\n\n"._YOUUSEDEMAIL." ($email) "._TOREGISTER." $sitename. "._FOLLOWINGMEM."\n\n"._UNICKNAME." $uname\n"._UPASSWORD." $makepass";
                    $subject=""._APPLAPPR."";
                    $from="$adminmail";
                    mail($email, $subject, $message, "From: $from\nX-Mailer: PHP/" . phpversion());
    sql_query("delete from ".$user_prefix."_users_pending where upid='$chng_upid' or uname='$chng_upid'", $dbi);
    Header("Location: admin.php?op=mod_users");
    break;

    case "denyUser":
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._USERADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._DENYUSER."</b></font><br><br>"
	.""._SURE2DENY." $chng_upid?<br><br>"
	."[ <a href=\"admin.php?op=denyUserConf&amp;chng_upid=$chng_upid\">"._YES."</a> | <a href=\"admin.php?op=mod_users\">"._NO."</a> ]</center>";
    CloseTable();
    include("footer.php");
    break;

    case "denyUserConf":
    $result = sql_query("select uname, email from $user_prefix"._users_pending." where upid=$chng_upid", $dbi);
    list($uname, $email) = mysqli_fetch_row($result, $dbi);
                     $message = ""._SORRYTO." $sitename "._YOUDENIED."";
                    $subject=""._APPLDENIED."";
                    $from="$email";
                    mail($email, $subject, $message, "From: $from\nX-Mailer: PHP/" . phpversion());
    sql_query("delete from ".$user_prefix."_users_pending where upid='$chng_upid' or uname='$chng_upid'", $dbi);
    Header("Location: admin.php?op=mod_users");
    break;

    case "detailsUser":
    detailsUser($chng_upid);
    break;

    case "mod_users":
    displayUsers();
    break;

    case "modifyUser":
    modifyUser($chng_uid);
    break;

    case "updateUser":
    updateUser($chng_uid, $chng_uname, $chng_name, $chng_url, $chng_email, $chng_femail, $chng_user_icq, $chng_user_aim, $chng_user_yim, $chng_user_msnm, $chng_user_from, $chng_user_occ, $chng_user_intrest, $chng_user_viewemail, $chng_avatar, $chng_sig, $chng_pass, $chng_pass2, $chng_newsletter);
    break;

    case "delUser":
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._USERADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._DELETEUSER."</b></font><br><br>"
	.""._SURE2DELETE." $chng_uid?<br><br>"
	."[ <a href=\"admin.php?op=delUserConf&amp;del_uid=$chng_uid\">"._YES."</a> | <a href=\"admin.php?op=mod_users\">"._NO."</a> ]</center>";
    CloseTable();
    include("footer.php");
    break;

    case "delUserConf":
    sql_query("delete from ".$user_prefix."_users where uid='$del_uid' or uname='$del_uid'", $dbi);
    Header("Location: admin.php?op=mod_users");
    break;

    case "addUser":
    $add_pass = md5($add_pass);
    if (!($add_uname && $add_email && $add_pass)) {
	include("header.php");
	GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._USERADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
        OpenTable();
	echo "<center><b>"._NEEDTOCOMPLETE."</b><br><br>"
	    .""._GOBACK."";
	CloseTable();
	include("footer.php");
        return;
    }
    $user_regdate = date("M d, Y");
    $sql = "insert into ".$user_prefix."_users ";
    $sql .= "(uid,name,uname,email,femail,url,user_regdate,user_icq,user_aim,user_yim,user_msnm,user_from,user_occ,user_intrest,user_viewemail,user_avatar,user_sig,pass,newsletter) ";
    $sql .= "values (NULL,'$add_name','$add_uname','$add_email','$add_femail','$add_url','$user_regdate','$add_user_icq','$add_user_aim','$add_user_yim','$add_user_msnm','$add_user_from','$add_user_occ','$add_user_intrest','$add_user_viewemail','$add_avatar','$add_user_sig','$add_pass','$add_newsletter')";
    $result = sql_query($sql, $dbi);
    if (!$result) {
        return;
    }
    Header("Location: admin.php?op=mod_users");
    break;
			
}

} else {
    echo "Access Denied";
}

?>