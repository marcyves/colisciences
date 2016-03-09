function popup(chemin){ 	
i1 = new Image;	
i1.src = chemin;	
html = '<HTML><HEAD><TITLE>a Blork Engine - image</TITLE></HEAD><BODY LEFTMARGIN=0 MARGINWIDTH=0 TOPMARGIN=0 MARGINHEIGHT=0><CENTER><IMG SRC="'+chemin+'" BORDER=0 NAME=imageTest onLoad="window.resizeTo(document.imageTest.width+14,document.imageTest.height+32)"></CENTER></BODY></HTML>';	
popupImage=window.open('','Blork','toolbar=0,location=0,directories=0,menuBar=0,scrollbars=0,resizable=1');	
popupImage.document.open();	
popupImage.document.write(html);	
popupImage.document.close(); };