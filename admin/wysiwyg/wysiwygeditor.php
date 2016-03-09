<?php    

function html_editor($textareaname,$contents,$myEditor)
{
 ?>
<script>
function copyValue_<?=$textareaname?>() {
	var theHtml = document.frames("<?=$myEditor ?>").document.frames("textEdit").document.body.innerHTML;
	document.all.<?=$textareaname?>.value = theHtml;
}

function SwapView<?=$textareaname?>_OnClick(){

  if(document.all.btnSwapView<?=$textareaname?>.value == "View Html"){
		var sMes = "Mode Wysiwyg";
    		var sStatusBarMes = "Le code Html est affiché";
		} 
	else {
		var sMes = "Codes Html"
    		var sStatusBarMes = "Le document apparait tel qu'il sera en ligne (wysiwyg)";
  		}
	
  document.all.btnSwapView<?=$textareaname?>.value = sMes;
  window.status  = sStatusBarMes;
  swapMode();
}

function makeUrl<?=$textareaname?>(){

	sUrl = document.all.what<?=$textareaname?>.value + document.all.url<?=$textareaname?>.value;
	doFormat('CreateLink',sUrl);
}

function ColorPalette<?=$textareaname?>_OnClick(colorString){
	
	cpick<?=$textareaname?>.bgColor=colorString;
	document.all.colourp<?=$textareaname?>.value=colorString;
	doFormat('ForeColor',colorString);
}


</script>
  <textarea name="<?=$textareaname ?>" style="display: none;"><?=$contents ?></textarea>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC" width="80%" height="40%" bordercolor="#CCCCCC">
    <tr valign="top"> 
      <td> 
        <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
          <tr valign="top"> 
            <td valign="top"> 
              <div id=editbar > 
                <table width="100%" border="0" cellpadding="0" cellspacing="0" align="left">
                  <tr> 
                    <td> 
                      <table border="0" cellpadding="0" cellspacing="0">
                        <tr> 
                          <td> 
                            <table border="0">
                              <tr valign="baseline"> 
                                <td nowrap><img class='clsCursor' src="./admin/wysiwyg/images/new.gif" width="16" height="16" border="0" alt="Start Over / New File" onClick="newFile();">&nbsp 
                                  <img class='clsCursor' src="./admin/wysiwyg/images/Cut.gif" width="16" height="16" border="0" alt="Cut " onClick="doFormat('Cut')">&nbsp 
                                  <img class='clsCursor' src="./admin/wysiwyg/images/Copy.gif" width="16" height="16" border="0" alt="Copy" onClick="doFormat('Copy')">&nbsp 
                                  <img class='clsCursor' src="./admin/wysiwyg/images/Paste.gif" border="0" alt="Paste" onClick="doFormat('Paste')" width="16" height="16">&nbsp 
                                </td>
                              </tr>
                            </table>
                          </td>
                          <td> 
                            <table border="0">
                              <tr valign="baseline"> 
                                <td nowrap> <img class='clsCursor' src="./admin/wysiwyg/images/para_bul.gif" width="16" height="16" border="0" alt="Bullet List" onClick="doFormat('InsertUnorderedList');" >&nbsp 
                                  <img class='clsCursor' src="./admin/wysiwyg/images/para_num.gif" width="16" height="16" border="0" alt="Numbered List" onClick="doFormat('InsertOrderedList');" >&nbsp 
                                  <img class='clsCursor' src="./admin/wysiwyg/images/indent.gif" width="20" height="16" alt="Indent" onClick="doFormat('Indent')">&nbsp 
                                  <img class='clsCursor' src="./admin/wysiwyg/images/outdent.gif" width="20" height="16" alt="Outdent" onClick="doFormat('Outdent')">&nbsp 
                                  <img class='clsCursor' src="./admin/wysiwyg/images/hr.gif" width="16" height="18" alt="HR" onClick="doFormat('InsertHorizontalRule')">&nbsp 
                                  <img class='clsCursor' src="./admin/wysiwyg/images/picture.gif" width="16" height="18" alt="HR" onClick="var strURL = window.prompt('Enter URL s Picture', '');if (strURL!=null) {doFormat('InsertImage',strURL);}">&nbsp 
                                </td>
                              </tr>
                            </table>
                          </td>
                          <td> 
                            <table border="0">
                              <tr valign="baseline"> 
                                <td nowrap><img src="./admin/wysiwyg/images/link.gif" border="0" alt="Link to external site"></td>
                                <td nowrap> 
                                  <select name="what<?=$textareaname?>" style="font: 8pt verdana;">
                                    <option value="http://" selected>http://</option>
                                    <option value="mailto:">mailto:</option>
                                    <option value="ftp://">ftp://</option>
                                    <option value="https://">https://</option>
                                  </select>
                                </td>
                                <td> 
                                  <input type="text" name="url<?=$textareaname?>" size="25" style="font: 8pt verdana;">
                                </td>
                                <td> 
                                  <input type="button" name="buttonadd<?=$textareaname?>" value="Add" onClick="makeUrl<?=$textareaname?>();" style="font: 8pt verdana;">
                                </td>
                              </tr>
                            </table>
                          </td>
                          <td><img class='clsCursor' src="./admin/wysiwyg/images/help.gif" width="20" height="20" align="middle" alt="Help" onClick="Help_OnClick();"> 
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr> 
                    <td height="41"> 
                      <table border="0">
                        <tr> 
                          <td nowrap valign="baseline"> 
                            <div align="left"> 
                              <select name="font<?=$textareaname ?>" onChange="if (document.all.font<?=$textareaname ?>.value !=1) {doFormat('FontName',document.all.font<?=$textareaname ?>.value);document.all.font<?=$textareaname ?>.selectedIndex = 0;}" style="font: 8pt verdana;">
                                <option value="1" selected >Select Font...</option>
                                <option value="arial">Arial, Helvetica, sans-serif</option>
                                <option value="times" >Times New Roman, Times,serif</option>
                                <option value="courier">Courier New, Courier, mono</option>
                                <option value="georgia">Georgia, Times New Roman</option>
                                <option value="verdana">Verdana, Arial, Helvetica</option>
                              </select>
                              <select name="size<?=$textareaname ?>" onChange="if (document.all.font<?=$textareaname ?>.value !='None') {doFormat('FontSize',document.all.size<?=$textareaname ?>.value);document.all.size<?=$textareaname ?>.selectedIndex = 0;}" style="font: 8pt verdana;">
                                <option value="None" selected>Size</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                              </select>
                              <img class='clsCursor' src="./admin/wysiwyg/images/Bold.gif" width="16" height="16" border="0" align="absmiddle" alt="Bold text" onClick="doFormat('Bold')">&nbsp 
                              <img class='clsCursor' src="./admin/wysiwyg/images/Italics.gif" width="16" height="16" border="0" align="absmiddle" alt="Italic text" onClick="doFormat('Italic')">&nbsp 
                              <img class='clsCursor' src="./admin/wysiwyg/images/underline.gif" width="16" height="16" border="0" align="absmiddle" alt="Underline text" onClick="doFormat('Underline')" >&nbsp 
                              <img class='clsCursor' src="./admin/wysiwyg/images/left.gif" width="16" height="16" border="0" alt="Align Left" align="absmiddle"  onClick="doFormat('JustifyLeft')"> 
                              <img class='clsCursor' src="./admin/wysiwyg/images/centre.gif" width="16" height="16" border="0" alt="Align Center" align="absmiddle" onClick="doFormat('JustifyCenter')">&nbsp 
                              <img class='clsCursor' src="./admin/wysiwyg/images/right.gif" width="16" height="16" border="0" alt="Align Right" align="absmiddle"  onClick="doFormat('JustifyRight')">&nbsp 
                            </div>
                          </td>
                          <td align="left" nowrap valign="baseline"> 
                            <input type="button" name="btnSwapView<?=$textareaname?>" value="View Html" onClick="SwapView<?=$textareaname?>_OnClick();" style="width:100px; font: 8pt verdana;">
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </div>
            </td>
          </tr>
          <tr valign="top" align="left"> 
            <td valign="top"> 
              <table width="100%" border="0" height="100%">
                <tr valign="top"> 
                  <td width="100%" height="100%"> 
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
                      <tr valign="top"> 
                        <td bgcolor="#FFFFFF"><iframe id="<?=$myEditor ?>" src="./admin/wysiwyg/pdedit.php?textareaname=<?=$textareaname?>" onFocus="initToolBar(this,'<?=$myEditor ?>')" width=100% height=100%></iframe></td>
                      </tr>
                    </table>
                  </td>
                  <td width="9%" align="center"> 
                    <table  bgcolor="#000000" width="74" id="cpick<?=$textareaname?>" border="1" cellspacing="0" cellpadding="0" align="center">
                      <tr> 
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                    <input type="text" name="colourp<?=$textareaname?>" size="8" value="#000000" style="width:74px; font: 8pt verdana" readonly>
                    <table border=1 bgcolor="#CCCCCC" cellpadding="0" cellspacing="0" width="74">
                      <tr> 
                        <td bgcolor="#ffffff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ffffff')"></td>
                        <td bgcolor="#ffffcc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ffffcc')"></td>
                        <td bgcolor="#ffff99" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ffff99')"></td>
                        <td bgcolor="#ffff66" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ffff66')"></td>
                        <td bgcolor="#ffff33" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ffff33')"></td>
                        <td bgcolor="#ffff00" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ffff00')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#ccffff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ccffff')"></td>
                        <td bgcolor="#ccffcc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ccffcc')"></td>
                        <td bgcolor="#ccff99" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ccff99')"></td>
                        <td bgcolor="#ccff66" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ccff66')"></td>
                        <td bgcolor="#ccff33" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ccff33')"></td>
                        <td bgcolor="#ccff00" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ccff00')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#99ffff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#99ffff')"></td>
                        <td bgcolor="#99ffcc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#99ffcc')"></td>
                        <td bgcolor="#99ff99" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#99ff99')"></td>
                        <td bgcolor="#99ff66" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#99ff66')"></td>
                        <td bgcolor="#99ff33" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#99ff33')"></td>
                        <td bgcolor="#99ff00" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#99ff00')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#00ffff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#00ffff')"></td>
                        <td bgcolor="#00ffcc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#00ffcc')"></td>
                        <td bgcolor="#00ff99" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#00ff99')"></td>
                        <td bgcolor="#00ff66" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#00ff66')"></td>
                        <td bgcolor="#00ff33" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#00ff33')"></td>
                        <td bgcolor="#00ff00" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#00ff00')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#ffccff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ffccff')"></td>
                        <td bgcolor="#ffcccc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ffcccc')"></td>
                        <td bgcolor="#ffcc99" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ffcc99')"></td>
                        <td bgcolor="#ffcc66" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ffcc66')"></td>
                        <td bgcolor="#ffcc33" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ffcc33')"></td>
                        <td bgcolor="#ffcc00" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ffcc00')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#ccccff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ccccff')"></td>
                        <td bgcolor="#cccccc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cccccc')"></td>
                        <td bgcolor="#cccc99" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cccc99')"></td>
                        <td bgcolor="#cccc66" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cccc66')"></td>
                        <td bgcolor="#cccc33" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cccc33')"></td>
                        <td bgcolor="#cccc00" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cccc00')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#00ccff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#00ccff')"></td>
                        <td bgcolor="#00cccc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#00cccc')"></td>
                        <td bgcolor="#00cc99" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#00cc99')"></td>
                        <td bgcolor="#00cc66" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#00cc66')"></td>
                        <td bgcolor="#00cc33" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#00cc33')"></td>
                        <td bgcolor="#00cc00" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#00cc00')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#ff99ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff99ff')"></td>
                        <td bgcolor="#ff99cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff99cc')"></td>
                        <td bgcolor="#ff9999" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff9999')"></td>
                        <td bgcolor="#ff9966" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff9966')"></td>
                        <td bgcolor="#ff9933" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff9933')"></td>
                        <td bgcolor="#ff9900" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff9900')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#cc99ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc99ff')"></td>
                        <td bgcolor="#cc99cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc99cc')"></td>
                        <td bgcolor="#cc9999" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc9999')"></td>
                        <td bgcolor="#cc9966" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc9966')"></td>
                        <td bgcolor="#cc9933" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc9933')"></td>
                        <td bgcolor="#cc9900" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc9900')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#9999ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#9999ff')"></td>
                        <td bgcolor="#9999cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#9999cc')"></td>
                        <td bgcolor="#999999" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#999999')"></td>
                        <td bgcolor="#999966" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#999966')"></td>
                        <td bgcolor="#999933" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#999933')"></td>
                        <td bgcolor="#999900" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#999900')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#6699ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#6699ff')"></td>
                        <td bgcolor="#6699cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#6699cc')"></td>
                        <td bgcolor="#669999" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#669999')"></td>
                        <td bgcolor="#669966" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#669966')"></td>
                        <td bgcolor="#669933" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#669933')"></td>
                        <td bgcolor="#669900" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#669900')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#3399ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#3399ff')"></td>
                        <td bgcolor="#3399cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#3399cc')"></td>
                        <td bgcolor="#339999" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#339999')"></td>
                        <td bgcolor="#339966" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#339966')"></td>
                        <td bgcolor="#339933" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#339933')"></td>
                        <td bgcolor="#339900" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#339900')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#0099ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#0099ff')"></td>
                        <td bgcolor="#0099cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#0099cc')"></td>
                        <td bgcolor="#009999" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#009999')"></td>
                        <td bgcolor="#009966" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#009966')"></td>
                        <td bgcolor="#009933" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#009933')"></td>
                        <td bgcolor="#009900" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#009900')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#ff66ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff66ff')"></td>
                        <td bgcolor="#ff66cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff66cc')"></td>
                        <td bgcolor="#ff6699" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff6699')"></td>
                        <td bgcolor="#ff6666" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff6666')"></td>
                        <td bgcolor="#ff6633" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff6633')"></td>
                        <td bgcolor="#ff6600" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff6600')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#cc66ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc66ff')"></td>
                        <td bgcolor="#cc66cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc66cc')"></td>
                        <td bgcolor="#cc6699" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc6699')"></td>
                        <td bgcolor="#cc6666" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc6666')"></td>
                        <td bgcolor="#cc6633" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc6633')"></td>
                        <td bgcolor="#cc6600" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc6600')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#9966ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#9966ff')"></td>
                        <td bgcolor="#9966cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#9966cc')"></td>
                        <td bgcolor="#996699" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#996699')"></td>
                        <td bgcolor="#996666" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#996666')"></td>
                        <td bgcolor="#996633" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#996633')"></td>
                        <td bgcolor="#996600" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#996600')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#6666ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#6666ff')"></td>
                        <td bgcolor="#6666cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#6666cc')"></td>
                        <td bgcolor="#666699" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#666699')"></td>
                        <td bgcolor="#666666" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#666666')"></td>
                        <td bgcolor="#666633" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#666633')"></td>
                        <td bgcolor="#666600" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#666600')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#3366ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#3366ff')"></td>
                        <td bgcolor="#3366cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#3366cc')"></td>
                        <td bgcolor="#336699" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#336699')"></td>
                        <td bgcolor="#336666" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#336666')"></td>
                        <td bgcolor="#336633" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#336633')"></td>
                        <td bgcolor="#336600" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#336600')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#0066ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#0066ff')"></td>
                        <td bgcolor="#0066cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#0066cc')"></td>
                        <td bgcolor="#006699" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#006699')"></td>
                        <td bgcolor="#006666" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#006666')"></td>
                        <td bgcolor="#006633" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#006633')"></td>
                        <td bgcolor="#006600" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#006600')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#ff33ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff33ff')"></td>
                        <td bgcolor="#ff33cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff33cc')"></td>
                        <td bgcolor="#ff3399" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff3399')"></td>
                        <td bgcolor="#ff3366" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff3366')"></td>
                        <td bgcolor="#ff3333" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff3333')"></td>
                        <td bgcolor="#ff3300" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff3300')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#cc33ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc33ff')"></td>
                        <td bgcolor="#cc33cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc33cc')"></td>
                        <td bgcolor="#cc3399" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc3399')"></td>
                        <td bgcolor="#cc3366" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc3366')"></td>
                        <td bgcolor="#cc3333" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc3333')"></td>
                        <td bgcolor="#cc3300" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc3300')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#9933ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#9933ff')"></td>
                        <td bgcolor="#9933cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#9933cc')"></td>
                        <td bgcolor="#993399" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#993399')"></td>
                        <td bgcolor="#993366" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#993366')"></td>
                        <td bgcolor="#993333" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#993333')"></td>
                        <td bgcolor="#993300" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#993300')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#6633ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#6633ff')"></td>
                        <td bgcolor="#6633cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#6633cc')"></td>
                        <td bgcolor="#663399" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#663399')"></td>
                        <td bgcolor="#663366" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#663366')"></td>
                        <td bgcolor="#663333" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#663333')"></td>
                        <td bgcolor="#663300" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#663300')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#3333ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#3333ff')"></td>
                        <td bgcolor="#3333cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#3333cc')"></td>
                        <td bgcolor="#333399" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#333399')"></td>
                        <td bgcolor="#333366" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#333366')"></td>
                        <td bgcolor="#333333" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#333333')"></td>
                        <td bgcolor="#333300" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#333300')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#0033ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#0033ff')"></td>
                        <td bgcolor="#0033cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#0033cc')"></td>
                        <td bgcolor="#003399" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#003399')"></td>
                        <td bgcolor="#003366" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#003366')"></td>
                        <td bgcolor="#003333" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#003333')"></td>
                        <td bgcolor="#003300" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#003300')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#ff00ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff00ff')"></td>
                        <td bgcolor="#ff00cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff00cc')"></td>
                        <td bgcolor="#ff0099" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff0099')"></td>
                        <td bgcolor="#ff0066" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff0066')"></td>
                        <td bgcolor="#ff0033" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff0033')"></td>
                        <td bgcolor="#ff0000" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#ff0000')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#cc00ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc00ff')"></td>
                        <td bgcolor="#cc00cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc00cc')"></td>
                        <td bgcolor="#cc0099" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc0099')"></td>
                        <td bgcolor="#cc0066" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc0066')"></td>
                        <td bgcolor="#cc0033" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc0033')"></td>
                        <td bgcolor="#cc0000" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#cc0000')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#9900ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#9900ff')"></td>
                        <td bgcolor="#9900cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#9900cc')"></td>
                        <td bgcolor="#990099" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#990099')"></td>
                        <td bgcolor="#990066" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#990066')"></td>
                        <td bgcolor="#990033" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#990033')"></td>
                        <td bgcolor="#990000" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#990000')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#6600ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#6600ff')"></td>
                        <td bgcolor="#6600cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#6600cc')"></td>
                        <td bgcolor="#660099" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#660099')"></td>
                        <td bgcolor="#660066" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#660066')"></td>
                        <td bgcolor="#660033" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#660033')"></td>
                        <td bgcolor="#660000" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#660000')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#3300ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#3300ff')"></td>
                        <td bgcolor="#3300cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#3300cc')"></td>
                        <td bgcolor="#330099" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#330099')"></td>
                        <td bgcolor="#330066" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#330066')"></td>
                        <td bgcolor="#330033" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#330033')"></td>
                        <td bgcolor="#330000" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#330000')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#0000ff" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#0000ff')"></td>
                        <td bgcolor="#0000cc" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#0000cc')"></td>
                        <td bgcolor="#000099" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#000099')"></td>
                        <td bgcolor="#000066" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#000066')"></td>
                        <td bgcolor="#000033" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#000033')"></td>
                        <td bgcolor="#000000" width="12"><img class="clsCursor" height=8 width=10 border=0 onClick="ColorPalette<?=$textareaname?>_OnClick('#000000')"></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
<!-- </form> -->
<script language="JavaScript">
  initToolBar("foo","<?=$myEditor ?>");
</script>
<?
}
?>