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

if ((isset($aid)) && (isset($pwd)) && ($op == "login")) {
    if($aid!="" AND $pwd!="") {
	$pwd = md5($pwd);
	$result=mysqli_query($dbi, "select pwd, admlanguage from ".$prefix."_authors where aid='$aid'");
	list($pass, $admlanguage)=mysqli_fetch_row($result);
	if($pass == $pwd) {
	    $admin = base64_encode("$aid:$pwd:$admlanguage");
	    setcookie("admin","$admin",time()+2592000);
	    unset($op);
	}
    }
}

$admintest = 0;

if(isset($admin)) {
  $admin = base64_decode($admin);
  $admin = explode(":", $admin);
  $aid = "$admin[0]";
  $pwd = "$admin[1]";
  $admlanguage = "$admin[2]";
  if ($aid=="" || $pwd=="") {
    $admintest=0;
    echo "<html>\n";
    echo "<title>INTRUDER ALERT!!!</title>\n";
    echo "<body bgcolor=\"#FFFFFF\" text=\"#000000\">\n\n<br>\n\n";
    echo "<center><img src=\"images/eyes.gif\" border=\"0\"><br><br>\n";
    echo "<font face=\"Verdana\" size=\"+4\"><b>Get Out!</b></font></center>\n";
    echo "</body>\n";
    echo "</html>\n";
    exit;
  }
  $result=mysqli_query($dbi, "select pwd from ".$prefix."_authors where aid='$aid'");
  if(!$result) {
        echo "Selection from database failed!";
        exit;
  } else {
    list($pass)=mysqli_fetch_row($result);
    if($pass == $pwd && $pass != "") {
        $admintest = 1;
    }
  }
}
?>