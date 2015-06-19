<?php



function getNombreOperador($id){

	$id = CleanID($id);

	if (!$id)
		return "Desconocido";
		

	$slot = "tNOMBRE_OPERADOR_". $id;
	
	if  ( $id and isset($_SESSION[$slot]) )		return $_SESSION[$slot];
	

	$sql = "SELECT Nombre FROM men_usuarios WHERE IdUsuario='$id'";
	$row = queryrow($sql);
	if (!$row)
		return "Desconocido";
		
	$nombre = $row["Nombre"];		
	
	$_SESSION[$slot] = $nombre;
		
	return $nombre;
}


function getIdCentroFromNombre($nombre){
		
	if (!$nombre)
		return 0;
	$nombresql = CleanParaMysql($nombre);
	
	error(__FILE__ . __LINE__ ,"INFO: sql'$nombresql', n'$nombre' ");
	$sql = "SELECT IdCentro FROM men_centros WHERE Nombre='".$nombresql."'";
	$row = queryrow($sql);
	if ($row){
		return $row["IdCentro"];
	}
	return 0;
}

function getIdActividadFromNombre($nombre){
	if (!$nombre)
		return 0;	
	$nombresql = CleanParaMysql($nombre);
	$sql = "SELECT IdActividad FROM men_actividades WHERE (Nombre='$nombresql')";
	$row = queryrow($sql);
	if ($row)	return $row["IdActividad"];
	
	return 0;
}
		
function getNombreProvID($id){
	$id = CleanID($id);		
	if (!$id) return "";	
	
	$slot = "tNOMBRE_PROVEEDOR_". $id;
	
	if  ( $id and isset($_SESSION[$slot]) )	return $_SESSION[$slot];	
	
	$sql = "SELECT Nombre FROM men_proveedores WHERE (IdProveedor='$id')";
	$row = queryrow($sql);
	if ($row){
		$_SESSION[$slot] = $row["Nombre"];
		return $row["Nombre"];
	}	
	
	return 0;	
}


function genMenulistCuanto($nombre){
	$nombre = CleanParaXul($nombre);
	
	echo "<menulist class='media' id='".$nombre."'><menupopup>";
	for($t=10;$t<100;$t=$t+10){
		echo "<menuitem value='".$t."' label='".$t."'/>";
	}
	
	echo "</menupopup></menulist>";
}

function genMenulistProveedores($nombre,$hueco=false,$selected=false) {
	$nombre = CleanParaXul($nombre);
	echo "<menulist crop='end' editable='true' class='media' id='".$nombre."'><menupopup>";
	
	if ($hueco){
		echo "<menuitem value='0' label=''/>";
	}
	
	//echo "<menuitem value='3' label='MAHO'/><menuitem value='5' label='CENTROA'/><menuitem value='8' label='CENTROB'/>";
	$sql = "SELECT IdProveedor, Nombre FROM men_proveedores WHERE Eliminado=0 ORDER BY Nombre ASC";
	$res = query($sql);		
	while ($row = Row($res)) {
		$val = $row["IdProveedor"];
		$nombre = CleanParaXul($row["Nombre"]);
		
		if ($selected && $val==$selected) $key = "selected ='true'";
		else $key = "";
		echo "<menuitem value='$val' $key label='$nombre'/>\n";
	}
	
	echo "</menupopup></menulist>";
}

function genMenulistCentros($nombre,$hueco=false,$selected=false) {	
	$nombre = CleanParaXul($nombre);			
	echo "<menulist class='media' id='".$nombre."'><menupopup id='".$nombre."-contenedor'>";

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
	
	echo "</menupopup></menulist>";
}

function genMenulistActividad($nombre,$hueco=false,$selected=false) {
	$nombre = CleanParaXul( $nombre );
	echo "<menulist class='media' id='".$nombre."'><menupopup id='".$nombre."-contenedor'>";
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
	
	echo "</menupopup></menulist>";
}

function getIdProvFromName( $nombre ){
	if (!$nombre or strlen($nombre)<1)
		return 0;		
		
	$nombre = CleanParaMysql($nombre);
	$sql = "SELECT IdProveedor FROM men_proveedores WHERE (Nombre='$nombre') AND (Eliminado='0')";
	$row = queryrow($sql);
	if ($row)
		return $row["IdProveedor"];
	return 0;
}


function getMaxIdRepartoCentros(){
	$sql = "SELECT Max(IdRepartoCentros) as IdMax FROM men_repartocentros";
	$row = queryrow($sql);
	if ($row)	return $row["IdMax"];	
	return 0;
}


function getMaxIdGestionPago(){
	$sql = "SELECT Max(IdGestionPago) as IdMax FROM men_gestiongastos";
	$row = queryrow($sql);
	if ($row)	return $row["IdMax"];
	
	return 0;
}


function getMaxIdGestionCobro(){
	$sql = "SELECT Max(IdGestionCobro) as IdMax FROM men_gestioncobros";
	$row = queryrow($sql);
	if ($row)	return $row["IdMax"];	
	return 0;
}


