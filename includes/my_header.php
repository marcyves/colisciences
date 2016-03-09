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

/* This file is to customize whatever stuff you need to include in your site 
   when the header loads. This can be used for third party banners, custom
   javascript, popup windows, etc. With this file you don't need to edit 
   system code each time you upgrade to a new version. Just remember, in case
   you add code here to not overwrite this file when updating!
   Whatever you put here will be between <head> and </head> tags. */
echo '
<SCRIPT LANGUAGE="JavaScript">
<!-- Original:  Nick Simonov (nicks@iln.net) -->
<!-- Web Site:  http://www.iln.net -->

<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->

<!-- Begin
var NS = (navigator.appName == "Netscape") ? 1 : 0;
if (NS) document.captureEvents(Event.DBLCLICK);
document.ondblclick = dict;

var newwin;
function dict() {
if (NS) {
t = document.getSelection();
opennewwin(t);
}
else {
t = document.selection.createRange();
if(document.selection.type == \'Text\' && t.text != \'\') {
document.selection.empty();
opennewwin(t.text);
      }
   }
}
function opennewwin(text) {
if (text > \'\') {
newwin = window.open(\'linkany.php?mot=\'+text, \'dictionary\', \'width=800, height=600, resizable=yes, menubar=yes, toolbar=yes, scrollbars=yes\');
setTimeout(\'newwin.focus()\', 100);
   }
}
//  End -->
</script>';

?>