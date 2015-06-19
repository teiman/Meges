
var esperandoCode = "<?xml version='1.0'?><?xml-stylesheet href='chrome://global/skin/' type='text/css'?><window id='yourwindow' xmlns='http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul'><box><image src='img/load.gif' style='width: 80px'/></box></window>";
var esperandoDataCode = "data:application/vnd.mozilla.xul+xml," + encodeURIComponent(esperandoCode);


function Aumentar(xdestino,cantidad){
	var xdes = id(xdestino);
	var res =	parseInt(parseInt(xdes.value) + parseInt(cantidad));
	
	if (res>100) res = 100;
	xdes.value = res;
}


/*------------------- Manejo de nombre -----------------------------*/

var centros2id = new Array();
var activ2id = new Array();


function getIdCentro(nombre){
	return centros2id[nombre];		
}

function setIdCentro(nombre,id){
	centros2id[nombre] = id;
}

function getIdActiv(nombre){
	return activ2id[nombre];		
}

function setIdActiv(nombre,id){
	activ2id[nombre] = id;
}




function setId(modo,NombreVal,IdValue){
	switch(modo){	
		case "attribActividad-list":
			setIdActiv(NombreVal,IdValue);
			break;
		default:
			setIdCentro(NombreVal,IdValue);			
	}
}

/*------------------- Manejo de nombre -----------------------------*/


/*------------------- Busqueda de Gastos-----------------------------*/

function CancelarModGasto(){
	PanelModoBuscar();
}


function ModGasto(IdGasto){
	if (!IdGasto) return;
	var xiframemodgasto = id("framemodgasto");
	if (!xiframemodgasto) return;	
	//xiframemodgasto.setAttribute("location",esperandoDataCode);		
	xiframemodgasto.setAttribute("src",esperandoDataCode);			
	
	setTimeout("ModGasto2("+IdGasto+")",100);
}

function ModGasto2(IdGasto){
	var xiframemodgasto = id("framemodgasto");
	xiframemodgasto.setAttribute("src","modgasto.php?modo=abrirmodificar&id="+IdGasto+"&r=" +Math.random());	
	setTimeout("PanelModoEdicionGasto()",200);
}




function PanelModoBuscar(){
	var xPanelMod = id("tab-busca");
	var pad = 1;
	var pG = id("panelGastos");	
	if (!pG) return;	
	var pBox = id("tabGastos");

	id("tab-alta").setAttribute("selectedIndex",pad);
	id("tab-alta").setAttribute("selected","false");
	id("tab-alta").setAttribute("beforeselected","true");
	id("tab-alta").setAttribute("selectedItem",xPanelMod);

	id("tab-modificar").setAttribute("selectedIndex",pad);
	id("tab-modificar").setAttribute("selected","false");
	id("tab-modificar").setAttribute("selectedItem",xPanelMod);
	id("tab-modificar").setAttribute("afterselected","false");

	id("tab-busca").setAttribute("selectedIndex",pad);
	id("tab-busca").setAttribute("selected","true");
	id("tab-busca").setAttribute("selectedItem",xPanelMod);
	id("tab-busca").setAttribute("beforeselected","false");

}


function PanelModoEdicionGasto() {
	var xPanelMod = id("tab-modificar");
	var pad = 2;
	var pG = id("panelGastos");	
	if (!pG) return;	
	var pBox = id("tabGastos");
	
	
	pBox.setAttribute("selectedIndex",pad);
	pBox.setAttribute("selectedTab",pad);
	pBox.setAttribute("selectedPanel",xPanelMod);
	
	pG.setAttribute("selectedIndex",pad);
	

	id("tab-alta").setAttribute("selectedIndex",pad);
	id("tab-alta").setAttribute("selected","false");
	id("tab-alta").setAttribute("selectedItem",xPanelMod);
	id("tab-alta").setAttribute("beforeselected","false");
	
	id("tab-busca").setAttribute("selectedIndex",pad);
	id("tab-busca").setAttribute("selected","false");
	id("tab-busca").setAttribute("beforeselected","true");
	id("tab-busca").setAttribute("selectedItem",xPanelMod);
	
	id("tab-modificar").setAttribute("selectedIndex",pad);
	id("tab-modificar").setAttribute("selected","true");
	id("tab-modificar").setAttribute("selectedItem",xPanelMod);
	id("tab-modificar").setAttribute("afterselected","false");
		
	//id("tab-alta").setAttribute("selectedIndex",pad);
	//id("tab-alta").setAttribute("selectedIndex",pad);
	//id("tab-alta").setAttribute("selectedIndex",pad);
}
	
	
/*------------------------------------------------*/
/*------------------- Busqueda de Gastos-----------------------------*/

