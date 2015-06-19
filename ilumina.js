
/*=========== VISUAL HINTS ==============*/

function unIluminate(name,tipo) {
	var me = document.getElementById(name);
	
	document.getElementById(name).style.backgroundColor='none';
	document.getElementById(name).style.color='black';
		
	if (tipo=="listbox")
		document.getElementById(name).style.cssText = " -moz-binding: url(\"chrome://global/content/bindings/listbox.xml#listitem\");";	
	else
	if (tipo=="menulist") 
		document.getElementById(name).style.cssText = "-moz-binding: url(\"chrome://global/content/bindings/menulist.xml#menulist\");";			  
	else if (tipo =="label-precio") {
		document.getElementById(name).style.cssText = " -moz-binding: url(\"chrome://global/content/bindings/listbox.xml#listitem\");";	
		me.style.align ="right";
		me.style.textAlign ="right";							
	} else if (tipo == "groupbox"){
		//document.getElementById(name).style.border	='none';
		document.getElementById(name).style.borderColor='threedshadow';
		document.getElementById(name).style.cssText = "-moz-binding: url(\"chrome://global/content/bindings/groupbox.xml#groupbox\");";			  					
	} else if (tipo =="label-descuento") {
		document.getElementById(name).style.cssText = " -moz-binding: url(\"chrome://global/content/bindings/listbox.xml#listitem\");";	
		me.style.align ="right";
		me.style.textAlign ="right";							
	}		
	
	//alert("In-Iluminate:" + name+id(name));
}


function Iluminate(name,tipo) {
	switch(tipo) {
		case "groupbox":
			document.getElementById(name).style.borderColor		= 'blue';
			//document.getElementById(name).style.borderColor		= 'yellow';
			break;
		default:
			document.getElementById(name).style.backgroundColor	= 'yellow';
			document.getElementById(name).style.color			= 'black';		
			break;
	}		
}

function Blink(name,tipo) {
	Iluminate(name,tipo);
	
	if (!tipo) tipo ="listbox";
		
	setTimeout("unIluminate('"+name+"','"+tipo+"') ",500);
}
/*------------------------------------------------*/
