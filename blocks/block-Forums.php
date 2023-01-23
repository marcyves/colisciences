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

global $prefix, $dbi, $sitename;

$result = mysqli_query($dbi, "SELECT topic_id, topic_title, forum_id FROM ".$prefix."_bbtopics ORDER BY topic_time DESC LIMIT 10");
$content = "<br>";
while(list($topic_id, $topic_title, $forum_id) = mysqli_fetch_row($result)) {
    $res = mysqli_query($dbi, "SELECT image FROM ".$prefix."_posts where topic_id='$topic_id'");
    list ($image) = mysqli_fetch_row($res);
    if (file_exists("images/forum/subject/$image")) {
	$content .= "<img src=\"images/forum/subject/$image\" border=\"0\" alt=\"\" width=\"15\" height=\"15\">&nbsp;<a href=\"modules.php?mop=modload&name=Forum&amp;file=viewtopic&amp;topic=$topic_id&amp;forum=$forum_id\">$topic_title</a><br>";
    } else {
	$content .= "<img src=\"images/forum/subject/default.gif\" border=\"0\" alt=\"\" width=\"15\" height=\"15\">&nbsp;<a href=\"modules.php?mop=modload&name=Forum&amp;file=viewtopic&amp;topic=$topic_id&amp;forum=$forum_id\">$topic_title</a><br>";
    }
}
$content .= "<br><center>[ <a href=\"modules.php?name=Forum\">$sitename "._BBFORUMS."</a> ]</center>";

?>