function toFechaModoHispano( Fecha ){
	// AAAA-MM-DD => DD-MM-AAA
	var datos = Fecha.split("-");
	return datos[2]+"-"+datos[1]+"-"+datos[0];	
}

var lbuscas = new Array();

function onResetearBusqueda(){
	lbuscas = new Array();
	EliminarLineas();
}


function EliminarLineas(){
	var ibuscas = lbuscas.length;
	var xabuelo = id("lista-gastos");
	
	for(var t=0;t<ibuscas;t++){		
		var tid = lbuscas[t];
		var xpadre = id("idlineabusca_" + tid);
		if (xpadre) {		
			xpadre.removeChild( id("idlineabusca_nombre_"+tid) );
			xpadre.removeChild( id("idlineabusca_numfac_"+tid) );
			xpadre.removeChild( id("idlineabusca_fecha_"+tid) );
			xpadre.removeChild( id("idlineabusca_importe_"+tid) );
			xpadre.removeChild( id("idlineabusca_cobro_"+tid) );
			xpadre.removeChild( id("idlineabusca_gasto_"+tid) );
			xpadre.removeChild( id("idlineabusca_operador_"+tid) );	
			xabuelo.removeChild( xpadre);
		}
		
		
	}
	
	lbuscas = new Array();
}





function CreaLineaBusqueda(IdGasto,NombreProv, NumFac, Fecha, ImporteTotal, 
	Cobro, Gasto, Operador,Concepto ){

	var ibuscas = lbuscas.length;
	
	for(var t=0;t<ibuscas;t++) {
		if (lbuscas[t] == IdGasto){
			//Ya existe ese gasto en ventana de buscar
			//alert("IdGasto conocido: " + IdGasto);
			return;	
		}
	}
	
	lbuscas[ibuscas] = IdGasto;//Registrar como a�adido
	
	//alert("Con: " + Concepto);
	
	if (id("sImpagos").checked && (parseFloat(ImporteTotal) <= parseFloat(Gasto))){
		//Oculta los pagados completos
		return;
	}
	

	Fecha = toFechaModoHispano( Fecha );

	var xl = document.createElement("listitem");
	xl.setAttribute("id","idlineabusca_" + IdGasto);
	xl.setAttribute("ondblclick","ModGasto("+ IdGasto+")");
	
	if (Concepto)
		xl.setAttribute("tooltiptext",Concepto);	
	
	var xnombre = document.createElement("label");	
	xnombre.setAttribute("value", NombreProv);	
	xnombre.setAttribute("id","idlineabusca_nombre_" + IdGasto);
	xnombre.setAttribute("crop","end");	
	xnombre.setAttribute("style","width: 8em;");	
	
	var xnumfac = document.createElement("label");
	xnumfac.setAttribute("value", NumFac);	
	xnumfac.setAttribute("id","idlineabusca_numfac_" + IdGasto);

	var xfecha = document.createElement("label");
	xfecha.setAttribute("value", Fecha);	
	xfecha.setAttribute("style", "width: 90px");	
	xfecha.setAttribute("id","idlineabusca_fecha_" + IdGasto);
	
	var ximporte = document.createElement("label");
	ximporte.setAttribute("value", FormateaDinero(ImporteTotal));		
	ximporte.setAttribute("id","idlineabusca_importe_" + IdGasto);		
	var extracss = "";
	if ( parseFloat(ImporteTotal) > parseFloat(Gasto) ){
		extracss= "color: red;"
	}	
	ximporte.setAttribute("style", "text-align: right;"+extracss);
	
	
	var xcobro = document.createElement("label");
	xcobro.setAttribute("value", FormateaDinero(Cobro));
	xcobro.setAttribute("style", "text-align: right");	
	xcobro.setAttribute("id","idlineabusca_cobro_" + IdGasto);
	
	var xgasto = document.createElement("label");
	xgasto.setAttribute("value", FormateaDinero(Gasto));	
	xgasto.setAttribute("style", "text-align: right");	
	xgasto.setAttribute("id","idlineabusca_gasto_" + IdGasto);//pagado?
	
	var xoperador = document.createElement("label");
	xoperador.setAttribute("value", Operador);	
	xoperador.setAttribute("id","idlineabusca_operador_" + IdGasto);
	xoperador.setAttribute("crop","end");	
	xoperador.setAttribute("style","width: 8em;");	
	
	var lgastos = id("lista-gastos");
	
	xl.appendChild( xnombre );
	xl.appendChild( xnumfac );
	xl.appendChild( xfecha );
	xl.appendChild( ximporte );
	xl.appendChild( xcobro );
	xl.appendChild( xgasto );
	xl.appendChild( xoperador );
	
	lgastos.appendChild( xl );	
}


