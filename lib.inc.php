<?php

/******************************************************************************/
/*                                                                            */
/* index.php - phpCB log-in / title page                                      */
/*                                                                            */
/******************************************************************************/
/*                                                                            */
/* Requirements: PHP, MySQL and web-browser                                   */
/*                                                                            */
/* Author: Marc Augier                                                        */
/*         <marc.augier@colisciences.fr>                                     */
/*                                                                            */
/* Created: 29 March 2002                                                     */
/*                                                                            */
/* Copyright (c) 2002 LCP - MEDIATEC                                          */
/*                                                                            */
/* This file is part of phpCB (http://colisciences/)                          */
/*                                                                            */
/******************************************************************************/

/*----------------------------------------------------------------------------*/
/* Function library                                                           */
/*----------------------------------------------------------------------------*/

/*                                                                              */
/* check to see if this person has access to admin this module                  */

function checkAdmin($where) {
    global $char;

    $thing = $char['admin'];
    $where = strtolower($where);

    if ($where == 'full' && $thing == 1)
        return true;

    if ($thing == 1)
        return true;

    if ($thing >= 4 && $where == 'faq') { // FAQ admin
        return true;
    }
    /*  you have to subtract in case we have this circumstance:
        say an admin value of 8 was a guild admin, and we want
        to know if this person is a forum admin.
        if we weren't subtracting, we'd get to the if above and
        it'd be false, but the if below would be true, and it shouldn't be.
    */
    $thing -= 4;
    if ($thing >= 2 && $where == 'forum') { // forum admin
        return true;
    }
    return false;
}


/*                                                                              */
/* parse the commands in the chat if they start with a slash                  */

function parseCommands($what) {
    // remove the slash
    if (substr($what, 0, 1) == "/")
        $what = substr($what, 1);
    // tokenize the string by spaces
    $str = explode(" ", $what);
    if ($str[0]) {
        // check all the first words
        switch ($str[0]) {
            case 'give': // give <name> [number] <object>
                if (!empty($str[1]) && !empty($str[2])) {
                    $name = $str[1];
                    if (empty($str[3]))
                        $object = $str[2];
                    else {
                        $number = $str[2];
                        $object = $str[3];
                    }
                } else { // invalid command

                }
                break;
        } // switch $str[0]
    } // if $str[0]
}


/*                                                                            */
/* string gameDate - A time formatting function similar to PHP's Date()       */

