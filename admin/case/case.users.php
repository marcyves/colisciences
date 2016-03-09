<?php

/********************************************************/
/* Tweak_Your_Account for PHP-Nuke 5.5.0                */
/* By: NukeScripts Network (webmaster@nukescripts.com)  */
/* http://www.nukescripts.com                           */
/*                                                      */
/********************************************************/

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }

switch($op) {

    case "mod_users":
    case "modifyUser":
    case "updateUser":
    case "delUser":
    case "delUserConf":
    case "addUser":
    case "approveUser":
    case "approveUserConf":
    case "detailsUser":
    case "denyUser":
    case "denyUserConf":
    include("admin/modules/users.php");
    break;

}

?>