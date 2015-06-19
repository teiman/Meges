<?php

require("tool.php");


header("Content-Type: application/vnd.mozilla.xul+xml");
header("Content-languaje: es");
	
	
echo $CabeceraXUL;


$outItems = "";

$sql = "SELECT IdListado,NombrePantalla,CodigoSQL FROM ".TABLA_LISTADOS." WHERE Eliminado=0";

$res = query($sql);



// function to change german umlauts into ue, oe, etc.
function cv_input($str){
     $out = "";
     for ($i = 0; $i<strlen($str);$i++){
           $ch= ord($str{$i});
           switch($ch){
               case 241: $out .= "&241;"; break;           
               case 195: $out .= "";break;   
               case 164: $out .= "ae"; break;
               case 188: $out .= "ue"; break;
               case 182: $out .= "oe"; break;
               case 132: $out .= "Ae"; break;
               case 156: $out .= "Ue"; break;
               case 150: $out .= "Oe"; break;

               default : $out .= chr($ch) ;
           }
     }
     return $out;
}


if ($res) {
	while ($row = Row($res)) {
		$NombrePantalla = $row["NombrePantalla"];		
		$id = $row["IdListado"];		
		
		$activos = DetectaActivos( $row["CodigoSQL"]);
		
		$code .= $row["CodigoSQL"] . "\n----------------------------------\n";
		
		$NombrePantalla = str_replace("ñ","&#241;",$NombrePantalla);
		$NombrePantalla = str_replace("Ñ","&#241;",$NombrePantalla);
		$NombrePantalla = str_replace("�","&#241;",$NombrePantalla);
		$NombrePantalla = str_replace("�","&#241;",$NombrePantalla);
		$NombrePantalla = cv_input($NombrePantalla);
		

		
		$outItems = $outItems . "<menuitem label='$NombrePantalla' value='$id' oncommand='SetActive(\"$activos\")'/>\n";			
	}
}


function DetectaActivos($cod){
	$a = "";	
	
	if( strpos($cod,'%IDIDIOMA%') >0 ){
		$a .= "IdIdioma,";	
	}		
	if( strpos($cod,'%DESDE%')  >0){
		$a .= "Desde,";	
	}
	if( strpos($cod,'%HASTA%') >0){
		$a .= "Hasta,";	
	}
	if( strpos($cod,'%IDTIENDA%')  >0){
		$a .= "IdTienda,";	
	}
	if( strpos($cod,'%IDFAMILIA%')  >0){
		$a .= "IdFamilia,";	
	}	
	if( strpos($cod,'%IDSUBFAMILIA%')  >0){
		$a .= "IdSubFamilia,";	
	}	
	if( strpos($cod,'%IDARTICULO%')  >0){
		$a .= "IdArticulo,";	
	}		
	if( strpos($cod,'%FAMILIA%')  >0){
		$a .= "IdFamilia,";	
	}			
	if( strpos($cod,'%IDACTIVIDAD%')  >0){
		$a .= "IdActividad,";	
	}
	if( strpos($cod,'%IDCENTRO%')  >0){
		$a .= "IdCentro,";	
	}	
	if( strpos($cod,'%IDPROVEEDOR%')  >0){
		$a .= "IdProveedor,";	
	}		
	return $a;
}

echo str_replace("@","?","<@xul-overlay href='datepicker-overlay.php' type='application/vnd.mozilla.xul+xml'@>");


function xComboAlmacenes($hueco=false,$selected=false){
	if ($hueco)		echo "<menuitem value='0' label=''/>";	
	
	$sql = "SELECT IdCentro, Nombre FROM men_centros WHERE Eliminado=0 ORDER BY Nombre ASC";
	$res = query($sql);
	while ($row = Row($res)) {
		$val = $row["IdCentro"];
		$nombre = CleanParaXul($row["Nombre"]);		
		if ($selected && $val==$selected) $key = "selected ='true'";
		else $key = "";

		echo "<menuitem value='$val' $key label='$nombre'/>\n";
	}
	
}

function xComboActividades($hueco=false,$selected=false){
		if ($hueco){
		echo "<menuitem value='0' label=''/>";
	}
	
	$sql = "SELECT IdActividad, Nombre FROM men_actividades WHERE Eliminado=0 ORDER BY Nombre ASC";
	$res = query($sql);
	while ($row = Row($res)) {
		$val = $row["IdActividad"];
		$nombre = CleanParaXul( $row["Nombre"] );
		
		if ($selected && $val==$selected) $key = "selected ='true'";
		else $key = "";

		echo "<menuitem value='$val' $key label='$nombre'/>\n";
	}
}	

