<?php

/********************************************************/
/* Tweak_Your_Account for PHP-Nuke 5.5.0                */
/* By: NukeScripts Network (webmaster@nukescripts.com)  */
/* http://www.nukescripts.com                           */
/*                                                      */
/********************************************************/

if (!eregi("modules.php", $PHP_SELF)) {
        die ("You can't access this file directly...");
}
require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);
$userpage = 1;

function nav() {
    global $allowusertheme, $allowuserdelete;

    echo "<table border=\"0\" cellpadding=\"15\" align=\"center\"><tr><td>";

    echo "<font class=\"content\">"
        ."<center><a href=\"modules.php?name=Your_Account&amp;op=edituser\"><img src=\"images/menu/info.gif\" border=\"0\" alt=\""._CHANGEYOURINFO."\"></a><br>"
        ."<a href=\"modules.php?name=Your_Account&amp;op=edituser\">"._CHANGEYOURINFO."</a>"
        ."</center></font></td>";
/*
    echo "<td><font class=\"content\">"
        ."<center><a href=\"modules.php?name=Your_Account&amp;op=edithome\"><img src=\"images/menu/home.gif\" border=\"0\" alt=\""._CHANGEHOME."\"></a><br>"
        ."<a href=\"modules.php?name=Your_Account&amp;op=edithome\">"._CHANGEHOME."</a>"
        ."</center></form></font></td>";

    echo "<td><font class=\"content\">"
        ."<center><a href=\"modules.php?name=Your_Account&amp;op=editcomm\"><img src=\"images/menu/comments.gif\" border=\"0\" alt=\""._CONFIGCOMMENTS."\"></a><br>"
        ."<a href=\"modules.php?name=Your_Account&amp;op=editcomm\">"._CONFIGCOMMENTS."</a>"
        ."</center></form></font></td>";
*/
    if ($allowusertheme==1) {
    echo "<td><font class=\"content\">"
        ."<center><a href=\"modules.php?name=Your_Account&amp;op=chgtheme\"><img src=\"images/menu/themes.gif\" border=\"0\" alt=\""._SELECTTHETHEME."\"><br>"
        .""._ACCTTHEME."</a>"
        ."</center></form></font></td>";
    }

    echo "<td><font class=\"content\">"
        ."<center><a href=\"modules.php?name=Your_Account&amp;op=logout\"><img src=\"images/menu/exit.gif\" border=\"0\" alt=\""._LOGOUTEXIT."\"></a><br>"
        ."<a href=\"modules.php?name=Your_Account&amp;op=logout\">"._LOGOUTEXIT."</a>"
        ."</center></form></font></td>";

    if ($allowuserdelete==1) {
    echo "<td><font class=\"content\">"
        ."<center><a href=\"modules.php?name=Your_Account&amp;op=delete\"><img src=\"images/menu/delete.gif\" border=\"0\" alt=\""._DELETEACCT."\"><br>"
        .""._DELETEACCT."</a>"
        ."</center></form></font></td>";
    }

    echo "</td></tr></table>\n";
}

function delete($uname) {
    global $adminmail, $user, $userinfo, $cookie, $user_prefix, $prefix, $dbi, $allowuserdelete;
    if ($allowuserdelete==1) {
        cookiedecode($user);
        $check = $cookie[1];
        $result = mysqli_query($dbi, "SELECT uid, uname, pass FROM $user_prefix"._users." WHERE uname='$check'");
        list($uid, $uname, $pass) = mysqli_fetch_row($result);
        include("header.php");
        OpenTable();
        echo "<center><font class=\"option\">"._SUREDELETE."<br><a href=\"modules.php?name=Your_Account&amp;op=delconfirm&amp;uid=$uid&amp;code=$pass\"><b>"._YES."</b></a> "._OR." <a href=\"modules.php?name=Your_Account\"><b>"._NO."</b></a></font></center>";
        CloseTable();
        include("footer.php");
    } else {
        include("header.php");
        OpenTable();
        echo "<font class=\"option\">"._ACTDISABLED."</font>";
        CloseTable();
        include("footer.php");
    }
}


function delconfirm($uid, $code) {
    global $senddeletemail, $adminmail, $user_prefix, $prefix, $uid, $dbi, $sitename, $allowuserdelete;
    if ($allowuserdelete==1) {
        setcookie("user");
        $result = mysqli_query($dbi, "SELECT email, uname, pass FROM $user_prefix"._users." WHERE uid='$uid'");
        list($email, $uname, $pass) = mysqli_fetch_row($result);
        if ($code == $pass) {
            if ($senddeletemail == 1) {
                $to = $adminmail;
                $from = $email;
                $subject = "$sitename - Member Deleted";
                $message = "
                $uname has been deleted from $sitename.\n
                -----------------------------------------------------------\n
                Do not reply to this message!!";
                mail($to,$subject,$message, "From: $from\nX-Mailer: PHP/" . phpversion());
            }
            mysqli_query($dbi, "DELETE FROM $user_prefix"._users." WHERE uid='$uid'");
            include("header.php");
            Opentable();
            echo "<center><font class=\"option\"><b>"._ACCTDELETED."</b></font></center>";
            CloseTable();
            include("footer.php");
        } else {
            include("header.php");
            OpenTable();
            echo "<center><font class=\"option\">Error!</font><br><br>";
            echo "<font class=\"option\">"._YOUBAD."</font></center>";
            CloseTable();
            include("footer.php");
        }
    } else {
        include("header.php");
        OpenTable();
        echo "<font class=\"option\">"._ACTDISABLED."</font>";
        CloseTable();
        include("footer.php");
    }
}