function getMaxIdRepartoActiv(){
	$sql = "SELECT Max(IdRepartoActiv) as IdMax FROM men_repartoactiv";
	$row = queryrow($sql);
	if ($row)	return $row["IdMax"];	
	return 0;
}





function getRepartoingresos( $IdReparto){
	$Id = CleanID($IdReparto);
	
	if (!$Id){
		return array();	
	}
	
	$sql = "SELECT Cuanto as eur, Fecha,esPagado FROM men_gestioncobros WHERE (IdGestionCobro = '$Id')";
	$out = array();
	$res = query($sql);
	if ($res){
		$t = 0;
		while( $row = Row($res)) {
			$eur		= $row["eur"];
			$Fecha 		= CleanFechaFromDB($row["Fecha"]);
			$esPagado	= $row["esPagado"];			
			$out[$t] 	= $Fecha . "#" . $esPagado . "#" . $eur ;
			$t++;					
		}		
	} 
	
	return $out;			
}


function getRepartoGastos( $IdReparto){
	$Id = CleanID($IdReparto);
	if (!$Id)
		return array();	
	
	$sql = "SELECT Cuanto as eur, Fecha, esPagado FROM men_gestiongastos WHERE (IdGestionPago = '$Id')";
	$out = array();
	$res = query($sql);
	if ($res){
		while( $row = Row($res)) {
			$index 		= $row["eur"] . "#" . $row["esPagado"];
			$Fecha 		= CleanFechaFromDB($row["Fecha"]);
			$out[$index] 	= $Fecha;		
		}		
	} 
	
	return $out;			
}



function getRepartoCentros( $IdReparto){
	$Id = CleanID($IdReparto);
	
	if (!$Id)
		return array();
	
	//$sql = "SELECT IdCentro as iid, Cuanto FROM men_repartocentros WHERE (IdRepartoCentros = '$Id')";
	
	$sql = "SELECT men_repartocentros.IdCentro as iid, men_repartocentros.Cuanto as Cuanto, men_centros.Nombre as Nombre
		FROM men_repartocentros, men_centros 
		WHERE (men_repartocentros.IdRepartoCentros = '$Id') AND 
		(men_repartocentros.IdCentro = men_centros.IdCentro)  ";
		
	$out = array();
	$res = query($sql);
	if ($res){
		while( $row = Row($res)) {
			$Id 		= $row["iid"] . "#" . $row["Nombre"];
			$Cuanto 	= $row["Cuanto"];
			$out[$Id] 	= $Cuanto;		
		}		
	} 
	
	return $out;			
}


function getRepartoActiv( $IdReparto){
	$Id = CleanID($IdReparto);
	
	if (!$Id)
		return array();	
	
	$sql = "SELECT men_repartoactiv.IdActividad as iid, men_repartoactiv.Cuanto as Cuanto, men_actividades.Nombre as Nombre
		FROM men_repartoactiv, men_actividades 
		WHERE (men_repartoactiv.IdRepartoActiv = '$Id') AND 
		(men_repartoactiv.IdActividad = men_actividades.IdActividad)  ";
	$out = array();
	$res = query($sql);
	if ($res){
		while( $row = Row($res)) {
			$Id 		= $row["iid"] . "#" .$row["Nombre"] ;
			$Cuanto 	= $row["Cuanto"];
			$out[$Id] 	= $Cuanto;		
		}		
	} 
	
	return $out;			
}


function AltaProv($nombre){
	global $UltimaInsercion;
	
	if (!$nombre or strlen($nombre)<1)
		return 0;		
	
	$nombre = CleanParaMysql($nombre);
	$sql = "INSERT INTO men_proveedores ( Nombre ) VALUES ('$nombre' )";
	$res = query($sql);
	if (!$res)	return 0;
	
	return $UltimaInsercion;
}


function MiniPaginaMensaje($aviso){	
	global $CabeceraXUL;
	
	$aviso = CleanParaXul($aviso);
	
	$xulcode = "<window id='login-meges' title='mensaje'
        xmlns:html='http://www.w3.org/1999/xhtml'        
        xmlns='http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul' debug='false'>       		
<box flex='1' class='frameExtra'>
<spacer flex='1' class='frameExtra'/>
   <vbox>
   <spacer flex='1' class='frameExtra'/>
	<groupbox style='width: 400px;height: 200px;background-color: #ECE8DE'>
	 	<spacer flex='1'/>		
		<groupbox>
		<description>$aviso</description>
		</groupbox>
		<spacer flex='1'/>		
	</groupbox>
	<spacer flex='1'/>	 
	</vbox>
<spacer flex='1'/>	
</box></window>";

	header("Content-Type: application/vnd.mozilla.xul+xml");
	header("Content-languaje: es");

	echo $CabeceraXUL;
	echo $xulcode;
}


?>