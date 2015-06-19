<?php



function AltaIngreso($Fecha,$Serie,$numfac,$cenfis,$impingreso,$impbase,$iva,$observ,$Concepto){
	global $UltimaInsercion;

	$operador = getSesionDato("IdOperador");

	$Concepto 		= CleanParaMysql($Concepto);
	$Fecha			= CleanParaMysql($Fecha);
	$numfac			= CleanInt($numfac);
	$cenfis			= CleanID($cenfis);
	$impbase		= CleanFloat($impbase);
	$observ			= CleanParaMysql($observ);
	$iva			= CleanFloat($iva);	 	
	$impingreso		= CleanFloat($impingreso);	

	$imptotal = $impbase + $iva;
	$sql = "INSERT INTO men_ingresos ( Concepto, UltimoOperador,Fecha,Serie, NumeroFactura " .
			",IdCentroFiscal,ImporteCobrado,ImporteTotal,ImporteBase, Comentarios, ImporteIVA)" .
			" VALUES ('$Concepto','$operador','$Fecha','$Serie','$numfac' ,'$cenfis','$impingreso','$imptotal'" .
			",'$impbase','$observ','$iva')";
	$res = query($sql,"Alta Ingreso");
	if (!$res)	return false;
			
	return $UltimaInsercion;
}

		//ModIngreso($IdIngreso,$Fecha,$Serie,$numfac ,$cenfis,$impingreso,$impbase,$IVA,$observ)
function ModIngreso($IdIngreso, $Fecha,$Serie,$numfac ,$cenfis,$impingreso,$impbase,$iva,$observ){

/*	UPDATE men_ingresos SET Fecha='2005-11-05',
	IdProveedor='15', NumeroFacturaProv='n50',IdCentroFiscal='1', 
	ImporteCobrado='50',ImporteTotal='0',ImporteBase='', Comentarios ='MAHO 50 ESTRELLAS' WHERE IdIngreso='68'*/
	
	$operador = getSesionDato("IdOperador");
	
	$Concepto 		= CleanParaMysql($Concepto);
	$Fecha			= CleanParaMysql($Fecha);
	$numfac			= CleanInt($numfac);
	$cenfis			= CleanID($cenfis);
	$impbase		= CleanFloat($impbase);
	$observ			= CleanParaMysql($observ);
	$iva			= CleanFloat($iva);	 	
	$impingreso		= CleanFloat($impingreso);	
	
	
	$imptotal = $impbase + $iva;
	$sql = "UPDATE men_ingresos
      SET Concepto= '$Concepto',UltimoOperador='$operador',ImporteIVA='$iva', Fecha='$Fecha',Serie='$Serie',
	  NumeroFactura='$numfac',IdCentroFiscal='$cenfis',
	  ImporteCobrado='$impingreso',ImporteTotal='$imptotal',ImporteBase='$impbase', Comentarios ='$observ' WHERE (IdIngreso='$IdIngreso')";
	$res = query($sql,"Modificar Ingreso");
	if (!$res)	return false;
			
	return $IdIngreso;
}


function TotalCobros($IdGestionCobros){
	$IdGestionCobros = CleanID($IdGestionCobros);
	
	if (!$IdGestionCobros){
		return 0;	
	}
	
	$sql = "SELECT SUM(Cuanto) as Total FROM men_gestioncobros WHERE (IdGestionCobro = '$IdGestionCobros')";
	$row = queryrow( $sql );
	
	if ($row) return CleanFloat($row["Total"]);
	
	return 0;
}

function EliminarRegistro($IdIngreso){
	$operador = getSesionDato("IdOperador");

	$sql = sprintf("UPDATE men_ingresos SET UltimoOperador='%d', Eliminado=1 WHERE IdIngreso='%d'",$operador,$IdIngreso);
	query($sql);
}