function onBuscar(){
	
	EliminarLineas();
	
	var datos;
	var tProveedor 	= id("sProveedor").label;
	var tFacProv 	= id("sFacProv").value;
	var tFechaDesde	= id("sFechaDesde").value;
	var tFechaHasta	= id("sFechaHasta").value;
	var tCentroFis	= id("sCentroFis").value;
	
	//var tCentroAtr	= id("sCentroAtrib").label;	
	//var tCentroAtr	= id("sCentroAtrib").value;		
	
	var mydata = new Array( 
		"Proveedor",tProveedor,
		"FacProv",tFacProv,
		"CentroFis",tCentroFis,
		"FechaDesde",tFechaDesde,
		"FechaHasta",tFechaHasta
	);				
		
	var dataExtra = preparaData( mydata );	
	var url = "formgastos.php?modo=buscar";
	var resBus = EnviarData(url,dataExtra);
	
	//alert(resBus);
	var visto =0;
	var datosLinea = resBus.split("Gasto\:");	
	for(var t=0;t< datosLinea.length;t++){
				
		datos = datosLinea[t].split("\n");
		if ( datos.length > 4){
			//alert("agnadiendo datos.."+datos);
			
			/*
			visto = visto + 1;
			if(visto==2) {
				alert( datosLinea[t] );
				alert( "4: " + datos[4] );	
				
			}*/
				/*
				echo getNombreProvID($row["IdProveedor"]) . $cr; 
				echo $row["NumeroFacturaProv"] . $cr;
				echo $row["Fecha"] . $cr; --4 
				echo $row["ImporteTotal"] . $cr; ---5
				echo "0" . $cr;//Cobro -- 6 
				echo TotalPagos($row["IdGestionPagos"]) . $cr;  -- 7 
				echo getNombreOperador($row["UltimoOperador"]) . $cr; --8 
				*/
			//function CreaLineaBusqueda(IdGasto,NombreProv, NumFac, Fecha, ImporteTotal, Cobro, Gasto, Operador ){			
			CreaLineaBusqueda( datos[1],datos[2],datos[3],
				datos[4] /* Fecha */ ,
				datos[5] /*ImpTotal*/, datos[6] /*0*/,
				datos[7]/*TotalPagos*/,datos[8] /* Operador */, 
				datos[9] /* Concepto */ );
		}	
	}
	
}



/*------------------------------------------------*/
/*----------------------Envio de datos--------------------------*/

function LimpiaNombre( cons ){
	// quita espacios antes y despues y el simbolo de porcentaje
	cons = cons.replace(/\%/,"");		
	cons = cons.replace(/\s+$/g,"");		
	cons = cons.replace(/^\s+/,"");		
	//cons = cons.replace(/ /," ");		
	return cons;	
}

function LimpiaDinero( cons ){ //Vale para cadenas como esta "320,20EUR", no vale para dinero negro
	// quita espacios antes y despues y el simbolo de porcentaje
	cons = cons.replace(/EUR/,"");	
	cons = cons.replace(/\,/,"");	
	return cons;
}


var pakdata;

function PaqueteAtribucion(IdGasto) {	
	pakdata = new Array();
	var t=0;
	
	pakdata[(t*2)]="IdGasto";
	pakdata[(t*2+1)]=IdGasto; t++;
	
	var contenedor = id("attribCentros-list");
	var nombrepakelemento;
    var listado = contenedor.childNodes;
	var len = listado.length;
	pakdata[(t*2)] 		= "AtribNumCentros";
	pakdata[(t*2+1)] 	= len; t++;
	
	var text,nombre,centaje;
    for (var num=0;num<len;num++) {
		text = listado[num].label;
		text = text.split("---");
		nombre 	= LimpiaNombre(text[0]);
		centaje = LimpiaNombre(text[1]);	
		
		nombrepakelemento = "AtribCentro_Nombre_" + num;	
		pakdata[(t*2)]		= nombrepakelemento ;
		pakdata[(t*2+1)]	= nombre;	t++;	
		nombrepakelemento = "AtribCentro_Centaje_" + num;	
		pakdata[(t*2)]		= nombrepakelemento ;
		pakdata[(t*2+1)]	= centaje;	t++;	
		nombrepakelemento = "AtribCentro_IdCentro_" + num;	
		pakdata[(t*2)]		= nombrepakelemento ;
		pakdata[(t*2+1)]	= getIdCentro(nombre);	t++;			
	}	
}

