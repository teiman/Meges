

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
/*-----------------------------------------------*/


function Aumentar(xdestino,cantidad){
	var xdes = id(xdestino);
	var res =	parseInt(parseInt(xdes.value) + parseInt(cantidad));
	
	if (res>100) res = 100;
	xdes.value = res;
}

/*------------------- Busqueda de ingresos-----------------------------*/

function CancelarModIngreso(){
	PanelModoBuscar();
}


function ModIngreso(IdIngreso){
	if (!IdIngreso) return;
	var xiframemodIngreso = id("framemodingreso");
	if (!xiframemodIngreso) return;	
	xiframemodIngreso.setAttribute("src","about:blank");	
	setTimeout("ModIngreso2("+IdIngreso+")",100);
}

function ModIngreso2(IdIngreso){
	if (!IdIngreso) return;
	var xiframemodIngreso = id("framemodingreso");
	if (!xiframemodIngreso) return;	
	xiframemodIngreso.setAttribute("src","modingreso.php?modo=abrirmodificar&id="+IdIngreso+"&r=" +Math.random());	
	setTimeout("PanelModoEdicionIngreso()",200);
}



function PanelModoBuscar(){
	var xPanelMod = id("tab-busca");
	var pad = 1;
	var pG = id("panelingresos");	
	if (!pG) return;	
	var pBox = id("tabingresos");

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



function PanelModoEdicionIngreso() {
	var xPanelMod = id("tab-modificar");
	var pad = 2;
	var pG = id("panelingresos");	
	if (!pG) return;	
	var pBox = id("tabingresos");
	
	
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
/*------------------- Busqueda de ingresos-----------------------------*/

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
	var xabuelo = id("lista-ingresos");
	
	for(var t=0;t<ibuscas;t++){		
		var tid = lbuscas[t];
		var xpadre = id("idlineabusca_" + tid);
		if (xpadre) {		
			xpadre.removeChild( id("idlineabusca_nombre_"+tid) );
			xpadre.removeChild( id("idlineabusca_numfac_"+tid) );
			xpadre.removeChild( id("idlineabusca_fecha_"+tid) );
			xpadre.removeChild( id("idlineabusca_importe_"+tid) );
			xpadre.removeChild( id("idlineabusca_cobro_"+tid) );
			xpadre.removeChild( id("idlineabusca_Ingreso_"+tid) );
			xpadre.removeChild( id("idlineabusca_operador_"+tid) );	
			xabuelo.removeChild( xpadre);
		}				
	}
	
	lbuscas = new Array();
}

			//	datos[1],datos[2],datos[3],
			//	datos[4] /* Fecha */ ,
			//	datos[5] /*ImpTotal*/, datos[6] /*ImporteConbrado*/,
			//	datos[7]/*TotalCobros*/,datos[8] /*NombreOperador*/ );

function CreaLineaBusqueda( IdIngreso,NombreProv, NumFac, 
							Fecha, 
							ImporteTotal, Cobro, 
							Ingreso, Operador , Concepto ){

	var ibuscas = lbuscas.length;
	
	for(var t=0;t<ibuscas;t++) {
		if (lbuscas[t] == IdIngreso){
			//Ya existe ese Ingreso en ventana de buscar
			//alert("IdIngreso conocido: " + IdIngreso);
			return;	
		}
	}
	
	lbuscas[ibuscas] = IdIngreso;//Registrar como a�adido	

	Fecha = toFechaModoHispano( Fecha );


	if (id("sImpagos").checked && (parseFloat(ImporteTotal) <= parseFloat(Ingreso))){
		//Oculta los pagados completos
		return;
	}

	var xl = document.createElement("listitem");
	xl.setAttribute("id","idlineabusca_" + IdIngreso);
	xl.setAttribute("ondblclick","ModIngreso("+ IdIngreso+");");
	
	if (Concepto)
		xl.setAttribute("tooltiptext",Concepto);
	
	
	var xnombre = document.createElement("label");	
	xnombre.setAttribute("value", NombreProv);//Serie?	
	xnombre.setAttribute("id","idlineabusca_nombre_" + IdIngreso);
	xnombre.setAttribute("crop","end");	
	xnombre.setAttribute("style","width: 8em;");	

	var xnumfac = document.createElement("label");
	xnumfac.setAttribute("value", NumFac);	
	xnumfac.setAttribute("id","idlineabusca_numfac_" + IdIngreso);

	var xfecha = document.createElement("label");
	xfecha.setAttribute("value", Fecha);	
	xfecha.setAttribute("style", "width: 90px");	
	xfecha.setAttribute("id","idlineabusca_fecha_" + IdIngreso);
	
	var ximporte = document.createElement("label");
	//ximporte.setAttribute("value", parseFloat(ImporteTotal) + " EUR");	
	ximporte.setAttribute("value", FormateaDinero(ImporteTotal));			
	var extracss = "";
	if ( parseFloat(ImporteTotal) > parseFloat(Ingreso) ){
		extracss= "color: red;"
	}	
	ximporte.setAttribute("style", "text-align: right;"+ extracss);		
	ximporte.setAttribute("id","idlineabusca_importe_" + IdIngreso);	
	
	var xcobro = document.createElement("label");
	//xcobro.setAttribute("value", parseFloat(Cobro) + " EUR");
	xcobro.setAttribute("value", FormateaDinero(Cobro) );
	
	
	xcobro.setAttribute("style", "text-align: right");	
	xcobro.setAttribute("id","idlineabusca_cobro_" + IdIngreso);
	//,1,,43,2005-11-30,886,43,,Desconocido,
	
	var xIngreso = document.createElement("label");
	//xIngreso.setAttribute("value", parseFloat(Ingreso) + " EUR");	
	xIngreso.setAttribute("value", FormateaDinero(Ingreso));	
	xIngreso.setAttribute("style", "text-align: right");	
	xIngreso.setAttribute("id","idlineabusca_Ingreso_" + IdIngreso);
	
	var xoperador = document.createElement("label");
	xoperador.setAttribute("value", Operador);	
	xoperador.setAttribute("id","idlineabusca_operador_" + IdIngreso);
	xoperador.setAttribute("crop","end");	
	xoperador.setAttribute("style","width: 8em;");	
	
	
	var lingresos = id("lista-ingresos");
	
	xl.appendChild( xnombre );
	xl.appendChild( xnumfac );
	xl.appendChild( xfecha );
	xl.appendChild( ximporte );
	xl.appendChild( xcobro );
	xl.appendChild( xIngreso );
	xl.appendChild( xoperador );
	
	lingresos.appendChild( xl );	
}



function onBuscar(){

	EliminarLineas();
	
	var datos;
	var tSerie 			= id("sSerie").value;
	var tNumFac 		= id("sNumFac").value;
	var tFechaDesde		= id("sFechaDesde").value;
	var tFechaHasta		= id("sFechaHasta").value;
	var tCentroFis		= id("sCentroFiscal").value;	
	var tImporteBase 	= id("sImporteBase").value;	
	var tImporteTotal 	= id("sImporteTotal").value;	
	var tImporteCobrado = id("sImporteCobrado").value;	
	var tSoloPendientes	= (id("sImpagos").checked)? "1":"0";  
	
	var mydata = new Array( 
		"SoloPendientes",tSoloPendientes,
		"Serie", tSerie,
		"NumFac",tNumFac,
		"FechaDesde",tFechaDesde,
		"FechaHasta",tFechaHasta,
		"CentroFis",tCentroFis,
		"ImporteTotal",tImporteTotal,
		"ImporteCobrado",tImporteCobrado,
		"ImporteBase",tImporteBase	
	);
				
	dataExtra = preparaData( mydata );	
	url = "formingresos.php?modo=buscar";
	resBus = EnviarData(url,dataExtra);
	
	//alert(resBus);
	var visto =0;
	var datosLinea = resBus.split("Ingreso\:");	
	for(var t=0;t< datosLinea.length;t++){
				
		datos = datosLinea[t].split("\n");
		if ( datos.length > 4){
			//function CreaLineaBusqueda(IdIngreso,NombreProv, NumFac, Fecha, ImporteTotal, Cobro, Ingreso, Operador ){			
			//prompt("DATOS:",datos);
			CreaLineaBusqueda( datos[1],datos[2],datos[3],
				datos[4] /* Fecha */ ,
				datos[5] /*ImpTotal*/, datos[6] /*ImporteConbrado*/,
				datos[7]/*TotalCobros*/,datos[8] /*NombreOperador*/,
				datos[9] /*Concepto*/ );
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
	//cons = cons.replace(/ /,"");		
	return cons;	
}

function LimpiaDinero( cons ){ //Vale para cadenas como esta "320,20EUR", no vale para dinero negro
	// quita espacios antes y despues y el simbolo de porcentaje
	cons = cons.replace(/EUR/,"");	
	cons = cons.replace(/\,/,"");	
	return cons;
}

function FormateaDinero( cons ){ //Toma un float, devuelve un numero con formato 233.32 EUR
	//alert("entra" + cons);
	cons = parseFloat(cons);
	cons = cons.toFixed(2);
	//alert("sale" + cons);
	return cons + " EUR";	
}


var pakdata;

function PaqueteAtribucion(IdIngreso) {	
	pakdata = new Array();
	var t=0;
	
	pakdata[(t*2)]="IdIngreso";
	pakdata[(t*2+1)]=IdIngreso; t++;
	
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

function PaqueteActividades(IdIngreso) {	
	pakdata = new Array();
	var t=0;
	
	pakdata[(t*2)]="IdIngreso";
	pakdata[(t*2+1)]=IdIngreso; t++;
	
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


function Paqueteingresos(IdIngreso) {	
	pakdata = new Array();
	var t=0;
	
	pakdata[(t*2)]="IdIngreso";
	pakdata[(t*2+1)]=IdIngreso; t++;
	
	var contenedor = id("gestionCobros-list");
	var nombrepakelemento;
    var listado = contenedor.childNodes;
	var len = listado.length;
	pakdata[(t*2)] 		= "AtribNumingresos";
	pakdata[(t*2+1)] 	= len; t++;
	
	var text,nombre,Ingreso;
    for (var num=0;num<len;num++) {
		text = listado[num].label;
		text = text.split("---");
		nombre 	= LimpiaNombre(text[0]);
		Ingreso = LimpiaDinero(LimpiaNombre(text[1]));	
		esPagado = id(listado[num].id + "_pago").checked;
		
		nombrepakelemento = "Atribingresos_Nombre_" + num;	
		pakdata[(t*2)]		= nombrepakelemento ;
		pakdata[(t*2+1)]	= nombre;	t++;	
		nombrepakelemento = "Atribingresos_Cantidad_" + num;	
		pakdata[(t*2)]		= nombrepakelemento ;
		pakdata[(t*2+1)]	= Ingreso;	t++;	
		nombrepakelemento = "Atribingresos_Pagado_" + num;	
		pakdata[(t*2)]		= nombrepakelemento ;
		pakdata[(t*2+1)]	= esPagado;	t++;		
	}	
}

function onModificarDatos() {
	onEnvioDatos("modificacion");
}








function onEnvioDatos(isMod){
	//var tProv = id("Proveedor").label;
	//var tFacProv	= id("FacProv").value;
	var tConcepto		= id("Concepto").value;
	var tSerie			= id("Serie").value;
	var tNumFac			= id("NumFac").value;
	
	var tCenFiscal 		= id("CentroFiscal").value;
	var tFecha			= id("Fecha").value;
	var tImporteBase	= id("ImporteBase").value;
	var tIVA			= id("IVA").value;
	var tImporteTotal 	= id("ImporteTotal").value;
	var tImporteCobrado = id("ImporteCobrado").value;
	var tObservaciones 	= id("Comentarios").value;
	var tIdIngreso 		= id("IdIngresoActual").value;

	var enviarActiv		= 1;
	var enviarCentros 	= 1;
	var enviarCobros	= 1;

	if(!tConcepto){
		tConcepto = "Otros";	
	}

	if (isMod){
		if (!cambioCobros)	enviarCobros = 0;
		if (!cambioActiv)	enviarActiv = 0;
		if (!cambioCentros) enviarCentros = 0;					
	}

	if (!tImporteBase || tImporteBase<0.01 ){
		return alert("Debe especificar un importe");
	}
	
	if (!tSerie || tSerie.length <1){
		return alert("Debe especificar una Serie de factura");
	}	
	
	if (!tNumFac || tNumFac.length <1){
		return alert("Debe especificar un numero de factura");
	}
	

	if (tFecha == "DD-MM-AAAA" || tFecha == "" || !tFecha){
		return alert("Debe especificar una fecha");
	}

	var tResto = parseInt(id("cuantoContribCentros-resto").value);
	if (tResto>1 && enviarCentros){						
		return alert("Debe completar el reparto por centros");
	}

	var tRestoActiv = parseInt(id("cuantoContribActividad-resto").value);
	if (tRestoActiv>1){
		if (!isMod || (isMod && cambioActiv) )
			return alert("Debe completar el reparto por actividades");
	}
	
	
	
	var data = preparaData( new Array(
		'Concepto', tConcepto,
		'Serie',tSerie,
		'NumFac',tNumFac,	
		'Fecha',tFecha,
		'CenFis',tCenFiscal,
		'ImporteBase', tImporteBase,
		'IVA', tIVA,
		'ImpTotal', tImporteTotal,
		'ImpCobrado',tImporteCobrado,
		'Observaciones',tObservaciones,
		'IdIngreso',tIdIngreso
		));

	var sufix = "";
	if (isMod) sufix = "_mod"; //sufijo de comandos modo
	
	var url = "formingresos.php?modo=alta"+sufix;
	var res = EnviarData(url,data);
	//alert("respuesta: "+res);
	if (res.match("=")){
		IdIngreso = res.split("=")[1];
		res = res.split("=")[0];				
	}
		
	switch(res.toLowerCase()){
		case "error":
			alert("Servidor no disponible, intentelo mas tarde");
			return 0;
		case "numfacusado":
			alert("Numero de factura ya en uso");								
			return 0;
		case "ok":
			//var Atribuciones = PaqueteAtribucion(IdIngreso);		
			if (enviarCentros){
				PaqueteAtribucion(IdIngreso);					
				var dataExtra = preparaData( pakdata );
			
				var url = "formingresos.php?modo=atrib";			
				resAtrib = EnviarData(url,dataExtra);	
				
				cambioCentros = 0;
			}
			//	
			if (enviarActiv){
				PaqueteActividades(IdIngreso);					
				dataExtra = preparaData( pakdata );
				url = "formingresos.php?modo=activ";
				resAtrib = EnviarData(url,dataExtra);
				cambioActiv = 0;				
			}
			//alert(resAtrib);
			if (enviarCobros){
				Paqueteingresos(IdIngreso);		
				dataExtra = preparaData( pakdata );
				url = "formingresos.php?modo=ingresos";		
				resAtrib = EnviarData(url,dataExtra);				
				cambioCobros = 0;
			}			

			if(!isMod)
				LimpiaCampos();//Todo ha sido correcto, asi que limpiamos el formulario.
			Blink("FormaDeIngresos","groupbox");
			
			return 1;
		default:
			alert("Error desconocido." + res);
			return 0;
		break;		
	}	
}

function preparaData( datos ){	
	var out;
	for(var t=0;t< datos.length;t=t+2){		
		//	out = out + "&" +datos[t]+"="+escape(datos[t+1]);
		out = out + "&" +datos[t]+"="+encodeURIComponent(datos[t+1]);			
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

function onChangeImporte(){
	var iva = parseFloat(id("IVA").value);
	var impor = parseFloat(id("ImporteBase").value);
	if (isNaN(iva))		iva = 0;
	if (isNaN(impor))		impor = 0;
	
	id("ImporteTotal").value = (iva + impor).toFixed(2);
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
	var requerido		= xCuanto.value;
	
	if (requerido>resto){
		return alert("Cantidad superior al resto");
	}

	AddAttribLine("attribActividad-list", IdCentro , NombreCentro, requerido,"cuantoContribActividad-resto","cuantoContribActividad" );
			
	var nuevoresto = parseInt(resto) - parseInt(xCuanto.value);
	id("cuantoContribActividad-resto").value 	= nuevoresto;
	xCuanto.value 							= nuevoresto;
	
	RecalculoActividad();
}

/*------------------------------------------------*/

/*---------------------- Cobros --------------------------*/

function EliminarLineaCobro(nombre){
	var padre = "gestionCobros-list";
	
	Eliminar( padre,nombre );
}




function UpdateImporteCobrado(cambio){
	//OBSOLETO
}

function AutoAjusteImporte(){
	var ipago = id("ImporteCobrado");	
	ipago.value = CalculoRealDeIngresos().toFixed(2);		
}

function AutoAjuste(){
	setTimeout("AutoAjusteImporte()",100);
}



function CalculoRealDeIngresos(){
	
	var node, len, idPago, xpago;
	//alert("doing...");

	var xmylist = document.getElementById("gestionCobros-list");	
	
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




function onAgregarCobro(){	
	var resetFecha 	= "DD-MM-AAAA";
	var xpago 		= id("newCobro");
	var xfecha 		= id("newFecha");
	if ( xfecha.value	== resetFecha ){
		return alert("Debe especificar una fecha");
	}
	
	pagoValue = parseFloat(xpago.value);
	if ( pagoValue <= 0 ){
		return alert("Debe especificar una cantidad");
	}
	
	xAddCobro("gestionCobros-list",xfecha.value,pagoValue,true);
	
	//UpdateImporteCobrado( pagoValue );
	
	xfecha.value	= resetFecha;
	xpago.value		= 0;
	
	//CalculoRealDeIngresos();	
	cambioGestionDinero();
	AutoAjuste();	
}

function xAddCobro( xLista, FechaCobro, Cantidad, cobrado ){
	var xlistacosa = id(xLista);
	if (!xlistacosa) return alert("E: no xlistacosa");
	var xmenuitem = document.createElement("listitem");	
	var nombreElemento = xLista + "_" + parseInt(Math.random()*39000) ;
	var desc = FechaCobro + " --- " + Cantidad + " EUR";
	
	if (cobrado == 0) cobrado = false;
	else if (cobrado == "0")	cobrado = false;		
	
	xmenuitem.setAttribute("label",desc);
	xmenuitem.setAttribute("value",Cantidad);
	xmenuitem.setAttribute("id",nombreElemento);	
	
	//xmenuitem.setAttribute("onclick","TogglePago('"+nombreElemento+"')");		
	//xmenuitem.setAttribute("ondblclick","EliminarLineaCobro('"+nombreElemento+"')");
	//xmenuitem.setAttribute("tooltiptext","Dobleclick para quitar elemento");	
		

	var xcell1 = document.createElement("listcell");	
	xcell1.setAttribute("label",desc);
	xcell1.setAttribute("id",nombreElemento + "_eur");
	
	var xcell3 = document.createElement("checkbox");
	xcell3.setAttribute("label","cobrado");
	xcell3.setAttribute("checked",((cobrado)?"true":false));
	xcell3.setAttribute("id",nombreElemento + "_pago");

	xmenuitem.appendChild( xcell1 );		
	xmenuitem.appendChild( xcell3 );		
	
		
	xlistacosa.appendChild( xmenuitem) ;
}

/*------------------------------------------------*/



/*---------------------Eliminar items---------------------------*/

//var cambioCobros = 0;
//var cambioActiv = 0;
//var cambioCentros = 0

function EliminarElementoSeleccionadoActividad(){	
	var xlist = id("attribActividad-list");
	var xsel = xlist.selectedItem;
	
	if (!xsel)		return;
	
	cambioActiv = 1;
	
	CorrigeValor("attribActividad-list", xsel.id, "cuantoContribActividad-resto", xsel.value, "cuantoContribActividad");
	
}

function EliminarElementoSeleccionadoCentros(){	
	var xlist = id("attribCentros-list");
	var xsel = xlist.selectedItem;
	
	if (!xsel)		return;
	
	cambioCentros = 1;
	
	CorrigeValor("attribCentros-list", xsel.id, "cuantoContribCentros-resto", xsel.value, "cuantoContribCentros");
	
}

function EliminarElementoSeleccionadoDinero(){
	var xmylist = document.getElementById("gestionCobros-list");	
	
	if (!xmylist)
		return alert("no xmylist!");

	var xsel = xmylist.selectedItem;
	
	if (!xsel)	return alert("no xsel!");
	
	//alert("previo...1:"+xsel.getAttribute("value"));
	
	UpdateImporteCobrado( 0 - xsel.getAttribute("value") );
	
	//alert("previo...2");
	
	cambioCobros = 1;
	
	EliminarLineaCobro(xsel.id);
	
	AutoAjuste();
}

function MarcarElementoSeleccionadoDinero(estado){
	var xmylist = document.getElementById("gestionCobros-list");	
	
	if (!xmylist)	return alert("no xmylist!");

	var xsel = xmylist.selectedItem;
	if (!xsel)	return alert("no xsel!");
	
	cambioCobros = 1;
	
	SetPago( xsel.id, estado);
}



/*------------------------------------------------*/


/*---------------------- Eliminar altas ---------------------*/

function CancelarAlta(){
	LimpiaCampos();
}

function LimpiaCampos(){
	//alert("Limpiando campos");

	id("Serie").value 	= "";
	id("NumFac").value 	= "";
	id("Fecha").value 	= "DD-MM-AAAA";
	//id("CentroFiscal").value = "";
	id("ImporteBase").value 	= "";
	id("ImporteTotal").value 	= "";
	id("ImporteCobrado").value 	= "";
	id("Comentarios").value 	= "";
	
	id("IVA").value 	= "";

	EliminarTodosActividad();
	
	id("cuantoContribActividad-resto").value 	= 100;
	id("cuantoContribActividad").value 		= 100;
	
	EliminarTodosCentros();

	id("cuantoContribCentros-resto").value 	= 100;
	id("cuantoContribCentros").value 		= 100;
	
	//removeAllItems("gestionCobros-list");
	EliminarTodosDinero();
		
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
	if (confirm('�Desea eliminar este registro?')) {
		document.location = "modingreso.php?modo=eliminarregistro&id=" + id("IdIngresoActual").value;
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