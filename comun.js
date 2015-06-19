

function id(nombre) { return document.getElementById(nombre); }

function Aviso(){
	//alert("Su sesion ha caducado, salga y vuelva a entrar");	
}


//window.onerror = Aviso;

function FiltraEntero(elemento) {
	var res = parseInt(elemento.value);
	if (isNaN(res))
		elemento.value = "0";
	else
		elemento.value = res;

}

function VaciarElemento(element){
	while(element.hasChildNodes())
	  element.removeChild(element.firstChild);
}
  
  
function Eliminar(tpadre,thijo){

	var xpadre = id(tpadre);
	var xhijo = id(thijo);
	
	if (!xhijo) return;
	
	if (!xpadre.firstChild)
		return;
	
	xhijo.parentNode.removeChild(xhijo);
}



function AgnadirProveedorSiNoExiste(prov){
	AgnadirElementoSiNoExiste(prov,"Proveedor");
	AgnadirElementoSiNoExiste(prov,"sProveedor");
}

function AgnadirElementoSiNoExiste(prov,campo){
	var xProv = id(campo);
	
	if (!xProv) return;

	var xlen 	= xProv.firstChild.childNodes.length;
	var xnodes 	= xProv.firstChild.childNodes;
	
	var visto = 0;
	for(var t=0;t<xlen;t++){
		if ( xnodes[t].label == prov ){
			visto = 1;
		}	
	}	
	if (!visto){
		var xnewitem = document.createElement("menuitem");
		//<menuitem value='16'  label='A'/>
		xnewitem.setAttribute("label",prov);
		
		xProv.firstChild.appendChild( xnewitem );
	}			
}

function cambioGestionDinero(){
	if (typeof cambioPagos!="undefined")
		cambioPagos	= 1;
		
	if (typeof cambioCobros!="undefined")
		cambioCobros = 1;
}

function TogglePago(nombreElemento){	
	var nE = id(nombreElemento + "_pago");
	if (!nE) return alert("no ne!");
	
	nE.checked = (nE.checked)?0:"true";		
	
	AutoAjuste();
	cambioGestionDinero();							
}

function SetPago(nombreElemento,esmodo){	
	var nE = id(nombreElemento + "_pago");
	if (!nE) return alert("no ne!");
	
	nE.checked = (esmodo)?"true":0;		
	
	AutoAjuste();
	cambioGestionDinero();							
}



function EliminarTodosActividad(){
	var xlist = id("attribActividad-list");
	var xsel;	
	
	while( xsel = xlist.firstChild){
		CorrigeValor("attribActividad-list", xsel.id, "cuantoContribActividad-resto", xsel.value, "cuantoContribCentros");	
	}

	cambioActiv = 1;			
	RecalculoActividad();
}

function EliminarTodosCentros(){
	var xlist = id("attribCentros-list");
	var xsel;	
	
	while( xsel = xlist.firstChild){
		CorrigeValor("attribCentros-list", xsel.id, "cuantoContribCentros-resto", xsel.value, "cuantoContribCentros");	
	}

	cambioCentros = 1;			
	RecalculoCentros();
}

function EliminarTodosDinero(){
	var xlist = id("gestionPagos-list");
	var modo=0;
	if (!xlist) {
		xlist = id("gestionCobros-list");
		modo=1;
	}
		
	var xsel;
		
	while( xsel = xlist.firstChild){
		if (modo)
			EliminarLineaCobro(xsel.id);
		else
			EliminarLineaPago(xsel.id);

	}

	cambioPagos = 1;
	AutoAjuste();
}