function gameDate($format, $timestamp = -1)
{

    global $epoch;

    if ($timestamp == -1) {
//        echo 'is -1<br>';
        $now = time() - $epoch;
//        echo 'now:  ' . $now . '<br>';
    } else {
//        echo 'is not -1<br>';
        $now = $timestamp - $epoch;
//        echo 'timestamp:  ' . $timestamp . '<br>';
//        echo 'epoch:  ' . $epoch . '<br>';
//        echo 'now:  ' . $now . '<br>';
//        echo 'timestamp - epoch:  ' . ($timestamp - $epoch) . '<br>';
    }

    if ($format == '') {
        return '';
    }

    // cheat sheet

    // seconds in a 336 day year:        29030400
    // seconds in a 28 day month:        2419200
    // seconds in a day:                86400

    // 1 game year   == 12 real weeks
    // 1 game month  == 1 real week
    // 1 game day    == 6 real hours
    // 1 game hour   == 15 real minutes
    // 1 game minute == 15 real seconds

    // 1 real day    == 4 game days
    // 1 real hour   == 4 game hours
    // 1 real minute ==


    $year = 60*60*24*7*12;    // 7257600
    $month = 60*60*24*7;    //  604800
    $day = 60*60*6;            //   21600
    $hour = 60*15;            //     900
    $minute = 15;           //      15

    $years = 1;
    while ($now >= $year) {
        $years++;
        $now -= $year;
    }
    $months = 0;
    while ($now >= $month) {
        $months++;
        $now -= $month;
    }
    $days = 0;
    while ($now >= $day) {
        $days++;
        $now -= $day;
    }
    $hours = 0;
    while ($now >= $hour) {
        $hours++;
        $now -= $hour;
    }
    $minutes = 0;
    while ($now >= $minute) {
        $minutes++;
        $now -= $minute;
    }

    $str = "";

    for ($i = 0; $i < strlen($format); ++$i) {
        switch (substr($format, $i, 1)) {
            case 'a':                // am or pm
                if ($hours > 12)
                    $str .= 'pm';
                else
                    $str .= 'am';
                break;
            case 'A':                // AM or PM
                if ($hours > 12)
                    $str .= 'PM';
                else
                    $str .= 'AM';
                break;
            case 'B':                // Swatch internet time (whatever this is...)
                break;
            case 'd':                // day of the month, "01" to "28"
                $temp = ($days % 28) + 1;
                if ($temp < 10)
                    $str .= "0" . $temp;
                else
                    $str .= $temp;
                break;
            case 'D':                // day of the week, textual, 3 letters, "Fri"
                $temp = $days % 7;
                if ($temp == 0)
                    $str .= "Sun";
                elseif ($temp == 1)
                    $str .= "Mon";
                elseif ($temp == 2)
                    $str .= "Tue";
                elseif ($temp == 3)
                    $str .= "Wed";
                elseif ($temp == 4)
                    $str .= "Thu";
                elseif ($temp == 5)
                    $str .= "Fri";
                elseif ($temp == 6)
                    $str .= "Sat";
                break;
            case 'F':                // month, textual, long "January"
                $temp = $months % 12;
                if ($temp == 0)
                    $str .= "January";
                elseif ($temp == 1)
                    $str .= "February";
                elseif ($temp == 2)
                    $str .= "March";
                elseif ($temp == 3)
                    $str .= "April";
                elseif ($temp == 4)
                    $str .= "May";
                elseif ($temp == 5)
                    $str .= "June";
                elseif ($temp == 6)
                    $str .= "July";
                elseif ($temp == 7)
                    $str .= "August";
                elseif ($temp == 8)
                    $str .= "September";
                elseif ($temp == 9)
                    $str .= "October";
                elseif ($temp == 10)
                    $str .= "November";
                elseif ($temp == 11)
                    $str .= "December";
                break;
            case 'g':                // hour, 12 hour format, no leading zeros "1" to "12"
                $temp = $hours % 12;
                if ($temp == 0)
                    $str .= "12";
                else
                    $str .= $temp;
                break;
            case 'G':                // hour, 24 hour formaat, no leading zeros "0" to "23"
                $str .= $hours % 24;
                break;
            case 'h':                // hour 12 hour format, "01" to "12"
                $temp = $hours % 12;
                if ($temp == 0)
                    $temp = 12;
                if ($temp < 10)
                    $str .= "0" . $temp;
                else
                    $str .= $temp;
                break;
            case 'H':                // hour 23 hour format, "00" to "23"
                $temp = ($hours % 24);
                if ($temp < 10)
                    $str .= "0" . $temp;
                else
                    $str .= $temp;
                break;
            case 'i':                // minutes "00" to "59"
                $temp = $minutes % 60;
                if ($temp < 10)
                    $str .= "0" . $temp;
                else
                    $str .= $temp;
                break;
            case 'j':                // day of the month without leading zeros "1" to "28"
                $str .= ($days % 28) + 1;
                break;
            case 'l':                // day of the week, textual, long "Friday"
                $temp = $days % 7;
                if ($temp == 0)
                    $str .= "Sunday";
                elseif ($temp == 1)
                    $str .= "Monday";
                elseif ($temp == 2)
                    $str .= "Tuesday";
                elseif ($temp == 3)
                    $str .= "Wednesday";
                elseif ($temp == 4)
                    $str .= "Thursday";
                elseif ($temp == 5)
                    $str .= "Friday";
                elseif ($temp == 6)
                    $str .= "Saturday";
                break;
            case 'm':                // month, "01" to "12"
                $temp = ($months % 12) + 1;
                if ($temp < 10)
                    $str .= "0" . $temp;
                else
                    $str .= $temp;
                break;
            case 'M':
                $temp = $months % 12;
                if ($temp == 0)
                    $str .= "Jan";
                elseif ($temp == 1)
                    $str .= "Feb";
                elseif ($temp == 2)
                    $str .= "Mar";
                elseif ($temp == 3)
                    $str .= "Apr";
                elseif ($temp == 4)
                    $str .= "May";
                elseif ($temp == 5)
                    $str .= "Jun";
                elseif ($temp == 6)
                    $str .= "Jul";
                elseif ($temp == 7)
                    $str .= "Aug";
                elseif ($temp == 8)
                    $str .= "Sep";
                elseif ($temp == 9)
                    $str .= "Oct";
                elseif ($temp == 10)
                    $str .= "Nov";
                elseif ($temp == 11)
                    $str .= "Dec";
                break;
            case 'n':                // month without leading zeros "1" to "12"
                $str .= ($months % 12) + 1;
                break;
            case 'r':                // RFP 822 formatted date;  "Thu 21 Dec 2000 16:01:07"
                break;
            case 'S':                // english ordinal suffix, textual, 2 characters "th" "nd"
/*                if ($str != "") {
                    $thing = substr($str, -1);
                    if (ctype_digit($thing)) {
                        switch ($thing) {
                            case "1":
                                break;
                            case "2":
                                break;
                            case "3":
                                break;
                            default:
                                break;
                        }
                    }
                } */
                break;
            case 'U':                // seconds since the epoch
                $str .= (time() - $epoch);
                break;
            case 'w':                // day of the week, numeric, "0" (Sunday) to "6" (Saturday)
                $str .= $days % 7;
                break;
            case 'Y':                // year, 4 digits, 1999
                // instead of the hard coded 1000, make it a $yearStart variable from config.inc.php
                // so the admin can set the starting year
                $str .= ($years + 1000);
                break;
            case 'y':                // year, 2 digits, 99
                if ($years < 10)
                    $str .= "0" . $years;
                else
                    $str .= $years;
                break;
            case 'z':                // day of the year "0" to "335"
                $str .= ($months * 28) + $days;
                break;
            default:
                $str .= substr($format, $i, 1);
                break;
        } // switch
        //echo $i . ":  |" . substr($format, $i, 1) . "|  " . $str . "<br>";
    } // for
    return $str;
}