switch($modo){
	case "eliminarregistro":
		$IdIngreso = CleanID($_GET["id"]);
		EliminarRegistro($IdIngreso);
		MiniPaginaMensaje("Registro eliminado");
		exit();
		break;
		
	case "abrirmodificar":
		$IdIngreso = CleanID($_GET["id"]); 
		$IdIngresoActual = $IdIngreso;		
		//error(__FILE__ . __LINE__ , "Info: idgac: $IdIngresoActual ");
		
		$row = queryrow("SELECT * FROM men_ingresos WHERE (IdIngreso='$IdIngreso') AND Eliminado=0","abrir para modificar");
		if ($row){
			$Concepto			= CleanText($row["Concepto"]); 			
			$IdProveedor 		= CleanID($row["IdProveedor"]);
			//$FacProv 			= $row["NumeroFacturaProv"];
			$Serie 				= CleanText($row["Serie"]);
			$NumFac	 			= CleanInt($row["NumeroFactura"]);
			$Fecha				= CleanFechaFromDB($row["Fecha"]);
			$IdCentroFiscal 	= CleanID($row["IdCentroFiscal"]);
			$Importe			= CleanID($row["ImporteBase"]);
			$ImporteTotal 		= CleanFloat($row["ImporteTotal"]);
			$ImporteCobrado 	= CleanFloat($row["ImporteCobrado"]);
			$Observaciones		= CleanText($row["Comentarios"]);	
			$IdAtribAct			= CleanID($row["IdRepartoActividad"]);
			$repartoact 		= getRepartoActiv($IdAtribAct);
			$repartocen			= getRepartoCentros($row["IdRepartoCentros"]);
			$repartoingresos 	= getRepartoingresos($row["IdGestionCobros"]);
			$IVA 				= CleanFloat($row["ImporteIVA"]);
			//error(__FILE__ . __LINE__ ,"repartoact($IdAtribAct): " . var_export( $repartoact,true) );
					
			$out = "";
			foreach ($repartoact as $key => $value){
				//AddAttribLine("attribActividad-list", IdCentro , NombreCentro, requerido,"cuantoContribActividad-resto","cuantoContribActividad" );
				list( $id, $nombreActividad) = split("#",$key);
				$nombreActividad = addslashes($nombreActividad); 
				$out .= "AddAttribLine('attribActividad-list', '$id' , '$nombreActividad', '$value','cuantoContribActividad-resto','cuantoContribActividad' );\n";
			}
			$AutoRepartoAct = $out;
			
			$out = "";
			foreach ($repartocen as $key => $value){				
				list( $id, $nombreActividad) = split("#",$key);	
				$nombreActividad = addslashes( $nombreActividad);			
				$out .= "AddAttribLine('attribCentros-list', '$id' , '$nombreActividad', '$value','cuantoContribCentros-resto','cuantoContribCentros' );\n";
			}
			$AutoRepartoCentros = $out;
			
			$out = "";
			foreach ($repartoingresos as $indice=>$combinado){
				list($fecha,$esPagado,$valor) = split("#",$combinado);							
				$out .="xAddCobro('gestionCobros-list','$fecha','$valor','$esPagado');\n";
			}
			$AutoRepartoingresos = $out;										
		} else {
			MiniPaginaMensaje("El registro no existe.");
			exit();
		}
		
		break;
	case "buscar":
		$cr = "\n";
		/*
		$prov 		= CleanNombre($_POST["Proveedor"]);
		$facprov 	= CleanNombre($_POST["FacProv"]);
		$centrofis 	= CleanID($_POST["CentroFis"]);
		$fecha 	= CleanFechaES($_POST["Fecha"]);*/
		
		$Concepto		= CleanText($_POST["Concepto"]);
		$Serie			= CleanText($_POST["Serie"]);
		$NumeroFactura 	= CleanID($_POST["NumFac"]);
		$fechahasta		= CleanFechaES($_POST["FechaHasta"]);
		$fechadesde 	= CleanFechaES($_POST["FechaDesde"]);
		$CentroFiscal 	= CleanID($_POST["CentroFis"]);
		$ImporteCobrado = CleanFloat($_POST["ImporteCobrado"]);
		$ImporteTotal 	= CleanFloat($_POST["ImporteTotal"]);
		$ImporteBase 	= CleanFloat($_POST["ImporteBase"]);	
		
		$SoloPendientes = CleanID($_POST["SoloPendientes"]);	
		
		/*
		"NumFac",tNumFac,
		"Fecha",tFecha,
		"CentroFis",tCentroFis,
		"ImporteTotal",tImporteTotal,
		"ImporteCobrado",tImporteCobrado,
		"ImporteBase",tImporteBase	*/
		
		
		$condiciones = Array();
		$i = 0;

	/*
		if ( $SoloPendientes > 0 ) {			
			$condiciones[$i++] = "ImporteCobrado < ImporteTotal";
		}*/		
		
		if ( $NumeroFactura ) {
			//$IdProveedor = getIdProvFromName($prov);
			$condiciones[$i++] = "NumeroFactura = '$NumeroFactura'";
		}
		
		if (strlen($fechadesde) > 0){
			$condiciones[$i++] = "Fecha >= '$fechadesde'";
		} 
		
		if (strlen($fechahasta) > 0){
			$condiciones[$i++] = "Fecha <= '$fechahasta'";
		} 		
		
		if ( $CentroFiscal ) {			
			$condiciones[$i++] = "IdCentroFiscal = '$CentroFiscal'";
		}
		if ( $ImporteCobrado ) {			
			$condiciones[$i++] = "ImporteCobrado = '$ImporteCobrado'";
		}
		if ( $ImporteTotal ) {			
			$condiciones[$i++] = "ImporteTotal = '$ImporteTotal'";
		}
		if ( $ImporteBase ) {			
			$condiciones[$i++] = "ImporteBase = '$ImporteBase'";
		}		
		if ( $ImporteIVA ) {			
			$condiciones[$i++] = "ImporteIVA = '$ImporteIVA'";
		}		
		if ( $Serie ) {			
			$condiciones[$i++] = "Serie = '$Serie'";
		}		

		if( !$i ){
			echo "ERROR=no datos busca";
			exit();
			break;
		}
		
		$condiciones[$i++] = "Eliminado = '0'";
		
		$campos = "IdIngreso, Concepto,Serie,NumeroFactura, Fecha,IdGestionCobros,ImporteTotal,ImporteBase,ImporteCobrado,UltimoOperador";
		$res = BuscaCompleja("men_ingresos",$campos, $condiciones, "ORDER BY Serie ASC, NumeroFactura ASC, Fecha DESC");
		if ($res) {
			while( $row = Row($res)){
				echo "Ingreso:\n" . $row["IdIngreso"] . $cr;
				echo $row["Serie"] . $cr; 
				echo $row["NumeroFactura"] . $cr;
				echo $row["Fecha"] . $cr;
				echo $row["ImporteTotal"] . $cr;
				echo $row["ImporteCobrado"] . $cr;
				echo TotalCobros($row["IdGestionCobros"]) . $cr; 
				echo getNombreOperador($row["UltimoOperador"]) . $cr;
				echo CleanParaXul($row["Concepto"]) . $cr;
			}
		}
				
		echo "OK";
		exit();	
		break;
	case "ingresos":
		$IdIngreso = CleanID($_POST["IdIngreso"]);
		$gastos 		= array();
		$gastosfechas 	= array();
		$fechas 		= array();
		
		$numingresos 	= CleanInt($_POST["AtribNumingresos"]);
			
		for($t=0;$t<$numingresos;$t++){
			$fechaIngreso 		= CleanFechaES($_POST["Atribingresos_Nombre_$t"]);
			$cantidadIngreso 	= CleanFloat($_POST["Atribingresos_Cantidad_$t"]);
			$esPagado			= $_POST["Atribingresos_Pagado_$t"];
			
			$gastosfechas[$t] 	= "$esPagado";
			$fechas[$t]			= $fechaIngreso;
			$gastos[$t]		= $cantidadIngreso;
			 			
		}	
		
		//Siempre marcamos como invalidos los datos viejos.('borramos')
		$row = queryrow("SELECT IdGestionCobros FROM men_ingresos WHERE IdIngreso = '$IdIngreso'");
		if ($row and isset($row["IdGestionCobros"]) and $row["IdGestionCobros"]>0){
			query("UPDATE men_gestioncobros SET Eliminado=1 WHERE IdGestionCobro=" . $row["IdGestionCobros"]);
			//Olvidamos a todos los efectos la serie de ingresos que habia antes
			// puesto que vamos a agnadir una nueva serie, o a dejarlo sin serie
			query("UPDATE men_ingresos SET IdGestionCobros = '0' WHERE (IdIngreso = '$IdIngreso')");			
			if (!$numingresos or !($numingresos>0)){
				//Simplemente no hay datos de pago. Se marca sin datos y se sale
				echo "OK";
				exit();				
			}				
		}		

		/*Id  IdRepartoCentros  IdCentro  Cuanto */
		$IdMax = intval( 1  + intval(getMaxIdGestionCobro()) );
		//error(__FILE__ . __LINE__ ,"IdMax(gasto): $IdMax");

		$numlines = 0;		
		//foreach( $ingresos as $fecha=>$gasto){			
		for( $t=0;$t<$numingresos;$t++){
			$fecha = $fechas[$t];
			$gasto = $gastos[$t];
			$esPagado = ($gastosfechas[$t]=="true")?"1":"0";
			$sql = "INSERT INTO men_gestioncobros (IdGestionCobro,Fecha,Cuanto,esPagado) VALUES ('$IdMax','$fecha','$gasto','$esPagado')";
			$res = query($sql);					
			$hayDatos = true;
		}
		
		if ($hayDatos){ //Si no ha habido cambios, no tocamos los datos viejos.
						// dado que IdMax no apuntaria a datos existentes.
			//Reajustar padre 
			$sql = "UPDATE men_ingresos SET IdGestionCobros = '$IdMax' WHERE (IdIngreso = '$IdIngreso')";
			query($sql);
		}
	 
		//query("select 'Prueba de attrib'");
		echo "OK";
		exit();	
		break;
	case "atrib":
		$IdIngreso = CleanID($_POST["IdIngreso"]);
		$centros = array();
		$idcentros = array();
		$numcentros = CleanInt($_POST["AtribNumCentros"]);
		
		if (!$numcentros){
			echo "ERROR";
			exit();				
		}		
		
		
		for($t=0;$t<$numcentros;$t++){
			$nombreCentro 	= CleanNombre($_POST["AtribCentro_Nombre_$t"]);
			$centajeCentro 	= CleanNombre($_POST["AtribCentro_Centaje_$t"]);
			$id 			= CleanID($_POST["AtribCentro_IdCentro_$t"]);
			$centros[$nombreCentro] = $centajeCentro;
			$idcentros[$nombreCentro] = $id;
		}	

		/*Id  IdRepartoCentros  IdCentro  Cuanto */
		$IdMax = intval( 1  + intval(getMaxIdRepartoCentros()) );
		error(__FILE__ . __LINE__ ,"IdMax: $IdMax");

		foreach( $centros as $nombre=>$porcen){
			if (!isset($idcentros[$nombre]) and !$idcentros[$nombre])
				$IdCentro = getIdCentroFromNombre($nombre);
			else
				$IdCentro = $idcentros[$nombre];
				
			$sql = "INSERT INTO men_repartocentros (IdRepartoCentros,IdCentro,Cuanto) VALUES ('$IdMax','$IdCentro','$porcen')";
			$res = query($sql);					
		}
	 
	 	//Reajustar padre 
		$sql = "UPDATE men_ingresos SET IdRepartoCentros = '$IdMax' WHERE IdIngreso = '$IdIngreso'";
		query($sql);
		
		//query("select 'Prueba de attrib'");
		echo "OK";
		exit();	
		break;
	case "activ":
		$IdIngreso = CleanID($_POST["IdIngreso"]);
		$activ = array();
		$idactiv = array();
		$numcentros = CleanInt($_POST["AtribNumActiv"]);
		
		if (!$numcentros){
			echo "ERROR";
			exit();				
		}		
		
		for($t=0;$t<$numcentros;$t++){
			$nombreCentro 	= CleanNombre($_POST["AtribActiv_Nombre_$t"]);
			$centajeCentro 	= CleanNombre($_POST["AtribActiv_Centaje_$t"]);
			$id 			= CleanID($_POST["AtribActiv_IdActiv_$t"]);
			$activ[$nombreCentro] = $centajeCentro;
			$idactiv[$nombreCentro] = $id;
		}	

		/*Id  IdRepartoCentros  IdCentro  Cuanto */
		$IdMax = intval( 1  + intval(getMaxIdRepartoActiv()) );
		error(__FILE__ . __LINE__ ,"IdMax: $IdMax");

		foreach( $activ as $nombre=>$porcen){
			if (!isset($idactiv[$nombre]) and !$idactiv[$nombre])
				$IdActividad = getIdActividadFromNombre($nombre);
			else
				$IdActividad = $idactiv[$nombre];
							
			
			$sql = "INSERT INTO men_repartoactiv (IdRepartoActiv,IdActividad,Cuanto) VALUES ('$IdMax','$IdActividad','$porcen')";
			$res = query($sql);					
		}
	 	
		//Reajustar padre 
		$sql = "UPDATE men_ingresos SET IdRepartoActividad = '$IdMax' WHERE IdIngreso = '$IdIngreso'";
		query($sql);
		
	 
		//query("select 'Prueba de attrib'");
		echo "OK";
		exit();	
		break;		
	case "alta_mod":
		$esMod = 1;//es una modificacion, no una alta

	case "alta":
		$Concepto	= CleanText($_POST["Concepto"]);	
		$Fecha		= CleanFechaES($_POST["Fecha"]);	
		$Serie	 	= CleanText($_POST["Serie"]);
		$numfac	 	= CleanText($_POST["NumFac"]);
		
		$cenfis 	= CleanId($_POST["CenFis"]);
		$impbase 	= CleanFloat($_POST["ImporteBase"]);
		$IVA 		= CleanFloat($_POST["IVA"]);
		$impingreso = CleanFloat($_POST["ImpCobrado"]);
		$observ 	= CleanText($_POST["Observaciones"]);
		//$Proveedor 	= CleanNombre($_POST["Prov"]);
		
		/*
		$IdProveedor = getIdProvFromName($Proveedor);
		if (!$IdProveedor){
			$IdProveedor = AltaProv($Proveedor);
		}*/
		//error(__FILE__.__LINE__ , "Info: $IdIngreso, $Fecha,$IdProveedor,$facprov,$cenfis,$impingreso,$impbase,$IVA,$observ ");
		if (!$esMod) {
			
			if ($id = AltaIngreso($Fecha,$Serie,$numfac ,$cenfis,$impingreso,$impbase,$IVA,$observ,$Concepto)){
				echo "OK=$id";			
			} else {
				echo "error=error";
			}
		} else {
			$IdIngreso = CleanID($_POST["IdIngreso"]);
			$imptotal = CleanFloat($_POST["ImpTotal"]);
			//error( __FILE__ . __LINE__ ,"Idgasto: idgasto '$IdIngreso'"); 
			//if ($id = ModIngreso($IdIngreso, $Fecha,$IdProveedor,$facprov ,$cenfis,$impingreso,$impbase,$IVA,$observ)){
			//if ($id = ModIngreso($IdIngreso, $Fecha,$IdProveedor,$facprov ,$cenfis,$impingreso,$impbase,$IVA,$observ)){
			if ($id = ModIngreso($IdIngreso,$Fecha,$Serie,$numfac ,$cenfis,$impingreso,$impbase,$IVA,$observ,$Concepto)){
				echo "OK=$id";			
			} else {
				echo "error=error";
			}				
		}
		exit();
		break;
}
		
		
		
		
		
?>