function PaqueteActividades(IdGasto) {	
	pakdata = new Array();
	var t=0;
	
	pakdata[(t*2)]="IdGasto";
	pakdata[(t*2+1)]=IdGasto; t++;
	
	var contenedor = id("attribActividad-list");
	var nombrepakelemento;
    var listado = contenedor.childNodes;
	var len = listado.length;
	pakdata[(t*2)] 		= "AtribNumActiv";
	pakdata[(t*2+1)] 	= len; t++;
	
	var text,nombre,centaje;
    for (var num=0;num<len;num++) {
		text = listado[num].label;
		text = text.split("---");
		nombre 	= LimpiaNombre(text[0]);
		centaje = LimpiaNombre(text[1]);	
		
		nombrepakelemento = "AtribActiv_Nombre_" + num;	
		pakdata[(t*2)]		= nombrepakelemento ;
		pakdata[(t*2+1)]	= nombre;	t++;	
		nombrepakelemento = "AtribActiv_Centaje_" + num;	
		pakdata[(t*2)]		= nombrepakelemento ;
		pakdata[(t*2+1)]	= centaje;	t++;	
		nombrepakelemento = "AtribActiv_IdActiv_" + num;	
		pakdata[(t*2)]		= nombrepakelemento ;
		pakdata[(t*2+1)]	= getIdActiv(nombre);	t++;			
	}	
}


function PaqueteGastos(IdGasto) {	
	pakdata = new Array();
	var t=0;
	
	pakdata[(t*2)]="IdGasto";
	pakdata[(t*2+1)]=IdGasto; t++;
	
	var contenedor = id("gestionPagos-list");
	var nombrepakelemento;
    var listado = contenedor.childNodes;
	var len = listado.length;
	pakdata[(t*2)] 		= "AtribNumGastos";
	pakdata[(t*2+1)] 	= len; t++;
	
	var text,nombre,gasto;
    for (var num=0;num<len;num++) {
		text = listado[num].label;
		text = text.split("---");
		nombre 	= LimpiaNombre(text[0]);
		gasto = LimpiaDinero(LimpiaNombre(text[1]));	
		esPagado = id(listado[num].id + "_pago").checked;
		
		nombrepakelemento = "AtribGastos_Nombre_" + num;	
		pakdata[(t*2)]		= nombrepakelemento ;
		pakdata[(t*2+1)]	= nombre;	t++;	
		nombrepakelemento = "AtribGastos_Cantidad_" + num;	
		pakdata[(t*2)]		= nombrepakelemento ;
		pakdata[(t*2+1)]	= gasto;	t++;
		nombrepakelemento = "AtribGastos_Pagado_" + num;	
		pakdata[(t*2)]		= nombrepakelemento ;
		pakdata[(t*2+1)]	= esPagado;	t++;				
	}	
}

function onModificarDatos() {
	onEnvioDatos("modificacion");
}