/* END of Game function library                                               */
/*----------------------------------------------------------------------------*/




/*----------------------------------------------------------------------------*/
/* System - Function library not directly related to the game code            */
/*----------------------------------------------------------------------------*/


/*                                                                            */
/* sess_open    -                                                             */
/* sess_read    - A set of functions that enable MySQL storage module for     */
/* sess_write   - PHP session library. Functions are registered by calling:   */
/* sess_destroy - session_set_save_handler()                                  */
/* sess_gc      -                                                             */

function sess_open($save_path, $sess_name)
{

    global $db_name, $db_host, $db_user, $db_pass;

    $link = mysql_pconnect($db_host, $db_user, $db_pass) or die(mysql_error());
    mysql_select_db($db_name) or die(mysql_error());

    return true;

}


function sess_read($sess_id)
{

    global $db_name, $TablePrefix;

    $result = mysql_query("SELECT data FROM " . $TablePrefix . "_sessions WHERE id = '$sess_id'") or die(mysql_error());

    if($result->num_rows == 0)
    {
        return '';
    } else {
        $row = mysqli_fetch_array($result);
        mysqli_free_result($result);
        return $row["data"];
    }

}


function sess_write($sess_id, $val)
{

    global $db_name, $TablePrefix;

    $result = mysql_query("REPLACE INTO " . $TablePrefix . "_sessions VALUES ('$sess_id', '$val', null)") or die(mysql_error());

    return true;

}


function sess_destroy($sess_id)
{

    global $db_name, $TablePrefix;

    $result = mysql_query("DELETE FROM " . $TablePrefix . "_sessions WHERE id = '$sess_id'") or die(mysql_error());

    return true;

}


function sess_gc($max_lifetime)
{

    global $db_name, $TablePrefix;

    $expiry = time() - $max_lifetime;

    $result = mysql_query("DELETE FROM " . $TablePrefix . "_sessions WHERE UNIX_TIMESTAMP(t_stamp) < $expiry") or die(mysql_error());

    return true;
}


/*                                                                            */
/* validateSession - Validate current session and updates the database        */

function validateSession()
{

    session_set_save_handler('sess_open', '', 'sess_read', 'sess_write', 'sess_destroy', 'sess_gc');            session_name('s');
    session_name('s');
    session_start();

    global $user_name, $user_pass, $user_time, $TablePrefix, $char;

    if (empty($user_name) || empty($user_pass) || empty($user_time) || ($user_time < (time() - 600))) {
        // Session doesn't exist or expired -> Redirects back to login page
        header('Location: index.php');
        exit;
    } else {
        // Continues with session and updates database
        $user_time = time();
        db_connect();
        $result = mysql_query("UPDATE $TablePrefix" . "_user SET online='$user_time' WHERE user_name='$user_name' AND user_pass='$user_pass'");
        $result = mysql_query("SELECT user_id, name, admin, hp, hp_max, mp, mp_max, stm, stm_max, exp, gp, map_name, map_xpos, map_ypos, avatar FROM $TablePrefix" . "_user WHERE user_name='$user_name' AND user_pass='$user_pass'");
        $char = mymysqli_fetch_array($result);
    }

}


/*                                                                            */
/* validateImageSession - Validate image session                              */

