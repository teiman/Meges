<?php

include("tool.php");


switch($modo){
	case "feature":
		$titulo =  _("Captura de Requisitos - Sugerencia");
		
		$tTitulo = _("Titulo");
		$tCuerpo = _("Sugerencia:");		
		$func = "EnviarSugerencia";
		$prepopulado = _("Escriba aquí su sugerencia");
		break;	
	case "bug":
		$titulo =  _("Captura de Requisitos - Defecto");
				
		$tTitulo = _("Titulo");
		$tCuerpo = _("Descripcion del problema:");		
		$func = "EnviarBug";
		$prepopulado= _("Escriba aqui 1) donde aparece el error (pagina y seccion..). 2) Que esperaba. 3) Que ocurrio");			
		break;	

	case "avisobug":
	case "enviosugerencia":		
		$res = mail("9lands@gmail.com", "[9Menorca-$modo]: ". $_POST["titulo"], "$modo:\n".$_POST["cuerpo"]);
		
		if ($res)
		   echo "OK";
		else
		   echo "ERROR";
		
		exit();
		break;
}


header("Content-Type: application/vnd.mozilla.xul+xml");
header("Content-languaje: es");

echo $CabeceraXUL;
 

?>
<window id="buzon-meges" title="meges"
        xmlns:html="http://www.w3.org/1999/xhtml"        
        xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul" debug="false">       
		
<box flex="1" class="frameExtra">
<spacer flex="1" class="frameExtra"/>
   <vbox>
   <spacer flex="1" class="frameExtra"/>
	<groupbox style="width: 400px;height: 200px;background-color: #ECE8DE">
	 	<spacer flex="1"/>		
		<groupbox>
			<caption label="<?php	echo CleanParaXul($titulo);	?>"/>
			<grid>
				<columns>
					<column flex="1"/>
					<column/>
				</columns>
				<rows>
					<row>
						<description>
						<?php  echo CleanParaXul($tTitulo); ?>						
						</description>
						<textbox id='titulo' type="normal"
						 onkeypress="if (event.which == 13) document.getElementById('cuerpo').focus()"/>
					</row>
					<row>
						<description>
						<?php echo CleanParaXul($tCuerpo); ?>													
						</description>
						<textbox rows="8" multiline="true" id='cuerpo' flex="1" style="width: 400px" 
							value="<?php echo CleanParaXul($prepopulado) ?>" />
					</row>
					<row>
						<description>
						<?php
						//	echo '<image style="width: 48px; height: 48px" src="img/toctoc.gif" />';						
							echo $imagen;												
						?>
						</description>
						<button label="<?php echo CleanParaXul(_("Enviar")) ?>" oncommand="<?php echo $func ?>()"/>
					</row>					
				</rows>												
			</grid>			
		</groupbox>
		<spacer flex="1"/>		
	</groupbox>
	<spacer flex="1"/>	 
	</vbox>
<spacer flex="1"/>	
</box>
<script><![CDATA[

function EnviarSugerencia() {
	EnviarMensaje( "<?php echo addslashes(_("Sugerencia enviada")) ?>","enviosugerencia");
}

function EnviarBug() {
	EnviarMensaje( "<?php echo addslashes(_("Incidencia anotada")) ?>" ,"avisobug");
}

function EnviarNotaNormal() {
	EnviarMensaje( "<?php echo addslashes(_("Nota enviada")) ?>" ,"avisonotanormal");
}

function EnviarNotaImportante() {
	EnviarMensaje( "<?php echo addslashes(_("Nota enviada")) ?>" ,"avisonotaimportante");
}


function EnviarMensaje(mensaje,modo){
	var titulo  = document.getElementById("titulo").value;		
	var cuerpo  = document.getElementById("cuerpo").value;		
	
	var xrequest = new XMLHttpRequest();
	var data = "titulo="+escape(titulo)+"&cuerpo=" + escape(cuerpo);       	
	
	var url="formbuzon.php?modo="+modo;
		
	xrequest.open("POST",url,false);
	xrequest.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	xrequest.send(data);
	var reply = xrequest.responseText;
	
	if (reply=="OK") {	
		document.getElementById("titulo").setAttribute("value","");
		document.getElementById("cuerpo").setAttribute("value","");
		document.getElementById("cuerpo").value = "";
		document.getElementById("titulo").value = "";
		alert(mensaje);
	} else {
		alert("<?php echo addslashes(_("El servidor esta ocupado, por favor intentelo mas tarde.")) ?>");
		alert( reply );
	}
}


// Corregimos el foco para situarse en el primer input box

var mainwindow = document.getElementById("buzon-meges");

mainwindow.setAttribute("onload","FixFocus()");

function FixFocus(){
	document.getElementById("titulo").focus();
}



]]></script>
</window>