function onEnvioDatos(isMod){
	var tConcepto		= id("Concepto").value;
	var tProv 			= id("Proveedor").label;
	var tFacProv		= id("FacProv").value;
	var tCenFiscal 		= id("CentroFiscal").value;
	var tFecha			= id("Fecha").value;
	var tImporteBase	= InterpretarFloatConfuso(id("ImporteBase").value);
	var tIVA			= InterpretarFloatConfuso(id("IVA").value);
	var tImporteTotal 	= InterpretarFloatConfuso(id("ImporteTotal").value);
	var tImportePagado 	= InterpretarFloatConfuso(id("ImportePagado").value);
	var tObservaciones 	= id("Comentarios").value;
	var tIdGasto 		= id("IdGastoActual").value;

	var enviarActiv 	= 1;
	var enviarCentros 	= 1;
	var enviarPagos		= 1;

	if(!tConcepto){
		tConcepto = "Otros";	
	}

	if (isMod){
		if (!cambioPagos)	enviarPagos = 0;
		if (!cambioActiv)	enviarActiv = 0;
		if (!cambioCentros) enviarCentros = 0;					
	}
	
	if (!tImporteBase || tImporteBase<0.01 ){
		return alert("Debe especificar un importe");
	}

	if (!tFecha || tFecha == "DD-MM-AAAA"){
		return alert("Debe especificar una fecha");
	}
	
	if (!tProv || tProv.length <1){
		return alert("Debe especificar Proveedor");
	}	
	
	if (!tFacProv || tFacProv.length <1 ){
		return alert("Debe especificar la factura de proveedor");
	}
	
	if (!tCenFiscal){
		return alert("Falta centro fiscal");
	}

	var tResto = parseInt(id("cuantoContribCentros-resto").value);
	if (tResto>1){
		if (!isMod || (isMod && cambioCentros) )
			return alert("Debe completar el reparto por centros");
	}

	var tRestoActiv = parseInt(id("cuantoContribActividad-resto").value);
	if (tRestoActiv>1){
		if (!isMod || (isMod && cambioActiv) )
			return alert("Debe completar el reparto por actividades");
	}

	CreaProgTimer("progAlta");


	var data = preparaData( new Array(
		'Concepto', tConcepto,	
		'Prov', tProv,
		'Fecha',tFecha,
		'FacProv',tFacProv,
		'CenFis',tCenFiscal,
		'ImporteBase', tImporteBase,
		'IVA', tIVA,
		'ImpTotal', tImporteTotal,
		'ImpPagado',tImportePagado,
		'Observaciones',tObservaciones,
		'IdGasto',tIdGasto
		));

	var sufix = "";
	if (isMod) sufix = "_mod"; //sufijo de comandos modo
		
	var url = "formgastos.php?modo=alta"+sufix;
	var res = EnviarData(url,data);
	//alert("respuesta: "+res);
	if (res.match("=")){
		IdGasto = res.split("=")[1];
		res = res.split("=")[0];				
	}
	
	UpdateProgValue("progAlta",40);
		
	switch(res.toLowerCase()){
		case "error":
			alert("Servidor no disponible, intentelo mas tarde");
			return 0;
		case "favprovusado":
			alert("Fact de proveedor ya en uso");								
			return 0;
		case "ok":
			//var Atribuciones = PaqueteAtribucion(IdGasto);		
			if (enviarCentros){
				PaqueteAtribucion(IdGasto);					
				var dataExtra = preparaData( pakdata );
			
				var url = "formgastos.php?modo=atrib";			
				resAtrib = EnviarData(url,dataExtra);	
			}
			UpdateProgValue("progAlta",60);
			//	
			if (enviarActiv){
				PaqueteActividades(IdGasto);					
				dataExtra = preparaData( pakdata );
				url = "formgastos.php?modo=activ";
				resAtrib = EnviarData(url,dataExtra);	
			}
			UpdateProgValue("progAlta",80);
			//alert(resAtrib);
			if (enviarPagos){
				PaqueteGastos(IdGasto);		
				dataExtra = preparaData( pakdata );
				url = "formgastos.php?modo=gastos";		
				resAtrib = EnviarData(url,dataExtra);				
			}			
			UpdateProgValue("progAlta",0);
			
			AgnadirProveedorSiNoExiste(tProv);
			
			if (!isMod)
				LimpiaCampos();
													
			Blink("FormaDeGastos","groupbox");
			
			return 1;
		default:
			alert("Error desconocido");
			return 0;
		break;		
	}	
}

function preparaData( datos ){	
	var out;
	for(var t=0;t< datos.length;t=t+2){
		out = out + "&" +datos[t]+"="+escape(datos[t+1]);
	}		
	return out;
}

function EnviarData(urlpost,datapost){
	var xrequest = new XMLHttpRequest();

	xrequest.open("POST",urlpost,false);
	xrequest.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	xrequest.send(datapost);
	
	return xrequest.responseText;
}

/*------------------------------------------------*/
/*-------------------Recalculo de importe-----------------------------*/


function InterpretarFloatConfuso(cons){
	cons = cons.replace(",",".");
	cons = parseFloat( cons );
	return cons;
}

function onChangeImporte(){
	var iva = parseFloat(id("IVA").value);
	var impor = parseFloat(InterpretarFloatConfuso(id("ImporteBase").value));
	if (isNaN(iva))		iva = 0;
	if (isNaN(impor))		impor = 0;
	
	id("ImporteTotal").value = iva + impor;
}

/*------------------------------------------------*/
/*----------------------Atribuciones--------------------------*/

function CorrigeValor(xPadrelist, xCasilla, xdestino, cantidad, xdestino2 ){
	Eliminar(xPadrelist, xCasilla);
	Aumentar(xdestino,cantidad);
	Aumentar(xdestino2,cantidad);
}