function userCheck($uname, $email) {
    global $stop, $user_prefix, $dbi;
    if ((!$email) || ($email=="") || (!eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$",$email))) $stop = "<center>"._ERRORINVEMAIL."</center><br>";
    if (strrpos($email,' ') > 0) $stop = "<center>"._ERROREMAILSPACES."</center>";
    if ((!$uname) || ($uname=="") || (ereg("[^a-zA-Z0-9_-]",$uname))) $stop = "<center>"._ERRORINVNICK."</center><br>";
    if (strlen($uname) > 25) $stop = "<center>"._NICK2LONG."</center>";
    if (eregi("^((root)|(adm)|(linux)|(webmaster)|(admin)|(god)|(administrator)|(administrador)|(nobody)|(anonymous)|(anonimo)|(an�nimo)|(operator))$",$uname)) $stop = "<center>"._NAMERESERVED."";

    /* This line allows you to block the use of a username containing one of the string values in this array */
    if ( eregi("(nuke)|(admin)|(staff)", $uname) ) $stop = "<center>"._ACCTNAMERESERVED."</center><br>";

    if (strrpos($uname,' ') > 0) $stop = "<center>"._NICKNOSPACES."</center>";
    if (sql_num_rows(mysqli_query($dbi, "select uname from ".$user_prefix."_users where uname='$uname'")) > 0) $stop = "<center>"._NICKTAKEN."</center><br>";
    if (sql_num_rows(mysqli_query($dbi, "select email from ".$user_prefix."_users where email='$email'")) > 0) $stop = "<center>"._EMAILREGISTERED."</center><br>";

    /* These lines checks your users_pending table for usernames already taken and emails already used */
    if (sql_num_rows(mysqli_query($dbi, "select uname from ".$user_prefix."_users_pending where uname='$uname'")) > 0) $stop = "<center>"._NICKTAKEN."</center><br>";
    if (sql_num_rows(mysqli_query($dbi, "select email from ".$user_prefix."_users_pending where email='$email'")) > 0) $stop = "<center>"._EMAILREGISTERED."</center><br>";

    return($stop);
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

function confirmNewUser($uname, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $newsletter) {
    global $stop, $EditedMessage, $sitename, $blkusrreg, $requireadmin;
    if ($blkusrreg==1) {
        include("header.php");
        filter_text($uname);
        $uname = $EditedMessage;
        if($user_viewemail == 1) {
            $user_viewemail = "1";
        } else {
            $user_viewemail = "0";
        }
        userCheck($uname, $email);
        if (!$stop) {
            if ($requireadmin == 0) {
                OpenTable();
                echo "<center><b>"._USERFINALSTEP."</b></center><br><br>$uname, "._USERCHECKDATA."<br><br>";
            } else {
                OpenTable();
                echo "<center><b>"._USERAPPFINALSTEP."</b></center><br><br>$uname, "._USERCHECKDATA."<br><br>";
            }
            echo "<b>"._UUSERNAME.":</b> $uname<br>"
                ."<b>"._EMAIL.":</b> $email<br>";
            if (($user_avatar) || ($user_avatar!="")) echo "<b>"._AVATAR.":</b> <img src=\"images/forum/avatar/$user_avatar\" alt=\"\"><br>";
            if ($newsletter == 1) { echo "<b>"._RECEIVENEWSLETTER.":</b> "._YES."<br>"; } else { echo "<b>"._RECEIVENEWSLETTER.":</b> "._NO."<br>"; }
            if (($user_from) || ($user_from!="")) echo "<b>"._LOCATION.":</b> $user_from<br>";
            if (($user_occ) || ($user_occ!="")) echo "<b>"._OCCUPATION.":</b> $user_occ<br>";
            if (($user_intrest) || ($user_intrest!="")) echo "<b>"._INTERESTS.":</b> $user_intrest<br>";
            if (($user_sig) || ($user_sig!="")) echo "<b>"._SIGNATURE.":</b> $user_sig<br>";
            echo "<form action=\"modules.php?name=Your_Account\" method=\"post\">"
                ."<input type=\"hidden\" name=\"uname\" value=\"$uname\">"
                ."<input type=\"hidden\" name=\"email\" value=\"$email\">"
                ."<input type=\"hidden\" name=\"user_avatar\" value=\"$user_avatar\">"
                ."<input type=\"hidden\" name=\"newsletter\" value=\"$newsletter\">"
                ."<input type=\"hidden\" name=\"user_from\" value=\"$user_from\">"
                ."<input type=\"hidden\" name=\"user_occ\" value=\"$user_occ\">"
                ."<input type=\"hidden\" name=\"user_intrest\" value=\"$user_intrest\">"
                ."<input type=\"hidden\" name=\"user_sig\" value=\"$user_sig\">"
                ."<input type=\"hidden\" name=\"user_viewemail\" value=\"$user_viewemail\">"
                ."<input type=\"hidden\" name=\"op\" value=\"finish\"><br><br>"
                ."<input type=\"submit\" value=\""._FINISH."\"> &nbsp;&nbsp;"._GOBACK."</form>";
            CloseTable();
        } else {
            OpenTable();
            echo "<center><font class=\"title\"><b>Registration Error!</b></font><br><br>";
            echo "<font class=\"content\">$stop<br>"._GOBACK."</font></center>";
            CloseTable();
        }
        include("footer.php");
    } else {
        include("header.php");
        OpenTable();
        echo "<font class=\"option\">"._ACTDISABLED."</font>";
        CloseTable();
        include("footer.php");
    }
}

function finishNewUser($uname, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $newsletter) {
    global $blkusrreg, $sendaddmail, $stop, $makepass, $EditedMessage, $adminmail, $sitename, $Default_Theme, $user_prefix, $dbi, $requireadmin;
    if ($blkusrreg==1) {
        include("header.php");
        userCheck($uname, $email);
        $user_regdate = date("M d, Y");
        if (!isset($stop)) {
            if ($requireadmin==0) {
                $makepass=makePass();
                $cryptpass = md5($makepass);
                /* Start code provided by Scorpion2001*/
                $tempto = mysqli_query($dbi, "SELECT max(uid) AS latest_uid FROM $user_prefix"._users."");
                list($latest_uid) = mysqli_fetch_array($tempto);
                if ($latest_uid == "-1") {
                    $latest_uid = 1;
                } else {
                    $latest_uid = $latest_uid+1;
                }
                $result = mysqli_query($dbi, "insert into $user_prefix"._users." values ('$latest_uid','','$uname','$email','','$user_avatar','$user_regdate','$user_occ','$user_from','$user_intrest','$user_sig','$user_viewemail','','$cryptpass',10,'',0,0,0,'',0,'','','$commentlimit',0,'$newsletter',0,0,0,1)");
                /* End code provided by Scorpion2001*/
                /*$result = mysqli_query($dbi, "insert into ".$user_prefix."_users values (NULL,'','$uname','$email','','$url','$user_avatar','$user_regdate','$user_icq','$user_occ','$user_from','$user_intrest','$user_sig','$user_viewemail','','$user_aim','$user_yim','$user_msnm','$cryptpass','10','','0','0','0','','0','','$Default_Theme','$commentlimit','0','$newsletter','0','0','0','1')");*/
                if (!$result) {
                    echo ""._ERROR."<br>";
                } else {
                    $message = ""._WELCOMETO." $sitename!\n\n"._YOUUSEDEMAIL." ($email) "._TOREGISTER." $sitename. "._FOLLOWINGMEM."\n\n"._UNICKNAME." $uname\n"._UPASSWORD." $makepass";
                    $subject=""._USERPASS4." $uname";
                    $from="$adminmail";
                    mail($email, $subject, $message, "From: $from\nX-Mailer: PHP/" . phpversion());
                    if ($sendaddmail == 1) {
                        $to = $adminmail;
                        $from = $email;
                        $subject = "$sitename - Member Added";
                        $message = "$uname has been added to $sitename.\n-----------------------------------------------------------\nDo not reply to this message!!";
                        mail($to, $subject, $message, "From: $from\nX-Mailer: PHP/" . phpversion());
                    }
                    title("$sitename: "._USERREGLOGIN."");
                    OpenTable();
                    echo "<center><b>"._ACCOUNTCREATED."</b><br><br>";
                    echo ""._YOUAREREGISTERED.""
                        ."<br><br>"
                        .""._YOUCANLOGIN."<br><br>"
                        .""._THANKSUSER." $sitename!</center>";
                    CloseTable();
                }
            } else {
                /* Start code provided by Scorpion2001*/
                $tempto = mysqli_query($dbi, "SELECT max(upid) AS latest_upid FROM $user_prefix"._users_pending."");
                list($latest_upid) = mysqli_fetch_array($tempto);
                if ($latest_upid == "-1") {
                    $latest_upid = 1;
                } else {
                    $latest_upid = $latest_upid+1;
                }
                $result = mysqli_query($dbi, "insert into $user_prefix"._users_pending." values ('$latest_upid','','$uname','$email','','$user_avatar','$user_regdate','$user_occ','$user_from','$user_intrest','$user_sig','$user_viewemail','','',10,'',0,0,0,'',0,'','','$commentlimit',0,'$newsletter',0,0,0,1)");
                /* End code provided by Scorpion2001*/
                /*$result = mysqli_query($dbi, "insert into ".$user_prefix."_users_pending values (NULL,'','$uname','$email','','$url','$user_avatar','$user_regdate','$user_icq','$user_occ','$user_from','$user_intrest','$user_sig','$user_viewemail','','$user_aim','$user_yim','$user_msnm','','10','','0','0','0','','0','','$Default_Theme','$commentlimit','0','$newsletter','0','0','0','1')");*/
                if (!$result) {
                    echo ""._ERROR."<br>";
                } else {
                    $message = ""._WELCOMETO." $sitename!\n\n"._YOUUSEDEMAIL." ($email) "._TOAPPLY." $sitename.\n\n"._YOUAREPENDING."\n\n"._FOLLOWINGMEM."\n\n"._UNICKNAME." $uname\n";
                    $subject=""._USERAPPL4." $uname";
                    $from="$adminmail";
                    mail($email, $subject, $message, "From: $from\nX-Mailer: PHP/" . phpversion());
                    /* SEND EMAIL TO ADMIN */
                        $to = $adminmail;
                        $from = $email;
                        $subject = "$sitename - Member Application";
                        $message = ""._UUSERNAME.": $uname\n"._EMAIL.": $email\n";
                        if (($user_avatar) || ($user_avatar!="")) $message .= ""._AVATAR.": $user_avatar\n";
                        if ($newsletter == 1) { $message .= ""._RECEIVENEWSLETTER.": "._YES."\n"; } else { $message .= ""._RECEIVENEWSLETTER.": "._NO."\n"; }
                        if (($user_from) || ($user_from!="")) $message .= ""._LOCATION.": $user_from\n";
                        if (($user_occ) || ($user_occ!="")) $message .= ""._OCCUPATION.": $user_occ\n";
                        if (($user_intrest) || ($user_intrest!="")) $message .= ""._INTERESTS.": $user_intrest\n";
                        if (($user_sig) || ($user_sig!="")) $message .= ""._SIGNATURE.": $user_sig\n";
                        $message .= "-----------------------------------------------------------\nDo not reply to this message!!";
                        mail($to, $subject, $message, "From: $from\nX-Mailer: PHP/" . phpversion());
                    /* CLOSE SEND EMAIL TO ADMIN */
                    title("$sitename: "._USERAPPLOGIN."");
                    OpenTable();
                    echo "<center><b>"._ACCOUNTRESERVED."</b><br><br>";
                    echo ""._YOUAREPENDING."<br><br>"
                        .""._THANKSAPPLYUSER." $sitename!</center>";
                    CloseTable();
                }
            }
        } else {
            echo "$stop";
        }
        include("footer.php");
    } else {
        include("header.php");
        OpenTable();
        echo "<font class=\"option\">"._ACTDISABLED."</font>";
        CloseTable();
        include("footer.php");
    }
}

function userinfo($uname, $bypass=0) {
    global $user, $cookie, $sitename, $prefix, $user_prefix, $dbi, $admin;
    $result = mysqli_query($dbi, "select uid, femail, bio, user_avatar, user_from, user_occ, user_intrest, user_sig, pass, newsletter from ".$user_prefix."_users where uname='$uname'");
    $userinfo = mysqli_fetch_array($result);
    if(!$bypass) cookiedecode($user);
    include("header.php");
    OpenTable();
    echo "<center>";
    if(($uname == $cookie[1]) AND ($userinfo[pass] == $cookie[2])) {
        echo "<font class=\"option\">$uname, "._WELCOMETO." $sitename!</font><br><br>";
        echo "<font class=\"content\">"._THISISYOURPAGE."</font></center><br><br>";
        nav();
    } else {
        echo "<font class=\"title\">"._PERSONALINFO.": $uname</font></center><br><br>";
    }
    if((sql_num_rows($result)==1) && ($userinfo[url] || $userinfo[femail] || $userinfo[bio] || $userinfo[user_avatar] || $userinfo[user_location] || $userinfo[user_occ] || $userinfo[user_intrest] || $userinfo[user_sig])) {
        echo "<center><font class=\"content\">";
        if ($userinfo[user_avatar]) echo "<img src=\"images/forum/avatar/$userinfo[user_avatar]\" alt=\"\"><br>\n";
        if ($userinfo[femail]) { echo ""._MYEMAIL." <a href=\"mailto:$userinfo[femail]\">$userinfo[femail]</a><br>\n"; }
        if ($userinfo[user_from]) echo ""._LOCATION.": $userinfo[user_from]<br>\n";
        if ($userinfo[user_occ]) echo ""._OCCUPATION.": $userinfo[user_occ]<br>\n";
        if ($userinfo[user_intrest]) echo ""._INTERESTS.": $userinfo[user_intrest]<br>\n";
        $userinfo[user_sig] = nl2br($userinfo[user_sig]);
        if ($userinfo[user_sig]) echo "<br><b>"._SIGNATURE.":</b><br>$userinfo[user_sig]<br>\n";
        if ($userinfo[bio]) { echo "<br><b>"._EXTRAINFO.":</b><br>$userinfo[bio]<br>\n"; }
        $result = mysqli_query($dbi, "select username from ".$prefix."_session where username='$uname'");
        list($username) = mysqli_fetch_row($result);
        if ($username == "") {
            $online = _OFFLINE;
        } else {
            $online = _ONLINE;
        }
        echo ""._USERSTATUS.": <b>$online</b><br>\n";
        if (($userinfo[newsletter] == 1) AND ($uname == $cookie[1]) AND ($userinfo[pass] == $cookie[2]) OR (is_admin($admin) AND ($userinfo[newsletter] == 1))) {
            echo "<i>"._SUBSCRIBED."</i><br>";
        } elseif (($userinfo[newsletter] == 0) AND ($uname == $cookie[1]) AND ($userinfo[pass] == $cookie[2]) OR (is_admin($admin) AND ($userinfo[newsletter] == 0))) {
            echo "<i>"._NOTSUBSCRIBED."</i><br>";
        }
        if (is_admin($admin)) {
            echo "[ <a href=\"admin.php?op=modifyUser&chng_uid=$userinfo[uid]\">"._EDITUSER."</a> ]<br>";
        }
        if (((is_user($user) AND $cookie[1] != $uname) OR is_admin($admin)) AND is_active("Private_Messages")) { echo "<br>[ <a href=\"modules.php?name=Private_Messages&amp;file=reply&amp;send=1&amp;uname=$uname\">"._USENDPRIVATEMSG." $uname</a> ]<br>\n"; }
        echo "</center></font>";
    } else {
        echo "<center>"._NOINFOFOR." $uname</center>";
    }
    CloseTable();
    echo "<br>";
    include("footer.php");
}

function main($user) {
    global $stop;
    if(!is_user($user)) {
        include("header.php");
        if ($stop) {
            OpenTable();
            echo "<center><font class=\"title\"><b>"._LOGININCOR."</b></font></center>\n";
            CloseTable();
            echo "<br>\n";
        } else {
            OpenTable();
            echo "<center><font class=\"title\"><b>"._USERREGLOGIN."</b></font></center>\n";
            CloseTable();
            echo "<br>\n";
        }
        if (!is_user($user)) {
            OpenTable();
            echo "<form action=\"modules.php?name=Your_Account\" method=\"post\">\n"
                ."<b>"._USERLOGIN."</b><br><br>\n"
                ."<table border=\"0\"><tr><td>\n"
                .""._NICKNAME.":</td><td><input type=\"text\" name=\"uname\" size=\"15\" maxlength=\"25\"></td></tr>\n"
                ."<tr><td>"._PASSWORD.":</td><td><input type=\"password\" name=\"pass\" size=\"15\" maxlength=\"20\"></td></tr></table>\n"
                ."<input type=\"hidden\" name=\"op\" value=\"login\">\n"
                ."<input type=\"submit\" value=\""._LOGIN."\"></form><br>\n\n"
                ."<center><font class=\"content\">[ <a href=\"modules.php?name=Your_Account&amp;op=pass_lost\">"._PASSWORDLOST."</a> | <a href=\"modules.php?name=Your_Account&amp;op=new_user\">"._REGNEWUSER."</a> ]</font></center>\n";
            CloseTable();
        }
        include("footer.php");
    } elseif (is_user($user)) {
        global $cookie;
        cookiedecode($user);
        userinfo($cookie[1]);
    }
}

function new_user() {
    global $blkusrreg, $requireadmin;
    if (!is_user($user)) {
        if ($blkusrreg==1) {
            include("header.php");
            if ($requireadmin == 0) {
                OpenTable();
                echo "<center><font class=\"title\"><b>"._USERREGLOGIN."</b></font></center>\n";
                CloseTable();
            } else {
                OpenTable();
                echo "<center><font class=\"title\"><b>"._USERAPPLOGIN."</b></font></center>\n";
                CloseTable();
            }
            echo "<br>\n";
            OpenTable();
            echo "<form name=\"Register\" action=\"modules.php?name=Your_Account\" method=\"post\">\n"
                ."<b>"._REGNEWUSER."</b><br><br>\n"
                ."<table cellpadding=\"0\" cellspacing=\"10\" border=\"0\">\n"
                ."<tr><td>"._NICKNAME.":</td><td><input type=\"text\" name=\"uname\" size=\"30\" maxlength=\"25\"> <font class=\"tiny\">"._REQUIRED."</font></td></tr>\n"
                ."<tr><td>"._EMAIL.":</td><td><input type=\"text\" name=\"email\" size=\"30\" maxlength=\"255\"> <font class=\"tiny\">"._REQUIRED."</font></td></tr>\n"
                ."<tr><td>"._WEBSITE.":</td><td><input type=\"text\" name=\"url\" size=\"30\" maxlength=\"255\"></td></tr>\n"
                ."<tr><td>"._AVATAR.":</td><td>[ <a href=\"modules.php?name=Your_Account&amp;op=avatarlist\">"._LIST."</a> ]&nbsp;&nbsp;\n"
                ."<select name=\"user_avatar\" onChange=\"showimage()\">\n";
            $direktori = "images/forum/avatar";
            $handle=opendir($direktori);
            while ($file = readdir($handle)) {
                $filelist[] = $file;
            }
            asort($filelist);
            while (list ($key, $file) = each ($filelist)) {
                if (ereg("blank", $file)) {
                    $sel = "selected";
                } else {
                    $sel = "";
                }
                ereg(".gif|.jpg",$file);
                if ($file == "." || $file == "..") {
                    $a=1;
                } else {
                    echo "<option value=\"$file\" $sel>$file</option>\n";
                }
            }
            echo "</select>&nbsp;&nbsp;<img src=\"images/forum/avatar/blank.gif\" name=\"avatar\" width=\"32\" height=\"32\" alt=\"\">\n"
                ."</td></tr>\n"
                ."<tr><td>"._RECEIVENEWSLETTER."</td><td>";
            echo "<input type=\"radio\" name=\"newsletter\" value=\"1\" checked>"._YES." &nbsp;"
                ."<input type=\"radio\" name=\"newsletter\" value=\"0\">"._NO."</td></tr>"
                ."<tr><td>"._LOCATION.":</td><td><input type=\"text\" name=\"user_from\" size=\"25\" maxlength=\"60\"></td></tr>\n"
                ."<tr><td>"._OCCUPATION.":</td><td><input type=\"text\" name=\"user_occ\" size=\"25\" maxlength=\"60\"></td></tr>\n"
                ."<tr><td>"._INTERESTS.":</td><td><input type=\"text\" name=\"user_intrest\" size=\"25\" maxlength=\"255\"></td></tr>\n"
                ."<tr><td>"._OPTION.":</td><td><INPUT TYPE=\"CHECKBOX\" NAME=\"user_viewemail\" VALUE=\"1\"> "._ALLOWEMAILVIEW."</td></tr>\n"
                ."<tr><td>"._SIGNATURE.":</td><td><TEXTAREA NAME=\"user_sig\" ROWS=\"6\" COLS=\"45\"></TEXTAREA></td></tr>\n"
                ."<tr><td>\n"
                ."<input type=\"hidden\" name=\"op\" value=\"new user\">\n"
                ."<input type=\"submit\" value=\""._NEWUSER."\">\n"
                ."</td></tr></table>\n"
                ."</form>\n"
                ."<br>\n";
                if ($requireadmin == 0) {
                echo ""._PASSWILLSEND."<br><br>\n"
                    .""._COOKIEWARNING."<br>\n"
                    .""._REGISTERNOW."<br>\n"
                    .""._WEDONTGIVE."<br><br>\n"
                    ."<center><font class=\"content\">[ <a href=\"modules.php?name=Your_Account\">"._USERLOGIN."</a> | <a href=\"modules.php?name=Your_Account&amp;op=pass_lost\">"._PASSWORDLOST."</a> ]</font></center>\n";
/*
*Marc Augier
*                      .""._ASREGUSER."<br>\n"
                    ."<ul>\n"
                    ."<li>"._ASREG1."\n"
                    ."<li>"._ASREG2."\n"
                    ."<li>"._ASREG3."\n"
                    ."<li>"._ASREG4."\n"
                    ."<li>"._ASREG5."\n"
                    ."<li>"._ASREG6."\n"
                    ."<li>"._ASREG7."\n"
                    ."</ul>\n"
*/
                } else {
                echo ""._IFAPRPASSWILLSEND."<br><br>\n"
                    .""._COOKIEWARNING."<br>\n"
                    .""._WEDONTGIVE."<br><br>\n"
                    ."<center><font class=\"content\">[ <a href=\"modules.php?name=Your_Account\">"._USERLOGIN."</a> | <a href=\"modules.php?name=Your_Account&amp;op=pass_lost\">"._PASSWORDLOST."</a> ]</font></center>\n";
                }
        CloseTable();
        include("footer.php");
        } else {
            include("header.php");
            OpenTable();
            echo "<font class=\"option\">"._ACTDISABLED."</font>";
            CloseTable();
            include("footer.php");
        }
    } elseif (is_user($user)) {
        global $cookie;
        cookiedecode($user);
        userinfo($cookie[1]);
    }
}

function pass_lost() {
    global $user;
    if (!is_user($user)) {
        include("header.php");
        OpenTable();
        echo "<center><font class=\"title\"><b>"._USERREGLOGIN."</b></font></center>\n";
        CloseTable();
        echo "<br>\n";
        OpenTable();
        echo "<b>"._PASSWORDLOST."</b><br><br>\n"
                .""._NOPROBLEM."<br><br>\n"
            ."<form action=\"modules.php?name=Your_Account\" method=\"post\">\n"
            ."<table border=\"0\"><tr><td>\n"
                .""._NICKNAME.":</td><td><input type=\"text\" name=\"uname\" size=\"15\" maxlength=\"25\"></td></tr>\n"
                ."<tr><td>"._CONFIRMATIONCODE.":</td><td><input type=\"text\" name=\"code\" size=\"11\" maxlength=\"10\"></td></tr></table><br>\n"
                ."<input type=\"hidden\" name=\"op\" value=\"mailpasswd\">\n"
                ."<input type=\"submit\" value=\""._SENDPASSWORD."\"></form><br>\n"
            ."<center><font class=\"content\">[ <a href=\"modules.php?name=Your_Account\">"._USERLOGIN."</a> | <a href=\"modules.php?name=Your_Account&amp;op=new_user\">"._REGNEWUSER."</a> ]</font></center>\n";
        CloseTable();
        include("footer.php");
    } elseif(is_user($user)) {
        global $cookie;
        cookiedecode($user);
        userinfo($cookie[1]);
    }
}

function logout() {
    global $prefix, $dbi, $user, $cookie;
    cookiedecode($user);
    $r_uname = $cookie[1];
    setcookie("user");
    setcookie("userColis");
    include("header.php");
    OpenTable();
    echo "<center><font class=\"option\"><b>"._YOUARELOGGEDOUT."</b></font></center>";
    CloseTable();
    $result = mysqli_query($dbi, "delete from ".$prefix."_session where username='$r_uname'");
    include("footer.php");
}

function mail_password($uname, $code) {
    global $sitename, $adminmail, $nukeurl, $user_prefix, $dbi;
    $result = mysqli_query($dbi, "select email, pass from ".$user_prefix."_users where (uname='$uname')");
    if(!$result) {
        OpenTable();
        echo "<center>"._SORRYNOUSERINFO."</center>";
        CloseTable();
    } else {
        $host_name = getenv("REMOTE_ADDR");
        list($email, $pass) = mysqli_fetch_row($result);
        $areyou = substr($pass, 0, 10);
        if ($areyou==$code) {
            $newpass=makePass();
            $message = ""._USERACCOUNT." '$uname' "._AT." $sitename "._HASTHISEMAIL."  "._AWEBUSERFROM." $host_name "._HASREQUESTED."\n\n"._YOURNEWPASSWORD." $newpass\n\n "._YOUCANCHANGE." $nukeurl/modules.php?name=Your_Account\n\n"._IFYOUDIDNOTASK."";
            $subject = ""._USERPASSWORD4." $uname";
            mail($email, $subject, $message, "From: $adminmail\nX-Mailer: PHP/" . phpversion());
            /* Next step: add the new password to the database */
            $cryptpass = md5($newpass);
            $query="update ".$user_prefix."_users set pass='$cryptpass' where uname='$uname'";
            if(!mysqli_query($dbi, $query)) {
                    echo ""._UPDATEFAILED."";
            }
            include("header.php");
            OpenTable();
            echo "<center>"._PASSWORD4." $uname "._MAILED."</center>";
            CloseTable();
            include("footer.php");
        /* If no Code, send it */
        } else {
            $result = mysqli_query($dbi, "select email, pass from ".$user_prefix."_users where (uname='$uname')");
            if(!$result) {
                echo "<center>"._SORRYNOUSERINFO."</center>";
            } else {
                $host_name = getenv("REMOTE_ADDR");
                list($email, $pass) = mysqli_fetch_row($result);
                $areyou = substr($pass, 0, 10);
                    $message = ""._USERACCOUNT." '$uname' "._AT." $sitename "._HASTHISEMAIL." "._AWEBUSERFROM." $host_name "._CODEREQUESTED."\n\n"._YOURCODEIS." $areyou \n\n"._WITHTHISCODE." $nukeurl/modules.php?name=Your_Account&op=pass_lost\n"._IFYOUDIDNOTASK2."";
                $subject=""._CODEFOR." $uname";
                mail($email, $subject, $message, "From: $adminmail\nX-Mailer: PHP/" . phpversion());
                include("header.php");
                echo "<center>"._CODEFOR." $uname "._MAILED."";
                include("footer.php");
                }
        }
    }
}

function docookie($setuid, $setuname, $setpass, $setstorynum, $setumode, $setuorder, $setthold, $setnoscore, $setublockon, $settheme, $setcommentmax) {
    $info = base64_encode("$setuid:$setuname:$setpass:$setstorynum:$setumode:$setuorder:$setthold:$setnoscore:$setublockon:$settheme:$setcommentmax");
    setcookie("user","$info",time()+15552000);
}

function login($uname, $pass) {
    global $setinfo, $user_prefix, $dbi;
    $result = mysqli_query($dbi, "select pass, uid, storynum, umode, uorder, thold, noscore, ublockon, theme, commentmax from ".$user_prefix."_users where uname='$uname'");
    $setinfo = mysqli_fetch_array($result);
    if ((sql_num_rows($result)==1) AND ($setinfo[uid] != 1) AND ($setinfo[pass] != "")) {
        $dbpass=$setinfo[pass];
        $non_crypt_pass = $pass;
          $old_crypt_pass = crypt($pass,substr($dbpass,0,2));
        $new_pass = md5($pass);
        if (($dbpass == $non_crypt_pass) OR ($dbpass == $old_crypt_pass)) {
            mysqli_query($dbi, "update ".$user_prefix."_users set pass='$new_pass' WHERE uname='$uname'");
            $result = mysqli_query($dbi, "select pass from ".$user_prefix."_users where uname='$uname'");
            list($dbpass) = mysqli_fetch_row($result);
        }
        if ($dbpass != $new_pass) {
            Header("Location: modules.php?name=Your_Account&stop=1");
                return;
        }
        docookie($setinfo[uid], $uname, $new_pass, $setinfo[storynum], $setinfo[umode], $setinfo[uorder], $setinfo[thold], $setinfo[noscore], $setinfo[ublockon], $setinfo[theme], $setinfo[commentmax]);
        Header("Location: modules.php?name=Your_Account&op=userinfo&bypass=1&uname=$uname");
    } else {
        Header("Location: modules.php?name=Your_Account&stop=1");
    }
}

function edituser() {
    global $user, $userinfo, $cookie;
    getusrinfo($user);
    if (($userinfo[uname] != $cookie[1]) AND ($userinfo[pass] != $cookie[2])) {
    include("header.php");
    OpenTable();
    echo "<center><font class=\"title\"><b>"._PERSONALINFO."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    nav();
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<table cellpadding=\"8\" border=\"0\"><tr><td>"
        ."<form name=\"Register\" action=\"modules.php?name=Your_Account\" method=\"post\">"
        ."<b>"._UREALNAME."</b> "._OPTIONAL."<br>"
        ."<input type=\"text\" name=\"realname\" value=\"$userinfo[name]\" size=\"30\" maxlength=\"60\"><br><br>"
        ."<b>"._UREALEMAIL."</b> "._REQUIRED."<br>"
        .""._EMAILNOTPUBLIC."<br>"
        ."<input type=\"text\" name=\"email\" value=\"$userinfo[email]\" size=\"30\" maxlength=\"255\"><br><br>"
        ."<b>"._UFAKEMAIL."</b> "._OPTIONAL."<br>"
        .""._EMAILPUBLIC."<br>"
        ."<input type=\"text\" name=\"femail\" value=\"$userinfo[femail]\" size=\"30\" maxlength=\"255\"><br><br>"
        ."<b>"._YOURAVATAR."</b> "._OPTIONAL."<br>[ <a href=\"modules.php?name=Your_Account&amp;op=avatarlist\">"._LIST."</a> ]&nbsp;&nbsp;"
        ."<select name=\"user_avatar\" onChange=\"showimage()\">"
        ."<option value=\"$userinfo[user_avatar]\">$userinfo[user_avatar]</option>";
    $direktori = "images/forum/avatar";
    $handle=opendir($direktori);
    while ($file = readdir($handle)) {
        $filelist[] = $file;
    }
    asort($filelist);
    while (list ($key, $file) = each ($filelist)) {
        ereg(".gif|.jpg",$file);
        if ($file == "." || $file == "..") {
            $a=1;
        } else {
            echo "<option value=\"$file\">$file</option>";
        }
    }
    echo "</select>&nbsp;&nbsp;<img src=\"images/forum/avatar/$userinfo[user_avatar]\" name=\"avatar\" width=\"32\" height=\"32\" alt=\"\">"
        ."<br><br>"
        ."<b>"._RECEIVENEWSLETTER."</b> &nbsp;&nbsp;";
    if ($userinfo[newsletter] == 1) {
        echo "<input type=\"radio\" name=\"newsletter\" value=\"1\" checked>"._YES." &nbsp;"
            ."<input type=\"radio\" name=\"newsletter\" value=\"0\">"._NO."";
    } elseif ($userinfo[newsletter] == 0) {
        echo "<input type=\"radio\" name=\"newsletter\" value=\"1\">"._YES." &nbsp;"
            ."<input type=\"radio\" name=\"newsletter\" value=\"0\" checked>"._NO."";
    }
    echo "<br><br>"
        ."<b>"._YLOCATION."</b> "._OPTIONAL."<br>"
        ."<input type=\"text\" name=\"user_from\" value=\"$userinfo[user_from]\" size=\"30\" maxlength=\"100\"><br><br>"
        ."<b>"._YOCCUPATION."</b> "._OPTIONAL."<br>"
        ."<input type=\"text\" name=\"user_occ\" value=\"$userinfo[user_occ]\" size=\"30\" maxlength=\"100\"><br><br>"
        ."<b>"._YINTERESTS."</b> "._OPTIONAL."<br>"
        ."<input type=\"text\" name=\"user_intrest\" value=\"$userinfo[user_intrest]\" size=\"30\" maxlength=\"100\"><br><br>"
        ."<b>"._SIGNATURE."</b> "._OPTIONAL."<br>"
        .""._255CHARMAX."<br>"
        ."<textarea wrap=\"virtual\" cols=\"50\" rows=\"5\" name=\"user_sig\">$userinfo[user_sig]</textarea><br>"
        ."<br><br>"
        ."<b>"._PASSWORD."</b> "._TYPENEWPASSWORD."<br>"
        ."<input type=\"password\" name=\"pass\" size=\"10\" maxlength=\"20\">&nbsp;&nbsp;<input type=\"password\" name=\"vpass\" size=\"10\" maxlength=\"20\">"
        ."<br><br>"
        ."<input type=\"hidden\" name=\"uname\" value=\"$userinfo[uname]\">"
        ."<input type=\"hidden\" name=\"uid\" value=\"$userinfo[uid]\">"
        ."<input type=\"hidden\" name=\"op\" value=\"saveuser\">"
        ."<input type=\"submit\" value=\""._SAVECHANGES."\">"
        ."</form></td></tr></table>";
    CloseTable();
    include("footer.php");
    } else {
        main($user);
    }
}


function saveuser($uid, $realname, $uname, $email, $femail, $pass, $vpass, $bio, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $attach, $newsletter) {
    global $user, $cookie, $userinfo, $EditedMessage, $user_prefix, $dbi;
    cookiedecode($user);
    $check = $cookie[1];
    $check2 = $cookie[2];
    $result = mysqli_query($dbi, "select uid, pass from ".$user_prefix."_users where uname='$check'");
    list($vuid, $ccpass) = mysqli_fetch_row($result);
    if (($uid == $vuid) AND ($check2 == $ccpass)) {
        if ((isset($pass)) && ("$pass" != "$vpass")) {
            echo "<center>"._PASSDIFFERENT."</center>";
        } elseif (($pass != "") && (strlen($pass) < $minpass)) {
            echo "<center>"._YOUPASSMUSTBE." <b>$minpass</b> "._CHARLONG."</center>";
        } else {
            if ($bio) { filter_text($bio); $bio = $EditedMessage; $bio = FixQuotes($bio); }
            if ($pass != "") {
                cookiedecode($user);
                mysqli_query($dbi, "LOCK TABLES ".$user_prefix."_users WRITE");
                $pass = md5($pass);
                mysqli_query($dbi, "update ".$user_prefix."_users set name='$realname', email='$email', femail='$femail', pass='$pass', bio='$bio' , user_avatar='$user_avatar', user_occ='$user_occ', user_from='$user_from', user_intrest='$user_intrest', user_sig='$user_sig', newsletter='$newsletter' where uid='$uid'");
                $result = mysqli_query($dbi, "select uid, uname, pass, storynum, umode, uorder, thold, noscore, ublockon, theme from ".$user_prefix."_users where uname='$uname' and pass='$pass'");
                if(sql_num_rows($result)==1) {
                    $userinfo = mysqli_fetch_array($result);
                    docookie($userinfo[uid],$userinfo[uname],$userinfo[pass],$userinfo[storynum],$userinfo[umode],$userinfo[uorder],$userinfo[thold],$userinfo[noscore],$userinfo[ublockon],$userinfo[theme],$userinfo[commentmax]);
                } else {
                    echo "<center>"._SOMETHINGWRONG."</center><br>";
                }
                mysqli_query($dbi, "UNLOCK TABLES");
            } else {
                mysqli_query($dbi, "update ".$user_prefix."_users set name='$realname', email='$email', femail='$femail', bio='$bio', user_avatar='$user_avatar', user_occ='$user_occ', user_from='$user_from', user_intrest='$user_intrest', user_sig='$user_sig', newsletter='$newsletter' where uid='$uid'");
            if ($attach) {
                $a = 1;
            } else {
                $a = 0;
            }
            }
            Header("Location: modules.php?name=Your_Account");
        }
    }
}

function edithome() {
    global $user, $userinfo, $Default_Theme, $cookie;
    getusrinfo($user);
    if (($userinfo[uname] != $cookie[1]) AND ($userinfo[pass] != $cookie[2])) {
    include("header.php");
    OpenTable();
    echo "<center><font class=\"title\"><b>"._HOMECONFIG."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    nav();
    CloseTable();
    echo "<br>";
    if($userinfo[theme]=="") {
        $userinfo[theme] = "$Default_Theme";
    }
    OpenTable();
    echo "<form action=\"modules.php?name=Your_Account\" method=\"post\">"
        ."<b>"._NEWSINHOME."</b> "._MAX127." "
        ."<input type=\"text\" name=\"storynum\" size=\"3\" maxlength=\"3\" value=\"$userinfo[storynum]\">"
        ."<br><br>";
    if ($userinfo[ublockon]==1) {
        $sel = "checked";
    }
    echo "<input type=\"checkbox\" name=\"ublockon\" $sel>"
        ." <b>"._ACTIVATEPERSONAL."</b>"
        ."<br>"._CHECKTHISOPTION.""
        ."<br>"._YOUCANUSEHTML."<br>"
        ."<textarea cols=\"55\" rows=\"5\" name=\"ublock\">$userinfo[ublock]</textarea>"
        ."<br><br>"
        ."<input type=\"hidden\" name=\"uname\" value=\"$userinfo[uname]\">"
        ."<input type=\"hidden\" name=\"uid\" value=\"$userinfo[uid]\">"
        ."<input type=\"hidden\" name=\"op\" value=\"savehome\">"
        ."<input type=\"submit\" value=\""._SAVECHANGES."\">"
        ."</form>";
    CloseTable();
    include("footer.php");
    } else {
        main($user);
    }
}

function chgtheme() {
    global $user, $userinfo, $Default_Theme, $cookie, $allowusertheme;
    if ($allowusertheme==1) {
    getusrinfo($user);
    if (($userinfo[uname] != $cookie[1]) AND ($userinfo[pass] != $cookie[2])) {
    include("header.php");
    OpenTable();
    echo "<center><font class=\"title\"><b>"._THEMESELECTION."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    nav();
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center>"
        ."<form action=\"modules.php?name=Your_Account\" method=\"post\">"
        ."<b>"._SELECTTHEME."</b><br>"
        ."<select name=\"theme\">";
    $handle=opendir('themes');
    while ($file = readdir($handle)) {
        if ( (!ereg("[.]",$file)) ) {
                $themelist .= "$file ";
        }
    }
    closedir($handle);
    $themelist = explode(" ", $themelist);
    sort($themelist);
    for ($i=0; $i < sizeof($themelist); $i++) {
            if($themelist[$i]!="") {
                echo "<option value=\"$themelist[$i]\" ";
            if((($userinfo[theme]=="") && ($themelist[$i]=="$Default_Theme")) || ($userinfo[theme]==$themelist[$i])) echo "selected";
            echo ">$themelist[$i]\n";
        }
    }
    if($userinfo[theme]=="") $userinfo[theme] = "$Default_Theme";
    echo "</select><br>"
        .""._THEMETEXT1."<br>"
        .""._THEMETEXT2."<br>"
        .""._THEMETEXT3."<br><br>"
        ."<input type=\"hidden\" name=\"uid\" value=\"$userinfo[uid]\">"
        ."<input type=\"hidden\" name=\"op\" value=\"savetheme\">"
        ."<input type=\"submit\" value=\""._SAVECHANGES."\">"
        ."</form>";
    CloseTable();
    include("footer.php");
    } else {
        main($user);
    }
    } else {
        include("header.php");
        OpenTable();
        echo "<font class=\"option\">"._ACTDISABLED."</font>";
        CloseTable();
        include("footer.php");
    }
}


function savehome($uid, $uname, $storynum, $ublockon, $ublock) {
    global $user, $cookie, $userinfo, $user_prefix, $dbi;
    cookiedecode($user);
    $check = $cookie[1];
    $check2 = $cookie[2];
    $result = mysqli_query($dbi, "select uid, pass from ".$user_prefix."_users where uname='$check'");
    list($vuid, $ccpass) = mysqli_fetch_row($result);
    if (($uid == $vuid) AND ($check2 == $ccpass)) {
        if(isset($ublockon)) $ublockon=1; else $ublockon=0;
        $ublock = FixQuotes($ublock);
        mysqli_query($dbi, "update ".$user_prefix."_users set storynum='$storynum', ublockon='$ublockon', ublock='$ublock' where uid=$uid");
        getusrinfo($user);
        docookie($userinfo[uid],$userinfo[uname],$userinfo[pass],$userinfo[storynum],$userinfo[umode],$userinfo[uorder],$userinfo[thold],$userinfo[noscore],$userinfo[ublockon],$userinfo[theme],$userinfo[commentmax]);
        Header("Location: modules.php?name=Your_Account");
    }
}

function savetheme($uid, $theme) {
    global $user, $cookie, $userinfo, $user_prefix, $dbi, $allowusertheme;
    if ($allowusertheme==1) {
    cookiedecode($user);
    $check = $cookie[1];
    $check2 = $cookie[2];
    $result = mysqli_query($dbi, "select uid, pass from ".$user_prefix."_users where uname='$check'");
    list($vuid, $ccpass) = mysqli_fetch_row($result);
    if (($uid == $vuid) AND ($check2 == $ccpass)) {
        mysqli_query($dbi, "update ".$user_prefix."_users set theme='$theme' where uid=$uid");
        getusrinfo($user);
        docookie($userinfo[uid],$userinfo[uname],$userinfo[pass],$userinfo[storynum],$userinfo[umode],$userinfo[uorder],$userinfo[thold],$userinfo[noscore],$userinfo[ublockon],$userinfo[theme],$userinfo[commentmax]);
        Header("Location: modules.php?name=Your_Account&theme=$theme");
    }
    } else {
        include("header.php");
        OpenTable();
        echo "<font class=\"option\">"._ACTDISABLED."</font>";
        CloseTable();
        include("footer.php");
    }
}

function editcomm() {
    global $user, $userinfo, $cookie;
    getusrinfo($user);
    if (($userinfo[uname] != $cookie[1]) AND ($userinfo[pass] != $cookie[2])) {
    include("header.php");
    OpenTable();
    echo "<center><font class=\"title\"><b>"._COMMENTSCONFIG."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    nav();
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<table cellpadding=\"8\" border=\"0\"><tr><td>"
        ."<form action=\"modules.php?name=Your_Account\" method=\"post\">"
        ."<b>"._DISPLAYMODE."</b>"
        ."<select name=\"umode\">";
    ?>
    <option value="nocomments" <?php if ($userinfo[umode] == 'nocomments') { echo "selected"; } ?>><?php echo _NOCOMMENTS ?>
    <option value="nested" <?php if ($userinfo[umode] == 'nested') { echo "selected"; } ?>><?php echo _NESTED ?>
    <option value="flat" <?php if ($userinfo[umode] == 'flat') { echo "selected"; } ?>><?php echo _FLAT ?>
    <option value="thread" <?php if (!isset($userinfo[umode]) || ($userinfo[umode]=="") || $userinfo[umode]=='thread') { echo "selected"; } ?>><?php echo _THREAD ?>
    </select>
    <br><br>
    <b><?php echo _SORTORDER ?></b>
    <select name="uorder">
    <option value="0" <?php if (!$userinfo[uorder]) { echo "selected"; } ?>><?php echo _OLDEST ?>
    <option value="1" <?php if ($userinfo[uorder]==1) { echo "selected"; } ?>><?php echo _NEWEST ?>
    <option value="2" <?php if ($userinfo[uorder]==2) { echo "selected"; } ?>><?php echo _HIGHEST ?>
    </select>
    <br><br>
    <b><?php echo _THRESHOLD ?></b>
    <?php echo _COMMENTSWILLIGNORED ?><br>
    <select name="thold">
    <option value="-1" <?php if ($userinfo[thold]==-1) { echo "selected"; } ?>>-1: <?php echo _UNCUT ?>
    <option value="0" <?php if ($userinfo[thold]==0) { echo "selected"; } ?>>0: <?php echo _EVERYTHING ?>
    <option value="1" <?php if ($userinfo[thold]==1) { echo "selected"; } ?>>1: <?php echo _FILTERMOSTANON ?>
    <option value="2" <?php if ($userinfo[thold]==2) { echo "selected"; } ?>>2: <?php echo _USCORE ?> +2
    <option value="3" <?php if ($userinfo[thold]==3) { echo "selected"; } ?>>3: <?php echo _USCORE ?> +3
    <option value="4" <?php if ($userinfo[thold]==4) { echo "selected"; } ?>>4: <?php echo _USCORE ?> +4
    <option value="5" <?php if ($userinfo[thold]==5) { echo "selected"; } ?>>5: <?php echo _USCORE ?> +5
    </select><br>
    <i><?php echo _SCORENOTE ?></i>
    <br><br>
    <INPUT type="checkbox" name="noscore" <?php if ($userinfo[noscore]==1) { echo "checked"; } ?>><b> <?php echo _NOSCORES ?></b> <?php echo _HIDDESCORES ?>
    <br><br>
    <b><?php echo _MAXCOMMENT ?></b> <?php echo _TRUNCATES ?><br>
    <input type="text" name="commentmax" value="<?php echo $userinfo[commentmax] ?>" size=11 maxlength=11> <?php echo _BYTESNOTE ?>
    <br><br>
    <input type="hidden" name="uname" value="<?php echo"$userinfo[uname]"; ?>">
    <input type="hidden" name="uid" value="<?php echo"$userinfo[uid]"; ?>">
    <input type="hidden" name="op" value="savecomm">
    <input type="submit" value="<?php echo _SAVECHANGES ?>">
    </form></td></tr></table>
    <?php
    CloseTable();
    echo "<br><br>";
    include("footer.php");
    } else {
        main($user);
    }
}

function savecomm($uid, $uname, $umode, $uorder, $thold, $noscore, $commentmax) {
    global $user, $cookie, $userinfo, $user_prefix, $dbi;
    cookiedecode($user);
    $check = $cookie[1];
    $check2 = $cookie[2];
    $result = mysqli_query($dbi, "select uid, pass from ".$user_prefix."_users where uname='$check'");
    list($vuid, $ccpass) = mysqli_fetch_row($result);
    if (($uid == $vuid) AND ($check2 == $ccpass)) {
        if(isset($noscore)) $noscore=1; else $noscore=0;
        mysqli_query($dbi, "update ".$user_prefix."_users set umode='$umode', uorder='$uorder', thold='$thold', noscore='$noscore', commentmax='$commentmax' where uid=$uid");
        getusrinfo($user);
        docookie($userinfo[uid],$userinfo[uname],$userinfo[pass],$userinfo[storynum],$userinfo[umode],$userinfo[uorder],$userinfo[thold],$userinfo[noscore],$userinfo[ublockon],$userinfo[theme],$userinfo[commentmax]);
        Header("Location: modules.php?name=Your_Account");
    }
}

function avatarlist() {
    include("header.php");
    Opentable();
    echo "<center><font class=\"option\"><b>"._AVAILABLEAVATARS."</b></font><br><br>";
    $direktori = "/var/www/html/images/forum/avatar/";
    $handle=opendir($direktori);
    while ($file = readdir($handle)) {
        $filelist[] = $file;
    }
    asort($filelist);
    $temcount = 1;
    while (list ($key, $file) = each ($filelist)) {
        if (ereg(".gif",$file)) {
            if ($file == "." || $file == "..") {
                $a=1;
            } else {
                echo "<img src=\"images/forum/avatar/$file\" border=\"0\" width=\"32\" height=\"32\" alt=\"$file\" hspace=\"10\" vspace=\"10\">";
            }
            if ($temcount == 10) {
                echo "<br>";
                $temcount -= 10;
            }
            $temcount ++;
        }
    }
    echo "<br><br><br>"
        .""._GOBACK.""
        ."</center>";
    CloseTable();
    include("footer.php");
}

switch($op) {

    case "delconfirm":
        delconfirm($uid, $code);
        break;

    case "delete":
        delete($uname);
        break;

    case "logout":
        logout();
        break;

    case "lost_pass":
        lost_pass();
        break;

    case "new user":
        confirmNewUser($uname, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $newsletter);
        break;

    case "finish":
        finishNewUser($uname, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $newsletter);
        break;

    case "mailpasswd":
        mail_password($uname, $code);
        break;

    case "userinfo":
        userinfo($uname, $bypass);
        break;

    case "login":
        login($uname, $pass);
        break;

    case "edituser":
        edituser();
        break;

    case "saveuser":
        saveuser($uid, $realname, $uname, $email, $femail, $pass, $vpass, $bio, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $attach, $newsletter);
        break;

    case "edithome":
        edithome();
        break;

    case "chgtheme":
        chgtheme();
        break;

    case "savehome":
        savehome($uid, $uname, $storynum, $ublockon, $ublock);
        break;

    case "savetheme":
        savetheme($uid, $theme);
        break;

    case "avatarlist":
        avatarlist();
        break;

    case "editcomm":
        editcomm();
        break;

    case "savecomm":
        savecomm($uid, $uname, $umode, $uorder, $thold, $noscore, $commentmax);
        break;

    case "pass_lost":
        pass_lost();
        break;

    case "new_user":
        new_user();
        break;

    default:
        main($user);
        break;

}

?>