function xComboProveedores($hueco=false,$selected=false){
		if ($hueco){
		echo "<menuitem value='0' label=''/>";
	}
	
	$sql = "SELECT IdProveedor as id, Nombre FROM men_proveedores WHERE Eliminado=0 ORDER BY Nombre ASC";
	$res = query($sql);
	while ($row = Row($res)) {
		$val = $row["id"];
		$nombre = CleanParaXul( $row["Nombre"] );
		
		if ($selected && $val==$selected) $key = "selected ='true'";
		else $key = "";

		echo "<menuitem value='$val' $key label='$nombre'/>\n";
	}
}	

function xComboFamilias(){ }



?>
<window id="login-meges" title="meges"
        xmlns:html="http://www.w3.org/1999/xhtml"        
        xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul">       		
<script type="application/x-javascript" src="calendario.js"/>		
<popup  id="oe-date-picker-popup" position="after_start" oncommand="RecibeCalendario( this )" value=""/>	

<groupbox>	
	<hbox>	
	<button image="img/document.png" label="Listar" oncommand="CambiaListado()"/>
	<menulist    id="esListas" label="Listados" class="media">						
         <menupopup>
		<?php echo $outItems; ?>
	</menupopup>
	</menulist>
	</hbox>	
	
	<hbox>
	
	<hbox id="getDesde" collapsed="true">
	<toolbarbutton image="calendar-up.gif" label="" onmousedown="EnviaCalendario('oe-date-picker-popup','Desde')" popup="oe-date-picker-popup" position="after_start" />		
	<label value="Desde"/>
	<textbox class="media" id="Desde" value=""/>
	</hbox>
	
	<hbox id="getHasta" collapsed="true">
	<toolbarbutton image="calendar-up.gif" label="" onmousedown="EnviaCalendario('oe-date-picker-popup','Hasta')" popup="oe-date-picker-popup" position="after_start" />		
	<label value="Hasta"/>
	<textbox class="media" id="Hasta" value=""/>
	</hbox>
	
	<hbox id="getIdTienda" collapsed="true">
	<menulist  id="Local">						
	<menupopup>
	 <menuitem label="Elije centro"/>
	<?php echo xComboAlmacenes(false,"menuitem") ?>
	 </menupopup>
	</menulist>	
	</hbox>	
	
	<hbox id="getIdCentro" collapsed="true">
	<menulist  id="Centro">						
	<menupopup>
	 <menuitem label="Elije centro"/>
	<?php echo xComboAlmacenes(false,"menuitem") ?>
	 </menupopup>
	</menulist>	
	</hbox>		
	
	<hbox id="getIdActividad" collapsed="true">
	<menulist  id="Actividad">						
	<menupopup>
	 <menuitem label="Elije actividad"/>
	<?php echo xComboActividades(false,"menuitem") ?>
	 </menupopup>
	</menulist>	
	</hbox>		
	
	</hbox>
	
	<hbox id="getIdFamilia" collapsed="true">
	<menulist  id="Familia">						
	<menupopup>
	 <menuitem label="Elije Familia"/>
	<?php echo xComboFamilias(false,"menuitem") ?>
	 </menupopup>
	</menulist>	
	</hbox>	
	<hbox id="getIdProveedor" collapsed="true">
	<menulist  id="Proveedor">						
	<menupopup>
	 <menuitem label="Elije proveedor"/>
	<?php echo xComboProveedores(false,"menuitem") ?>
	 </menupopup>
	</menulist>	
	</hbox>		
	
	
</groupbox>
<iframe id="webarea" src="about:blank" flex='1'/>
<script><![CDATA[

function id(nombre) { return document.getElementById(nombre); };


function CambiaListado() {
	var idlista 	= id("esListas").value;
	var web 	= id("webarea");

	web.setAttribute("src", "listado.php?id="+idlista+
		"&Desde="+id("Desde").value +
		"&Hasta="+id("Hasta").value +
		"&IdLocal="+id("Local").value+
		"&IdFamilia="+id("Familia").value+
		"&IdActividad="+id("Actividad").value+
		"&IdCentro="+id("Centro").value+		
		"&IdProveedor="+id("Proveedor").value+
		"&r=" + Math.random());
}

function Mostrar( idmostrar){
	var xthingie = id("get"+ idmostrar );
	
	if ( xthingie ){
		xthingie.setAttribute("collapsed","false");	
	}
}

function SetActive( val ){
	var dinterface = val.split(",");
	
	id("getDesde").setAttribute("collapsed","true");
	id("getHasta").setAttribute("collapsed","true");
	id("getIdTienda").setAttribute("collapsed","true");
	id("getIdFamilia").setAttribute("collapsed","true");
	id("getIdActividad").setAttribute("collapsed","true");	
	id("getIdCentro").setAttribute("collapsed","true");	
	id("getIdProveedor").setAttribute("collapsed","true");
	
	for( t=0;t<dinterface.length;t++){
		Mostrar(dinterface[t]);
	}
	
}

/*
<?php

//echo $code;

?>

*/

//]]></script>
</window>