function AddAttribLine( xLista, IdCosa, NombreCosa, Cantidad, destinoRestore, destinoRestore2 ){
	var xlistacosa = id(xLista);
	if (!xlistacosa) return alert("E: no xlistacosa");
	var xmenuitem = document.createElement("listitem");	
	var nombreElemento = xLista + "_" + IdCosa;
	xmenuitem.setAttribute("label",NombreCosa + " --- " + Cantidad + " %");
	xmenuitem.setAttribute("value",Cantidad);
	xmenuitem.setAttribute("id",nombreElemento);	
	//xmenuitem.setAttribute("ondblclick",'Eliminar("'+xLista+'","'+nombreElemento+'");Aumentar("'+destinoRestore+'","'+Cantidad+'");Aumentar("'+destinoRestore2+'","'+Cantidad+'")');	
	//xmenuitem.setAttribute("ondblclick",'CorrigeValor("'+xLista+'","'+nombreElemento+'","'+destinoRestore+'","'+Cantidad+'","'+destinoRestore2+'")');	
	//cuantoContribCentros-resto
	
	setId(xLista,NombreCosa, IdCosa);//Mantiene un diccionario NombreCentro<-->IdCentro
	
	xlistacosa.appendChild( xmenuitem) ;
}

function CalcularRestoAttrib(){
	return 0;
}

function onAtribCentros() {
	var xCuanto = id("cuantoContribCentros");
	var xdonde = id("attribCentros");
	if(!xdonde) return alert("Aplication Error: no xdonde");
	var IdCentro 		= xdonde.value;	
	var NombreCentro 	= xdonde.label;
	
	var oldAdded = id("attribCentros-list_" + IdCentro);
	if (oldAdded){	//Ya se a�adio esa linea
		var cmdRemove =  oldAdded.getAttribute("ondblclick");
		//alert(  cmdRemove );
		eval( cmdRemove );
		//return;
	}
	
	
	var resto = parseInt( id("cuantoContribCentros-resto").value ); 
	if (resto<1)		return alert("Distribucion % completa");
	var requerido		= xCuanto.value;
	
	if (requerido>resto){
		return alert("Cantidad superior al resto");
	}

	AddAttribLine("attribCentros-list", IdCentro , NombreCentro, requerido,"cuantoContribCentros-resto","cuantoContribCentros" );
	var nuevoresto = parseInt(resto) - parseInt(xCuanto.value);
	id("cuantoContribCentros-resto").value 	= nuevoresto;
	xCuanto.value 							= nuevoresto;
	//alert("Terminado bien cuanto:"+ IdCentro +":id, "+ NombreCentro +":nc, "+ xCuanto.value+":cuanto" );
	
	RecalculoCentros();
	
}


function onAtribActividad() {
	var xCuanto = id("cuantoContribActividad");
	var xdonde = id("attribActividad");
	if(!xdonde) return alert("Aplication Error: no xdonde");
	var IdCentro 		= xdonde.value;	
	var NombreCentro 	= xdonde.label;
	
	var oldAdded = id("attribActividad-list_" + IdCentro);
	if (oldAdded){	//Ya se a�adio esa linea
		eval( oldAdded.getAttribute("ondblclick") );
		//alert("hi!");
	}
	
	
	var resto = parseInt( id("cuantoContribActividad-resto").value ); 
	if (resto<1)		return alert("Distribucion % completa");
	var requerido		= parseInt(InterpretarFloatConfuso(xCuanto.value));
	
	if (requerido>resto){
		return alert("Cantidad superior al resto");
	}

	AddAttribLine("attribActividad-list", IdCentro , NombreCentro, requerido,"cuantoContribActividad-resto","cuantoContribActividad" );
	var nuevoresto = parseInt(parseInt(resto) - InterpretarFloatConfuso(xCuanto.value));
	id("cuantoContribActividad-resto").value 	= nuevoresto;
	xCuanto.value 							= nuevoresto;
	RecalculoActividad();		
}

/*------------------------------------------------*/

/*---------------------- Pagos --------------------------*/


function AutoAjusteImporte(){
	var ipago = id("ImportePagado");	
	ipago.value = CalculoRealDePagos().toFixed(2);		
}

function AutoAjuste(){
	setTimeout("AutoAjusteImporte()",100);
}



function CalculoRealDePagos(){
	
	var node, len, idPago,xpago;
	//alert("doing...");

	var xmylist = document.getElementById("gestionPagos-list");	
	
	if (!xmylist)	return alert("no xmylist!");
	var xsel = xmylist.childNodes;	
	
	var len = xsel.length;
	var Total = 0;
	for(var t=0;t<len;t++){
		node = xmylist.childNodes[t];		
		idPago = node.id + "_pago";
		xpago = id(idPago);
		
		//Solo se contabiliza aquellos marcados como pagados.
		if (xpago.checked)	
			Total = parseFloat(Total) + parseFloat(node.value);
			
		
	}
	
	return  Total;	
}



function EliminarLineaPago(nombre){
	//Eliminar linea de pago/cobro. Vale para los dos tipos (autodetecta).
	var padre = "gestionPagos-list";

	//Eliminar( padre,nombre + "_label");	
	//Eliminar( padre,nombre + "_eur");	
//	Eliminar( padre,nombre + "_pago");
	Eliminar( padre,nombre );
}

