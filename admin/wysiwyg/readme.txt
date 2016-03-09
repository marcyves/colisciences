/**************************************************************************/
/* PHP-NUKE 5.4 - WYSIWYG MODULE                                          */
/* =============================                                          */
/*                                                                        */
/*     ORIGINAL AUTHOR: Paul R Boon                                       */
/*     ORIGINAL CREDIT: Macromedia Dreamweaver Ultradev Exchange          */
/*           http://www.macromedia.com/exchange/ultradev/                 */
/*           http://www.publicdomain.to                                   */
/*                                                                        */
/* ADAPTED TO PHPNUKE BY :  Idefix (webmaster@nukedownload.com)           */
/*                                                                        */
/*                    http://www.nukedownload.com                         */
/*                                                                        */
/* Version 1.2 22/01/2002                                                 */
/* Version 1.1 08/12/2001                                                 */
/* Version 1.0 31/10/2001                                                 */                                      */
/**************************************************************************/

-----------------------------------------------------------
CHANGE FROM VERSION 1.1 
----------------------------------------------------------

- Fix the "Error document.fHtml.Editor is not an object"  bug (I hope)
- Use as a Module

-----------------------------------------------------------
CHANGE FROM VERSION 1.0 
----------------------------------------------------------

- Add image Insertion
- Add a WYSIWYG with Extended Story
- Add a WYSIWYG in Admin stories
- Fix the "Error document.fHtml.Editor is not an object"  bug


------------------
What is this ?
------------------

It's a WYSIWYG editor for submiting your news.
It doesn't work whith Nescape only with Internet Explorer 5.x.

-------------------
Features
-------------------

- Browser detection (IE/ Netscape). You can see the WYSIWYG editor only with IE.
- Select Font
- Change Color
- Bullet List
- Add URL
- Add picture

---------------------
Future features?
---------------------
 
- Multilanguage
- Further suggestions?

---------------------
Installation
---------------------

- Unpack the zip file 
- copy the /html/modules/* in your /modules directories
- copy the /html/admin/modules/stories.php in your /admin/modules directorie

-----------------------  
Known Bugs
-----------------------

VERSION 1.2:
------------

- The size or Font not change in the preview:
it's not a wysiwyg editor bug !
 Take a look a your style.css themes and replace 
 the line 
FONT	{FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px}
as  {FONT-FAMILY: Verdana,Helvetica;}
or  delete this line


VERSION 1.1:
------------

Error document.fHtml.Editor is not an object when you click on preview

VERSION 1.0:
------------

Error document.fHtml.Editor is not an object when you click on preview



