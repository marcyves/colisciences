<?php

########################################################################
# PHP-Nuke Block: Total Hits v0.1                                      #
#                                                                      #
# Copyright (c) 2001 by C. Verhoef (cverhoef@gmx.net)                  #
#                                                                      #
########################################################################
# This program is free software. You can redistribute it and/or modify #
# it under the terms of the GNU General Public License as published by #
# the Free Software Foundation; either version 2 of the License.       # 
######################################################################## 

if (eregi("block-Who_is_Online.php", $PHP_SELF)) {
    Header("Location: index.php");
    die();
}

global $user, $cookie, $prefix, $dbi, $user_prefix;

cookiedecode($user);
$ip = getenv("REMOTE_ADDR");
$username = $cookie[1];
if (!isset($username)) {
    $username = "$ip";
    $guest = 1;
}

$result = mysqli_query($dbi, "SELECT username FROM ".$prefix."_session where guest=1");
$guest_online_num = sql_num_rows($result);

$result = mysqli_query($dbi, "SELECT username FROM ".$prefix."_session where guest=0");
$member_online_num = sql_num_rows($result);

$who_online_num = $guest_online_num + $member_online_num;
$who_online = "<center><font class=\"content\">"._CURRENTLY." $guest_online_num "._GUESTS." $member_online_num "._MEMBERS."<br>";
$result = mysqli_query($dbi, "select title from ".$prefix."_blocks where bkey='online'");
list($title) = mysqli_fetch_row($result);
$content = "$who_online";
if (is_user($user)) {
    $content .= "<br>"._YOUARELOGGED." <b>$username</b>.<br>";
    if (is_active("Private_Messages")) {
	$result = mysqli_query($dbi, "select uid from ".$user_prefix."_users where uname='$username'");
	list($uid) = mysqli_fetch_row($result);
	$result2 = mysqli_query($dbi, "select to_userid from ".$prefix."_priv_msgs where to_userid='$uid'");
	$numrow = sql_num_rows($result2);
	$content .= ""._YOUHAVE." <a href=\"modules.php?name=Private_Messages\"><b>$numrow</b></a> "._PRIVATEMSG."";
    }
    $content .= "</font></center>";
} else {
    $content .= "<br>"._YOUAREANON."</font></center>";
}

?>