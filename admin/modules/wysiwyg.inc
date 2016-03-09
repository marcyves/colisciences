<?php

include_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);

if(ereg("MSIE", getenv("HTTP_USER_AGENT")))	{
	include "admin/wysiwyg/wysiwygeditor.php";
//    jscript_wysiwyg();
}

function editWysiwygTextarea($texte){

        if(ereg("MSIE", getenv("HTTP_USER_AGENT")))	{
         	 html_editor("texte",$texte,"myEditor");
         } else	{
			 echo "<textarea cols=\"50\" rows=\"12\" name=\"texte\">$texte</textarea><br>"
	        ."<br><br><b>"._EXTENDEDTEXT.":</b><br>"
			."<textarea cols=\"50\" rows=\"12\" name=\"storyext\"></textarea><br>"
			."("._AREYOUSURE.")</font><br><br>"
	        ."<font class=\"content\">"._ALLOWEDHTML."<br>";
	    	while (list($key,) = each($AllowableHTML)) echo " &lt;".$key."&gt;";
		}
}

function editWysiwygBouton(){

        if(ereg("MSIE", getenv("HTTP_USER_AGENT")))	{
         	 echo "<br><br><input type=\"submit\" name=\"op\" value=\""._PREVIEW."\" onClick=\"copyValue_texte('myEditor');\">";
         } else	{
	    	echo "<br><br><input type=\"submit\" name=\"op\" value=\""._PREVIEW."\">&nbsp;&nbsp;"
			."<select name=\"posttype\">\n"
			."<option value=\"exttrans\">"._EXTRANS."</option>\n"
			."<option value=\"html\" >"._HTMLFORMATED."</option>\n"
			."<option value=\"plaintext\" selected>"._PLAINTEXT."</option>\n"
			."</select>"
			."<br>("._SUBPREVIEW.")</form>";
		}
}

?>