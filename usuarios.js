



function id(nombre) { return document.getElementById(nombre); };

var xlistadoUsuarios = id("listadoUsuarios");
var xnombreUsuario = id("nombreUsuario");
var ajaxCargaUsuario = new XMLHttpRequest();

function genRandomID() {
	return "R"+Math.random()*90000;	
}

//Agnade usuario en el interface
function xAddUser(IdUsuario, vnombreUsuarioAlta) {
	var xitem = document.createElement("listitem");
	var rid = "user_" + IdUsuario;
		
	xitem.setAttribute("label",vnombreUsuarioAlta);	
	xitem.setAttribute("id",rid);	
	xitem.setAttribute("onclick","EditarUser("+IdUsuario+", this)");	
	
	xitem.setAttribute("image","img/cliente16.png");
	xitem.setAttribute("class","listitem-iconic");
	
	
	xlistadoUsuarios.appendChild(xitem);
}

function xDelUser(IdUsuario){
	if (!IdUsuario)
	if (!IdUsuario)
		return;
	var rid = "user_" + IdUsuario;	
	var xus = id(rid);
	
	if (xus){
		xlistadoUsuarios.removeChild(xus);	
		editandoIdUsuario = 0;
	} else {
		//alert("no existe "+rid);
	}
}



//Intenta una alta de usuario, y si tiene exito, lo da de alta en las X.
function AddNewUser(){
	var vnombreUsuarioAlta = id("nombreUsuarioAlta").value;	
	
	if ( vnombreUsuarioAlta.length < 1)
		return alert("Nombre demasiado corto!");		
	
	
		
	//Construimos envio de datos
	var data = "&nombre=" + escape(vnombreUsuarioAlta);
	var url = "formusers.php?modo=alta";
			
	var ajax = new XMLHttpRequest();
	ajax.open("POST",url,false);
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	ajax.send(data);
	resultado = ajax.responseText.split("=");
	if (resultado[0] != "OK"){
		return alert("Datos no validos, pruebe otro nombre");
	}	
	
//	alert( resultado[1]+vnombreUsuarioAlta);
	xAddUser(resultado[1],vnombreUsuarioAlta);
	
	id("nombreUsuarioAlta").value  = "";
	
	Blink("CreandoNuevo","groupbox");
}

var editandoIdUsuario = 0;

function EditarUser(IdUsuario, xsender){
	//alert(IdUsuario+ sender.label);
	var xnombreUser = id("nombreUsuario");
	if (!xnombreUser)
		return alert("error: es necesario recargar la pagina");
	
	//xnombreUser.setAttribute("value",xsender.label);
	xnombreUser.value = xsender.getAttribute("label");
	editandoIdUsuario = IdUsuario;
	AjaxCargarUsuario(IdUsuario);
	//alert(IdUsuario);
	Blink("ModificandoUsuario","groupbox");	
}

function AjaxCargarUsuario(IdUsuario){

	
	var url = "formusers.php?modo=CargarUsuario&IdUsuario="+IdUsuario;
				
	ajaxCargaUsuario.open("GET",url,true);
	ajaxCargaUsuario.onreadystatechange = CargarUsuario;
	ajaxCargaUsuario.send(null)
	
}

function preparaData( datos ){	
	var out;
	for(var t=0;t< datos.length;t=t+2){
		out = out + "&" +datos[t]+"="+escape(datos[t+1]);
	}		
	return out;
}

function CargarUsuario(){
	ocupado = 0;
	if (ajaxCargaUsuario.readyState==4) {
		var rawtext = ajaxCargaUsuario.responseText;	
		if (rawtext=="error")		return;				
		InterpretarDatosUsuario(rawtext);				
	}
}

function InterpretarDatosUsuario(rawtext){
	if (!rawtext)	return;
	var pieces = rawtext.split("#");
	if (pieces.length<2) return;
		
	id("nombreUsuario").value 	= pieces[0];
	id("usuario").value 		= pieces[1];
	id("pass").value 			= pieces[2];
	
	if ( pieces[3] == "1" ){
		id("activo").setAttribute("checked","true");
	} else {
		id("activo").setAttribute("checked","false");	
	}

	if ( pieces[4] == "1" ){
		id("administrador").setAttribute("checked","true");
	} else {
		id("administrador").setAttribute("checked","false");	
	}
	

}

function BorrarUser(){
	if (!editandoIdUsuario)
		return;
	

	if (id("administrador").checked){
		return alert("No se puede eliminar la cuenta de administrador.");	
	}	
	
	if (!confirm("¿Esta seguro de que quiere borrar este usuario?")){
		return;
	}

	//confirm("HOLA MUNDO!");

	var url="formusers.php?modo=borrar&id=" +editandoIdUsuario; 
	var ajax = new XMLHttpRequest();
	ajax.open("GET",url,false);
	ajax.send(null);
	
	if (!ajax.responseText){
		return alert("Intentelo mas tarde. Servidor ocupado");
	}
	
	resultado = ajax.responseText.split("=");
	
	if (resultado[0] != "OK"){	
		return alert("Datos no validos, no se pudo completar la operacion");
	}
		
	xDelUser( editandoIdUsuario );
	
	LimpiarFormulario();
	Blink("ModificandoUsuario","groupbox");	
}


//Intenta una alta de usuario, y si tiene exito, lo da de alta en las X.
function UpdateUser(){
	var vnombreUsuario = xnombreUsuario.value;
	if ( vnombreUsuario.length < 1)
		return alert("Nombre demasiado corto!");	

	

	var usuario = id("usuario").value;
	var pass 	= id("pass").value;
	
	var esActivo = 0; if ( id("activo").checked ) esActivo = 1;		
	var esAdmin = 0; if ( id("administrador").checked ) esAdmin = 1;				

	//Construimos envio de datos
	var data = "&nombre=" + escape(vnombreUsuario);
		data = data + "&IdUsuario=" + escape(editandoIdUsuario);
		data = data + "&usuario=" + escape(usuario);
		data = data + "&pass=" + escape(pass);
		data = data + "&activo=" + escape(esActivo);
		data = data + "&admin=" + escape(esAdmin);
		
	var url = "formusers.php?modo=update";
			
	var ajax = new XMLHttpRequest();
	ajax.open("POST",url,false);
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	ajax.send(data);
	resultado = ajax.responseText.split("=");
	if (resultado[0] != "OK"){	
		return alert("Datos no validos, pruebe otro nombre");
	}
		
	var usuarioNombreEnListado = id("user_"+ editandoIdUsuario);
	if (usuarioNombreEnListado){
		oldnombre	 = usuarioNombreEnListado.label;
		usuarioNombreEnListado.setAttribute("label",vnombreUsuario);
	}	
	
	Blink("ModificandoUsuario","groupbox");
}


function LimpiarFormulario(){
	id("nombreUsuario").value 	= "";
	id("usuario").value 		= "";
	id("pass").value 			= "";
	id("activo").setAttribute("checked","false");	
	id("administrador").setAttribute("checked","false");	
}
