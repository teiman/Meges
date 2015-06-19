
var solapas = new Array();

solapas['gastos'] 		= "formgastos.php";
solapas['ingresos'] 	= "formingresos.php";
solapas['listados'] 	= "formlistados.php";
solapas['users'] 		= "formusers.php";
solapas['documentos'] 	= "funcion_no_disponible.php";
solapas['debug'] 		= "reporte.php";

function id(nombre) { return document.getElementById( nombre ); }

function AbrirSolapa(nombreSolapa) {
    var xsolapa = id(nombreSolapa);
    var xtab = id("tab-" + nombreSolapa);
        
    if (!xsolapa) return alert("Internal error: solapa " + nombreSolapa );
    if (!xtab) return alert("Internal error: tab " + nombreSolapa);
        
    xsolapa.setAttribute("src", solapas[nombreSolapa]);
    xtab.setAttribute("collapsed","false");				       
}

function CerrarSolapa( nombreSolapa ){
    var xtab = id("tab-" + nombreSolapa);
        
    if (!xtab) return alert("Internal error: tab " + nombreSolapa);
        
	xtab.setAttribute("collapsed","true");        
}


var IdUsuarioLogueado = 0;

function Login(){
	
	var url = "index.php?modo=login";
	
	var datos = new Array(
		"usuario",id("usuario").value,
		"pass",id("pass").value	
	);	
	
	var datosEnviar = preparaData( datos );
	
	var ajax = new XMLHttpRequest();
	ajax.open("POST",url,false);
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	ajax.send(datosEnviar);
	
	var resultado = ajax.responseText;	
	
	//alert("resultado: "+resultado);
	
	if ( !resultado || resultado == "ERROR" || resultado == "0" )	return ErrorLogin();	

	//El retorno viene en campos separados por "#"
	var etapas 	= resultado.split("#");
	var idus 	= etapas[0];
	var esAdmin	= etapas[1];
	var nombreUsuario = etapas[2];

	
	AccionesLogueoCorrecto(idus,esAdmin,nombreUsuario);		
}

function ErrorLogin(){
	alert("Vuelvalo a intentar");
}

function Logout(){
	document.location = "logout.php";
}

function AccionesLogueoCorrecto(idnt,esAdmin,nombreDelUsuario){
	IdUsuarioLogueado = idnt;

	//alert( "esadmin:"+esAdmin);
	esAdmin = parseInt(esAdmin);

	if (esAdmin>0) 
		AbrirSolapa("listados");        
	if (esAdmin>0) 
		AbrirSolapa("users");           
     	
	AbrirSolapa("gastos");                
	AbrirSolapa("ingresos");                
	AbrirSolapa("documentos");
	if (esAdmin>0)
		AbrirSolapa("debug");

	//CerrarSolapa("login");
	id("grupo-login").setAttribute("collapsed","true");
	id("mensajebienvenida").setAttribute("label","USUARIO: " + nombreDelUsuario);	
	id("logout-box").setAttribute("collapsed","false");	
	//id("mensaje-nombre").setAttribute("label","USUARIO: "+ nombreDelUsuario);
	
}

function preparaData( datos ){	
	var out;
	for(var t=0;t< datos.length;t=t+2){
		out = out + "&" +datos[t]+"="+escape(datos[t+1]);
	}		
	return out;
}