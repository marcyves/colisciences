<script language="JavaScript">
<!--
  var errorString = "L'éditeur des paratextes ne fonctionne qu'avec Windows et IE. Désolé."
  var Ok = "false";
  var name =  navigator.appName;
  var version =  parseFloat(navigator.appVersion);
  var platform = navigator.platform;

	if (platform == "Win32" && name == "Microsoft Internet Explorer" && version >= 4){
		Ok = "true";
	} else {
		Ok= "false";
	}

//	if (Ok == "false") {
//		alert(errorString);
//	}



function initToolBar(ed,myEditor) {
    
	var eb = document.all.editbar;
	if (ed!=null) {
		eb._editor = window.frames[myEditor];
	}
}

function doFormat(what) {

var eb = document.all.editbar;
eb._editor.execCommand(what, arguments[1]);
		
}

function swapMode() {

	var eb = document.all.editbar._editor;
  eb.swapModes();
}

function create() {

    var eb = document.all.editbar;
    eb._editor.newDocument();
}

function newFile(){

	create();
}



function Help_OnClick(){
  window.open("./admin/wysiwyg/images/help_document.htm","wHelp", "toolbar=0, scrollbars=yes, width=640, height=480");
}
//-->
</script>