function UpdateImportePagado(cambio){
	//OBSOLETO
}

function onAgregarPago(){	
	var resetFecha	= "DD-MM-AAAA";
	var xpago 		= id("newPago");
	var xfecha 		= id("newFecha");
	if (xfecha.value== resetFecha){
		return alert("Debe especificar una fecha");
	}
	
	pagoValue = parseFloat(xpago.value);
	if (pagoValue<=0){
		return alert("Debe especificar una cantidad");
	}
	
	xAddPago("gestionPagos-list",xfecha.value,pagoValue,true);
	
	//UpdateImportePagado( pagoValue );
	
	xfecha.value	= resetFecha;
	xpago.value		= 0;

	cambioGestionDinero();	
	AutoAjuste();
}




function xAddPago( xLista, FechaPago, Cantidad, pagado ){
///	FixesLabels();
	var xlistacosa = id(xLista);
	if (!xlistacosa) return alert("E: no xlistacosa");	
	var xmenuitem = document.createElement("listitem");	
	var nombreElemento = xLista + "_" + parseInt(Math.random()*39000);
	var desc = 	FechaPago + " --- " + Cantidad + " EUR"
		
	if (pagado == 0) pagado = false;
	else if (pagado == "0")	pagado = false;	
	
	xmenuitem.setAttribute("label",desc);
	xmenuitem.setAttribute("value",Cantidad);
	xmenuitem.setAttribute("id",nombreElemento);	
	
//	xmenuitem.setAttribute("onclick","TogglePago('"+nombreElemento+"')");		
//	xmenuitem.setAttribute("ondblclick","EliminarLineaPago('"+nombreElemento+"')");
//	xmenuitem.setAttribute("tooltiptext","Dobleclick para quitar elemento");	
		
//	var xcell = document.createElement("listcell");	
//	xcell.setAttribute("label",FechaPago);
//	xcell.setAttribute("id",nombreElemento + "_label");	

	var xcell1 = document.createElement("listcell");	
//	xcell1.setAttribute("label",Cantidad + " EUR");
	xcell1.setAttribute("label",desc);
	xcell1.setAttribute("id",nombreElemento + "_eur");
	
	var xcell3 = document.createElement("checkbox");
	xcell3.setAttribute("label","pagado");
	xcell3.setAttribute("checked",((pagado)?"true":false));
	xcell3.setAttribute("id",nombreElemento + "_pago");
//	xcell3.setAttribute("onclick","TogglePago('"+nombreElemento+"')");	

//	xmenuitem.appendChild( xcell );	
	xmenuitem.appendChild( xcell1 );		
	xmenuitem.appendChild( xcell3 );		
	//xmenuitem.appendChild( document.createElement("listcell"));		
		
	xlistacosa.appendChild( xmenuitem) ;
}

/*------------------------------------------------*/

/*---------------------- ProgTimer --------------------------*/

var progactual = new Array();

function UpdateProgTimer(progName){
	var actual = progactual[progName]; //valor actual deseado del progressbar 
	var dx = 10; //paso de rosca 
	
	if (!id(progName)) return;	
	
	if (actual == 0) {
		setactual = 100;
		progactual[progName] = -1;				
	} else if (actual == -1) {
		setactual = 0;
		progactual[progName] = 0;
		id(progName).value = setactual;
		return;
	} else if (actual < min) {
		setactual = dx + 1;
	} else {
		setactual = actual - min;
	}	
	
	id(progName).value = setactual;

	setTimeout("UpdateProgTimer('"+progName+"')",10);
}

function CreaProgTimer(progName){
	var xprog = id(progName);	
	if (!xprog)
		return;
	xprog.value = 100;
	progactual[progName] = 0;
	setTimeout("UpdateProgTimer('"+progName+"')",10);
}

function UpdateProgValue(progName,nuevoValor){
	if (!id(progName)) return;

	id(progName).value 		= nuevoValor;
	progactual[progName] 	= nuevoValor;
}

/*------------------------------------------------*/


/*---------------------Eliminar items---------------------------*/


function EliminarElementoSeleccionadoActividad(){	
	var xlist = id("attribActividad-list");
	var xsel = xlist.selectedItem;
	
	if (!xsel)		return;
	
	cambioActiv = 1;	
	CorrigeValor("attribActividad-list", xsel.id, "cuantoContribActividad-resto", xsel.value, "cuantoContribActividad");	
	
	RecalculoActividad();
}