function validateImageSession()
{

    session_set_save_handler('sess_open', '', 'sess_read', 'sess_write', 'sess_destroy', 'sess_gc');            session_name('s');
    session_name('s');
    session_start();

    global $user_name, $user_pass, $user_time, $TablePrefix, $char;

    if (empty($user_name) || empty($user_pass) || empty($user_time) || ($user_time < (time() - 610))) {
        // Session doesn't exist or expired -> Null
        exit;
    }

}


/*                                                                            */
/* db_connect - Initiate MySQL database connection if not already so          */

function db_connect()
{

    global $db_host, $db_user, $db_pass, $db_name;

    $db = mysql_pconnect($db_host, $db_user, $db_pass) or die(mysql_error());
    mysql_select_db($db_name) or die(mysql_error());

}


/*                                                                            */
/* startTiming - Initiate PHP script execution timer                          */

function startTiming()
{

    global $startTime;

    $microTime = microtime();
    $timeparts = explode(" ",$microTime);
    $startTime = $timeparts[1].substr($timeparts[0],1);

}


/*                                                                            */
/* string stopTiming - Terminate timer and returns execution time             */

function stopTiming()
{

    global $startTime;

    $microTime = microtime();
    $timeparts = explode(" ",$microTime);
    $endTime = $timeparts[1].substr($timeparts[0],1);
    $execTime = round($endTime - $startTime, 5);
    return $execTime;

}


/*                                                                            */
/* string hostIdentify - Return visitor's host information                    */
/*                       Script extracted from: http://www.cgsa.net/php       */

function hostIdentify()
{

global $HTTP_VIA, $HTTP_X_COMING_FROM, $HTTP_X_FORWARDED_FOR, $HTTP_X_FORWARDED,
       $HTTP_COMING_FROM, $HTTP_FORWARDED_FOR, $HTTP_FORWARDED, $REMOTE_ADDR;

if ($HTTP_X_FORWARDED_FOR) {
    // case 1.A: proxy && HTTP_X_FORWARDED_FOR is defined
    $b = ereg("^([0-9]{1,3}\.){3,3}[0-9]{1,3}", $HTTP_X_FORWARDED_FOR, $array);
    if ($b && (count($array) >= 1)) {
        return (gethostbyaddr($array[0]));
    } else {
        return ($REMOTE_ADDR . '_' . $HTTP_VIA . '_' . $HTTP_X_FORWARDED_FOR);
    }
} else if ($HTTP_X_FORWARDED) {
    // case 1.B: proxy && HTTP_X_FORWARDED is defined
    $b = ereg("^([0-9]{1,3}\.){3,3}[0-9]{1,3}", $HTTP_X_FORWARDED, $array);
    if ($b && (count($array) >= 1)) {
        return (gethostbyaddr($array[0]));
    } else {
        return ($REMOTE_ADDR . '_' . $HTTP_VIA . '_' . $HTTP_X_FORWARDED);
    }
} else if ($HTTP_FORWARDED_FOR) {
    // case 1.C: proxy && HTTP_FORWARDED_FOR is defined
    $b = ereg("^([0-9]{1,3}\.){3,3}[0-9]{1,3}", $HTTP_FORWARDED_FOR, $array);
    if ($b && (count($array) >= 1)) {
        return (gethostbyaddr($array[0]));
    } else {
        return ($REMOTE_ADDR . '_' . $HTTP_VIA . '_' . $HTTP_FORWARDED_FOR);
    }
} else if ($HTTP_FORWARDED) {
    // case 1.D: proxy && HTTP_FORWARDED is defined
    $b = ereg("^([0-9]{1,3}\.){3,3}[0-9]{1,3}", $HTTP_FORWARDED, $array);
    if ($b && (count($array) >= 1)) {
        return (gethostbyaddr($array[0]));
    } else {
        return ($REMOTE_ADDR . '_' . $HTTP_VIA . '_' . $HTTP_FORWARDED);
    }
} else if ($HTTP_VIA) {
    // case 2: proxy && HTTP_(X_) FORWARDED (_FOR) not defined && HTTP_VIA defined
    // other exotic variables may be defined
    return ($HTTP_VIA . '_' . $HTTP_X_COMING_FROM . '_' . $HTTP_COMING_FROM);
} else if($HTTP_X_COMING_FROM || $HTTP_COMING_FROM) {
    // case 3: proxy && only exotic variables defined
    // the exotic variables are not enough, we add the REMOTE_ADDR of the proxy
    return ($REMOTE_ADDR . '_' . $HTTP_X_COMING_FROM . '_' . $HTTP_COMING_FROM);
} else {
    // case 4: no proxy
    // or tricky case: proxy + refresh
    return (gethostbyaddr($REMOTE_ADDR));
}

}


/* END of System function library                                             */
/*----------------------------------------------------------------------------*/

?>
