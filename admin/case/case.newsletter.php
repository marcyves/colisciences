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

switch($op) {

    case "newsletter":
    case "newsletter_send":
    case "newsletter_sent":
    case "massmail_send":
    case "massmail_sent":
    case "check_type":
    include("admin/modules/newsletter.php");
    break;

}

?>