function EliminarElementoSeleccionadoCentros(){	
	var xlist = id("attribCentros-list");
	var xsel = xlist.selectedItem;
	
	if (!xsel)		return;
	
	cambioCentros = 1;	
	CorrigeValor("attribCentros-list", xsel.id, "cuantoContribCentros-resto", xsel.value, "cuantoContribCentros");	
	
	RecalculoCentros();
}



function EliminarElementoSeleccionadoDinero(){
	var xlist = id("gestionPagos-list");
	if (!xlist)
		xlist = id("gestionCobros-list");
		
	var xsel = xlist.selectedItem;
	
	if (!xsel)		return;
	
	cambioPagos = 1;
	
	//UpdateImportePagado( 0 - xsel.value );
	EliminarLineaPago(xsel.id);
	
	AutoAjuste();
}





function MarcarElementoSeleccionadoDinero(estado){
	var xmylist = document.getElementById("gestionPagos-list");	
	
	if (!xmylist)	return alert("no xmylist!");

	var xsel = xmylist.selectedItem;
	if (!xsel)	return alert("no xsel!");
	
	cambioCobros = 1;
	
	SetPago( xsel.id, estado);
}


/*------------------------------------------------*/



/*---------------------- Eliminar altas ---------------------*/

function FormateaDinero( cons ){ //Toma un float, devuelve un numero con formato 233.32 EUR
	//alert("entra" + cons);
	cons = parseFloat(cons);
	cons = cons.toFixed(2);
	//alert("sale" + cons);
	return cons + " EUR";	
}


function CancelarAlta(){
	LimpiaCampos();	
}

function LimpiaCampos(){
	//alert("Limpiando campos");
	

	id("IVA").value 	= "";
	id("FacProv").value 	= "";
	id("Fecha").value 	= "DD-MM-AAAA";
	//id("CentroFiscal").value = "";
	
	id("ImporteTotal").value 	= "";
	id("ImportePagado").value 	= "";
	id("Comentarios").value 	= "";
	id("ImporteBase").value		= "";
	
	id("Concepto").value 		= "";

	EliminarTodosActividad();
		
	id("cuantoContribActividad-resto").value 	= 100;
	id("cuantoContribActividad").value 			= 100;
	
	EliminarTodosCentros();

	id("cuantoContribCentros-resto").value 	= 100;
	id("cuantoContribCentros").value 		= 100;
	
	EliminarTodosDinero();
	
	var xprov =	id("Proveedor");
	xprov.label = "";
	xprov.value = "";
	xprov.setAttribute("label","");
	xprov.setAttribute("value","");
}

function removeAllItems( mylist ) {

	var xbox = id( mylist );
	var xelement;
	
	if(!xbox)
		return alert("No xbox found");

	var nods = xbox.childNodes;
	
	for (var t=0;t<nods.length;t++){
		eval( nods[t].getAttribute("ondblclick") );	
	}
	
}
/*------------------------------------------------*/



/*---------------- Eliminar un registro --------------------*/

function EliminarRegistro(){
	if (confirm('¿Desea eliminar este registro?')) {
		document.location = "modgasto.php?modo=eliminarregistro&id=" + id("IdGastoActual").value;
	}
}
/*------------------------------------------------*/

/*------------- Distribuciones Real ------------------*/

function RecalculoActividad(){
	ProgramarRecalculo( "attribActividad-list","cuantoContribActividad-resto","cuantoContribActividad");
}

function RecalculoCentros(){
	ProgramarRecalculo( "attribCentros-list","cuantoContribCentros-resto","cuantoContribCentros");
}


function ProgramarRecalculo( xlistaDistroid, xrestoid, xseguirid){
	setTimeout('CalculoRealDeDistro("'+xlistaDistroid+'","'+xrestoid+'","'+xseguirid+'")',50);
}

function CalculoRealDeDistro(xlistaDistroid, xrestoid,xseguirid){
	var node, len;
	//alert("doing...");

	var xmylist = document.getElementById(xlistaDistroid);	
	
	if (!xmylist)	return alert("no xmylist!");
	var xsel = xmylist.childNodes;	
	
	//Calcula el total de lo mostrado
	var len = xsel.length;
	var Total = 100;
	for(var t=0;t<len;t++){
		node = xmylist.childNodes[t];		
		Total = parseFloat(Total) - parseFloat(node.value);
	}
	
	//Regulariza
	if (Total<0) Total = 0;
	else if (Total>100) Total = 100;
	
	//Ajusta los campos del interfaz
	id(xrestoid).value = Total;
	id(xseguirid).value = Total;
	
	//alert("Nuevo total es: " + Total);
}
/*-------------------------------------*/
