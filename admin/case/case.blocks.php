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

switch($op) {

    case "BlocksAdmin":
    case "BlocksAdd":
    case "BlocksEdit":
    case "BlocksEditSave":
    case "ChangeStatus":
    case "BlocksDelete":
    case "BlockOrder":    
    case "HeadlinesDel":
    case "HeadlinesAdd":
    case "HeadlinesSave":
    case "HeadlinesAdmin":
    case "HeadlinesEdit":
    case "fixweight":
    case "block_show":
    include("admin/modules/blocks.php");
    break;

}

?>